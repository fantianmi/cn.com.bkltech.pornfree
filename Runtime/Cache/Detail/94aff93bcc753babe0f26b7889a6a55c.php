<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!--Declare page as mobile friendly --> 
	<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0"/>
	<!-- Declare page as iDevice WebApp friendly -->
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title><?php echo ($data["title"]); ?></title>
	<link href="/Public/Detail/css/style.css?v=1.0.7" rel="stylesheet" type="text/css">
</head>
<body>
	<!-- <h1><?php echo ($data["title"]); ?></h1> -->
	<div class="content_info"><span class="info_ctime"><?php echo (time_format($data["create_time"])); ?>戒客团队</span></div>
	<?php if($data.path): ?><div>
			<img width=100% src="<?php echo ($data["path"]); ?>" alt="">
		</div><?php endif; ?>
	<div class="content">
		<?php echo ($data["content"]); ?>
	</div>
	<!-- <div class="page_select">
		<div class="pre_content">
			<?php if($data['prev']['title']): ?><a href="<?php echo U('?id='.$data['prev']['id']);?>">
					上一篇 : <?php echo ($data['prev']['title']); ?>
				</a>
			<?php else: ?>
				<a href="">
					上一篇 : 没有上一篇了
				</a><?php endif; ?>
		</div>
		<div class="next_content">
			<?php if($data['next']['title']): ?><a href="<?php echo U('?id='.$data['next']['id']);?>">
					下一篇 : <?php echo ($data['next']['title']); ?>
				</a>
			<?php else: ?>
				<a href="">
					下一篇 : 没有下一篇了
				</a><?php endif; ?>
		</div>
	</div> -->
</body>