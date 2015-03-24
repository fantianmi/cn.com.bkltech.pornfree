<?php
namespace Common\Model;
use Think\Model;

class ShibuUserModel extends Model
{
/**
 * 获取食补完成的人数 ( 有多少人完成过某个食补 )
 * @param  int  $id  食补id
 * @param  int  $uid 用户id
 * @return int  
 */
	public function getCount($id,$uid){
		$map['sid'] = $id;
		$count = $this->where($map)->group($uid)->count();
		return $count;
	}
}




