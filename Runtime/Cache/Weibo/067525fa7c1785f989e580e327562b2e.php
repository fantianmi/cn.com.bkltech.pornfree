<?php if (!defined('THINK_PATH')) exit();?><div class="row" id="weibo_<?php echo ($weibo["id"]); ?>">
    <div class="col-xs-12">

        <div class="col-md-2 col-sm-2 col-xs-12 text-center" style="position: relative">
            <a class="s_avatar" href="<?php echo ($weibo["user"]["space_url"]); ?>" ucard="<?php echo ($weibo["user"]["uid"]); ?>">
                <img src="<?php echo ($weibo["user"]["avatar64"]); ?>"
                     class="avatar-img"
                     style="width: 64px;"/>
            </a>
        </div>

        <div class="col-md-10 col-sm-8 col-xs-12">
            <div class="weibo_content" id="weibo_content1">
                <div class="weibo_content_sj pull-left hidden-xs"></div>

                <?php if(($weibo["is_top"]) == "1"): ?><div class="ribbion-green">

                    </div><?php endif; ?>

                <p>
                    <?php if(modC('SHOW_TITLE',1)): ?><small class="font_grey">【<?php echo ($weibo["user"]["title"]); ?>】</small><?php endif; ?><a ucard="<?php echo ($weibo["user"]["uid"]); ?>"
                                                                                                                        href="<?php echo ($weibo["user"]["space_url"]); ?>" class="user_name">  <?php echo (htmlspecialchars($weibo["user"]["nickname"])); ?>
                </a>
                    <?php echo ($weibo["user"]["icons_html"]); ?>
                    <?php if(is_array($weibo['user']['rank_link'])): $i = 0; $__LIST__ = $weibo['user']['rank_link'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i; if($vl['is_show']): ?><img src="<?php echo ($vl["logo_url"]); ?>" title="<?php echo ($vl["title"]); ?>" alt="<?php echo ($vl["title"]); ?>"
                                 class="rank_html"/><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                    <?php if(is_administrator(is_login()) || $weibo['user']['uid'] == is_login() ){ ?>

                    <span class="pull-right" style="margin-right: 20px;">

                      <span class="weibo_admin_btn" style="display: none">
                          <img src="/Public/Core/images/mark-aw1.png"/>
                      </span>

                        <div class="mark_box" style="display: none">
                            <ul class="nav text-center mark_aw">
                                <!--  <li><a>收藏</a></li>-->

                                <?php if(is_administrator()): ?><li class="weibo-set-post cpointer" data-weibo-id="<?php echo ($weibo["id"]); ?>"><a>
                                        <?php if(($weibo["is_top"]) == "1"): ?>取消置顶<?php else: ?>设为置顶<?php endif; ?>
                                    </a>
                                    </li><?php endif; ?>
                                <?php if($weibo['can_delete']): ?><li class="weibo-comment-del cpointer" data-weibo-id="<?php echo ($weibo["id"]); ?>"><a>删除微博</a>
                                    </li><?php endif; ?>
                            </ul>
                        </div>
                        </span>

                    <?php } ?>

                </p>
                <div class="weibo_content_p">
                    <?php echo ($weibo["fetchContent"]); ?>
                </div>

                <div class="row weibo-comment-list" style="display: none;" data-weibo-id="<?php echo ($weibo["id"]); ?>">
                    <div class="col-xs-12">
                        <div class="light-jumbotron" style="padding: 1em 2em;">
                            <div class="weibo-comment-container">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="weibo_content_bottom row">
                    <!--"<?php echo U('bboard/Index/tmldetail',array('topic_id'=>$vo['topic_id']));?>"-->

                    <div class="col-md-6"> <span ><a
                            href="<?php echo U('Weibo/Index/weiboDetail',array('id'=>$weibo['id']));?>"><?php echo (friendlydate($weibo["create_time"])); ?></a> </span>
                        &nbsp;&nbsp;<span>来自
                               <?php if($weibo['from'] == ''): ?>网站端
                                   <?php else: ?>
                                   <strong><?php echo ($weibo["from"]); ?></strong><?php endif; ?>
                            </span>

                    </div>
                    <div class="col-md-6">
                                  <span class="pull-right" data-weibo-id="<?php echo ($weibo["id"]); ?>">
                        <?php $weiboCommentTotalCount = $weibo['comment_count']; ?>
                        <?php echo Hook('support',array('table'=>'weibo','row'=>$weibo['id'],'app'=>'Weibo','uid'=>$weibo['uid']));?>
<?php echo Hook('repost',array('weiboId'=>$weibo['id']));?>
&nbsp;&nbsp;&nbsp;
<span class="weibo-comment-link cpointer" data-weibo-id="<?php echo ($weibo["id"]); ?>">
    评论 <?php echo ($weiboCommentTotalCount); ?>
</span>   </span>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>