<?php
namespace Common\Model;
use Think\Model;

class JingchongModel extends Model
{
/**
*获取用户的精虫上脑列表
*@return  array
*/
	public function getUserList($uid,$order='ctime DESC')
	{
		$map['uid'] = $uid;
		$list = $this->where($map)->order($order)->select();
		return $list;
	}
/**
 *检查用户是否上过脑
 *@return  bool
 */
    public  function isShang($uid){
        $re = $this->where('uid='.$uid)->find();
        if($re){
            return true;
        }else{
            return false;
        }
    }
}




