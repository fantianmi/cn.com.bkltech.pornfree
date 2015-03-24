<?php
namespace Face\Controller;
use Think\Controller;
use User\Api\UserApi;
// Think\Controller:showRegError方法不存在！
class RegController extends Controller{
	public function register($username = '', $nickname = '', $password = '', $email = '', $verify = '', $type = 'start'){
		/*$username = I('param.username');
		$password = I('param.password');
		if(empty($username)){ exit("error"); }
		if(empty($password)){ exit("error"); }*/
		/* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $nickname, $password, $email);
            dump($uid);
            if (0 < $uid) { //注册成功
                $uid = $User->login($username, $password);//通过账号密码取到uid
                D('Member')->login($uid, false);//登陆
                $reg_weibo = C('USER_REG_WEIBO_CONTENT');//用户注册的微博内容
                if ($reg_weibo != '') {//为空不发微博
                    D('Weibo/Weibo')->addWeibo($uid, $reg_weibo);
                }

                echo "success";;
            } else { //注册失败，显示错误信息
                echo "error";
            }

		/*$data = array(
			'username'=>'usernamesa',
			'password'=>'passworda'
		);
		if(M("ucenter_member")->add($data)){
			echo json_encode(array('msg'=>'success','ret'=>1));
		}else{
			echo json_encode(array('msg'=>'error','ret'=>0));
		}*/
	}
}

