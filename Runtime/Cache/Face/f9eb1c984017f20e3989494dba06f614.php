<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Document</title>
</head>
<body>
	<form action="http://192.168.0.129:8080/thinkox4/index.php?s=/forum/index/doEdit" method="post" enctype="multipart/form-data">
	post_id: <input type="text" name="post_id" />
		<br />
		uid: <input type="text" name="uid" />
		<br />
		forum_id:<input type="text" name="forum_id" />
		<br />
		title:<input type="text" name="title" />
		<br />
		content: <input type="text" name="content" />
		<br />
		image: <input type="file" name="image1" />
		<br />
		<input type="file" name="image2" />
		<br />
		<input type="submit" value="提交" />
	</form>
</body>
</html>