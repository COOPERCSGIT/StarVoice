<?php
namespace app\admin\model;
use think\Model;

class User extends Model
{
	public function getAddtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
	
	public function getStatusAttr($value)
    {
    	return $value ? '正常' : "禁用";
    }
	
	//获取当前用户更多数据
	public function getNumsAttr($value,$data)
    {
    	//获取当前用户发布语音数量
    	$nums['vnum'] = model("voice")->where("uid",$data['uid'])->count();
    	//获取当前用户收藏语音数量
    	$nums['fnum'] = model("favorite")->where("uid",$data['uid'])->count();
    	//获取当前用户关注的用户数量
    	$nums['bnum'] = model("focus")->where("uid",$data['uid'])->count();		
		//获取当前关注该用户的用户数量
    	$nums['cnum'] = model("focus")->where("cid",$data['uid'])->count();
        return $nums;
    }
}