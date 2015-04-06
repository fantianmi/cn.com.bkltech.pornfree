<?php
namespace Common\Model;
use Think\Model;

class MemberModel extends Model
{
/**
*获取用户的昵称
*@param   $uid     用户的id
*@return  string   昵称  
*/
	public function getNickname($uid){
		$nickname = $this->where("uid={$uid}")->getField('nickname');
		return $nickname ? $nickname : '';
	}
/**
*新增百度推送的用户信息
*@param  $uid          用户id
*@param  $user_id      APP百度获取
*@param  $channel_id   APP百度获取
*/
	public function addBaiUser($uid,$user_id,$channel_id,$type){
		$data = array(
			'uid'        => $uid,
			'user_id'    => $user_id,
			'channel_id' => $channel_id,
			'bType'      => $type
		);
		$result = $this->save($data);
		if($result !== false){
			$return = true;
		}else{
			$return = false;
		}
		return $return;
	}
/**
*获取百度推送的用户信息 user_id 和 channel_id
*@param  $uid  用户id
*/
	public function getBaiUser($uid){
		$map['uid'] = $uid;
		$field = array('user_id','channel_id','bType');
		$data  = $this->field($field)->where($map)->find();
		return $data;
	}
/**
 * 检查昵称是否重复
 * @param  int    $uid      用户id
 * @param  string $nickname 昵称
 * @return bool             重复返回 true 不重复 false
 */
	public function checkNicknames($uid,$nickname=''){
		$map['nickname'] = $nickname;
		$map['uid']      = array('neq',$uid);
		$result = $this->where($map)->find();
		return $result ? true : false;
	}

/**
 * 积分排行榜
 */
    public function rank($pagenum=1,$pagesize=10,&$totalcount=0){
        $totalcount = $this->where('status=1')->count();
        $map['status'] = 1;
        $map['uid']    = array('neq','99');
        $list = $this->field('uid,nickname,score')->where($map)->order('score DESC')->page($pagenum,$pagesize)->select();
        foreach($list as &$val){
            $val['avatar'] = query_user('avatar32',$val['uid']);
        }
        unset($val);
        return $list;
    }


}




