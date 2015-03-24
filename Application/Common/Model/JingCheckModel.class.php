<?php
namespace Common\Model;
use Think\Model;

class JingCheckModel extends Model
{
/**
 * 添加数据
 * @param int  $uid        用户id
 * @param string  $content 内容
 * @param integer $type    类型 1：签到信息 2：上脑信息
 */
	public function addData($uid,$content='',$type=1){
		if(empty($uid)) return false;
		$data = array(
			'uid'         => $uid,
			'content'     => $content,
			'create_time' => NOW_TIME,
			'type'        =>$type
		);
		$result = $this->add($data);
		return $result;
	}
}




