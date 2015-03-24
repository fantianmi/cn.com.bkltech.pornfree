<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="http://pornfree.bkltech.com.cn/index.php?s=/home/user/login.html" method="post" class="lg_lf_form " >
                    <div class="form-group">
                        <label for="inputEmail" class=".sr-only col-xs-12"></label>

                        <div class="col-xs-12 col-xs-offset-1">
                            <input type="text" id="inputEmail" class="form-control" placeholder="请输入用户名"
                                   ajaxurl="/member/checkUserNameUnique.html" errormsg="请填写1-16位用户名"
                                   nullmsg="请填写用户名"
                                   datatype="*1-16" value="" name="username">
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class=".sr-only col-xs-12"></label>

                        <div class="col-xs-12 col-xs-offset-1">
                            <div id="password_block" class="input-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="请输入密码"
                                       errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">

                                <div class="input-group-addon"><a style="width: 100%;height: 100%" href="javascript:void(0);" onclick="change_show(this)">show</a></div>
                            </div>
                        </div>
                        <div style="margin-left: 30px;"><a class="btn btn-link" href="<?php echo U('User/mi');?>" style="color: #848484;font-size: 12px;">忘记密码？</a></div>
                        <div class="clearfix"></div>
                    </div>
                    <?php if(C(VERIFY_OPEN) == 1 OR C(VERIFY_OPEN) == 3): ?><div class="form-group">
                            <label for="verifyCode" class=".sr-only col-xs-12" style="display: none"></label>

                            <div class="col-xs-12 col-md-5 col-xs-offset-1">
                                <input type="text" id="verifyCode" class="form-control" placeholder="验证码"
                                       errormsg="请填写验证码" nullmsg="请填写验证码" datatype="*5-5" name="verify">
                            </div>
                            <div class="col-xs-12 col-md-6 lg_lf_fm_verify">
                                <img class="verifyimg reloadverify  " alt="点击切换" src="<?php echo U('verify');?>"
                                     style="cursor:pointer;max-width: 100%">
                            </div>
                            <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip col-xs-offset-1"></div>
                            <div class="clearfix"></div>
                        </div><?php endif; ?>
                    <div class="checkbox lg_lf_fm_checkbox col-xs-offset-1">
                        <label>
                            <input type="checkbox" name="remember" style="cursor:pointer;"> 记住登录
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: -15px;">登 录</button>
                    <div class="clearfix"></div>
                </form>
</body>
</html>