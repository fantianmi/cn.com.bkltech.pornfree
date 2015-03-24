<?php
namespace Face\Controller;
use Think\Controller;
class PublicController extends Controller{
/**
意见反馈处理
*/
	public function yijian(){
		$content = op_t(I('content'));
		$uid     = I('uid','','intval');
		if(empty($content)){ exit( json_encode(array('msg'=>'error','ret'=>100,'data'=>'')));}
		$data = array(
			'uid'    => $uid,
			'content'=>$content,
			'create_time'=>time()
		);
		if(M('yijian')->add($data)){
			echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
		}else{
			echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
		}
	}
/**
*获取百度推送user_id和channel_id添加到表里
*/
	public function getUserInfo(){
		$uid        = I('uid','','intval');
		$user_id    = I('user_id');
		$channel_id = I('channel_id');
		$type       = I('type','','strtolower');
		if(empty($uid) || empty($user_id)) exit( err(100,'用户uid和user_id不能为空') );
		/*检查类型*/
		if(!$this->checkType($type)) exit( err(200,'设备类型不合法') );

		$result = D('Member')->addBaiUser($uid,$user_id,$channel_id,$type);
		if($result){
			echo suc();
		}else{
			echo err(1,'绑定失败');
		}
	}
/**
*破解接口
**/
	public function pojie(){
		$uid = I('uid','','intval');
		if(empty($uid)) exit( err(100,'用户id不能为空') );
		/*检测今天是否签到*/
		$map['ctime'] = array('gt',strtotime(date('Ymd')));
		$map['uid']   = $uid;
		$map['con_num'] = 0;
		$model        = D('Check_info');
		$ischeck      = $model->where($map)->find();
		if($ischeck){
			echo err(200,'今天破戒了，无需再破戒');
		}else{
			/*获取签到总天数*/
			$total = $model->field('total_num')->where("uid={$uid}")->order('ctime DESC')->limit(1)->find();
			/*将连签天数改为0*/
			$content = '很遗憾！今天你已经破解。';
			$data = array(
				'uid'       => $uid,
				'con_num'   => 0,
				'total_num' => $total['total_num']?$total['total_num']:0,
				'ctime'     => NOW_TIME,
				'content'   => $content,
			);
			$result = $model->add($data);
			if($result){
				echo suc();
				D('JingCheck')->addData($uid,$content);
			}else{
				echo err(300,'破戒失败了,请联系管理员');
			}
		}
	}
/**
*检查设备类型是否合法
*@param   $type  只支持ios和android
*@return  bool
*/
	public function checkType($type){
		if(empty($type)) return false;
		$arr = array('ios','android');
		$type = strtolower($type);
		return in_array($type,$arr);
	}
	


	public function ceshi(){
		header("content-type:text/html;charset=utf-8");
		$sql = "select z.id zid,z.uid,z.name,z.create_time from thinkox_zhengzhuang z left join (select *,count(*) uc from thinkox_zhengzhuang_user group by zid) u ON z.id=u.zid where z.type=0 and z.status=1 order by u.uc DESC";
		$re = M()->query($sql);
		dump($re);
	}

}

