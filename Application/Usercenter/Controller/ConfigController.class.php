<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-11
 * Time: PM1:13
 */

namespace Usercenter\Controller;

use Think\Controller;

class ConfigController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        
        $this->setTitle('编辑资料');

    }

    public function index($uid = null,$password = '',$age_lu='',$image='', $age='', $tab = '', $nickname = '', $sex = 0, $email = '', $signature = ''
        , $community = 0, $district = 0, $city = 0, $province = 0)
    {
        // 检查昵称不能重复
        $checkNicknames = D('Member')->checkNicknames($uid,$nickname);
        if($checkNicknames) exit( err(200,'昵称已被占用') );
        /*echo suc($_POST);
        die;*/
        if(empty($uid)){
            exit(err(100));
        }
        if ($_FILES['image']['error'] === 0) {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->savePath  =      './Avatar/'; // 设置附件上传目录
            $upload->subName   = array('date','Ymd');

            $info   =   $upload->upload();
            $path = $info['image']['savepath'].$info['image']['savename'];
            $path = trim($path,'.');
            $data = array(
                'uid' => $uid,
                'path' => $path,
                'create_time' => time(),
                'status' => 1,
                'is_temp' => 1
            );
            M('avatar')->add($data);
        }

        $nickname = op_t(trim($nickname));
        $signature = op_t(trim($signature));
        $sex = intval($sex);
        $email = op_t(trim($email));
        $province = trim($province);
        $city = trim($city);
        $community = trim($community);
        $district = trim($district);
        
        $user['pos_province'] = $province;
        $user['pos_city'] = $city;
        $user['pos_district'] = $district;
        $user['pos_community'] = $community;

        $user['age'] = $age;
        $user['nickname'] = $nickname;
        $user['sex'] = intval($sex);
        $user['signature'] = $signature;
        $user['uid'] = $uid;
        $user['age_lu'] = $age_lu;
        
        $rs_member = M('member')->save($user);

        clean_query_user_cache($uid, array('nickname', 'sex', 'signature', 'email', 'pos_province', 'pos_city', 'pos_district', 'pos_community'));
        
        //TODO tox 清空缓存
        if ($rs_member !== false) {
            $datas = M('ucenter_member')->where("id={$uid}")->find();
            $users = $this->getUser($uid);
            $datas['nickname'] = $users['nickname'];
            $datas['age'] = $users['age'];
            $datas['sex'] = $users['sex'];
            $datas['age_lu'] = $users['age_lu'];
            $datas['province'] = $users['province'];

            $first = M('check_info')->field('ctime')->where("uid={$uid}")->order('ctime')->limit(1)->find();
            $end   = M('check_info')->field('ctime')->where("uid={$uid}")->order('ctime DESC')->limit(1)->find();
            $datas['first']  = $first['ctime'];//第一次签到的时间戳
            $datas['end']    = $end['ctime'];//最后一次签到的时间戳
            /*用户的排名*/
            $score = M('member')->field('score')->where("uid={$uid}")->find();
            $where = $score['score'];
            $count = M('member')->where("score > {$where}")->count();
            $count = $count+1;

            $uinfo = query_user(array('avatar32','title'),$uid);
            $datas['paiming'] = $count;
            $datas['avatar']  = $uinfo['avatar32'];//获取用户的头像
            $datas['uid']     = $datas['id'];
            $datas['diwei']   = $uinfo['title'];
            unset($datas['id']);
            echo suc($datas);
        } else {
            echo err();
        }
    }
/**
获取用户的资料
*/
    public function getInfo(){
        $uid = I('uid');
        if(empty($uid)){ exit(err(100));}
        $user = query_user(array('nickname', 'signature', 'email', 'mobile', 'rank_link', 'sex', 'age','age_lu', 'pos_province', 'pos_city', 'pos_district', 'pos_community'), $uid);
        $user['avatar'] = $this->getAvatar($uid);
        exit( json_encode(array('msg'=>'success','ret'=>0,'data'=>$user)));
    }

    /**验证用户名
     * @param $nickname
     * @auth 陈一枭
     */
    private function checkNickname($nickname)
    {
        $length = mb_strlen($nickname, 'utf8');
        if ($length == 0) {
            $this->error('请输入昵称。');
        } else if ($length >= 10) {
            $this->error('昵称不能超过10个字。');
        } else if ($length <= 1) {
            $this->error('昵称不能少于1个字。');
        }
        $match = preg_match('/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']+$/', $nickname);
        if (!$match) {
            $this->error('昵称只允许中文、字母、下划线和数字。');
        }

        $map_nickname['nickname'] = $nickname;
        $map_nickname['uid'] = array('neq', is_login());
        $had_nickname = D('Member')->where($map_nickname)->count();
        if ($had_nickname) {
            $this->error('昵称已被人使用。');
        }
    }


    /**验证签名
     * @param $signature
     * @auth 陈一枭
     */
    private function checkSignature($signature)
    {
        $length = mb_strlen($signature, 'utf8');
        if ($length >= 30) {
            $this->error('签名不能超过30个字');
        }
    }


    /**获取用户扩展信息
     * @param null $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function getExpandInfo($uid = null)
    {
        $profile_group_list = $this->_profile_group_list($uid);
        if ($profile_group_list) {
            $info_list = $this->_info_list($profile_group_list[0]['id'], $uid);
            $this->assign('info_list', $info_list);
            $this->assign('profile_group_id', $profile_group_list[0]['id']);
            //dump($info_list);exit;
        }

        $this->assign('profile_group_list', $profile_group_list);
    }


    /**显示某一扩展分组信息
     * @param null $profile_group_id
     * @param null $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function showExpandInfo($profile_group_id = null, $uid = null)
    {
        $res = D('field_group')->where(array('id' => $profile_group_id, 'status' => '1'))->find();
        if (!$res) {
            $this->error('信息出错！');
        }
        $profile_group_list = $this->_profile_group_list($uid);
        $info_list = $this->_info_list($profile_group_id, $uid);
        $this->assign('info_list', $info_list);
        $this->assign('profile_group_id', $profile_group_id);
        //dump($info_list);exit;
        $this->assign('profile_group_list', $profile_group_list);
        $this->defaultTabHash('expand-info');
        $this->display('expandinfo');
    }

    /**修改用户扩展信息
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function edit_expandinfo($profile_group_id)
    {

        $field_setting_list = D('field_setting')->where(array('profile_group_id' => $profile_group_id, 'status' => '1'))->order('sort asc')->select();

        if (!$field_setting_list) {
            $this->error('没有要修改的信息！');
        }

        $data = null;
        foreach ($field_setting_list as $key => $val) {
            $data[$key]['uid'] = is_login();
            $data[$key]['field_id'] = $val['id'];
            switch ($val['form_type']) {
                case 'input':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->error($val['field_name'] . '内容不能为空！');
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->error($val['submit']['msg']);
                        }
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'radio':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'checkbox':
                    $val['value'] = $_POST['expand_' . $val['id']];
                    if (!is_array($val['value']) && $val['required'] == 1) {
                        $this->error('请至少选择一个：' . $val['field_name']);
                    }
                    $data[$key]['field_data'] = is_array($val['value']) ? implode('|', $val['value']) : '';
                    break;
                case 'select':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'time':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    $val['value'] = strtotime($val['value']);
                    $data[$key]['field_data'] = $val['value'];
                    break;
                case 'textarea':
                    $val['value'] = op_t($_POST['expand_' . $val['id']]);
                    if (!$val['value'] || $val['value'] == '') {
                        if ($val['required'] == 1) {
                            $this->error($val['field_name'] . '内容不能为空！');
                        }
                    } else {
                        $val['submit'] = $this->_checkInput($val);
                        if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                            $this->error($val['submit']['msg']);
                        }
                    }
                    $val['submit'] = $this->_checkInput($val);
                    if ($val['submit'] != null && $val['submit']['succ'] == 0) {
                        $this->error($val['submit']['msg']);
                    }
                    $data[$key]['field_data'] = $val['value'];
                    break;
            }
        }
        $map['uid'] = is_login();
        $is_success = false;
        foreach ($data as $dl) {
            $map['field_id'] = $dl['field_id'];
            $res = D('field')->where($map)->find();
            if (!$res) {
                if ($dl['field_data'] != '' && $dl['field_data'] != null) {
                    $dl['createTime'] = $dl['changeTime'] = time();
                    if (!D('field')->add($dl)) {
                        $this->error('信息添加时出错！');
                    }
                    $is_success = true;
                }
            } else {
                $dl['changeTime'] = time();
                if (!D('field')->where('id=' . $res['id'])->save($dl)) {
                    $this->error('信息修改时出错！');
                }
                $is_success = true;
            }
            unset($map['field_id']);
        }
        clean_query_user_cache(is_login(), 'expand_info');
        if ($is_success) {
            $this->success('保存成功！');
        } else {
            $this->error('没有要保存的信息！');
        }
    }

    /**input类型验证
     * @param $data
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    function _checkInput($data)
    {
        if ($data['form_type'] == "textarea") {
            $validation = $this->_getValidation($data['validation']);
            if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                if ($validation['max'] == 0) {
                    $validation['max'] = '';
                }
                $info['succ'] = 0;
                $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间";
            }
        } else {
            switch ($data['child_form_type']) {
                case 'string':
                    $validation = $this->_getValidation($data['validation']);
                    if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                        if ($validation['max'] == 0) {
                            $validation['max'] = '';
                        }
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间";
                    }
                    break;
                case 'number':
                    if (preg_match("/^\d*$/", $data['value'])) {
                        $validation = $this->_getValidation($data['validation']);
                        if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                            if ($validation['max'] == 0) {
                                $validation['max'] = '';
                            }
                            $info['succ'] = 0;
                            $info['msg'] = $data['field_name'] . "长度必须在" . $validation['min'] . "-" . $validation['max'] . "之间，且为数字";
                        }
                    } else {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "必须是数字";
                    }
                    break;
                case 'email':
                    if (!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "格式不正确，必需为邮箱格式";
                    }
                    break;
                case 'phone':
                    if (!preg_match("/^\d{11}$/", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . "格式不正确，必须为手机号码格式";
                    }
                    break;
            }
        }
        return $info;
    }

    /**处理$validation
     * @param $validation
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    function _getValidation($validation)
    {
        $data['min'] = $data['max'] = 0;
        if ($validation != '') {
            $items = explode('&', $validation);
            foreach ($items as $val) {
                $item = explode('=', $val);
                if ($item[0] == 'min' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['min'] = $item[1];
                }
                if ($item[0] == 'max' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['max'] = $item[1];
                }
            }
        }
        return $data;
    }

    /**分组下的字段信息及相应内容
     * @param null $id 扩展分组id
     * @param null $uid
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _info_list($id = null, $uid = null)
    {
        $info_list = null;

        if (isset($uid) && $uid != is_login()) {
            //查看别人的扩展信息
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1', 'visiable' => '1'))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = $uid;
        } else if (is_login()) {
            $field_setting_list = D('field_setting')->where(array('profile_group_id' => $id, 'status' => '1'))->order('sort asc')->select();

            if (!$field_setting_list) {
                return null;
            }
            $map['uid'] = is_login();

        } else {
            $this->error('请先登录！');
        }
        foreach ($field_setting_list as $val) {
            $map['field_id'] = $val['id'];
            $field = D('field')->where($map)->find();
            $val['field_content'] = $field;
            $info_list[$val['id']] = $val;
            unset($map['field_id']);
        }

        return $info_list;
    }


    /**扩展信息分组列表获取
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function _profile_group_list($uid = null)
    {
        if (isset($uid) && $uid != is_login()) {
            $map['visiable'] = 1;
        }
        $map['status'] = 1;
        $profile_group_list = D('field_group')->where($map)->order('sort asc')->select();

        return $profile_group_list;
    }


    public function changeAvatar()
    {
        $this->defaultTabHash('change-avatar');
        $this->display();
    }

    public function doCropAvatar($crop)
    {
        //调用上传头像接口改变用户的头像
        $result = callApi('User/applyAvatar', array($crop));
        $this->ensureApiSuccess($result);

        //显示成功消息
        $this->success($result['message'], U('Usercenter/Config/index', array('tab' => 'avatar')));
    }

    public function doUploadAvatar($image='')
    {
        if($_FILES['image']['error'] === 0){
            $uid = I('uid');
            if(empty($uid)) exit(err(100));//判断uid不能为空
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->savePath  =      './Avatar/'; // 设置附件上传目录
            $upload->subName   = array('date','Ymd');

            $info   =   $upload->upload();
            if($info){
                $path = $info['image']['savepath'].$info['image']['savename'];
                $path = trim($path,'.');
                $data = array(
                    'uid' => $uid,
                    'path' => $path,
                    'create_time' => time(),
                    'status' => 1,
                    'is_temp' => 1
                );
                if($aid = M('avatar')->add($data)){
                    $path = M('avatar')->where("id={$aid}")->getField('path');
                    echo suc("/Uploads".$path);
                }else{
                    echo err();
                }
            }else{
                echo err();
            }
        }else{
            echo err(400);
        }
    }

    private function iframeReturn($result)
    {
        $json = json_encode($result);
        $json = htmlspecialchars($json);
        $html = "<textarea data-type=\"application/json\">$json</textarea>";
        echo $html;
        exit;
    }


    public function doChangePassword()
    {
        $path = dirname($_SERVER['SCRIPT_FILENAME']);
        require_once $path."/Application/User\Common\common.php";
        $uid = $_POST['uid'];
        $password = $_POST['password'];
        $repass = $_POST['repass'];
        // 验证两次密码是否一致
        if($password != $repass){ echo json_encode(array('data'=>array(),'ret'=>904,'msg'=>'error'));die; }
        // 检测密码格式
        /*if(preg_match('/.{5,20}/',$password)){ echo json_encode(array('data'=>array(),'msg'=>'error','ret'=>903));die; }*/
        $data = array(
            'password'=>think_ucenter_md5($password,'u+Sw98l%gWK4AZ#[ThQzex^,5ObV_tk("-N]viq7'),
        );
        if(M('ucenter_member')->where("id={$uid}")->save($data)){
            echo json_encode(array('msg'=>'success','ret'=>0));
        }else{
            echo json_encode(array('msg'=>'error','ret'=>1));
        }
        //调用接口
        // $result = callApi('User/changePassword', array($old_password, $new_password));
        // $this->ensureApiSuccess($result);

        //显示成功信息
        // $this->success($result['message']);
    }

    /**
     * @param $sex
     * @return int
     * @auth 陈一枭
     */
    private function checkSex($sex)
    {

        if ($sex < 0 || $sex > 2) {
            $this->error('性别必须属于男、女、保密。');
            return $sex;
        }
        return $sex;
    }

    /**
     * @param $email
     * @param $email
     * @auth 陈一枭
     */
    private function checkEmail($email)
    {
        $pattern = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if (!preg_match($pattern, $email)) {
            $this->error('邮箱格式错误。');
        }

        $map['email'] = $email;
        $map['id'] = array('neq', get_uid());
        $had = D('UcenterMember')->where($map)->count();
        if ($had) {
            $this->error('该邮箱已被人使用。');
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
                $return = $avatar['path'];
            }
            return $return;
        }
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
}