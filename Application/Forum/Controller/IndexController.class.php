<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-8
 * Time: PM4:30
 */

namespace Forum\Controller;

use Think\Controller;
use Weibo\Api\WeiboApi;

define('TOP_ALL', 2);
define('TOP_FORUM', 1);

class IndexController extends Controller
{
    private function getForumList()
    {
        $forum_list = S('forum_list');
        if (empty($forum_list)) {
            //读取板块列表
            $forum_list = D('Forum/Forum')->where(array('status' => 1))->order('sort asc')->select();
            S('forum_list', $forum_list, 300);
        }
        // dump($forum_list);die;
        return $forum_list;
    }

    public function _initialize()
    {
        // echo "string";
        $forum_list = $this->getForumList();
        //判断板块能否发帖
        foreach ($forum_list as &$e) {
            $e['allow_publish'] = $this->isForumAllowPublish($e['id']);
        }
        unset($e);
        // echo "string";
        $myInfo = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url', 'icons_html'), is_login());
        // dump($myInfo);
        $this->assign('myInfo', $myInfo);
        //赋予论坛列表
        $this->assign('forum_list', $forum_list);


    }

    public function index($page = 1)
    {
        redirect(U('forum', array('page' => intval($page))));
    }
    
    public function indexApi($page = 1)
    {
        $types=D('Forum')->getAllForums();
        
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$types));
    }


    // 获取论坛板块
    public function getForum(){
        $list = $this->getForumList();
        foreach ($list as &$v) {
                $v['path'] = $this->getLogo($v['logo']);
            }
        if($list){
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$list));
        }else{
            echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
        }
    }
    // 获取论坛板块的logo
    public function getLogo($logo){
        $arr = M('picture')->where("id={$logo}")->find();
        return $arr['path'];
    }
    // 获取板块帖子
    public function getList($id = 0,$order = 'reply',$pagenum = 1,$pagesize = 10){
        $id = intval($id);
        if ($order == 'ctime') {
            $order = 'create_time desc';
        } else if ($order == 'reply') {
            $order = 'reply_count desc';
        }else if($order == 'click'){
            $order = 'id desc';
        }else{
            $order = 'id desc';//默认的
        }
        if ($id == 0) {
            $map = array('status' => 1);
        }else{
            $map = array('forum_id' => $id, 'status' => 1);
        }
        $count = D('ForumPost')->where($map)->count();
        $list = D('ForumPost')->where($map)->order($order)->page($pagenum, $pagesize)->select();
        foreach ($list as &$v) {
            $v['nickname'] = $this->getNickname($v['uid']);
            $v['support'] = $this->getSupport($v['id']);
            $v['avatar'] = $this->getAvatar($v['uid']);
            $v['content'] = strip_tags($v['content']);
        }
        // $list['content'] = strip_tags($list['content']);
        // dump($list);
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        $datas = array(
            'pagedatas'=>$list,
            'totalcount'=>$count,
            'pagesize'=>$pagesize,
            'pagenum'=>$pagenum,
            'totalpage'=>$totalpage,
            "hasNextPage"=>$hasNextPage
        );
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
    }
/**
我的回复
*/
    public function getHui(){
        $uid = I('uid');
        $pagesize = I('pagesize',10);
        $pagenum = I('pagenum',1);
        if (empty($uid)) {
            exit(err(100));
        }
        /*查询的字段*/
        $field = array('id','post_id','content','uid','to_uid','ctime'=>'create_time','to_f_reply_id');

        $lzl = M('ForumLzlReply');
        $data = $lzl->field($field)->where("to_uid={$uid} and is_del=0")->order('ctime DESC')->page($pagenum,$pagesize)->select();
        $member = M('member');
        $avatar = M('avatar');
        $reply  = M('forum_post_reply');
        $post   = M('forum_post');
        foreach ($data as &$val) {
            $nickname = $member->where("uid={$val['uid']}")->getField('nickname');
            $val['nickname'] = $nickname?$nickname:'';
            $ava = $avatar->field('path')->where("uid={$val['uid']}")->order('create_time DESC')->limit(1)->find()['path'];
            $val['avatar'] = $ava?'/Uploads'.$ava:'';
            $val['title'] = $reply->where("id={$val['to_f_reply_id']}")->getField('content');
            $val['type']  = 'lzl';
        }
        /*帖子*/
        /*获取用户发的帖子id*/
        $pid = M('forum_post')->where("uid={$uid} and status=1")->getField('id',true);
        $pid = implode(',',$pid);
        $list = M('forum_post_reply')->where("post_id in({$pid}) and status=1")->order('create_time DESC')->page($pagenum,$pagesize)->select();
        foreach ($list as &$v) {
            $nickname = $member->where("uid={$v['uid']}")->getField('nickname');
            $v['nickname'] = $nickname?$nickname:'';
            $avas = $avatar->field('path')->where("uid={$v['uid']}")->order('create_time DESC')->limit(1)->find()['path'];
            $v['avatar'] = $avas?'/Uploads'.$avas:'';
            $v['title'] = $post->where("id={$v['post_id']}")->getField('title');
            $v['type'] = 'post';
        }
        if($data && $list){
            $data = array_merge((array)$data,(array)$list);
        }else if($data && !$list){
            $data = $data;
        }else if(!$data && $list){
            $data = $list;
        }else if(!$data && !$list){
            $data = '';
        }
        echo suc($data?$data:'');
    }
/**
*获取置顶的帖子，并完善数据
*@param   $id   板块id
*@param   array
*/
    public function getTop($id=1,$uid,$forum_key_value){
        $data = D('ForumPost')->getTop($id);
        if(empty($data)) return '';//没用置顶的帖子直接返回不做处理
        /*完善置顶的帖子内容*/
        foreach ($data as &$v) {
            // 加入板块的信息
            $forum = $forum_key_value[$v['forum_id']];
            $v['forum_title'] = $forum['title'];
            // 加入用户的信息
            $info = query_user(array('avatar32','nickname','title'),$v['uid']);
            $v['avatar']      = $info['avatar32'];
            $v['diwei']       = $info['title'];
            $v['nickname']    = $info['nickname'];
            $v['support']     = $this->getSupport($v['id']);
            $v['bookmark']    = $this->getBookmark($v['id']);
            $v['content']     = strip_tags($v['content']);
            $v['isSupport']   = $this->isSupport($uid,$v['id']);
            $v['reply_count'] = D('ForumPostReply')->getReplyCount($v['id']);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
            $v['types'] = 'all';
        }
        unset($v);
        return $data;
    }
/**
大厅（全部帖子）---> 接口(按浏览次数最多的排序)
*/
    public function getAllList(){
        $uid     = I('uid');
        $pagenum   = I('pagenum',1);
        $pagesize  = I('pagesize',10);
        // 得到所有帖子列表
        $map['status'] = 1;
        $order     = 'create_time DESC';
        $ForumPost = D('ForumPost');
        $count     = $ForumPost->where($map)->count();
        $list      = $ForumPost->where($map)->order($order)->page($pagenum,$pagesize)->select();
        // 得到所有板块信息（带缓存）
        $forums = D('Forum')->getForumList();
        $forum_key_value = array();
        foreach ($forums as $f) {
            $forum_key_value[$f['id']] = $f;
        }

        foreach ($list as &$v) {
            // 加入板块的信息
            $forum = $forum_key_value[$v['forum_id']];
            $v['forum_title'] = $forum['title'];
            // 加入用户的信息
            $info = query_user(array('avatar32','nickname','title'),$v['uid']);
            $v['avatar']      = $info['avatar32'];
            $v['diwei']       = $info['title'];
            $v['nickname']    = $info['nickname'];
            $v['support']     = $this->getSupport($v['id']);
            $v['bookmark']    = $this->getBookmark($v['id']);
            $v['content']     = strip_tags($v['content']);
            $v['isSupport']   = $this->isSupport($uid,$v['id']);
            $v['reply_count'] = D('ForumPostReply')->getReplyCount($v['id']);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
            $v['types'] = 'all';
        }
        unset($v);
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        if($$pagenum==1){
	        $datas = array(
	            'top'      =>$this->getTop(1,$uid,$forum_key_value),
	            'pagedatas'=>$list,
	            'totalcount'=>$count,
	            'pagesize'=>$pagesize,
	            'pagenum'=>$pagenum,
	            'totalpage'=>$totalpage,
	            "hasNextPage"=>$hasNextPage
	        );
        }else{
	        	$datas = array(
	            'pagedatas'=>$list,
	            'totalcount'=>$count,
	            'pagesize'=>$pagesize,
	            'pagenum'=>$pagenum,
	            'totalpage'=>$totalpage,
	            "hasNextPage"=>$hasNextPage
	        );
        }
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
    }
/**
大厅（精华热帖）---> 接口(按回复次数最多的排序)
*/
    public function getReply(){
        $uid     = I('uid');
        if(empty($uid)) exit(err(100));
        $pagenum = I('pagenum',1);
        $pagesize = I('pagesize',10);

        $map['status'] = 1;
        $count = M('ForumPost')->where($map)->count();
        $list = M('ForumPost')->where($map)->page($pagenum,$pagesize)->order('reply_count DESC')->select();
        foreach ($list as &$v) {
            $v['nickname']    = $this->getNickname($v['uid']);
            $v['support']     = $this->getSupport($v['id']);
            $v['bookmark']    = $this->getBookmark($v['id']);
            $v['avatar']      = $this->getAvatar($v['uid']);
            $v['content']     = strip_tags($v['content']);
            $v['diwei']       = $this->getDiwei($v['uid']);
            $v['isSupport']   = $this->isSupport($uid,$v['id']);
            $v['reply_count'] = D('ForumPostReply')->getReplyCount($v['id']);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
            $v['types'] = 'hot';
        }
        $totalpage = ceil($count/$pagesize);
        if($pagenum >= $totalpage){
            $hasNextPage = false;
        }else{
            $hasNextPage = true;
        }
        $datas = array(
            'pagedatas'=>$list,
            'totalcount'=>$count,
            'pagesize'=>$pagesize,
            'pagenum'=>$pagenum,
            'totalpage'=>$totalpage,
            "hasNextPage"=>$hasNextPage
        );
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$datas));
    }
/*大厅，精华帖子接口*/
    public function getJing(){
        $uid     = I('uid');
        if(empty($uid)) exit(err(100));
        $pagenum  = I('pagenum',1);
        $pagesize = I('pagesize',10);
        $map['is_top'] = 2;
        $map['status'] = 1;
        $count = M('ForumPost')->where($map)->count();
        $list  = M('ForumPost')->where($map)->page($pagenum,$pagesize)->order('create_time DESC')->select();
        foreach ($list as &$v) {
            $v['nickname']    = $this->getNickname($v['uid']);
            $v['support']     = $this->getSupport($v['id']);
            $v['bookmark']    = $this->getBookmark($v['id']);
            $v['avatar']      = $this->getAvatar($v['uid']);
            $v['content']     = strip_tags($v['content']);
            $v['diwei']       = $this->getDiwei($v['uid']);
            $v['isSupport']   = $this->isSupport($uid,$v['id']);
            $v['reply_count'] = D('ForumPostReply')->getReplyCount($v['id']);
            if(!empty($this->getImage($v['id']))){
                $v['images']  = $this->getImage($v['id']);
            }
            $v['types'] = 'jing';
        }
        echo sucp($pagenum,$pagesize,$count,$list);
    }
    /**某个版块的帖子列表
     * @param int $id 版块ID
     * @param int $page 分页
     * @param string $order 回复排序方式
     * @auth 陈一枭
     */
    public function forum($id = 0, $page = 1, $order = 'reply')
    {
        $id = intval($id);
        $page = intval($page);
        $order = op_t($order);

        $count = S('forum_count_' . $id);
        if (empty($count)) {
            if ($id != 0) {
                $map['id'] = $id;
            }

            $map['status'] = 1;
            $count['forum'] = D('Forum')->where($map)->count();
            $count['post'] = D('ForumPost')->where($map)->count();
            $count['all'] = $count['post'] + D('ForumPostReply')->where($map)->count() + D('ForumLzlReply')->where($map)->count();
            S('forum_count_' . $id, $count, 60);
        }
        $this->assign('count', $count);
        $id = intval($id);
        if ($order == 'ctime') {
            $order = 'create_time desc';
        } else if ($order == 'reply') {
            $order = 'last_reply_time desc';
        }else{
            $order = 'last_reply_time desc';//默认的
        }
        $this->requireForumAllowView($id);
        $forums = $this->getForumList();
        // dump($forums);
        $forum_key_value = array();
        foreach ($forums as $f) {
            $forum_key_value[$f['id']] = $f;
        }


        //读取帖子列表
        if ($id == 0) {
            $map = array('status' => 1);
            $list_top = D('ForumPost')->where(' status=1 AND is_top=' . TOP_ALL)->order($order)->select();
        } else {
            $map = array('forum_id' => $id, 'status' => 1);
            $list_top = D('ForumPost')->where('status=1 AND (is_top=' . TOP_ALL . ') OR (is_top=' . TOP_FORUM . ' AND forum_id=' . intval($id) . ' and status=1)')->order($order)->select();
        }

        foreach ($list_top as &$v) {
            $v['forum'] = $forum_key_value[$v['forum_id']];
        }
        unset($v);
        $list = D('ForumPost')->where($map)->order($order)->page($page, 10)->select();
        $totalCount = D('ForumPost')->where($map)->count();
        foreach ($list as &$v) {
            $v['forum'] = $forum_key_value[$v['forum_id']];
        }
        unset($v);
        //读取置顶列表

        //显示页面
        $this->assign('forum_id', $id);

        if ($id != 0) {
            $forum = $forum_key_value[$id];
            $this->assign('forum', $forum);
        } else {
            $this->assign('forum', array('title' => '论坛 Forum'));
        }

        // dump($list);
        $this->assignAllowPublish();
        $this->assign('list', $list);
        $this->assign('list_top', $list_top);
        $this->assign('totalCount', $totalCount);
        if (op_t($_GET['order']) == 'ctime') {
            $this->assign('order', 1);
        } else {
            $this->assign('order', 0);
        }
        $this->display();
    }

    public function forums()
    {
        $this->display();
    }

    /**
     *帖子详情页
     *
     * sr与sp仅作用于楼中楼消息来访，sp指代消息中某楼层的ID，sp指代该消息所在的分页
     *
     * @param      $id
     * @param int $page
     * @param null $sr 楼中楼回复消息中某楼层的ID
     * @param int $sp 楼中楼回复消息中的分页ID
     * @auth  陈一枭
     */
    public function detail($id, $page = 1, $sr = null, $sp = 1)
    {
        $id = intval($id);
        $page = intval($page);
        $sr = intval($sr);
        $sp = intval($sp);
        $uid = I('uid','','intval');

        $limit = 10;
        //读取帖子内容
        $post = D('ForumPost')->where(array('id' => $id, 'status' => 1))->find();

        if (!$post) {
            echo json_encode(array('msg'=>'error','ret'=>404,'data'=>''));
            exit;
        }
        $post['forum'] = D('Forum')->find($post['forum_id']);
        $post['nickname'] = $this->getNickname($post['uid']);
        if($re=$this->getAvatar($post['uid'])) $post['avatar']=$re;
        // $post['avatar'] = $this->getAvatar($post['uid']);
        $post['content'] = op_h($post['content'], 'html');
        $post['support'] = $this->getSupport($id);
        $post['diwei']   = $this->getDiwei($post['uid']);
        // $post['avatar']  = $this->getAvatar($post['uid']);
        //增加浏览次数
        D('ForumPost')->where(array('id' => $id))->setInc('view_count');
        //读取回复列表
        /*$map = array('post_id' => $id, 'status' => 1);
        $replyList = D('ForumPostReply')->getReplyList($map, 'create_time', $page, $limit);

        $replyTotalCount = D('ForumPostReply')->where($map)->count();
        //判断是否需要显示1楼
        if ($page == 1) {
            $showMainPost = true;
        } else {
            $showMainPost = false;
        }*/

        // foreach ($replyList as &$reply) {
        //     $reply['content'] = op_h($reply['content'], 'html');
        // }

        // unset($reply);
        //判断是否已经收藏
        if(!empty($uid)){
            $isBookmark = D('ForumBookmark')->exists($uid, $id);
        }
        if($isBookmark){
            $post['isBookmark'] = 1;
        }else{
            $post['isBookmark'] = 0;
        }
        //显示页面
        foreach ($post as $v) {
            if($re=$this->getImage($post['id'])) $post['images'] = $re;
            // $post['images'] = $this->getImage($post['id']);
        }
        $post['pinglun']     = $this->getPing($id);
        $post['reply_count'] = D('ForumPostReply')->getReplyCount($id);
        // dump($post);
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$post));
        exit;
    }
/**
*@var      根据帖子id获取帖子的详情（ 不带评论 ）
*@param    $id   帖子的id
*/
    public function detailContent(){
        $id   = I('id');
        $post = D('ForumPost')->where(array('id' => $id, 'status' => 1))->find();
        if (!$post) {
            echo json_encode(array('msg'=>'帖子不存在','ret'=>404,'data'=>''));
            exit;
        }
    }

    public function delPostReply($id)
    {
        $id = intval($id);

        $this->requireLogin();
        $this->requireCanDeletePostReply($id);
        $res = D('ForumPostReply')->delPostReply($id);
        $res && $this->success($res);
        !$res && $this->error('');
    }


    public function editReply($reply_id = null)
    {
        $reply_id = intval($reply_id);

        $has_permission = $this->checkRelyPermission($reply_id);
        if (!$has_permission) {
            $this->error('您不具备编辑该回复的权限。');
        }
        if ($reply_id) {
            $reply = D('forum_post_reply')->where(array('id' => $reply_id, 'status' => 1))->find();
        } else {
            $this->error('参数出错！');
        }

        $this->setTitle('编辑回复 —— 论坛');
        //显示页面
        $this->assign('reply', $reply);
        $this->display();
    }

    public function doReplyEdit($reply_id = null, $content)
    {
        $reply_id = intval($reply_id);
        //对帖子内容进行安全过滤
        $content = $this->filterPostContent($content);


        $has_permission = $this->checkRelyPermission($reply_id);
        if (!$has_permission) {
            $this->error('您不具备编辑该回复的权限。');
        }


        if (!$content) {
            $this->error("回复内容不能为空！");
        }
        $data['content'] = $content;
        $data['update_time'] = time();
        $post_id = D('forum_post_reply')->where(array('id' => intval($reply_id), 'status' => 1))->getField('post_id');
        $reply = D('forum_post_reply')->where(array('id' => intval($reply_id)))->save($data);
        if ($reply) {
            S('post_replylist_' . $post_id, null);
            $this->success('编辑回复成功', U('Forum/Index/detail', array('id' => $post_id)));
        } else {
            $this->error("编辑回复失败");
        }
    }

    public function edit($forum_id = 0, $post_id = null)
    {
        $forum_id = intval($forum_id);
        $post_id = intval($post_id);

        //判断是不是为编辑模式
        $isEdit = $post_id ? true : false;
        //如果是编辑模式的话，读取帖子，并判断是否有权限编辑
        if ($isEdit) {
            $post = D('ForumPost')->where(array('id' => intval($post_id), 'status' => 1))->find();
            $this->requireAllowEditPost($post_id);
        } else {
            $post = array('forum_id' => $forum_id);
        }
        //获取论坛编号
        $forum_id = $forum_id ? intval($forum_id) : $post['forum_id'];

        //确认当前论坛能发帖
        $this->requireForumAllowPublish($forum_id);

        //确认论坛能发帖
        if ($forum_id) {
            $this->requireForumAllowPublish($forum_id);
        }

        //显示页面
        $this->assign('forum_id', $forum_id);
        $this->assignAllowPublish();
        $this->assign('post', $post);
        $this->assign('isEdit', $isEdit);
        $this->display();
    }

    public function doEdit($uid = 0,$post_id = null, $forum_id = 0,$image='', $title, $content)
    {
        $post_id = intval($post_id);
        $forum_id = intval($forum_id);
        $title = op_t($title);
        $content = strip_tags($content);


        //判断是不是编辑模式
        $isEdit = $post_id ? true : false;
        $forum_id = intval($forum_id);

        //确认当前论坛能发帖
        $this->requireForumAllowPublish($forum_id);

        if ($title == '') {
            echo json_encode(array('msg'=>'error','ret'=>100,'data'=>''));
            exit;
            // $this->error('请输入标题。');
        }
        if ($forum_id == 0) {
            echo json_encode(array('msg'=>'error','ret'=>200,'data'=>''));
            exit;
        }


        //写入帖子的内容
        $model = D('ForumPost');
        if ($isEdit) {
            $data = array('id' => intval($post_id), 'title' => $title, 'content' => $content, 'parse' => 0, 'forum_id' => intval($forum_id));
            $result = $model->editPost($data);
            if (!$result) {
                echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
                exit;
            }else{
                // 图片处理start==========================
                $arr = array();
                if($_FILES['image1']['error'] === 0){
                }
                if($arr){
                    if(!M('forumImage')->addAll($arr)){
                        echo json_encode(array('msg'=>'error','ret'=>400,'data'=>''));
                        exit;
                    }
                }
            }
                // 图片处理end============================
                echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
                exit;
            
        } else {
            $data = array('uid' => $uid, 'title' => $title, 'content' => $content, 'parse' => 0, 'forum_id' => $forum_id);

            $before = getMyScore();
            $tox_money_before = getMyToxMoney();
            $result = $model->createPosts($data,$uid);
            $after = getMyScore();
            $tox_money_after = getMyToxMoney();
            if (!$result) {
                echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
                exit;
            }
            $arr = array();
            if($_FILES['image1']['error'] === 0){
                $path = $this->upImage();
                foreach ($path as $v) {
                    $rePath = $this->minImage(trim($v['savepath'].$v['savename'],'.'),$v['savename']);
                    $arr[]  = array(
                        'pid'=>$result,
                        'path'=>'/Uploads'.trim($v['savepath'].$v['savename'],'.'),
                        'mpath'=>$rePath['mPath'],
                        'cpath'=>$rePath['cPath'],
                        'ctime'=>NOW_TIME
                    );
                }
            }
            if($arr){
                if(!M('forumImage')->addAll($arr)){
                    echo json_encode(array('msg'=>'error','ret'=>400,'data'=>''));
                    exit;
                }
            }
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
            exit;
        }

        //发布帖子成功，发送一条微博消息
        $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Forum/Index/detail', array('id' => $post_id));
        $weiboApi = new WeiboApi();
        $weiboApi->resetLastSendTime();

        //实现发布帖子发布图片微博(公共内容)
        $type = 'feed';
        $feed_data = array();
        //解析并成立图片数据
        $arr = array();
        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $data['content'], $arr); //匹配所有的图片

        if (!empty($arr[0])) {

            $feed_data['attach_ids'] = '';
            $dm = "http://$_SERVER[HTTP_HOST]" . __ROOT__; //前缀图片多余截取
            $max = count($arr['1']) > 9 ? 9 : count($arr['1']);
            for ($i = 0; $i < $max; $i++) {
                $tmparray = strpos($arr['1'][$i], $dm);
                if (!is_bool($tmparray)) {
                    $path = mb_substr($arr['1'][$i], strlen($dm), strlen($arr['1'][$i]) - strlen($dm));
                    $result_id = D('Home/Picture')->where(array('path'=>$path))->getField('id');

                } else {
                    $path = $arr['1'][$i];
                    $result_id = D('Home/Picture')->where(array('path'=>$path))->getField('id');
                    if(!$result_id){
                        $result_id = D('Home/Picture')->add(array('path'=>$path,'url' => $path, 'status' => 1, 'create_time' => time()));
                    }
                }
                $feed_data['attach_ids'] = $feed_data['attach_ids'] . ',' . $result_id;
            }
            $feed_data['attach_ids'] = substr($feed_data['attach_ids'], 1);
        }

        $feed_data['attach_ids'] != false &&  $type = "image";

        //开始发布微博
        if ($isEdit) {
            $weiboApi->sendWeibo("我更新了帖子【" . $title . "】：" . $postUrl, $type, $feed_data);
        } else {
            $weiboApi->sendWeibo("我发表了一个新的帖子【" . $title . "】：" . $postUrl, $type, $feed_data);
        }


        //显示成功消息
        $message = $isEdit ? '编辑成功。' : '发表成功。' . getScoreTip($before, $after) . getToxMoneyTip($tox_money_before, $tox_money_after);
        $this->success($message, U('Forum/Index/detail', array('id' => $post_id)));
    }

    public function doReply($post_id, $content,$uid = 0)
    {
        $post_id = intval($post_id);
        $content = $this->filterPostContent($content);

        //确认有权限回复
        // $this->requireAllowReply($post_id);

        if($uid == 0){
            echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
            die;
        }
        //检测回复时间限制
        $uid = $uid;
        $near = D('ForumPostReply')->where(array('uid' => $uid))->order('create_time desc')->find();

        $cha = time() - $near['create_time'];
        // if ($cha > 10) {

            //添加到数据库
            $model = D('ForumPostReply');
            $before = getMyScore();
            $tox_money_before = getMyToxMoney();
            $result = $model->addReply($post_id, $content,$uid);
            $after = getMyScore();
            $tox_money_after = getMyToxMoney();
            if (!$result) {
                echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
                exit;
                // $this->error('回复失败：' . $model->getError());
            }
            //显示成功消息
            $data = D('ForumPostReply')->where("id={$result}")->find();
            $data['nickname'] = $this->getNickname($data['uid']);
            $data['avatar']   = $this->getAvatar($data['uid']);
            $data['diwei']    = $this->getDiwei($data['uid']);
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$data));
            /*回复成功推送消息*/
            $nickname    = $this->getNickname($uid);
            $title       = $nickname.' 回复了您的帖子';
            $description = $data['content'];
            $postUid     = D('ForumPost')->getPostUid($post_id);
            D('BaiTui')->genDataOne($postUid,$title,$description,$data['uid']);
    }
// 取消收藏
    public function doBookmarks(){
        $uid = $_REQUEST['uid'];
        $post_id = $_REQUEST['post_id'];
        $result = D('ForumBookmark')->removeBookmark($uid, $post_id);
        if($result){
            echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
        }else{
            echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
        }
    }
// 添加收藏
    public function doBookmark()
    {
        $uid = $_REQUEST['uid'];
        $post_id = $_REQUEST['post_id'];
        //确认用户已经登录
        //写入数据库
            $data = array(
                'uid'=>$uid,
                'post_id'=>$post_id,
                'create_time'=>time()
            );
            $where = array(
                'uid'=>$uid,
                'post_id'=>$post_id
            );
            if (M('ForumBookmark')->where($where)->count() > 0) {
                exit( json_encode(array('msg'=>'error','ret'=>500,'data'=>'')));
            }
            $result = M('ForumBookmark')->add($data);
            if (!$result) {
                echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
            }else{
                echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
            }

    }

    private function assignAllowPublish()
    {
        $forum_id = $this->get('forum_id');
        $allow_publish = $this->isForumAllowPublish($forum_id);
        $this->assign('allow_publish', $allow_publish);
    }

    private function requireLogins()
    {
        if (!$this->isLogin()) {
            echo json_encode(array('msg'=>'error','ret'=>2,'data'=>''));
            exit;
            // $this->error('需要登录才能操作');
        }
    }

    private function requireLogin()
    {
        if (!$this->isLogin()) {
            // $this->error('需要登录才能操作');
        }
    }

    private function isLogin()
    {
        return is_login() ? true : false;
    }

    private function requireForumAllowPublish($forum_id)
    {
        $this->requireForumExists($forum_id);
        $this->requireLogin();
        $this->requireForumAllowCurrentUserGroup($forum_id);
    }

    private function isForumAllowPublish($forum_id)
    {
        if (!$this->isLogin()) {
            return false;
        }
        if (!$this->isForumExists($forum_id)) {
            return false;
        }
        if (!$this->isForumAllowCurrentUserGroup($forum_id)) {
            return false;
        }
        return true;
    }

    private function requireAllowEditPost($post_id)
    {
        $this->requirePostExists($post_id);
        $this->requireLogin();

        if (is_administrator()) {
            return true;
        }
        //确认帖子时自己的
        $post = D('ForumPost')->where(array('id' => $post_id, 'status' => 1))->find();
        if ($post['uid'] != is_login()) {
            $this->error('没有权限编辑帖子');
        }
    }

    private function requireForumAllowView($forum_id)
    {
        $this->requireForumExists($forum_id);
    }

    private function requireForumExists($forum_id)
    {
        if (!$this->isForumExists($forum_id)) {
            $this->error('论坛不存在');
        }
    }

    private function isForumExists($forum_id)
    {
        $forum_id = intval($forum_id);
        $forum = D('Forum')->where(array('id' => $forum_id, 'status' => 1));
        return $forum ? true : false;
    }

    private function requireAllowReply($post_id)
    {
        $post_id = intval($post_id);
        $this->requirePostExists($post_id);
        $this->requireLogin();
    }

    private function requirePostExists($post_id)
    {
        $post_id = intval($post_id);
        $post = D('ForumPost')->where(array('id' => $post_id))->find();
        if (!$post) {
            $this->error('帖子不存在');
        }
    }

    private function requireForumAllowCurrentUserGroup($forum_id)
    {
        $forum_id = intval($forum_id);
        if (!$this->isForumAllowCurrentUserGroup($forum_id)) {
            $this->error('该板块不允许发帖');
        }
    }

    private function isForumAllowCurrentUserGroup($forum_id)
    {
        $forum_id = intval($forum_id);
        //如果是超级管理员，直接允许
        if (is_login() == 1) {
            return true;
        }

        //如果帖子不属于任何板块，则允许发帖
        if (intval($forum_id) == 0) {
            return true;
        }

        //读取论坛的基本信息
        $forum = D('Forum')->where(array('id' => $forum_id))->find();
        $userGroups = explode(',', $forum['allow_user_group']);

        //读取用户所在的用户组
        $list = M('AuthGroupAccess')->where(array('uid' => is_login()))->select();
        foreach ($list as &$e) {
            $e = $e['group_id'];
        }

        //每个用户都有一个默认用户组
        $list[] = '1';

        //判断用户组是否有权限
        $list = array_intersect($list, $userGroups);
        return $list ? true : false;
    }


    public function search($page = 1)
    {
        $page = intval($page);
        $_REQUEST['keywords'] = op_t($_REQUEST['keywords']);


        //读取帖子列表
        $map['title'] = array('like', "%{$_REQUEST['keywords']}%");
        $map['content'] = array('like', "%{$_REQUEST['keywords']}%");
        $map['_logic'] = 'OR';
        $where['_complex'] = $map;
        $where['status'] = 1;

        $list = D('ForumPost')->where($where)->order('last_reply_time desc')->page($page, 10)->select();
        $totalCount = D('ForumPost')->where($where)->count();
        $forums = $this->getForumList();
        $forum_key_value = array();
        foreach ($forums as $f) {
            $forum_key_value[$f['id']] = $f;
        }
        foreach ($list as &$post) {
            $post['colored_title'] = str_replace('"', '', str_replace($_REQUEST['keywords'], '<span style="color:red">' . $_REQUEST['keywords'] . '</span>', op_t(strip_tags($post['title']))));
            $post['colored_content'] = str_replace('"', '', str_replace($_REQUEST['keywords'], '<span style="color:red">' . $_REQUEST['keywords'] . '</span>', op_t(strip_tags($post['content']))));
            $post['forum'] = $forum_key_value[$post['forum_id']];
        }
        unset($post);

        $_GET['keywords'] = $_REQUEST['keywords'];
        //显示页面
        $this->assign('list', $list);
        $this->assign('totalCount', $totalCount);
        $this->display();
    }


    private function limitPictureCount($content)
    {
        //默认最多显示10张图片
        $maxImageCount = modC('LIMIT_IMAGE',10);
        //正则表达式配置
        $beginMark = 'BEGIN0000hfuidafoidsjfiadosj';
        $endMark = 'END0000fjidoajfdsiofjdiofjasid';
        $imageRegex = '/<img(.*?)\\>/i';
        $reverseRegex = "/{$beginMark}(.*?){$endMark}/i";

        //如果图片数量不够多，那就不用额外处理了。
        $imageCount = preg_match_all($imageRegex, $content);
        if ($imageCount <= $maxImageCount) {
            return $content;
        }

        //清除伪造图片
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //临时替换图片来保留前$maxImageCount张图片
        $content = preg_replace($imageRegex, "{$beginMark}$1{$endMark}", $content, $maxImageCount);

        //替换多余的图片
        $content = preg_replace($imageRegex, "[图片]", $content);

        //将替换的东西替换回来
        $content = preg_replace($reverseRegex, "<img$1>", $content);

        //返回结果
        return $content;
    }

    private function requireCanDeletePostReply($post_id)
    {
        if (!$this->canDeletePostReply($post_id)) {
            $this->error('您没有删贴权限');
        }
    }

    private function canDeletePostReply($post_id)
    {
        //如果是管理员，则可以删除
        if (is_administrator()) {
            return true;
        }

        //如果是自己的回帖，则可以删除
        $reply = D('ForumPostReply')->find($post_id);
        if ($reply['uid'] == get_uid()) {
            return true;
        }

        //其他情况不能删除
        return false;
    }


    /**过滤输出，临时解决方案
     * @param $content
     * @return mixed|string
     * @auth 陈一枭
     */
    private function filterPostContent($content)
    {
        $content = op_h($content);
        $content = $this->limitPictureCount($content);
        $content = op_h($content);
        return $content;
    }

    /**
     * @param $reply_id
     * @return mixed
     * @auth 陈一枭
     */
    private function checkRelyPermission($reply_id)
    {
        $reply = D('ForumPostReply')->find(intval($reply_id));
        $has_permission = $reply['uid'] == is_login() || is_administrator();
        return $has_permission;
    }

    // 获得帖子的发布人昵称
    public function getNickname($uid = ''){
        if($uid != ''){
            $re = M('member')->field(array('nickname'))->where("uid={$uid}")->find();
            return $re['nickname'];
        }
    }
    // my的赞接口
    public function addSupport($post_id=0,$uid=0){
        if($post_id == 0){
            echo json_encode(array('msg'=>'error','ret'=>400,'data'=>''));
            exit;
        }
        if($uid == 0){
            echo json_encode(array('msg'=>'error','ret'=>300,'data'=>''));
            exit;
        }
        $data = array(
            'appname'=>'Forum',
            'row'=>$post_id,
            'uid'=>$uid,
            'create_time'=>time(),
            'table'=>'post'
        );
        $where = array(
            'appname'=>'Forum',
            'row'=>$post_id,
            'uid'=>$uid,
            'table'=>'post'
        );
        if(M('support')->where($where)->count()){
            echo json_encode(array('msg'=>'error','ret'=>500,'data'=>''));
            exit;
        }else{
            if(M('support')->add($data)){
                echo json_encode(array('msg'=>'success','ret'=>0,'data'=>''));
            }else{
                echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
            }
        }
    }
    // my上传帖子图片
    public function upImage($image=''){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     9145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =      './Editor/'; // 设置附件上传目录
        $upload->autoSub   = true;
        $upload->subName   = array('date','Ymd');

        $info   =   $upload->upload();
        if($info){
            return $info;
        }else{
            echo json_encode(array('msg'=>'error','ret'=>400,'data'=>''));
            exit;
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
*@param post_id 文章的id
*@return 文章的标题
* 
*/
    protected function getTitle($post_id){
        $post = M('forum_post')->where("id={$post_id}")->find();
        $post['content'] = strip_tags($post['content']);
        return $post;
    }
/**
*@param 缩小图片方法(传入图片文件)
*@return 缩小后的图片名
*/
    protected function minImage($pathImage,$savename){
        $image = new \Think\Image();

        $srcImage = dirname($_SERVER['SCRIPT_FILENAME'])."/Uploads".$pathImage;
        $image->open($srcImage);
        $re = $image->thumb(150, 150,\Think\Image::IMAGE_THUMB_SCALE)->save(dirname($srcImage)."/min_".$savename);
        /*裁剪图片100*100*/
        $return = array(
            'mPath' => "/Uploads".dirname($pathImage)."/min_".$savename,
            'cPath' => "/Uploads".dirname($pathImage)."/cen_".$savename,
        );
        $this->cenImage(dirname($pathImage)."/min_".$savename,$savename);
        return $return;
    }
/*裁剪方法，配置上面的用*/
    protected function cenImage($pathImage,$savename){
        $image = new \Think\Image();
        $srcImage = dirname($_SERVER['SCRIPT_FILENAME'])."/Uploads".$pathImage;
        $image->open($srcImage);
        $image->thumb(100, 100,\Think\Image::IMAGE_THUMB_CENTER)->save(dirname($srcImage)."/cen_".$savename);
    }
/*根据文章id获取下面的评论*/
    protected function getPing($post_id){
        $result = M('forum_post_reply')->where("post_id={$post_id} and status=1")->select();
        foreach ($result as &$v) {
            $v['nickname'] = $this->getNickname($v['uid']);
            $v['diwei'] = $this->getDiwei($v['uid']);
            if($re=$this->getAvatar($v['uid'])) $v['avatar'] = $re;
            if($resu = $this->getRep($v['id'],$v['post_id'])) {
                $v['count'] = count($resu);
                $v['ping']  = $resu;
            }
        }
        return $result;
    }
/*配合上面获得回复的评论*/
    protected function getRep($id,$post_id){
        $result = M('forum_lzl_reply')->where("to_f_reply_id={$id} and post_id={$post_id} and is_del=0")->select();
        foreach ($result as &$v) {
            $v['nickname'] = $this->getNickname($v['uid']);
            if($re=$this->getAvatar($v['uid'])) $v['avatar'] = $re;
            $v['diwei'] = $this->getDiwei($v['uid']);
             $to_name = M('member')->where("uid={$v['to_uid']}")->getField('nickname');
             $v['to_name'] = $to_name?$to_name:'';
             $v['to_uid'] = $v['to_uid']?$v['to_uid']:0;
        }
        return $result;
    }

/*获取帖子收藏的次数*/
    protected function getBookmark($post_id){
        $count = M('forum_bookmark')->where("post_id={$post_id}")->count();
        if($count >= 1){
            return $count;
        }else{
            return 0;
        }
    }
/*根据用户id获取用户地位*/
    protected function getDiwei($uid){
        $paihang = query_user(array('title'),$uid);
        return $paihang['title'];
    }
/*判断帖子是否点赞*/
    protected function isSupport($uid,$post_id){
        $count = M('support')->where("uid={$uid} and row={$post_id}")->count();
        if($count > 0){
            return 1;
        }else{
            return 0;
        }
    }

}