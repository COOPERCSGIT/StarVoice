<?php
namespace app\admin\controller;

class Index extends Base
{
    public function index()
    {
    	return $this->fetch();
    }
	
	public function admin()
    {
    	$this->assign("daylogs", daylogs('2017-02-01'));
    	return $this->fetch();
    }
	
	public function account()
    {
    	$this->assign("info",'');
    	if(request()->isPost()){
			$where['username'] = input('param.username');
			if(input('param.oldpwd')) $where['pwd'] = md5(input('param.oldpwd'));
			$admin = db("admin")->where($where)->find();
			if($admin){
	    		$file = request()->file('headpic');
				if($file){
				    // 移动到框架应用根目录/public/uploads/ 目录下
				    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				    if($info){
				        $data['headpic'] = str_replace('\\','/',$info->getSaveName());
				    }else{
						$this->error($file->getError());
				    }
				}
				if(input('param.oldpwd')!="" && input('param.pwd')!=""){
					$data['pwd'] = md5(input('post.pwd'));
				}		
				$res = db("admin")->where($where)->update($data);
				if($res){
					if(input('param.oldpwd')){
						session('ext_admin',null);
						$this->success('管理员密码修改成功，需要重新登陆！','index/index');
					}else{
						$this->success('修改成功','index/admin');
					}
				}else{
					@unlink($data['headpic']);
					$this->success('管理员密码修改失败！');
				}
			}else{
				$this->assign("info",'登陆密码不正确！');
			}			
    	}
    	return $this->fetch();
    }
}
