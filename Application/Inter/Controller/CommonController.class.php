<?php
namespace Inter\Controller;
use Think\Controller;


/**
 * 以后新增的或者修改的接口都写在Inter分组下了
 */

class CommonController extends Controller
{
	/*public function _initialize(){
		
	}*/

	public function getMessageModel($message)
    {

        $appname = ucwords($message['appname']);
        $messageModel = D($appname . '/' . $appname . 'Message');
        return $messageModel;
    }
/**
 * 判断用户是否被关注
 * @param  int     $uid    他
 * @param  int     $to_uid 是否关注他
 * @return boolean  关注 true  没关注 false
 */
    public function isFollow($uid,$to_uid){
    	$map['who_follow'] = $uid;
    	$map['follow_who'] = $to_uid;
    	$re = M('Follow')->where($map)->find();
    	if($re) return true;
    	return false;
    }

    public function getTalk($message_id, $talk_id, $uid)
    {
        if ($message_id != 0) {
            /*如果是传递了message_id，就是创建对话*/
            $message = D('Message')->find($message_id);

            //权限检测，防止越权创建聊天
            if (($message['to_uid'] != $this->mid && $message['from_uid'] != $this->mid) || !$message) {
                $this->error('非法操作。');
            }

            //如果已经创建过聊天了，就不再创建
            $map['message_id'] = $message_id;
            $map['status'] = 1;
            $talk = D('Talk')->where($map)->find();
            if ($talk) {
                redirect(U('UserCenter/Message/talk', array('talk_id' => $talk['id'])));
            }


            $memeber = $message['from_uid'];


            //TODO 调用模型创建聊天
            D('Common/Talk')->createTalk($memeber, $message);
            $messageModel = $this->getMessageModel($message);


            //关联聊天到当前消息
            $message['talk_id'] = $talk['id'];
            D('Message')->save($message);

            //插入第一条消息
            $talkMessage['uid'] = $message['from_uid'];
            $talkMessage['talk_id'] = $talk['id'];
            $talkMessage['content'] = $messageModel->getFindContent($message);
            $talkMessageModel = D('TalkMessage');
            $talkMessage = $talkMessageModel->create($talkMessage);
            $talkMessage['id'] = $talkMessageModel->add($talkMessage);


            D('Message')->sendMessage($message['from_uid'], '聊天名称：' . $talk['title'], '您有新的主题聊天', U('UserCenter/Message/talk', array('talk_id' => $talk['id'])), $uid, 0);

            return $talk;

        } else {
            $talk = D('Talk')->find($talk_id);
            /*$uids_array = D('Talk')->getUids($talk['uids']);
            if (!count($uids_array)) {
                $this->error('越权操作。');
                return $talk;
            }*/
            return $talk;
        }
    }



}




