<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="http://192.168.0.200:8080/thinkox4/index.php?s=/face/zhengzhuang/addreport" method="post">
		post_id : <input type="text" name="post_id" />
		<br />
		uid : <input type="text" name="uid" />
		<br />
		<input type="submit" value="提交" />
		<?php echo C('FOOTER_SUMMARY');?>
		<?php echo C('FOOTER_SUMMARY');?>
	</form>
</body>
</html>