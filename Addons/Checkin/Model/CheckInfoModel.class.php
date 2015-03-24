<?php
namespace Addons\Checkin\Model;
use Think\Model;

/**
*@description 签到表的模型
*@Author    説好吥倣手 < 1018808441@qq.com >
*/

class CheckInfoModel extends Model
{
/**
*@description   获取用户的连签天数( 最后一次的签到时间超过2天连签天数为0 )
*@param  $uid   用户的id
*@return int    成功返回连签天数（int ）
*/
	public function getCheckDay($uid){
		if(!$uid) return 0;
		$data = $this->where("uid={$uid}")->order("ctime DESC")->limit(1)->find();
		/*没有数据返回0*/
		if(!$data) return 0;

		$time = NOW_TIME - $data['ctime'];
		$time = floor($time/(24*3600));

		if($time > 1){//超过1天的返回0
			return 0;
		}else{
			return $data['con_num'];
		}
	}
}


