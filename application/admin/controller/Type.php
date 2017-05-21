<?php
namespace app\admin\controller;
class Type extends Base
{
	public function allclass()
    {    	
    	if(request()->isAjax()){
    		$sEcho = input("param.sEcho/d");
			$ds = input("iDisplayStart/d");
			$length = input("param.iDisplayLength/d");
			$column = input("param.iSortCol_0/d");
			$sort = input("param.sSortDir_0/s");
    		$list = model('type')->where("type='voice' AND pid=0")->order("sort ASC")->limit($ds, $length)->select();
			foreach($list as $key=>$value){
				$list[$key]['other'] = '<a href="'.request()->root.url('type/addclass',['tid'=>$value['tid']]).'" class="btn btn-primary">编辑</a> <a href="javascript:;" data-url="'.request()->root.url('type/delclass',['id'=>$value['tid']]).'" class="btn btn-danger btn-del">删除</a>';
			}
			$total = model("type")->where("type='voice' AND pid=0")->count();			
			$source['sEcho'] = $sEcho;
	        $source['iTotalDisplayRecords'] = $total;
	        $source['iTotalRecords'] = $total; //总共有几条数据				
			$source['aaData'] = $list;
			return json($source);
		}	
    	return $this->fetch();
    }
    
    public function addclass()
    {
    	$where['tid'] = input("param.tid");
		$type = model("type")->where($where)->find();
		$this->assign("type",$type);
				
		$result['type'] = 'error';			
        if (request()->isPost() && request()->isAjax()){
        	$data['title'] = input('param.title/s');
        	$data['pid'] = input('param.pid/d');
			$data['sort'] = input('param.sort/d');
			$data['type'] = 'voice';
        	if($type){	
				$res = model('type')->where($where)->update($data);
				if($res) {
					$result['info'] = '分类【'.$data['title'].'】已经成功修改';
					$result['type'] = 'success';
				}else{
					$result['info'] = '分类【'.$data['title'].'】修改失败';
				}
        	}else{
        		$tids = model('type')->column("tid");
        		$data['tid'] = codeStr(1,$tids,1);
				$data['addtime'] = time();
				$res = model('type')->insert($data);					
				if($res) {
					$result['info'] = '分类【'.$data['title'].'】已经成功添加';
					$result['type'] = 'success';
				}else{
					$result['info'] = '分类【'.$data['username'].'】添加失败';
				}
			}
			return json($result);
        }	
		
    	return $this->fetch();
    }
    
    public function delclass(){
		if (request()->isPost() && request()->isAjax()){	
			$id = input("param.id");
			if($id){
				$res = model("type")->where("tid",$id)->delete();
				if($res){
					$result['type'] = 'success';
					$result['info'] = '已经成功删除分类';
				}else{
					$result['type'] = 'error';
					$result['info'] = '删除分类失败';
				}
			}else{
				$result['type'] = 'error';
				$result['info'] = 'ID有误';
			}			
			return json($result);
		}
	}
}
