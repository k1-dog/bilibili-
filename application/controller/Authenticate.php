<?php


namespace app\controller;
use app\model\User;
use think\Request;
use think\Controller;
use think\Auth;
use think\facade\Cookie;


class Authenticate extends Controller
{
    public function auth()
    {
        $rules = $_SESSION['referer_url'];

        //获得当前页面的控制器 / 方法
        /*$request=$this->request;
        $moudle=$request->module(); //获取当前模块名称
        $con=$request->controller(); //获取当前控制器名称
        $action=$request->action();  //获取当前方法名称
        $rules=$con.'/'.$action;  //组合  控制器/方法
        echo $rules."<br>";*/
        $auth=new Auth(); //实例化auth类
        $notCheck=array('Index/index','Index/login','Index/dologin','Index/logout');  //都可以访问的页面
        $bili = new User('kulumi');
        $superUser = $bili -> where('username','kulumi') ->value('id');
        if($_SESSION['userData']['valid']!=$superUser) {  //不是超级管理员才进行权限判断
            if (!in_array($rules, $notCheck)) {  // 是否在开放权限里面
                if (!$auth->check($rules, $_SESSION['userData']['valid'])) {  // 第一个参数-控制/方法;第二个参数-当前登陆会员的id
                    //$this->redirect(Session::get('url')); //跳转
                    echo "您无该页面的访问权限";
                }
                else{
                    echo "您成功访问了".$rules.'页面</br>';
                }
            }
        }
    }
    public function checkLogin()
    {
        $_post = $this->request->post();
        if (!empty($_post['name']) && !empty($_post['pwd'])) {
            $uName = $_post['name'];
            $biliUser = new User($uName);
            $where = function ($query) use($uName){
                $query -> field(['password','id']) -> where('username','=',$uName);
            };
            $result = $biliUser->find($where)->getData();
            if($result['id']) //result is 1 means that user is existed
            {
                print_r(base64_encode($_post['pwd']));
                if(base64_encode($_post['pwd']) == $result['password']){
                    Cookie::set('userData', array(
                        'valid' => 1,
                        'uid' => $result['id'],
                        'pwd' => $result['password'],
                        'user' => $uName,
                    ),null);
                    //$this->auth(); 进行权限的认证
                    $this->redirect(ROOT_PATH.'/public/login'); //跳转
                }else{
                    echo "密码不正确";
                }
            }else{
                echo "用户不存在";
            }

        }else{
            echo "您输入的验证信息不完全";
        }
    }

}