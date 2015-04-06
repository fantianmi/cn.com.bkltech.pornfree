<?php
namespace Common\Model;
use Think\Model;

class PojieRankModel extends Model
{
/**
 * 排行榜（ 按照最后破解时间排序 ）
 */
	public function rank($pagenum=1,$pagesize=10,&$totalcount=0){
		$totalcount = $this->where('status=1')->count();
		if($totalcount == 0) return '';//没有数据就直接返回
		$list = $this->where('status=1')->order('last_time DESC')->page($pagenum,$pagesize)->select();
		foreach ($list as &$val) {
			$info = query_user(array('nickname','avatar32'),$val['uid']);
			$val['nickname'] = $info['nickname'];
			$val['avatar']   = $info['avatar32'];
		}
		unset($val);
		return $list;
	}
/**
 * 向破解表插入数据的方法( 破解表uid是唯一索引 )
 * @param  [type] $uid [description]
 */
	public function pojie($uid){
		if(intval($uid) < 1) return '';
		if($this->isCheck($uid)){
			// 破过戒就是跟新
			return $this->updateInfo($uid);
		}else{
			// 没有破过戒就是新增
			return $this->addInfo($uid);
		}
	}
/**
 * 检查超过一天没签到默认为破解
 * @param  [type] $uid [description]
 * @return [type]      [description]
 */
	public function pojieTime($uid){
		// 今天有破戒记录了就不再操作了
		if($this->isCheckDay($uid)) return true;
		// 设置过期时间
		$time = strtotime(date('Ymd')) - 86400;
        // 判断用户以前是否有签到记录，没用签到记录就不操作
        if(!M('CheckInfo')->where('uid='.$uid)->find()) return true;
		// 获取用户最后次签到的时间
		$lastTime  = M('CheckInfo')->where('uid=' . $uid)->order('ctime DESC')->getField('ctime');
		// 最后次签到的时间小于了过期时间为破解了
		if($lastTime < $time){
			if($this->isCheck($uid)){//检查是否有破解记录
				return $this->updateInfo($uid,strtotime(date('Ymd')));
			}else{
				return $this->addInfo($uid,strtotime(date('Ymd')));
			}
		}
		return true;
	}
/**
 * 将状态修改为不破解
 * @param  [type] $uid [description]
 */
	public function editStatus($uid){
		// 检查是否有破解记录
		if($this->where('uid=' . $uid)->count() < 1) return false;

		$result = $this->where('uid=' . $uid)->save(array('status' => 0));
		if($result !== false) return true;
		return false;
	}



/**
 * 检查用户是否破过戒
 */
	public function isCheck($uid){
		$user = $this->where('uid=' . $uid)->count();
		return $user ? true : false;
	} 
/**
 * 新增破解用户
 */
	public function addInfo($uid,$time = NOW_TIME,$status = 1){
		$data = array(
				'uid'         => $uid,
				'create_time' => $time,
				'last_time'   => $time,
				'status'      => $status
			);
		return $this->add($data);
	}
/**
 * 跟新破解用户的最后破解时间
 */
	public function updateInfo($uid,$time = NOW_TIME,$status = 1){
		$result = $this->where('uid=' . $uid)->save(array('last_time' => $time, 'status' => $status));
		if($result !== false) return true;
		return false;
	}
/**
 * 检查今天是否破过戒
 * @param  [type]  $uid [description]
 */
	public function isCheckDay($uid){
		$fisrt            = strtotime(date('Ymd'));
		$last             = $fisrt + 86400;
		$map['uid']       = $uid;
		$map['last_time'] = array('between',array($fisrt,$last));
		$result           = $this->where($map)->count();
		return $result ? true : false;
	}
}