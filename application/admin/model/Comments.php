<?php
namespace app\admin\model;
use think\Model;

class Comments extends Model
{
	public function getAddtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
	
	public function getTypeAttr($value)
    {
    	$types = array("0"=>'文字','1'=>'语音');
        return $types[$value]; 
    }
	
	public function getUidAttr($value)
    {
        return model("user")->where("uid",$value)->value("nickname");
    }
	
	public function getUserFaceAttr($value,$data)
    {
        return model("user")->where("uid",$data['uid'])->value("headpic");
    }
	
	public function getContentAttr($value,$data)
    {
        return $data['type'] ? '<a href="'.$value.'" target="blank" style="text-decoration:none">点击播放 <em class="fa fa-play-circle"></em></a>' : $value; 
    }
}