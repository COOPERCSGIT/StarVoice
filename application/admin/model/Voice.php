<?php
namespace app\admin\model;
use think\Model;

class Voice extends Model
{
	public function getAddtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
    
    public function getUidAttr($value)
    {
        return model("user")->where("uid",$value)->value("nickname");
    }
	
	public function getFaceAttr($value,$data)
    {
        return model("user")->where("uid",$data['uid'])->value("headpic");
    }
	
	public function getFavnumAttr($value)
    {
        return $value ? $value : 0;
    }
	
	public function getCommentsAttr($value,$data)
    {
        return model("comments")->where("vid",$data['vid'])->count();
    }
	
	public function getVoiceCommentAttr($value,$data)
    {
    	$wh['vid'] = $data['vid'];
		$wh['type'] = 1;
    	$comments = model("comments")->where($wh)->field("uid,cid,type,content,zan,clength")->order("zan DESC")->select();
        $list = array();
		foreach($comments as $key=>$row){
			$list[$key]['cid'] = $row['cid'];
			$list[$key]['user'] = $row['uid'];
			$list[$key]['uid'] = $row->getData("uid");
			$list[$key]['headpic'] = $row->userface;
			$list[$key]['content'] = $row->getData("content");
			$list[$key]['zan'] = $row['zan'];
			$list[$key]['clength'] = $row['clength'];
		}
        return $list;
    }
	
	public function getTxtCommentAttr($value,$data)
    {
    	$wh['vid'] = $data['vid'];
		$wh['type'] = 0;
    	$comments = model("comments")->where($wh)->field("cid,uid,type,content,zan,clength")->order("zan DESC")->select();
		$list = array();
		foreach($comments as $key=>$row){
			$list[$key]['cid'] = $row['cid'];
			$list[$key]['uid'] = $row->getData("uid");			
			$list[$key]['user'] = $row['uid'];
			$list[$key]['headpic'] = $row->userface;
			$list[$key]['content'] = $row['content'];
			$list[$key]['zan'] = $row['zan'];
			$list[$key]['clength'] = $row['clength'];
		}
        return $list;
    }
	
	public function getFocusAttr($value,$data)
    {
    	$focus = model("focus")->where('cid',$data['uid'])->find();
		
        return $focus ? 1 : 0;
    }
	
	public function getPlaynumAttr($value)
    {
        return $value ? $value : 0;
    }
    
    public function getTidAttr($value)
    {
        return model("type")->where("tid",$value)->value("title");
    }
}