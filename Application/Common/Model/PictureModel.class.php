<?php
namespace Common\Model;
use Think\Model;

class PictureModel extends Model
{
/**
*只获取图片的路径
*@param   $id     图片的id
*@return  string  图片的路径
*/
	public function getPath($id){
		$path = $this->where("id={$id}")->getField('path');
		return $path ? $path : '';
	}
}




