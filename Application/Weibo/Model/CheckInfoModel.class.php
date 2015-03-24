<?php
namespace Weibo\Model;

use Think\Model;

class CheckInfoModel extends Model
{
/**
 * 获取全国排行天数（ 按照连续签到天数 ）
 * @param  integer $firsts 当前页
 * @param  integer $nexts  页大小
 * @return array
 */
	public function getConNum($firsts=1,$nexts=20){
		$first = ($firsts-1)*$nexts;
		$next  = $nexts;
		/*连签天数须大于的时间戳*/
		$time = strtotime(date('Ymd')) - 86400; 
		$sql = "select uid,con_num c from (select * from thinkox_check_info t where t.ctime >={$time} order by ctime desc) a group by uid having con_num != 0 order by con_num desc limit {$first},$next";
		$data = M()->query($sql);
		/*----获取总记录数( 暂时没用 )----*/
		/*$sqlCount = "select count(*) c from
(select count(*) from thinkox_check_info group by uid) a";
		$re = M()->query($sqlCount);
		$count = $re[0]['c'];*/

		return $data;
	}


/**
 * 获取用户在全国排行( 按连签排的 )里的名次
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
	public function getRanking($uid){
		$info = S('count_'.$uid);
		if(!empty($info)) return $info;
		// 获取自己的连签天数
		$time = strtotime(date('Ymd')) - 86400;

		$map['uid']   = $uid;
		$map['ctime'] = array('gt',$time);
		$checkInfo    = $this->where($map)->order('ctime DESC')->find();
		$con_num      = $checkInfo['con_num']?$checkInfo['con_num']:0;

		$sql = "select * from (select * from thinkox_check_info t order by ctime desc) b group by uid having b.ctime > {$time} and b.con_num > {$con_num}";
		$data = M()->query($sql);
		$count = count($data) + 1;
		// 写入缓存
		S('count_'.$uid,$count,600);
		return $count;
	}
}