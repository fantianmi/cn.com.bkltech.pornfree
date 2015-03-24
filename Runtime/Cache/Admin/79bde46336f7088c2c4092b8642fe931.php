<?php if (!defined('THINK_PATH')) exit();?> <div class="container-span top-columns cf">
	<dl class="show-num-mod">
		<dt><i class="count-icon user-count-icon"></i></dt>
		<a target="_blank" href="<?php echo U('User/index');?>" title="正常状态用户数/用户总个数"><dd>
			<strong><?php echo ($info["user"]); ?>/<?php echo ($info["userall"]); ?></strong>
			<span>用户个数</span>
		</dd></a>
	</dl>
	<dl class="show-num-mod">
		<dt><i class="count-icon user-action-icon"></i></dt>
				<a target="_blank" href="<?php echo U('Action/actionlog');?>" title="用户行为"><dd>
			<strong><?php echo ($info["action"]); ?></strong>
			<span>用户行为</span>
		</dd></a>
	</dl>
	<dl class="show-num-mod">
		<dt><i class="count-icon doc-count-icon"></i></dt>
				<a target="_blank" href="<?php echo U('Forum/post');?>" title="正常状态帖子数/帖子总条数"><dd>
			<strong><?php echo ($info["forumtie"]); ?>/<?php echo ($info["forumall"]); ?></strong>
			<span>帖子条数</span>
		</dd></a>
	</dl>
	<dl class="show-num-mod">
		<dt><i class="count-icon doc-modal-icon"></i></dt>
				<a target="_blank" href="<?php echo U('Weibo/weibo');?>" title="正常状态微博数/微博总条数"><dd>
			<strong><?php echo ($info["weibo"]); ?>/<?php echo ($info["weiboall"]); ?></strong>
			<span>微博条数</span>
		</dd></a>
	</dl>
	<dl class="show-num-mod">
		<dt><i class="count-icon category-count-icon"></i></dt>
				<a target="_blank" href="<?php echo U('Category/index');?>" title="未读消息数/消息总数"><dd>
			<strong><?php echo ($info["message"]); ?>/<?php echo ($info["messageall"]); ?></strong>
			<span>站内消息</span>
		</dd></a>
	</dl>
</div>