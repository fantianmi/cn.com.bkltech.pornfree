<?php if (!defined('THINK_PATH')) exit(); if($list['type'] == 2): if(count($list['res']) > 0): switch($list["style"]): case "1": ?><script src="/Public/Core/js/ext/slider/js/jquery.slides.min.js"></script>
                <link href="/Public/Core/js/ext/slider/css/slider.css" rel="stylesheet" type="text/css"/>
                <div style="height: <?php echo ($list["ad"]["height"]); ?>;width:<?php echo ($list["ad"]["width"]); ?>px;">

                <div id="slide_<?php echo ($list["ad"]["pos"]); ?>" style="">
                    <?php if(is_array($list["res"])): $i = 0; $__LIST__ = $list["res"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["link"]); ?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><img src="<?php echo (getthumbimagebyid($vo["advspic"],$list['ad']['width'],$list['ad']['height'])); ?>" alt="<?php echo ($vo["title"]); ?>"></a><?php endforeach; endif; else: echo "" ;endif; ?>
                    <a href="#" class="slidesjs-previous slidesjs-navigation"><i class="glyphicon glyphicon-chevron-left"></i></a>
                    <a href="#" class="slidesjs-next slidesjs-navigation"><i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>

                </div>
                <script>
                    $(function () {
                        $("#slide_<?php echo ($list["ad"]["pos"]); ?>").slidesjs({
                            navigation: false
                        });
                    })
                </script><?php break;?>
            <?php case "2": ?><script src="/Public/Core/js/ext/kinmaxshow/js/kinmaxshow.min.js"></script>
                <div  style="width:<?php echo ($list["ad"]["width"]); ?>px;height:<?php echo ($list["ad"]["height"]); ?>px">
                    <div id="slide_<?php echo ($list["ad"]["pos"]); ?>" >
                        <?php if(is_array($list["res"])): $i = 0; $__LIST__ = $list["res"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div>
                                <a href="<?php echo ($vo["link"]); ?>" target="_blank" title="<?php echo ($vo["title"]); ?>"><img src="<?php echo (getthumbimagebyid($vo["advspic"],$list['ad']['width'],$list['ad']['height'])); ?>" alt="<?php echo ($vo["title"]); ?>"></a>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function(){
                        var h_s=<?php echo ($list["ad"]["height"]); ?>;
                    $("#slide_<?php echo ($list["ad"]["pos"]); ?>").kinMaxShow({height:h_s});
                    });
                </script><?php break; endswitch; endif; ?>





    <?php elseif($list['type'] == 3): ?>
    <div style="width:<?php echo ($list['width']); ?>;height:<?php echo ($list['height']); ?>;background: #cecece;">
        <div style="margin:0px auto;width:150px;line-height: 30px;"><?php echo ($list["advstext"]); ?></div>
    </div>
    <?php elseif($list['type'] == 1): ?>
    <?php if($list['advspic'] != 0): ?><a href="<?php echo ($list["link"]); ?>"><img src="<?php echo (get_cover($list["advspic"],'path')); ?>"/></a><?php endif; ?>

    <?php elseif($list['type'] == 4): ?>
    <?php echo ($list["advshtml"]); endif; ?>
<!-- add more -->