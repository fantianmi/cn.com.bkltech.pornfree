<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 想天
 * 创建日期: 3/12/14
 * 创建时间: 12:49 PM
 * 版权所有 想天工作室(www.ourstu.com)
 */

namespace Usercenter\Controller;

use Think\Controller;

class MessageController extends BaseController
{
    protected $mTalkModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->mTalkModel = D('Talk');
        $this->setTitle('个人中心');
    }

    public function index()
    {

    }

    /**消息页面
     * @param int    $page
     * @param string $tab 当前tab
     */
    public function message($page = 1, $tab = 'unread')
    {
        //从条件里面获取Tab
        $map = $this->getMapByTab($tab, $map);

        $map['to_uid'] = is_login();

        $messages = D('Message')->where($map)->order('create_time desc')->page($page, 10)->select();
        $totalCount = D('Message')->where($map)->order('create_time desc')->count(); //用于分页

        foreach ($messages as &$v) {
            if ($v['from_uid'] != 0) {
                $v['from_user'] = query_user(array('username', 'space_url', 'avatar64', 'space_link'), $v['from_uid']);
            }
        }

        $this->assign('totalCount', $totalCount);
        $this->assign('messages', $messages);

        //设置Tab
        $this->defaultTabHash('message');
        $this->assign('tab', $tab);
        $this->display();
    }

    /**
     * 聊天列表页面
     */
    public function session()
    {
        $this->defaultTabHash('session');
        $talks = D('Talk')->where('uids like' . '"%[' . is_login() . ']%"' . ' and status=1')->order('update_time desc')->select();
        foreach ($talks as $key => $v) {
            $users = array();
            $uids_array = $this->mTalkModel->getUids($v['uids']);
            foreach ($uids_array as $uid) {
                $users[] = query_user(array('avatar64', 'username', 'space_link', 'id'), $uid);
            }
            $talks[$key]['users'] = $users;
            $talks[$key]['last_message'] = D('Talk')->getLastMessage($talks[$key]['id']);
        }
        $this->assign('talks', $talks);
        $this->display();
    }

    /**对话页面
     * 创建聊天或显示现有聊天。
     * @param int $message_id 消息ID 只提供消息则从消息自动创建一个聊天
     * @param int $talk_id 聊天ID
     */
    public function talk($message_id = 0, $talk_id = 0)
    {
        //获取当前聊天
        $talk = $this->getTalk($message_id, $talk_id);
        $map['talk_id'] = $talk['id'];
        $messages = D('TalkMessage')->where($map)->order('create_time desc')->limit(20)->select();
        $messages = array_reverse($messages);
        foreach ($messages as &$mes) {
            $mes['user'] = query_user(array('avatar128', 'uid', 'username'), $mes['uid']);
        }
        unset($mes);
        $this->assign('messages', $messages);

        $this->assign('talk', $talk);
        $self = query_user(array('avatar128'), is_login());
        $this->assign('self', $self);
        $this->assign('mid', is_login());
        $this->defaultTabHash('session');
        $this->display();
    }

    /**
     * 删除现有聊天
     */
    public function doDeleteTalk($talk_id = 0)
    {
        $this->requireLogin();

        //确认当前用户属于聊天。
        $talk = D('Talk')->find($talk_id);
        $uid = get_uid();
        if (false === strpos($talk['uids'], "[$uid]")) {
            $this->error('您没有权限删除该聊天');
        }

        //如果删除前聊天中只有两个人，就将聊天标记为已删除。
        $uids = explode(',', $talk['uids']);
        if (count($uids) <= 2) {
            D('Talk')->where(array('id' => $talk_id))->setField('status', -1);
            D('Message')->where(array('talk_id' => $talk_id))->setField('talk_id', 0);
        } //如果删除前聊天中有多个人，就退出聊天。
        else {
            $uids = array_diff($uids, array("[$uid]"));
            $uids = implode(',', $uids);
            D('Talk')->where(array('id' => $talk_id))->save(array('uids' => $uids));
            D('Message')->where(array('talk_id' => $talk_id, 'uid' => get_uid()))->setField('talk_id', 0);
        }

        //返回成功结果
        $this->success('删除成功', 'refresh');
    }

    /**回复的时候调用，通过该函数，会回调应用对应的postMessage函数实现对原始内容的数据添加。
     * @param $content 内容文本
     * @param $talk_id 聊天ID
     */
    public function postMessage($content, $talk_id)
    {
        $content = op_t($content);
        //空的内容不能发送
        if (!trim($content)) {
            $content='&nbsp;';
        }

        D('TalkMessage')->addMessage($content, is_login(), $talk_id);
        $talk = D('Talk')->find($talk_id);
        $message = D('Message')->find($talk['message_id']);

        if ($talk['appname'] != '') {
            $messageModel = $this->getMessageModel($message);

             $messageModel->postMessage($message, $talk, $content, is_login());
        }
        exit(json_encode(array('status' => 1, 'content' => $content)));
        $this->success("发送成功");
    }

    /**
     * @param $message
     * @return \Model
     */
    private function getMessageModel($message)
    {

        $appname = ucwords($message['appname']);
        $messageModel = D($appname . '/' . $appname . 'Message');
        return $messageModel;
    }

    /**
     * @param $message_id
     * @param $talk_id
     * @param $map
     * @return array
     */
    private function getTalk($message_id, $talk_id)
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

            /*创建talk*/
            $talk['uids'] = implode(',', array('[' . is_login() . ']', '[' . $message['from_uid'] . ']'));
            $talk['appname'] = $message['appname'];
            $talk['apptype'] = $message['apptype'];
            $talk['source_id'] = $message['source_id'];
            $talk['message_id'] = $message_id;

            //通过消息获取到对应应用内的消息模型
            $messageModel = $this->getMessageModel($message);
            //从对应模型内取回对话源资料
            $talk = array_merge($messageModel->getSource($message), $talk);

            //创建聊天
            $talk = D('Talk')->create($talk);
            $talk['id'] = D('Talk')->add($talk);
            /*创建talk end*/


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


            D('Message')->sendMessage($message['from_uid'], '聊天名称：' . $talk['title'], '您有新的主题聊天', U('UserCenter/Message/talk', array('talk_id' => $talk['id'])), is_login(), 0);

            return $talk;

        } else {
            $talk = D('Talk')->find($talk_id);
            $uids_array = $this->mTalkModel->getUids($talk['uids']);
            if (!count($uids_array)) {
                $this->error('越权操作。');
                return $talk;
            }
            return $talk;
        }
    }

    
/**
我发布的帖子（接口）
*/
    public function myPost(){
        $uid = I('uid');
        $pagenum = I('pagenum',1);
        $pagesize = I('pagesize',10);
        if(empty($uid)) exit(err(100));
        $list = M('ForumPost')->where("uid={$uid} and status=1")->order('create_time desc')->page($pagenum,$pagesize)->select();
        $avatar = $this->getAvatar($list[0]['uid']);
        $nickname = $this->getNicknames($list[0]['uid']);
        foreach ($list as &$v) {
            $v['content'] = strip_tags($v['content']);
            $v['avatar'] = $avatar;
            $v['support'] = $this->getSupport($v['id']);
            $v['nickname'] = $nickname;
            $v['diwei']   = $this->getDiwei($uid);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
            $v['types'] = 'mypost';
        }
        $count = M('ForumPost')->where("uid={$uid} and status=1")->count();
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        if($list){
            $datas = array(
            'pagedatas'=>$list,
            'totalcount'=>$count,
            'pagesize'=>$pagesize,
            'pagenum'=>$pagenum,
            'totalpage'=>$totalpage,
            "hasNextPage"=>$hasNextPage
            );
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
        }else{
            echo suc();
        }
        
    }

    public function collection($type='forum',$pagenum=1,$uid = 0,$pagesize=10)
    {
        if($uid == 0){
            exit(err(100));
        }
        $type=op_t($type);
        $totalCount=0;
        $list=$this->_getList($uid,$type,$totalCount,$pagenum,$pagesize);
        foreach ($list as &$v) {
            $v['nickname'] = $this->getNicknames($v['uid']);
            $v['avatar'] = $this->getAvatar($v['uid']);
            $v['content'] = strip_tags($v['content']);
            $v['support'] = $this->getSupport($v['id']);
            $v['diwei']   = $this->getDiwei($v['uid']);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
        }
        $count = D('ForumBookmark')->where("uid={$uid}")->count();
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        if($list){
            $datas = array(
            'pagedatas'=>$list,
            'totalcount'=>$count,
            'pagesize'=>$pagesize,
            'pagenum'=>$pagenum,
            'totalpage'=>$totalpage,
            "hasNextPage"=>$hasNextPage
            );
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
        }else{
            echo suc();
        }
    }

    public function _getList($uid,$type='forum',&$totalCount=0,$page=1,$r=10)
    {
        $map['uid']=$uid;
        switch ($type) {
            case 'forum':
                $forums = $this->getForumList();
                $forum_key_value = array();
                foreach ($forums as $f) {
                    $forum_key_value[$f['id']] = $f;
                }
                $post_ids=D('ForumBookmark')->where($map)->field('post_id')->select();
                $post_ids=array_column($post_ids,'post_id');
                $map_forum=array('id'=>array('in',$post_ids),'status'=>1);
                $model=D('ForumPost');
                $list=$model->where($map_forum)->page($page,$r)->order('update_time desc')->select();
                $totalCount=$model->where($map_forum)->count();
                foreach ($list as &$v) {
                    $v['forum'] = $forum_key_value[$v['forum_id']];
                }
                break;
            default:
                $this->error('非法操作！');
                break;
        }
        return $list;
    }

    private function getForumList()
    {
        $forum_list = S('forum_list');
        if (empty($forum_list)) {
            //读取板块列表
            $forum_list = D('Forum/Forum')->where(array('status' => 1))->order('sort asc')->select();
            S('forum_list', $forum_list, 300);
        }
        return $forum_list;
    }
    /**
     * @param $tab
     * @param $map
     * @return mixed
     */
    private function getMapByTab($tab, $map)
    {
        switch ($tab) {
            case 'system':
                $map['type'] = 0;
                break;
            case 'user':
                $map['type'] = 1;
                break;
            case 'app':
                $map['type'] = 2;
                break;
            case 'all':
                break;
            default:
                $map['is_read'] = 0;
                break;
        }
        return $map;
    }

/**
公用的类
*/

/**
*@param uid 用户的id
*@return 用户的昵称
* 
*/
    public function getNicknames($uid){
        $re = M('member')->field(array('nickname'))->where("uid={$uid}")->find();
        return $re['nickname'];
    }


/**
*@param uid 用户的id
*@return 用户的头像路径
* 
*/
    protected function getAvatar($uid=''){
        if(empty($uid)){
            return '';
        }else{
            $avatar = M('avatar')->field('path')->where("uid={$uid}")->order("create_time DESC")->find();
            if(empty($avatar['path'])){
                $return = '';
            }else{
                $return = "/Uploads".$avatar['path'];
            }
            return $return;
        }
    }
/**
*@param $postId 文章的id
*@return 文章被赞的次数
* 
*/
    public function getSupport($postId=''){
        if(empty($postId)){
            return '';
        }else{
            $count = M('support')->where("row={$postId}")->count();
            return $count;
        }
    }

    // my获取帖子图片
    public function getImage($pid){
        $image = M('forumImage')->where("pid={$pid}")->select();
        $path = dirname($_SERVER['SCRIPT_FILENAME']);
        $ob = new \Think\Image();
        if($image){
            foreach ($image as &$v) {
                $paths = $path.$v['path'];
                if(file_exists($paths)){
                    $ob->open($paths);
                    $v['width'] = $ob->width();
                    $v['height'] = $ob->height();
                }
            }
            return $image;
        }else{
            return '';
        }
    }
/*根据用户id获取用户地位*/
    protected function getDiwei($uid){
        $paihang = query_user(array('title'),$uid);
        return $paihang['title'];
    }
}