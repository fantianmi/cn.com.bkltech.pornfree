<?php if (!defined('THINK_PATH')) exit(); if($lists != false): ?><div class="common_block_border blog_position">

        <div class="common_block_title">
            <?php if(($category) != ""): ?>本类推荐
                <?php else: ?>
                推荐阅读<?php endif; ?>
        </div>

        <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><div class="clearfix" style="position: relative">
                <?php if(($data["cover_id"]) != "0"): ?><div class="col-md-4">
                    <a title="<?php echo (op_t($data["title"])); ?>"
                       href="<?php echo U('Article/detail?id='.$data['id']);?>"><img alt="<?php echo (op_t($data["title"])); ?>"
                                                                          src="<?php echo (getthumbimagebyid($data["cover_id"],100,70)); ?>"
                                                                          style="width: 100px;height: 70px"></a>
                </div>
                    <?php $col=8; ?>
                    <?php else: ?>
                    <?php $col=12; endif; ?>
                <div class="col-md-<?php echo ($col); ?>">
                    <div>
                        <h3 class="text-more" style="width: 100%"><a title="<?php echo (op_t($data["title"])); ?>"
                                                                     href="<?php echo U('Article/detail?id='.$data['id']);?>"><?php echo ($data["title"]); ?></a>
                        </h3>
                    </div>
                    <div>
                        <?php $user=query_user(array('avatar32','space_url'),$data['uid']) ?>
                        <span class="author"><a href="<?php echo ($user["space_url"]); ?>"
                                                ucard="<?php echo ($data["uid"]); ?>"><?php echo (get_nickname($data["uid"])); ?></a>&nbsp;&nbsp;<?php echo (date('Y-m-d H:i:s',$data["create_time"])); ?></span>

                    </div>
                    <div>

                    </div>
                </div>
             <span class="pull-right" style="position: absolute;right: 0;bottom: 0">
                                       &nbsp;&nbsp;
                                        <span><i class="glyphicon glyphicon-fire"></i>  <?php echo ($data["view"]); ?> </span>&nbsp;&nbsp;
                                </span>
            </div>
            <?php if($i == count($lists)): ?><div style="margin: 15px"></div>
                <?php else: ?>
                <div style="border-bottom: 1px dashed rgb(204, 204, 204);margin: 15px"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </div><?php endif; ?>