<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
</head>
<body>
	<form class="" action="http://192.168.0.129:8080/thinkox4/index.php?s=/home/user/register
" method="post">
                        <div class="form-group">
                            <label for="username" class=".sr-only col-xs-12" style="display: none"></label>
                            <input type="text" id="username" onblur="setNickname(this);" class="form-control" placeholder="请输入用户名"
                                   ajaxurl="/member/checkUserNameUnique.html" errormsg="请填写4-16位用户名"
                                   nullmsg="请填写用户名"
                                   datatype="*4-16" value="" name="username">
                            <span class="help-block">输入用户名，只允许字母和数字和下划线</span>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                            <div id="password_block" class="input-group">
                                <input type="password" id="inputPassword" class="form-control" placeholder="请输入密码"
                                       errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">

                                <div class="input-group-addon"><a style="width: 100%;height: 100%"
                                                                  href="javascript:void(0);"
                                                                  onclick="change_show(this)">show</a></div>
                            </div>
                            <span class="help-block">请输入密码</span>
                            <div class="clearfix"></div>
                        </div>
                        <div class="form-group">
                                <label for="verifyCode" class=".sr-only col-xs-12" style="display: none"></label>

                                <div class="col-xs-12 Validform_checktip text-warning lg_lf_fm_tip"></div>
                                <div class="clearfix"></div>
                            </div>                        <div style="float: left;vertical-align: bottom;margin-top: 12px;color: #848484;">
                            已有账户， <a href="/thinkox/index.php?s=/home/user/login.html" title="" style="color: #03B38B;">登录</a>
                        </div>
                        <button type="submit" class="btn btn-primary pull-right">提 交</button>
                    </form>

</body>
</html>