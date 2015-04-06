<?php
namespace Inter\Controller;


/**
 * 新增或修改的接口文件
 */
class CenterController extends CommonController
{
/**
 * 关注接口
 * @param  int    $who_follow   谁关注
 * @param  int    $follow_who   被关注
 */
	public function follow(){
		$who_follow = I('who_follow',0,'intval');
		$follow_who = I('follow_who',0,'intval');
		if($who_follow < 1 || $follow_who < 1)
			exit( err(100,'关注人和被关注人id不能为空') );

		if(D('Follow')->hg_follow($who_follow, $follow_who)){
			echo suc('','关注成功了');
			// 推送消息
			$nickname = query_user('nickname',$who_follow);
			$title = $nickname . '关注了您';
			$re = D('BaiTui')->genDataOne($follow_who,$title,$title,'attention');
		}else{
			echo err(1,'关注失败');
		}
	}
/**
 * 取消关注接口
 * @param  int    $who_follow   谁关注
 * @param  int    $follow_who   被关注
 */
	public function unfollow(){
		$who_follow = I('who_follow');
		$follow_who = I('follow_who');
		if(empty($who_follow) || empty($follow_who))
			exit( err(100,'关注人和被关注人id不能为空') );

		if(D('Follow')->hg_unfollow($who_follow, $follow_who)){
			echo suc('','取消关注成功');
		}else{
			echo err(1,'取消关注失败');
		}
	}
/**
 * 获取粉丝与关注接口 （ 带分业 ）
 * @param int $uid      谁的粉丝与关注
 * @param int $pagenum  当前页
 * @param int $pagesize 页大小
 */
	/*public function fansFollowing(){
		$uid      = I('uid',0,'intval');
		$pagenum  = I('pagenum',1);
		$pagesize = I('pagesize',10);
		if(!$uid) exit( err(100,'用户id不能为空') );
		$follow = D('Follow');
		// 获取粉丝
		$fans = $follow->hg_getFans($uid, $pagenum, array('avatar32', 'uid', 'nickname', 'title'), $totalCount, $pagesize);
		$fanss = calculation($pagenum,$pagesize,$totalCount,$fans);
		// 获取关注
		$following = $follow->hg_getFollowing($uid, $pagenum, array('avatar32', 'uid', 'nickname', 'title'), $totalCounts, $pagesize);
		$follows   = calculation($pagenum,$pagesize,$totalCounts,$following);
		// 组合数据
		$data = array(
			'fans'   => $fanss,  //粉丝
			'follow' => $follows //关注
		);
		echo suc($data);
	}*/
/**
 * 粉丝列表接口
 */
	public function fansList(){
		$uid      = I('uid',0,'intval');
		$pagenum  = I('pagenum',1);
		$pagesize = I('pagesize',10);
		if(!$uid) exit( err(100,'用户id不能为空') );
		$follow = D('Follow');
		// 获取粉丝
		$fans = $follow->hg_getFans($uid, $pagenum, array('avatar32', 'uid', 'nickname', 'title'), $totalCount, $pagesize);
		echo sucp($pagenum, $pagesize, $totalCount?$totalCount:0, $fans);
	}
/**
 * 关注列表接口
 */
	public function followList(){
		$uid      = I('uid',0,'intval');
		$pagenum  = I('pagenum',1);
		$pagesize = I('pagesize',10);
		if(!$uid) exit( err(100,'用户id不能为空') );
		$follow = D('Follow');
		// 获取关注
		$following = $follow->hg_getFollowing($uid, $pagenum, array('avatar32', 'uid', 'nickname', 'title'), $totalCounts, $pagesize);
		echo sucp($pagenum, $pagesize, $totalCounts, $following);
	}
/**
 * 创建聊天对象（ 点击私信的时候需要访问的方法 ）
 * @param  int  $uid    发起人的id
 * @param  int  $to_uid 被发起人的id
 */
	public function createTalk(){
		$uid    = I('uid','','intval');
		$to_uid = I('to_uid','','intval');
        // 不能与自己聊天
        if($uid == $to_uid) exit( err(100,'参数错误') );
        
		if(empty($uid) || empty($to_uid))
			exit( err(100, '参数出错') );
        $memebers = explode(',', $to_uid);
        $talk = D('Common/Talk')->hg_createTalk($memebers, '', $uid);
        if(!$talk['id']) exit( err(200, '创建聊天对象失败') );

        echo suc(array('talk_id'=>$talk['id']),'创建成功');
        // return $talk['id'];
	}

/**
 * 删除聊天对象
 * @param int $talk_id  聊天对象的id
 */
    public function delTalk(){
        $talk_id = I('talk_id',0,'intval');
        if($talk_id < 1) exit( err(100,'聊天对象id不能为空') );
        if(D('Talk')->where('id='.$talk_id)->delete()){
            echo suc('','删除成功');
        }else{
            echo err(1,'删除失败');
        }
    }

/**
 * 我的私信聊天列表接口
 * @return [type] [description]
 */
	public function session()
    {
    	$uid      = I('uid');
    	$pagenum  = I('pagenum',1);
        $pagesize = I('pagesize',10);
        if(empty($uid)) exit( err(100, '用户id不能为空') );
        

        $talk = D('Talk');
        $talks = $talk->where('uids like' . '"%[' . $uid . ']%"' . ' and status=1')->order('update_time desc,create_time desc')->page($pagenum,$pagesize)->select();
        $count = $talk->where('uids like' . '"%[' . $uid . ']%"' . ' and status=1')->order('update_time desc,create_time desc')->count();
        foreach ($talks as $key => $v) {
            $users      = array();
            $uids_array = $talk->getUids($v['uids']);
            foreach ($uids_array as $uids) {
                if($uids != $uid)
                    $users = query_user(array('avatar32', 'nickname',  'uid'), $uids);
            }
            $talks[$key]['users']        = $users;
            $talks[$key]['last_message'] = $talk->getLastMessage($talks[$key]['id']);
        }
        // 数据有点多乱（ 重新组装需要的 ）
        $data = array();
        foreach ($talks as $val) {
        	$time = $val['last_message']['create_time'] ? $val['last_message']['create_time'] : 0;
            $data[] = array(
                'uid'          => $val['users']['uid'],
                'nickname'     => $val['users']['nickname'],
                'avatar'       => $val['users']['avatar32'],
                'content'      => $val['last_message']['content'],
                'create_time'  => $time,
                'talk_id'      => $val['id']
            );
        }
        echo sucp($pagenum,$pagesize,$count,$data,'操作成功');
    }
/**
 * 打开一个聊天窗接口
 */
    public function getSession(){
    	$id       = I('talk_id','','intval');
    	$_uid     = I('uid','','intval');
    	$pagenum  = I('pagenum',1);
    	$pagesize = I('pagesize',10);
    	if(empty($id) || empty($_uid)) exit( err(100,'缺少参数') );
        //获取当前聊天
        $talk = $this->getTalk(0, $id, $_uid);
        // dump($talk);
        $uids = D('Talk')->getUids($talk['uids']);
        foreach ($uids as $uid) {
            if ($uid != $_uid) {
                $talk['first_user'] = query_user(array('avatar32', 'username'), $uid);
                $talk['ico'] = $talk['first_user']['avatar32'];
                break;
            }
        }
        $map['talk_id'] = $talk['id'];
        D('TalkPush')->where(array('uid'=>get_uid(),'source_id'=>$id))->setField('status',-1);
        D('TalkMessagePush')->where(array('uid'=>get_uid(),'talk_id'=>$id))->setField('status',-1);
        $messages = D('TalkMessage')->where($map)->order('create_time desc')->page($pagenum,$pagesize)->select();
        $count = D('TalkMessage')->where($map)->count();
        $messages = array_reverse($messages);
        foreach ($messages as &$mes) {
            $info = query_user(array('avatar32', 'nickname'), $mes['uid']);
            $mes['nickname'] = $info['nickname'];
            $mes['avatar']   = $info['avatar32'];
        }
        unset($mes);
        $talk['messages'] = $messages;
        // $talk['self'] = query_user(array('avatar32'), $_uid);
        // $talk['mid'] = $_uid;
        echo sucp($pagenum,$pagesize,$count,$talk['messages']?$talk['messages']:'');
    }
/**
 * 聊天框发送消息接口
 * 
 * @param  int  $uid      发送人id
 * @param  int  $talk_id  聊天id
 * @param  str  $content  内容
 */
	public function postMessage(){
		$uid     = I('uid');    //发送人id
		$content = I('content');
		$talk_id = I('talk_id');
		if(empty($uid) || empty($content) || empty($talk_id))
			exit( err(100, '缺少参数') );

		$result = D('TalkMessage')->addMessage($content, $uid, $talk_id);
		if(!$result) exit( err(200, '发送失败') );

        $talk = D('Talk')->find($talk_id);
        /*$message = D('Message')->find($talk['message_id']);
        $messageModel = $this->getMessageModel($message);

        $rs = $messageModel->postMessage($message, $talk, $content, $uid);*/
        
        D('TalkMessage')->sendMessage($content, D('talk')->getUids($talk['uids']), $talk_id, $uid);
        /*if (!$rs) {
            exit( err(200,'写入数据库失败') );
        }*/
        $data = D('TalkMessage')->where("id={$result['id']}")->find();
        $info = query_user(array('nickname', 'avatar32'),$data['uid']);
        $data['nickname'] = $info['nickname'];
        $data['avatar']   = $info['avatar32'];
        echo suc($data, '发送成功');
        // 发送成功推送消息
        D('Talk')->tuiMessage($talk_id,$uid);
	}
/**
 * 根据症状id获取相同症状的所有人
 * @param  int  $uid  查看人id
 * @param  int  $zid  症状id
 */
	public function getTogether(){
		$uid      = I('uid','','intval');
		$zid      = I('zid','','intval');
		$pagenum  = I('pagenum',1);
		$pagesize = I('pagesize',10);
		if(empty($uid) || empty($zid))
			exit( err(100, 'uid和zid不能为空') );
		$data = D('ZhengzhuangUser')->getUser($zid, $uid, $pagenum, $pagesize ,$totalCount);
		// dump($data);
		echo sucp($pagenum, $pagesize, $totalCount, $data, '获取成功');
	}
/**
 * 用户访问其他用户中心的接口
 * @param  int   $uid    访问人id
 * @param  Inter $to_uid 被访问人id
 */
	public function ucenter(){
		$uid    = I('uid','','intval');
		$to_uid = I('to_uid','','intval');
		if(empty($uid) || empty($to_uid)) exit( err(100, '缺少用户参数') );
		$arr = array('title','age','age_lu','pos_province','nickname','sex','avatar32','fans');
		$info = query_user($arr, $to_uid);
		$info['isfollow'] = $this->isFollow($uid,$to_uid) ? 1 : 0;
		// 获取用户总天数
		$map['uid'] = $to_uid;
		$checkInfo  = M('CheckInfo')->where($map)->order('ctime DESC')->find();
		$info['total_num'] = $checkInfo['total_num']?$checkInfo['total_num']:0;
		$info['ranking']   = D('Weibo/CheckInfo')->getRanking($to_uid);
		echo suc($info,'操作成功');
	}
/**
 * 搜藏文章接口
 * @param  int   id   文章id
 * @param  int   uid  用户id
 */
	public function bookmark(){
		$uid         = I('uid',0,'intval');
		$document_id = I('id',0,'intval');
		if($uid < 1 || $document_id < 1) exit( err(100, '文章id和用户id不能为空') );
		$model = D('DocumentBookmark');
		if($model->checkBook($uid,$document_id)) exit( err(100, '已经收藏过了') );
		if($model->bookmark($uid,$document_id)){
			echo suc(0,'收藏成功');
		}else{
			echo err(200, '收藏失败');
		}
	}
/**
 * 取消收藏文章接口
 * @return [type] [description]
 */
	public function unBookmark(){
		$uid         = I('uid',0,'intval');
		$document_id = I('id',0,'intval');
		if($uid < 1 || $document_id < 1) exit( err(100, '文章id和用户id不能为空') );
		if(D('DocumentBookmark')->unBookmark($uid,$document_id)){
			echo suc('','取消收藏成功');
		}else{
			echo err(200,'取消收藏失败');
		}
	}
/**
 * 用户获取文章的收藏列表
 * @param   Inter  $uid 用户id
 */
	public function bookList(){
		$uid      = I('uid',0,'intval');
		$pagenum  = I('pagenum',1,'intval');
		$pagesize = I('pagesize',10,'intval');
		if($uid < 1) exit( err(100,'用户id不能为空') );
		$list = D('DocumentBookmark')->getList($uid,$pagenum,$pagesize,$totalcount);
		// 加入一些必须的数据
		$model = D('DocumentBookmark');
        foreach ($list as &$v) {
            $v['isbook'] = $model->checkBook($uid,$v['id']);
        }
        unset($v);
		echo sucp($pagenum,$pagesize,$totalcount,$list);
	}
/**
 * 破解排行榜接口
 */
	public function pojieRank(){
		$pagenum  = I('pagenum',1);
		$pagesize = I('pagesize',10);
		$list     = D('PojieRank')->rank($pagenum,$pagesize,$totalcount);
		echo sucp($pagenum,$pagesize,$totalcount,$list);
	}
/**
 * 积分等级排行榜
 */
    public function scoreRank(){
        $pagenum  = I('pagenum',1);
        $pagesize = I('pagesize',10);
        $list     = D('Member')->rank($pagenum,$pagesize,$totalcount);
        echo sucp($pagenum,$pagesize,$totalcount,$list);
    }
	
    
	public function cishi(){
		exit();
	}



}





