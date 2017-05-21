<?php
namespace app\admin\controller;
class Voice extends Base
{
    public function allvoice()
    {
    	$where = array();
    	$stitle = "";
    	if(input("param.uid")){
    		$where['uid'] = input("param.uid");
    		$stitle = "——用户【".model("user")->where('uid',input("param.uid"))->value("nickname")."】发布的语音";
    	}
    	if(input("param.fid")){
    		$vids = model("favorite")->distinct(true)->where("uid",input("param.fid"))->column("vid");
    		$where['vid'] = array("in", $vids);
    		$stitle = "——用户【".model("user")->where('uid',input("param.fid"))->value("nickname")."】收藏的语音";
    	}
		if(request()->isAjax()){
			$list = model('voice')->where($where)->order("addtime DESC")->select();
			foreach($list as $key=>$value){
				$list[$key]['title'] = '<a href="'.$value['reurl'].'" target="_blank">'.$value['title'].'</a>';
				$list[$key]['fav_num'] = $value['fav_num'] ? '<a href="'.request()->root.url('user/alluser',['vid'=>$value['vid']]).'">'.$value['fav_num'].'次</a>' : '0次';
				$list[$key]['play_num'] = $value['play_num'].'次';
				$list[$key]['comment'] = $value->comments ? '<a href="'.request()->root.url('voice/comments',['vid'=>$value['vid']]).'">'.$value->comments.'次</a>' : '0次';
				$list[$key]['other'] = '<a href="'.$value['allbackgrund'].'" target="_blank" class="btn btn-info btn-sm">查看原图</a> <a href="javascript:;" data-url="'.request()->root.url('voice/delvoice',['id'=>$value['vid']]).'" class="btn btn-danger btn-sm btn-del">删除</a>';
				$list[$key]['is_hot'] = $value['is_hot'] ? '<a href="javascript:;" data-url="'.request()->root.url('voice/tjvoice',['id'=>$value['vid'],'is_hot'=>0]).'" class="btn btn-success btn-sm btn-other">是</a>' : '<a href="javascript:;" data-url="'.request()->root.url('voice/tjvoice',['id'=>$value['vid'],'is_hot'=>1]).'" class="btn btn-sm btn-default btn-other">否</a>';
			}			
			$source['aaData'] = $list;
			return json($source);
		}
		$this->assign("stitle",$stitle);	
    	return $this->fetch();
    }
	
	public function delvoice(){
		if (request()->isPost() && request()->isAjax()){	
			$id = input("param.id");
			if($id){
				$res = model("voice")->where("vid",$id)->delete();
				if($res){
					$result['type'] = 'success';
					$result['info'] = '已经成功删除语音';
				}else{
					$result['type'] = 'error';
					$result['info'] = '删除语音失败';
				}
			}else{
				$result['type'] = 'error';
				$result['info'] = '语音ID有误';
			}			
			return json($result);
		}
	}
	
	public function tjvoice(){
		if (request()->isPost() && request()->isAjax()){	
			$id = input("param.id");			
			$is_hot = input("param.is_hot/d");
			if($id && $is_hot>=0){
				$data['is_hot'] = $is_hot;
				$res = model("voice")->update($data,array('vid'=>$id));
				if($res){
					$str = $is_hot ? "推荐热门" : "撤销热门";
					$result['type'] = 'success';
					$result['info'] = '已经成功'.$str.'语音';
				}else{
					$result['type'] = 'error';
					$result['info'] = $str.'语音失败';
				}
			}else{
				$result['type'] = 'error';
				$result['info'] = '语音ID有误';
			}			
			return json($result);
		}
	}
	
	public function addyuyin(){
		$type = model("type")->field("tid,title")->order("sort ASC")->select();
		$this->assign("type",$type);
		
		$users = model("user")->field("uid,nickname,type")->order("addtime DESC")->select();
		$this->assign("users",$users);
				
		$result['type'] = 'error';			
        if (request()->isPost() && request()->isAjax()){
        	$data = myUpload(input('param.uid'));
			
			$info = input('param.');
			if(is_array($data) && is_array($info)){	
				$vids = model('voice')->column("vid");
        		$data['vid'] = codeStr(1,$vids);
				$data['addtime'] = time();
				$data = array_merge($data,$info);
				$res = model('voice')->insert($data);					
				if($res) {
					$file_path = 'http://snsbao.com/yuliao/评论库.txt';
					$comments = getRequest($file_path);
					$comments = array_merge(array_filter(explode(PHP_EOL, $comments)));	
					$uids = model("user")->column("uid");
					$num = rand(5,10);
					$cids = model('comments')->column("cid");
        			for($i=0; $i<$num ; $i++){
						$cdata[$i]['cid'] = codeStr(1,$cids);
						$cids = array_merge($cids,$cdata);	
						$ui = array_rand($uids);	
						$cdata[$i]['uid'] = $uids[$ui];
						$cms = array_rand($comments);
						$cdata[$i]['content'] = $comments[$cms];
						$cdata[$i]['vid'] = $data['vid'];
						$cdata[$i]['addtime'] = time()+rand(60,360);
						unset($comments[$cms]);
						unset($uids[$ui]);	
					}
					model('comments')->insertAll($cdata,'',true);
					
					$result['info'] = '语音【'.$data['title'].'】已经成功添加';
					$result['type'] = 'success';
				}else{
					$result['info'] = '语音【'.$data['username'].'】添加失败';
				}
			}else{
				$result['info'] = $data;
			}
			return json($result);
        }	
		
    	return $this->fetch();
	}
	
	public function comments()
    {
    	$where['vid'] = input('param.vid');
		$this->assign("vname",model('voice')->where($where)->value("title"));
		if(request()->isAjax()){
			$list = model('comments')->where($where)->order("addtime DESC")->select();
			foreach($list as $key=>$value){				
				$list[$key]['other'] = '<a href="javascript:;" data-url="'.request()->root.url('voice/delvoice',['id'=>$value['vid']]).'" class="btn btn-danger btn-sm btn-del">删除</a>';
			}			
			$source['aaData'] = $list;
			return json($source);
		}	
    	return $this->fetch();
    }
}
