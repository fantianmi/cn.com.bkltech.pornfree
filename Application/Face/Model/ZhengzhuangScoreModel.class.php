<?php
// +----------------------------------------------------------------------
// | Author: 说好吥倣手  < 1018808441@qq.com >
// +----------------------------------------------------------------------
namespace Face\Model;
use Think\Model;

class ZhengzhuangScoreModel extends Model 
{
/**
*@var      获取症状的分数( 当天的分数 )
*@param    @uid   用户的id
*@param    @zid   症状的id
*@return   int    用户症状的分数（ 没有就返回空 ）
*/
	public function getZScore($uid,$zid){
		$map['uid'] = $uid;
		$map['zid'] = $zid;
		/*默认只获取最后一次的分数，没有就返回空*/
		$score = $this->field('score')->where($map)->order('create_time DESC')->limit(1)->find();
		// $score = $this->where($map)->getField('score');
		return $score ? $score['score'] : '';
	}
}