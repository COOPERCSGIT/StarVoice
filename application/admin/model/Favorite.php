<?php
namespace app\admin\model;
use think\Model;

class Favorite extends Model
{
	public function getAddtimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }
}