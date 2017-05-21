<?php
namespace app\admin\controller;
class User extends Base
{
    public function alluser()
    {
    	$where = array();
    	$stitle = "";
    	if(input("param.vid")){
    		$uids = model("favorite")->distinct(true)->where("vid",input("param.vid"))->column("uid");
    		$where['uid'] = array("in", $uids);
    		$stitle = "——收藏【".model("voice")->where('vid',input("param.vid"))->value("title")."】的用户";
    	}
    	
    	if(input("param.pid")){
    		$uids = model("focus")->distinct(true)->where("uid",input("param.pid"))->column("cid");
    		$where['uid'] = array("in", $uids);
    		$stitle = "——【".model("user")->where('uid',input("param.pid"))->value("nickname")."】关注的用户";
    	}
		if(request()->isAjax()){
			$list = model('user')->where($where)->order("addtime DESC")->select();
			foreach($list as $key=>$value){				
				$list[$key]['vnum'] = '<a href="'.request()->root.url('voice/allvoice',['uid'=>$value['uid']]).'">'.$value->nums['vnum'].'条</a>';
				$list[$key]['fnum'] = '<a href="'.request()->root.url('voice/allvoice',['fid'=>$value['uid']]).'">'.$value->nums['fnum'].'条</a>';
				$list[$key]['bnum'] = '<a href="'.request()->root.url('user/alluser',['pid'=>$value['uid']]).'">'.$value->nums['bnum'].'位</a>';
				$list[$key]['other'] = '<a href="javascript:;" data-url="'.request()->root.url('user/deluser',['id'=>$value['uid']]).'" class="btn btn-danger btn-sm btn-del">删除</a>';
			}			
			$source['aaData'] = $list;
			return json($source);
		}
		$this->assign("stitle",$stitle);	
    	return $this->fetch();
    }
	
	public function batchuser()
    {
    	$num = input("param.num/d");
		vendor("randname.rn");
		$rnd = new \rndChinaName();
		$result['type'] = 'error';				
        if (request()->isPost() && request()->isAjax() && $num>0){
        	$users = model("user")->column("uid");
			$cos = codeStr($num,$users);
					
			if(is_array($cos)){
				foreach ($cos as $key => $value) {
					if(!in_array($value, $users)){
						$data[$key]['uid'] = $value;
						$data[$key]['addtime'] = time()+rand(0,99);
						$data[$key]['nickname'] = $rnd->getName(2);
						$data[$key]['wxopenid'] = codeStr(1,$users,0,20,TRUE);
						$data[$key]['headpic'] = '20170101/touxiang.png';
						$data[$key]['type'] = 1;
						$data[$key]['pwd'] = md5('123456');
					}
				}
				$res = model('user')->insertAll($data,'',true);
			}else{					
				$data['uid'] = $cos;
				$data['nickname'] = $rnd->getName(2);
				$data['pwd'] = md5('123456');
				$data['addtime'] = time();
				$data['wxopenid'] = codeStr(1,$users,0,20,TRUE);
				$data['headpic'] = $head[array_rand($head)];
				$data['type'] = 1;
				$res = model('user')->insert($data);
			}
						
			if($res) {
				$result['type'] = 'success';
				$result['info'] = '已经成功生成'.$num."个用户";
			}else{
				$result['info'] = "生成用户失败";
			}
			
			return json($result);
        }else{
        	$result['info'] = '请填写用户生成数量！';
        }		
		
		
    	return $this->fetch();
    }
	
	public function deluser(){
		if (request()->isPost() && request()->isAjax()){	
			$id = input("param.id");
			if($id){
				$res = db("user")->delete($id);
				if($res){
					$result['type'] = 'success';
					$result['info'] = '已经成功删除用户';
				}else{
					$result['type'] = 'error';
					$result['info'] = '删除用户失败';
				}
			}else{
				$result['type'] = 'error';
				$result['info'] = '用户ID有误';
			}			
			return json($result);
		}
	}
}
