<?php
namespace Common\Model;
use Think\Model;

class ZhengzhuangUserModel extends Model
{
/**
*获取用户的症状
*@param   $uid    用户id
*@return  array
*/
	public function getUserZheng($uid)
	{
		// header("content-type:text/html;charset=utf-8");
		$map['thinkox_zhengzhuang_user.uid'] = $uid;
		$field = array(
			'thinkox_zhengzhuang_user.zid',
			'thinkox_zhengzhuang.name',
			'thinkox_zhengzhuang_user.create_time'
		);
		$order = 'thinkox_zhengzhuang_user.create_time DESC';
		$zhengList = $this->field($field)->where($map)->join('__ZHENGZHUANG__ ON __ZHENGZHUANG__.id=thinkox_zhengzhuang_user.zid','LEFT')->order($order)->select();
		return $zhengList;
	}
/**
 * 获取有当前症状的所有人的信息
 * @param  [type] $zid         [description]
 * @param  [type] $uid         [description]
 * @param  [type] $pagenum     [description]
 * @param  [type] $pagesize    [description]
 * @param  [type] &$totalCount [description]
 * @return [type]              [description]
 */
	public function getUser($zid,$uid,$pagenum,$pagesize,&$totalCount){
		$map['zid'] = $zid;
		$map['uid'] = array('neq', $uid);
		$list = $this->where($map)->page($pagenum,$pagesize)->select();
		if(!$list) return '';
		$totalCount = $this->where($map)->count();
		$arr = array('nickname','title','avatar32');
		foreach ($list as $k => $val) {
			$users = query_user($arr, $val['uid']);
			$list[$k]['nickname'] = $users['nickname'];
			$list[$k]['avatar']   = $users['avatar32'];
			$list[$k]['title']    = $users['title'];
		}
		// dump($list);
		return $list;
	}
/**
 * 检查用户是否有症状
 * @param  [type]  $uid 用户id
 * @return boolean      [description]
 */
	public function isUserZheng($uid){
		if(empty($uid)) return false;
		$result = $this->where('uid='.$uid)->find();
		if($result) return true;
		return false;
	}
/**
 * 获取相同症状的人数（ 包括自己 ）
 * @param  int   $zid 症状的id
 * @return int
 */
	public function getCount($zid){
		$c = S('zhengzhuang_user_'.$zid);
		if(!empty($c)) return $c;
		
		$count = $this->where('zid='.$zid)->count();
		$count = $count ? $count : 0;
		S('zhengzhuang_user_'.$zid,$count,600);
		return $count;
	}

}





