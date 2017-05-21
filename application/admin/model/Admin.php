<?php
namespace app\admin\model;
use think\Model;

class Admin extends Model
{
	public function getAddtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
	
	public function getStatusAttr($value)
    {
    	return $value ? '正常' : "禁用";
    }
}