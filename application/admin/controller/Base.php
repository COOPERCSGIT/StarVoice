<?php
namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
	public function _initialize()
    {
        $this->isLogin();
		$this->assign("admin",session('ext_admin'));
    }
	
	public function _empty($name)
    {
        //跳转到404
        return $this->error();
    }
	
	public function isLogin(){
		$request = request();
    	$act = $request->action();
		if(session('ext_admin')){		
			if($act=='index'){
				return $this->redirect('admin');
			}
		}else{
			if($act!='index'){
				return $this->redirect('index');
			}
		}
	}
}
