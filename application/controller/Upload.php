<?php


namespace app\controller;


use app\model\File;
use think\Controller;
use think\Db;
use think\facade\Cookie;
use think\Request;
use think\Image;
use think\facade\Env;

class Upload extends Controller
{
    private $image = [
        "containerWidth" => 0,
        "containerHeight" => 0,
        "cropWidth" => 0,
        "cropHeight" => 0,
        "cropTop" => 0,
        "name" => '',
        "extension" => '',
    ];
    //private $imgHandle;
    private $imageTextWater;
    private $imgSaveName;
    private $timeFlag;
    public function index(){
    }
    public function picture(Request $request)
    {
        $this->extraInfoCollection();
        $imgHandle = $request -> file('biliImage');//接收获取图片句柄
        if (empty($imgHandle)){
            $this->error('请选择上传文件');
        }
        $this->timeFlag = date('Ymd');
        $name = iconv('utf-8','gbk',$imgHandle -> getInfo()['name']);
        $img_path = str_replace('\\', '/', UPLOAD_PATH).'/'.$this->timeFlag;
        //if(!file_exists($img_path)){mkdir($img_path,0777,true);}
        $info = $imgHandle -> move($img_path, $name);
        if ($info){
            $this->imgSaveName = $info -> getSaveName();
            $this->imgInfoCollection($info);//进一步读取图片的相关基础信息存放数据库
            $status = 0;
            $message = '图片上传成功';
        }else{
            $status = 1;
            $message = '图片上传失败';
        }

        $this->imageTextWater = '_'.date('H:i:s',time()).'_满脑子骚操作的大湿_'.upFileSalt;
        //$imgProcessName = substr($imgProcessPath,strripos($imgProcessPath,"public")+6);//加工后文件名
        $imgOpen = Image::open($img_path.'\\'.$this->imgSaveName);
        $this->imgProcess($imgOpen); //实施图片的缩放裁剪加工步骤
        unset($info); //关闭之前的文件移动句柄才能把该文件的所有权限移交给别人
        $delName = $this->fileDel($name); //删除原图缓解资源占用
        return ['status'=>$status,'message'=>$message,'delPath'=>$delName];
    }
    public function imgInfoCollection($imgName)
    {
        $this->image["extension"] = $imgName -> getExtension();
    }
    public function extraInfoCollection()
    {
        $post = $this->request->post();
        $this->image["containerWidth"] = $post["cWidth"];
        $this->image["containerHeight"] = $post["cHeight"];
        $this->image["cropWidth"] = $post["cropWidth"];
        $this->image["cropHeight"] = $post["cropHeight"];
        $this->image["cropTop"] = $post["cropTop"];
    }
    public function imgProcess($imgOpen)
    {
        $imgOpen -> thumb($this->image["containerWidth"], $this->image["containerHeight"])
        -> crop($this->image["cropWidth"],$this->image["cropHeight"],0,$this->image["cropTop"])
        ->text('满脑子骚操作的大湿', FONT_PATH. 'ZIHUN-nihong169.ttf',10, '#000000', Image::WATER_NORTHEAST,0,0)
        -> save('./uploads/'.$this->timeFlag.'/'.md5($this->imageTextWater).'.'.$this->image['extension'],null);
        $this->image["name"] = md5($this->imageTextWater);
        //exit;
    }
    //删除单个文件方法
    public function fileDel($fileName){

        $filename = UPLOAD_PATH.$this->timeFlag.'/'.$fileName;
        echo $filename;
        if(file_exists($filename)){
            unlink($filename);
        }else{
            return 'file not exists!';
        }
        return $fileName;
    }

    public function fileUpload(Request $request)
    {
        $file_handle = $request -> file('bili_file');//接收获取文件句柄
        if (empty($file_handle)){
            return json(['error' => '请选择上传文件']);
        }
        $time_flag = date('Ymd'); //时间戳
        $file_name = iconv('utf-8','gbk',$file_handle -> getInfo()['name']);
        $file_path = str_replace('\\', '/', FILE_PATH).$time_flag;
        $info = $file_handle -> move($file_path, ''); //使用原文件名并且可以覆盖
        if ($info){
            $user_file = $time_flag .'/' . $info -> getSaveName();
            $hash_file = md5_file($file_path.'/'.$file_name);
            $result = Db::name('file')
                ->data([
                    'username' => Cookie::get('userData')['user'],
                    'userfile' => $user_file,
                    'hashfile' => $hash_file,
                ])
                ->insert();
            if ($result){
                $status = 0;
                $message = '文件上传成功';
            }else{
                $status = 2;
                $message = '文件数据上传数据库失败';
            }
        }else{
            $status = 1;
            $message = '文件上传失败';
        }
        return json(['status'=>$status, 'message'=>$message]);
    }

    public function fileDown(Request $req)
    {
        /*//转换到文件上传目录
        chdir(FILE_PATH);*/
        //文件下载之前先校验hash值(双方的)
        $FILE = new File();
        $_file_get = $req->get();
        $_file_name = $_file_get['file_down'];
        $_file = str_replace('\\', '/', FILE_PATH).$_file_name; //获取用户在浏览器希望下载的文件
        $_user = $_file_get['file_user'];
        if(!file_exists($_file))
        {
            return json(['msg' => 'error', 'data' => '文件不存在']);
        }
        //用户的目标文件在服务器端存储的hash值
        $condition=[
            'username'    =>    ['=', $_user],
            'userfile'    =>    ['=', $_file_name]
        ];
        $where = function ($query) use($condition){
            $query -> field('hashfile') -> where($condition);
        };
        $file_md5 = $FILE -> find($where)->getData()['hashfile'];
        //待校验的hash值
        $destination_md5 = md5_file($_file);
        if ($destination_md5 == $file_md5) {
            //echo "The file is ok.";
            if (!$this->download( $_file_name)){
                echo <<<EOF
<script>alert("文件下载过程出现错误,请重新下载");</script>
EOF;
                exit();
                //return json(['msg' => 'error', 'data' => '文件下载过程出现错误,请重新下载']);
            }
        }
        else {
            echo <<<EOF
<script>alert("文件已被篡改,该文件不可用");</script>
EOF;
            exit();
            //return json(['msg' => 'error', 'data' => '文件已被篡改,该文件不可用']);
        }
        echo <<<EOF
<script>alert("文件下载成功");</script>
EOF;
        //return json(['msg' => 'success', 'data' => '文件下载成功']);
        return 1;
    }

    public function download($file, $new_name='')
    {
        if(!isset($file)){
            return false;
        }
        $file_save = explode("/", $file)[1];
        $file_path = str_replace('\\', '/', FILE_PATH).$file;
        $file_name = basename($file_save);
        $file_group = explode('.',$file_save);
        $file_ext = $file_group[count($file_group)-1];
        $file_name = trim($new_name == '') ? $file_name : urlencode($new_name);
        //$file_handle = fopen($file_path,'r'); //打开文件
        //输入文件标签
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($file_path));
        header("Content-Disposition: attachment; filename=".$file_name);
        //输出文件内容
        /*echo fread($file_handle, filesize($file_path));
        fclose($file_handle);*/
        readfile($file_path);
        return true;
    }

    public function disFile()
    {
        //回馈上传文件的那些用户名信息
        return $this->view->fetch("upload/fileIndex");
    }

    public function userList()
    {
        //回馈上传文件的那些用户名信息
        $userList = Db::name('file')->field('username')->group('username')->paginate(3);
        $this->assign('userList', $userList); //注入所有用户名信息
        $this->assign('htmlInfo','userHtml');
        return $this->view->fetch("upload/userCtr");
    }
    public function fileList()
    {
        //回馈上传文件的那些文件名信息
        $data = Db::name('file')->field('username, userfile')->paginate(3);
        $this->assign('fileData', $data); //注入所有文件名信息
        $this->assign('htmlInfo','fileHtml');
        echo json_encode($this->view->fetch("upload/fileCtr"));
    }

}