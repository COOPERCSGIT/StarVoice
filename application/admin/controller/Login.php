<?php
namespace app\admin\controller;

class Login extends \think\Controller
{
    public function index()
    {
    	$where['username'] = input('request.username');
      	$where['pwd']  = md5(input('request.pwd'));
		$admin = db("admin")->where($where)->find();
		if($admin){
			unset($admin["pwd"]);
            session("ext_admin", $admin);
			return $this->redirect('index/admin');
		}else{
			$this->error('用户名或密码有误！');
		}
    }
	
	public function logout(){
		session('ext_admin',null);
		$this->redirect("index/index");
	}
}
