<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" type="text/css" href="/Addons/ImageSlider/FlexSlider/flexslider.css" />
<style type="text/css">
    .flex-direction-nav li{line-height: 40px;}
    .flexslider .slides > li {
        overflow: hidden;
        height:<?php echo ($config["imgHeight"]); ?>px;
    }
    .flex-control-nav{bottom: 10px;}
</style>
<script type="text/javascript" src="/Addons/ImageSlider/FlexSlider/jquery.flexslider-min.js"></script>
<script type="text/javascript">
$(function() {
  $('.flexslider').flexslider({
    animation: "slide",
        prevText:'',
        nextText: "",
        thumbCaptions　: true,
        slideshowSpeed : <?php echo ((isset($config["second"]) && ($config["second"] !== ""))?($config["second"]):'3000'); ?>,
        itemHeight:200,
        direction: "<?php echo ((isset($config["direction"]) && ($config["direction"] !== ""))?($config["direction"]):'horizontal'); ?>"
  });
});  
</script>
<!-- Place somewhere in the <body> of your page -->
<div class="flexslider" style="width:<?php echo ($config["imgWidth"]); ?>px; height:<?php echo ($config["imgHeight"]); ?>px;">
  <ul class="slides">
      <?php if(is_array($images)): foreach($images as $k=>$images): ?><li>
        <a href="<?php echo ($urls[$k]); ?>" target="_blank"><img src="<?php echo ($images["path"]); ?>" /></a>
    </li><?php endforeach; endif; ?>
    <!-- items mirrored twice, total of 12 -->
  </ul>
</div>