<?php
namespace Common\Model;
use Think\Model;

class ZhengzhuangScoreModel extends Model
{
/**
*获取用户症状的 平均分、打分次数、最高分、最低分
*/
	public function getAvgAndCount($zid,$uid){
		$avg    = $this->getZhengAvg($zid,$uid);
		$count  = $this->getZhengCount($zid,$uid);
		$first  = $this->getZhengFirst($zid,$uid);
		$last   = $this->getZhengLast($zid,$uid);
		$return = array(
			'scoreAvg'   => $avg,
			'scoreCount' => $count,
			'scoreFirst' => $first,
			'scoreLast'  => $last
		);
		return $return;
	}
/**
*获取用户症状的平均分
*@param  $zid  症状id
*@param  $uid  用户id
*@return int
*/
	public function getZhengAvg($zid,$uid){
		$map['uid'] = $uid;
		$map['zid'] = $zid;
		$score = $this->where($map)->avg('score');
		return $score?$score:0;
	}
/**
*获取用户症状的打分次数
*@param  $zid  症状id
*@param  $uid  用户id
*@return int
*/
	public function getZhengCount($zid,$uid){
		$map['uid'] = $uid;
		$map['zid'] = $zid;
		$count = $this->where($map)->count();
		return $count;
	}
/**
*获取用户症状的最高分
*@param  $zid  症状id
*@param  $uid  用户id
*@return int
*/
	public function getZhengFirst($zid,$uid){
		$map['uid'] = $uid;
		$map['zid'] = $zid;
		$first = $this->where($map)->max('score');
		return $first?$first:0;
	}
/**
*获取用户症状的最低分
*@param  $zid  症状id
*@param  $uid  用户id
*@return int
*/
	public function getZhengLast($zid,$uid){
		$map['uid'] = $uid;
		$map['zid'] = $zid;
		$last = $this->where($map)->min('score');
		return $last?$last:0;
	}
}




