<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-8
 * Time: PM4:30
 */

namespace Forum\Controller;

use Think\Controller;

define('TOP_ALL', 1);
define('TOP_FORUM', 2);

class LzlController extends Controller
{


    public function  lzllist($to_f_reply_id, $page = 1,$p=1)
    {
        $limit = 5;
        $list = D('ForumLzlReply')->getLZLReplyList($to_f_reply_id,'ctime asc',$page,$limit);
        $totalCount = D('forum_lzl_reply')->where('is_del=0 and to_f_reply_id=' . $to_f_reply_id)->count();
        $data['to_f_reply_id'] = $to_f_reply_id;
        $pageCount = ceil($totalCount / $limit);
        $html = getPageHtml('changePage', $pageCount, $data, $page);
        $this->assign('lzlList', $list);
        $this->assign('html', $html);
        $this->assign('p', $p);
        $this->assign('nowPage', $page);
        $this->assign('totalCount', $totalCount);
        $this->assign('limit', $limit);
        $this->assign('count', count($list));
        $this->assign('to_f_reply_id', $to_f_reply_id);
        $this->display();
    }


    public function doSendLZLReply($post_id,$uid=0, $to_f_reply_id, $to_reply_id, $to_uid, $content,$p=1)
    {

        if($uid == 0){
            echo json_encode(array('msg'=>'error','ret'=>300,'data'=>''));
            exit;
        }
        //写入数据库
        $model = D('ForumLzlReply');
        $before=getMyScore();
        $tox_money_before=getMyToxMoney();
        $result = $model->addLZLReply($uid,$post_id, $to_f_reply_id, $to_reply_id, $to_uid, op_t($content),$p);
        $after=getMyScore();
        $tox_money_after=getMyToxMoney();
        if (!$result) {
            echo json_encode(array('msg'=>'error','ret'=>1,'data'=>''));
            exit;
            // $this->error('发布失败：' . $model->getError());
        }
        $data = D('ForumLzlReply')->where("id={$result}")->find();
        $data['nickname'] = $this->getNickname($data['uid']);
        $data['to_name']   = $this->getNickname($data['to_uid']);
        $data['avatar']   = $this->getAvatar($data['uid']);
        $data['diwei']    = $this->getDiwei($uid);
        echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$data));
        $nickname = D('Member')->getNickname($uid);
        $title = $nickname.' 回复了您的评论';
        D('BaiTui')->genDataOne($to_uid,$title,$title,$data['uid']);
        exit;
        //显示成功页面
        $totalCount = D('forum_lzl_reply')->where('is_del=0 and to_f_reply_id=' . $to_f_reply_id)->count();
        $limit = 5;
        $pageCount = ceil($totalCount / $limit);
        // exit(json_encode(array('status'=>1,'info'=>'回复成功。'.getScoreTip($before,$after).getToxMoneyTip($tox_money_before,$tox_money_after),'url'=>$pageCount)));
    }

    private function requireLogin()
    {
        if (!is_login()) {
            $this->error('需要登录');
        }
    }

public function delLZLReply($id){
    $this->requireLogin();
    $data['post_reply_id']=D('ForumLzlReply')->where('id='.$id)->getfield('to_f_reply_id');
    $res= D('ForumLzlReply')->delLZLReply($id);
    $data['lzl_reply_count']=D('ForumLzlReply')->where('is_del=0 and to_f_reply_id='.$data['post_reply_id'])->count();
    $res &&   $this->success($res,'',$data);
    !$res &&   $this->error('');
}

/**
*@param uid 用户的id
*@return 用户的昵称
* 
*/
    public function getNickname($uid){
        if($uid != ''){
            $re = M('member')->field(array('nickname'))->where("uid={$uid}")->find();
            return $re['nickname'];
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
/*根据用户id获取用户地位*/
    protected function getDiwei($uid){
        $paihang = query_user(array('title'),$uid);
        return $paihang['title'];
    }

}