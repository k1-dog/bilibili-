<?php


namespace app\controller;
use think\facade\Session;

class Index extends Authenticate
{
    private $loginFlag = 1;
    public function initialize()
    {
        $request=$this->request;
        $con=$request->controller(); //获取当前控制器名称
        $action=$request->action();  //获取当前方法名称
        $rules=$con.'/'.$action;  //组合  控制器/方法
        Session::set('referer_url',$rules);
        return $this->authLogin();
    }

    public function index()
    {
        //return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }
    public function authLogin()
    {
        if (!empty($_SESSION['userData']['valid'])) {
            echo "您现在拥有的身份凭证为" . $_SESSION['userData']['valid'] . '</br>';
            Authenticate::auth();
        } else {
            try {
                $this->loginFlag = 0;
                echo $this->view->fetch('login/authLogin');
            } catch (\Exception $e) {
            }
        }
    }
    public function deepAccess()
    {
        if ($this->loginFlag){
            echo "Deep Auth Check Method!"."<br>";
        }
    }
    public function rule5()
    {
        if ($this->loginFlag){
            echo "rule5 Auth Success!"."<br>";
        }
    }
    public function kulumi()
    {
        if ($this->loginFlag){
            echo "kulumi Auth Success!"."<br>";
        }
    }
    public function natsumi()
    {
        if ($this->loginFlag){
            echo "natsumi Auth Success!"."<br>";
        }
    }
}