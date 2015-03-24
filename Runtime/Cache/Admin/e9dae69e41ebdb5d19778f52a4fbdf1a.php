<?php if (!defined('THINK_PATH')) exit();?><div class="tools" style="margin-bottom:10px;">
    <a class="btn" href="<?php echo addons_url('SuperLinks://SuperLinks/add');?>">新 增</a>
    <button class="btn ajax-post" target-form="ids" url="<?php echo addons_url('SuperLinks://SuperLinks/savestatus',array('status'=>1));?>">启 用</button>
    <button class="btn ajax-post" target-form="ids" url="<?php echo addons_url('SuperLinks://SuperLinks/savestatus',array('status'=>0));?>">禁用</button>
</div>
<table>
	<thead>
		<tr>
			<th class="row-selected row-selected"><input class="check-all" type="checkbox"></th>
			<th>序号</th>
			<?php if(is_array($listKey)): $i = 0; $__LIST__ = $listKey;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><th><?php echo ($vo); ?></th><?php endforeach; endif; else: echo "" ;endif; ?>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php if(is_array($_list)): $vo = 0; $__LIST__ = $_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lv): $mod = ($vo % 2 );++$vo;?><tr>
			<td><input class="ids" type="checkbox" name="id[]" value="<?php echo ($lv["id"]); ?>"></td>
			<td><?php echo ($lv["id"]); ?></td>
			<?php if(is_array($listKey)): $i = 0; $__LIST__ = $listKey;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lk): $mod = ($i % 2 );++$i;?><td><?php echo ($lv["$key"]); ?></td><?php endforeach; endif; else: echo "" ;endif; ?>
			<td>
				<a href="<?php echo addons_url('SuperLinks://SuperLinks/edit',array('id'=>$lv['id']));?>">编辑</a>
				<?php if($lv["status"] == 1): ?><a class="confirm ajax-get" href="<?php echo addons_url('SuperLinks://SuperLinks/forbidden',array('id'=>$lv['id']));?>">禁用</a>
				<?php else: ?>
				<a class="confirm ajax-get" href="<?php echo addons_url('SuperLinks://SuperLinks/off',array('id'=>$lv['id']));?>">启用</a><?php endif; ?>
				<a class="confirm ajax-get" href="<?php echo addons_url('SuperLinks://SuperLinks/del',array('id'=>$lv['id']));?>">删除</a>
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
</table>

<script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>