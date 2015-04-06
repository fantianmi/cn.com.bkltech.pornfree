<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use User\Api\UserApi;

require_once APP_PATH . 'User/Conf/config.php';

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController
{

    /* 用户中心首页 */
    public function index()
    {

    }
    /* 注册页面 */
    public function register()
    {
        $username = I('username');
        $password = I('password');
        $nickname = '';
        $email = '';
        if (!C('USER_ALLOW_REGISTER')) {
            exit(err(200));//注册已关闭
        }
        if (strpos($username, ' ') !== false) {
            exit(err(300));//用户名里有空格，不让注册
        }
        preg_match('/[a-zA-Z0-9_@\-\.]{6,30}/', $username, $result);
        if (!$result) {
            exit(err(300));//用户名不是数字，字母，下划线的不能注册
        }
        $len = strlen($password);
        if($len < 6 || $len > 30){
            exit(err(500));//密码长度须在6-30之间
        }
        if(M('ucenter_member')->where("username='{$username}'")->find()){
            exit(err(400));
        }
            /* 调用注册接口注册用户 */
            $User = new UserApi;
            $uid = $User->register($username, $nickname, $password, $email);
            if (0 < $uid) { //注册成功
                $uid = $User->login($username, $password);//通过账号密码取到uid
                D('Member')->login($uid, false);//登陆
                $reg_weibo = C('USER_REG_WEIBO_CONTENT');//用户注册的微博内容
                if ($reg_weibo != '') {//为空不发微博
                    D('Weibo/Weibo')->addWeibo($uid, $reg_weibo);
                }
                $data = M('ucenter_member')->field("password",true)->where("id={$uid}")->select();

                $field = array(
                    'id'=>'uid',
                    'username',
                    'password',
                    'email',
                    'mobile',
                );
                $user = M('ucenter_member')->field($field)->where("id={$uid}")->find();
                $users = $this->getUser($user['uid']);
                $user['nickname'] = $users['nickname'];
                $user['age'] = $users['age'];
                $user['sex'] = $users['sex'];
                $user['age_lu'] = $users['age_lu'];
                /*处理地位*/
                $uScore = M('member')->where("uid={$uid}")->getField("score");
                $result = M('config')->field("value")->where('name="_USER_LEVEL"')->limit(999)->select();
                $str = $result[0]['value'];
                $arr = explode("\r\n",$str);
                $name1 = end(explode(':',$arr[0]));
                $name2 = end(explode(':',$arr[1]));
                $name3 = end(explode(':',$arr[2]));
                $name4 = end(explode(':',$arr[3]));
                $name5 = end(explode(':',$arr[4]));
                $name6 = end(explode(':',$arr[5]));
                $name7 = end(explode(':',$arr[6]));
                $name8 = end(explode(':',$arr[7]));
                $name9 = end(explode(':',$arr[8]));
                $name10 = end(explode(':',$arr[9]));
                $name11 = end(explode(':',$arr[10]));
                preg_match('/\d{1,5}/',$arr[0],$re1);
                preg_match('/\d{1,5}/',$arr[1],$re2);
                preg_match('/\d{1,5}/',$arr[2],$re3);
                preg_match('/\d{1,5}/',$arr[3],$re4);
                preg_match('/\d{1,5}/',$arr[4],$re5);
                preg_match('/\d{1,5}/',$arr[5],$re6);
                preg_match('/\d{1,5}/',$arr[6],$re7);
                preg_match('/\d{1,5}/',$arr[7],$re8);
                preg_match('/\d{1,5}/',$arr[8],$re9);
                preg_match('/\d{1,5}/',$arr[9],$re10);
                preg_match('/\d{1,5}/',$arr[10],$re11);
                if($uScore == 0){
                    $cScore = $re2[0];
                    $demo = $name2;
                }else{
                    switch ($uScore) {
                        case $uScore==0:
                            $cScore = $re2[0];
                            $demo = $name2;
                            break;
                        case $uScore<$re2[0]:
                            $cScore = $re2[0] - $uScore;
                            $demo = $name2;
                            break;
                        case $uScore<$re3[0]:
                            $cScore = $re3[0] - $uScore;
                            $demo = $name3;
                            break;
                        case $uScore<$re4[0]:
                            $cScore = $re4[0] - $uScore;
                            $demo = $name4;
                            break;
                        case $uScore<$re5[0]:
                            $cScore = $re5[0] - $uScore;
                            $demo = $name5;
                            break;
                        case $uScore<$re6[0]:
                            $cScore = $re6[0] - $uScore;
                            $demo = $name6;
                            break;
                        case $uScore<$re7[0]:
                            $cScore = $re7[0] - $uScore;
                            $demo = $name7;
                            break;
                        case $uScore<$re8[0]:
                            $cScore = $re8[0] - $uScore;
                            $demo = $name8;
                            break;
                        case $uScore<$re9[0]:
                            $cScore = $re9[0] - $uScore;
                            $demo = $name9;
                            break;
                        case $uScore<$re10[0]:
                            $cScore = $re10[0] - $uScore;
                            $demo = $name10;
                            break;
                        case $uScore<$re11[0]:
                            $cScore = $re11[0] - $uScore;
                            $demo = $name11;
                            break;
                        default:
                            $cScore = 0;
                            $demo = "已经是最高地位";
                            break;
                    }
                }
                $paihang = query_user(array('title'),$uid);
                $score = M('member')->field('score')->where("uid={$uid}")->find();
                $where = $score['score'];
                $count = M('member')->where("score > {$where}")->count();
                $count = $count+1;
                $user['diwei'] = $paihang['title'];
                $user['paiming'] = $count;
                $user['cScore'] = $count;
                $user['cDiwei'] = $demo;
                echo suc($user);
            } else { //注册失败，显示错误信息
                echo err(1);
            }
    }

    /* 注册页面step2 */
    public function step2($type = 'upload')
    {
        $type = op_t($type); //显示上传头像页面
        $this->assign('type', $type);
        $this->display('register');
    }

    public function doCropAvatar($crop)
    {
        //调用上传头像接口改变用户的头像
        $result = callApi('User/applyAvatar', array($crop));
        $this->ensureApiSuccess($result);

        //显示成功消息
        $this->success($result['message'], U('Home/User/step3'));
    }

    /* 注册页面step3 */
    public function step3($type = 'finish')
    {
        $type = op_t($type);
        $this->assign('type', $type);
        $this->display('register');
    }


    public function getUser($uid){
        $user = M('member')->field(array('nickname','sex','age','age_lu','pos_province'))->where("uid={$uid}")->find();
        $arr = array(
            'nickname'=>$user['nickname'],
            'sex'=>$user['sex'],
            'age'=>$user['age'],
            'age_lu'=>$user['age_lu'],
            'province'=>$user['pos_province']
        );
        return $arr;
    }
    /* 登录页面 */
    public function login()
    {
        $username = I('username');
        $password = I('password');
        if(empty($username) || empty($password)){
           echo json_encode(array('msg'=>'用户名密码不能为空','ret'=>100,'data'=>''));
        }
            /* 调用UC登录接口登录 */
            $user = new UserApi;
            $uid = $user->login($username, $password);
            if (0 < $uid) { //UC登录成功
                /* 登录用户 */
                $Member = D('Member');
                $field = array(
                    'id'=>'uid',
                    'username',
                    'password',
                    'email',
                    'mobile',
                );
                $data = M('ucenter_member')->field($field)->where("id={$uid}")->find();
                $user = $this->getUser($data['uid']);
                $data['nickname'] = $user['nickname'];
                $data['age'] = $user['age'];
                $data['sex'] = $user['sex'];
                $data['age_lu'] = $user['age_lu'];
                $data['province'] = $user['province'];

                /*获取最后次签到的记录*/
                $end = M('Check_info')->where("uid={$uid}")->order('ctime DESC')->limit(1)->find();
                if($end){//有签到记录
                    $con_nums = $end['con_num'];
                    if($con_nums == 0){//破戒直接为空
                        $data['first'] = '';
                        $data['end']   = '';
                    }else{
                        $gt = strtotime(date('Ymd')) - 86400;
                        if($end['ctime'] >= $gt){//签到时间是否在有效期内
                            /*获取连签时间*/
                            $first = M('Check_info')->field('ctime')->where("uid={$uid}")->order('ctime DESC')->page($con_nums,1)->find();
                            $data['first'] = $first['ctime'];
                            $data['end']   = $end['ctime'];
                        }else{//连签时间过期返回空
                            $data['first'] = '';
                            $data['end']   = '';
                        }
                    }
                }else{//没用签到记录反回空
                    $data['first'] = '';
                    $data['end']   = '';
                }

                /*$first = M('check_info')->field('ctime')->where("uid={$uid}")->order('ctime')->limit(1)->find();
                $end   = M('check_info')->field('ctime')->where("uid={$uid}")->order('ctime DESC')->limit(1)->find();
                $data['first']  = $first['ctime'];//第一次签到的时间戳
                $data['end']    = $end['ctime'];//最后一次签到的时间戳*/
                /*用户的排名*/
                $score = M('member')->field('score')->where("uid={$uid}")->find();
                $where = $score['score'];
                $count = M('member')->where("score > {$where}")->count();
                $count = $count+1;
                $data['paiming'] = $count;
                $data['avatar']  = "/Uploads".$this->getAvatar($uid);//获取用户的头像
                $data['totalcount'] = M('jingchong')->where("uid={$uid}")->count();//总上脑次数
                $diwei = query_user(array('title'),$uid);//地位
                $data['diwei'] = $diwei['title'];//地位
                /*今日上脑次数*/
                $starts = strtotime(date('Ymd'));
                $ends = $starts+3600*24;
                $map = array(
                    'ctime'=>array('BETWEEN',"{$starts},{$ends}"),
                    'uid'  =>array('eq',$uid)
                );
                $count = M('jingchong')->where($map)->count();
                $data['count'] = $count;
                /*连签第一天的次数*/
                $con_num = M('check_info')->where("uid={$uid}")->order('ctime DESC')->find();
                $conNum = $con_num['con_num'];//连签的次数
                $time = M('check_info')->where("uid={$uid}")->order('ctime DESC')->page($conNum,1)->select();
                $data['time'] = $time[0]['ctime']?$time[0]['ctime']:0;
                /*处理地位*/
                $uScore = M('member')->where("uid={$uid}")->getField("score");
                $result = M('config')->field("value")->where('name="_USER_LEVEL"')->limit(999)->select();
                $str = $result[0]['value'];
                $arr = explode("\r\n",$str);
                $name1 = end(explode(':',$arr[0]));
                $name2 = end(explode(':',$arr[1]));
                $name3 = end(explode(':',$arr[2]));
                $name4 = end(explode(':',$arr[3]));
                $name5 = end(explode(':',$arr[4]));
                $name6 = end(explode(':',$arr[5]));
                $name7 = end(explode(':',$arr[6]));
                $name8 = end(explode(':',$arr[7]));
                $name9 = end(explode(':',$arr[8]));
                $name10 = end(explode(':',$arr[9]));
                $name11 = end(explode(':',$arr[10]));
                preg_match('/\d{1,5}/',$arr[0],$re1);
                preg_match('/\d{1,5}/',$arr[1],$re2);
                preg_match('/\d{1,5}/',$arr[2],$re3);
                preg_match('/\d{1,5}/',$arr[3],$re4);
                preg_match('/\d{1,5}/',$arr[4],$re5);
                preg_match('/\d{1,5}/',$arr[5],$re6);
                preg_match('/\d{1,5}/',$arr[6],$re7);
                preg_match('/\d{1,5}/',$arr[7],$re8);
                preg_match('/\d{1,5}/',$arr[8],$re9);
                preg_match('/\d{1,5}/',$arr[9],$re10);
                preg_match('/\d{1,5}/',$arr[10],$re11);
                if($uScore == 0){
                    $cScore = $re2[0];
                    $demo = $name2;
                }else{
                    switch ($uScore) {
                        case $uScore==0:
                            $cScore = $re2[0];
                            $demo = $name2;
                            break;
                        case $uScore<$re2[0]:
                            $cScore = $re2[0] - $uScore;
                            $demo = $name2;
                            break;
                        case $uScore<$re3[0]:
                            $cScore = $re3[0] - $uScore;
                            $demo = $name3;
                            break;
                        case $uScore<$re4[0]:
                            $cScore = $re4[0] - $uScore;
                            $demo = $name4;
                            break;
                        case $uScore<$re5[0]:
                            $cScore = $re5[0] - $uScore;
                            $demo = $name5;
                            break;
                        case $uScore<$re6[0]:
                            $cScore = $re6[0] - $uScore;
                            $demo = $name6;
                            break;
                        case $uScore<$re7[0]:
                            $cScore = $re7[0] - $uScore;
                            $demo = $name7;
                            break;
                        case $uScore<$re8[0]:
                            $cScore = $re8[0] - $uScore;
                            $demo = $name8;
                            break;
                        case $uScore<$re9[0]:
                            $cScore = $re9[0] - $uScore;
                            $demo = $name9;
                            break;
                        case $uScore<$re10[0]:
                            $cScore = $re10[0] - $uScore;
                            $demo = $name10;
                            break;
                        case $uScore<$re11[0]:
                            $cScore = $re11[0] - $uScore;
                            $demo = $name11;
                            break;
                        default:
                            $cScore = 0;
                            $demo = "已经是最高地位";
                            break;
                    }
                }
                $paihang = query_user(array('title'),$uid);
                $score = M('member')->field('score')->where("uid={$uid}")->find();
                $where = $score['score'];
                $count = M('member')->where("score > {$where}")->count();
                $count = $count+1;
                $data['diwei'] = $paihang['title'];
                $data['paiming'] = $count;
                $data['cScore'] = $count;
                $data['cDiwei'] = $demo;
                $data['checkCount'] = D('Addons://Checkin/CheckInfo')->getCheckDay($uid);
                echo json_encode(array('msg'=>'success','ret'=>0,'data'=>$data));
                // 处理破解信息
                D('PojieRank')->pojieTime($uid);
                if ($Member->login($uid, $remember == 'on')) { //登录用户
                    //TODO:跳转到登录前页面

                    if (UC_SYNC && $uid != 1) {
                        //同步登录到UC
                        $ref = M('ucenter_user_link')->where(array('uid' => $uid))->find();
                        $html = '';
                        $html = uc_user_synlogin($ref['uc_uid']);
                    }
                } else {
                }

            } else { //登录失败
                switch ($uid) {
                    case -1:
                        $error = '300';
                        break; //系统级别禁用
                    case -2:
                        $error = '200';
                        break;
                    default:
                        $error = $uid;
                        break; // 0-接口参数错误（调试阶段使用）
                }
                echo json_encode(array('msg'=>'用户名密码错误','ret'=>$error,'data'=>''));
            }
    }

    /* 退出登录 */
    public function logout()
    {
        if (is_login()) {
            D('Member')->logout();
            $this->success('退出成功！', U('User/login'));
        } else {
            $this->redirect('User/login');
        }
    }

    /* 验证码，用于登录和注册 */
    public function verify()
    {
        $verify = new \Think\Verify();
        $verify->entry(1);
    }

    /* 用户密码找回首页 */
    public function mi($username = '', $email = '', $verify = '')
    {
        $username = strval($username);
        $email = strval($email);

        if (IS_POST) { //登录验证
            //检测验证码

            if (!check_verify($verify)) {
                $this->error('验证码输入错误');
            }

            //根据用户名获取用户UID
            $user = D('User/UcenterMember')->where(array('username' => $username, 'email' => $email, 'status' => 1))->find();
            $uid = $user['id'];
            if (!$uid) {
                $this->error("用户名或邮箱错误");
            }

            //生成找回密码的验证码
            $verify = $this->getResetPasswordVerifyCode($uid);

            //发送验证邮箱
            $url = 'http://' . $_SERVER['HTTP_HOST'] . U('Home/User/reset?uid=' . $uid . '&verify=' . $verify);
            $content = C('USER_RESPASS') . "<br/>" . $url . "<br/>" . C('WEB_SITE') . "系统自动发送--请勿直接回复<br/>" . date('Y-m-d H:i:s', TIME()) . "</p>";
            send_mail($email, C('WEB_SITE') . "密码找回", $content);
            $this->success('密码找回邮件发送成功', U('User/login'));
        } else {
            if (is_login()) {
                redirect(U('Weibo/Index/index'));
            }

            $this->display();
        }
    }

    /**
     * 重置密码
     */
    public function reset($uid, $verify)
    {
        //检查参数
        $uid = intval($uid);
        $verify = strval($verify);
        if (!$uid || !$verify) {
            $this->error("参数错误");
        }

        //确认邮箱验证码正确
        $expectVerify = $this->getResetPasswordVerifyCode($uid);
        if ($expectVerify != $verify) {
            $this->error("参数错误");
        }

        //将邮箱验证码储存在SESSION
        session('reset_password_uid', $uid);
        session('reset_password_verify', $verify);

        //显示新密码页面
        $this->display();
    }

    public function doReset($password, $repassword)
    {
        //确认两次输入的密码正确
        if ($password != $repassword) {
            $this->error('两次输入的密码不一致');
        }

        //读取SESSION中的验证信息
        $uid = session('reset_password_uid');
        $verify = session('reset_password_verify');

        //确认验证信息正确
        $expectVerify = $this->getResetPasswordVerifyCode($uid);
        if ($expectVerify != $verify) {
            $this->error("验证信息无效");
        }

        //将新的密码写入数据库
        $data = array('id' => $uid, 'password' => $password);
        $model = D('User/UcenterMember');
        $data = $model->create($data);
        if (!$data) {
            $this->error('密码格式不正确');
        }
        $result = $model->where(array('id' => $uid))->save($data);
        if ($result === false) {
            $this->error('数据库写入错误');
        }

        //显示成功消息
        $this->success('密码重置成功', U('Home/User/login'));
    }

    private function getResetPasswordVerifyCode($uid)
    {
        $user = D('User/UcenterMember')->where(array('id' => $uid))->find();
        $clear = implode('|', array($user['uid'], $user['username'], $user['last_login_time'], $user['password']));
        $verify = thinkox_hash($clear, UC_AUTH_KEY);
        return $verify;
    }

    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0)
    {
        switch ($code) {
            case -1:
                $error = '用户名长度必须在4-16个字符以内！';
                break;
            case -2:
                $error = '用户名被禁止注册！';
                break;
            case -3:
                $error = '用户名被占用！';
                break;
            case -4:
                $error = '密码长度必须在6-30个字符之间！';
                break;
            case -5:
                $error = '邮箱格式不正确！';
                break;
            case -6:
                $error = '邮箱长度必须在4-32个字符之间！';
                break;
            case -7:
                $error = '邮箱被禁止注册！';
                break;
            case -8:
                $error = '邮箱被占用！';
                break;
            case -9:
                $error = '手机格式不正确！';
                break;
            case -10:
                $error = '手机被禁止注册！';
                break;
            case -11:
                $error = '手机号被占用！';
                break;
            case -20:
                $error = '用户名只能由数字、字母和"_"组成！';
                break;
            case -30:
                $error = '昵称被占用！';
                break;
            case -31:
                $error = '昵称被禁止注册！';
                break;
            case -32:
                $error = '昵称只能由数字、字母、汉字和"_"组成！';
                break;
            default:
                $error = '未知错误24';
        }
        return $error;
    }


    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function profile()
    {
        if (!is_login()) {
            $this->error('您还没有登陆', U('User/login'));
        }
        if (IS_POST) {
            //获取参数
            $uid = is_login();
            $password = I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if ($data['password'] !== $repassword) {
                $this->error('您输入的新密码与确认密码不一致');
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if ($res['status']) {
                $this->success('修改密码成功！');
            } else {
                $this->error($res['info']);
            }
        } else {
            $this->display();
        }
    }

/*获取用户的头像*/
    protected function getAvatar($uid){
        $re = M('avatar')->where("uid={$uid}")->order('create_time DESC')->limit(1)->find();
        return $re['path'];
    }
}