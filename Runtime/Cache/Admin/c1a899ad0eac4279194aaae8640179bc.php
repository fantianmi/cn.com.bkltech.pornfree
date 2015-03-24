<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>管理平台</title>
    <link href="/thinkox4/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/thinkox4/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/thinkox4/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/thinkox4/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/thinkox4/Public/Admin/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/thinkox4/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/thinkox4/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/thinkox4/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/thinkox4/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
	<style>
		.dragsort {
		    width:400px;
		}
	</style>

</head>
<body>
<!-- 头部 -->
<div class="header">
    <!-- Logo -->
    <a href="<?php echo U('Home/Index/index');?>" title="回到前台" target="_blank"><span class="logo"></span></a>
    <!-- /Logo -->

    <!-- 主导航 -->
    <ul class="main-nav">
        <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
    <!-- /主导航 -->

    <!-- 用户栏 -->
    <div class="user-bar">
        <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
        <ul class="nav-list user-menu hidden">
            <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em>
            </li>
            <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
            <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
            <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
        </ul>
    </div>
</div>
<!-- /头部 -->

<!-- 边栏 -->
<div class="sidebar">
    <!-- 子导航 -->
    
        <div id="subnav" class="subnav">
            <?php if(!empty($_extra_menu)): ?>
                <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
            <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                    <ul class="side-sub-menu">
                        <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
                <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    
    <!-- /子导航 -->
</div>
<!-- /边栏 -->

<!-- 内容区 -->
<div id="main-content">
    <div id="top-alert" class="fixed alert alert-error" style="display: none;">
        <button class="close fixed" style="margin-top: 4px;">&times;</button>
        <div class="alert-content">这是内容</div>
    </div>
    <div id="main" class="main">
        
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                    <span>您的位置:</span>
                    <?php $i = '1'; ?>
                    <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                            <?php else: ?>
                            <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                        <?php $i = $i+1; endforeach; endif; ?>
                </div><?php endif; ?>
            <!-- nav -->
        

        
	<!-- 标题栏 -->
	<div class="main-title cf">
		<h2><?php if(isset($data)): ?>编辑<?php else: ?>新增<?php endif; ?>钩子</h2>
	</div>

	<!-- 修改密码表单 -->
	<form action="<?php echo U('updateHook');?>" method="post" class="form-horizontal">
		<div class="form-item cf">
			<label class="item-label">钩子名称<span class="check-tips">（需要在程序中先添加钩子，否则无效）</span></label>
			<div class="controls">
				<input type="text" value="<?php echo ($data["name"]); ?>" name="name" class="text input-large">
			</div>
		</div>
		<div class="form-item cf">
			<label class="item-label">钩子描述<span class="check-tips">（钩子的描述信息）</span></label>
			<div class="controls">
				<label class="textarea input-large"><textarea name="description" ><?php echo ($data["description"]); ?></textarea></label>
			</div>
		</div>
		<div class="form-item cf">
			<label class="item-label">钩子类型<span class="check-tips">（区分钩子的主要用途）</span></label>
			<div class="controls">
				<select name="type">
					<?php $_result=C('HOOKS_TYPE');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if(($data["type"]) == $key): ?>selected<?php endif; ?>><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>
		<?php if(isset($data)): ?><div class="form-item cf">
				<label class="item-label">钩子挂载的插件排序<span class="check-tips">（拖动后保存顺序，影响同一个钩子挂载的插件执行先后顺序）</span></label>
				<div class="controls">
					<input type="hidden" name="addons" value="<?php echo ($data["addons"]); ?>" readonly>
					<?php if(empty($data["addons"])): ?>暂无插件，无法排序
					<?php else: ?>
					<ul id="sortUl" class="dragsort">
						<?php $_result=explode(',',$data['addons']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$addons_vo): $mod = ($i % 2 );++$i;?><li class="getSort"><b>&times;</b><em><?php echo ($addons_vo); ?></em></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
					<script type="text/javascript">
						$(function(){
							$("#sortUl").dragsort({
	                            dragSelector:'li',
	                            placeHolderTemplate: '<li class="draging-place">&nbsp;</li>',
	                            dragEnd:function(){
	                            	updateVal();
	                            }
	                        });

							$('#sortUl li b').click(function(){
                            	$(this).parent().remove();
                            	updateVal();
                            });

							// 更新排序后的隐藏域的值
	                        function updateVal() {
	                        	var sortVal = [];
                            	$('#sortUl li').each(function(){
                            		sortVal.push($('em',this).text());
                            	});
                                $("input[name='addons']").val(sortVal.join(','));
	                        }
						})
					</script><?php endif; ?>
				</div>
			</div><?php endif; ?>
		<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>">
		<button type="submit" class="btn submit-btn ajax-post" target-form="form-horizontal">确 定</button>
		<button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
	</form>

    </div>
    <div class="cont-ft">
        <div class="copyright">
            <div class="fl"><a href="">重庆黑光科技公司</a></div>
            <div class="fr"></div>
        </div>
    </div>
</div>
<!-- /内容区 -->
<script type="text/javascript">
    (function () {
        var ThinkPHP = window.Think = {
            "ROOT": "/thinkox4", //当前网站地址
            "APP": "/thinkox4/index.php?s=", //当前项目地址
            "PUBLIC": "/thinkox4/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
</script>
<script type="text/javascript" src="/thinkox4/Public/static/think.js"></script>
<script type="text/javascript" src="/thinkox4/Public/Admin/js/common.js"></script>
<script type="text/javascript">
    +function () {
        var $window = $(window), $subnav = $("#subnav"), url;
        $window.resize(function () {
            $("#main").css("min-height", $window.height() - 130);
        }).resize();

        /* 左边菜单高亮 */
        url = window.location.pathname + window.location.search;
        url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
        $subnav.find("a[href='" + url + "']").parent().addClass("current");

        /* 左边菜单显示收起 */
        $("#subnav").on("click", "h3", function () {
            var $this = $(this);
            $this.find(".icon").toggleClass("icon-fold");
            $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                    prev("h3").find("i").addClass("icon-fold").end().end().hide();
        });

        $("#subnav h3 a").click(function (e) {
            e.stopPropagation()
        });

        /* 头部管理员菜单 */
        $(".user-bar").mouseenter(function () {
            var userMenu = $(this).children(".user-menu ");
            userMenu.removeClass("hidden");
            clearTimeout(userMenu.data("timeout"));
        }).mouseleave(function () {
                    var userMenu = $(this).children(".user-menu");
                    userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                    userMenu.data("timeout", setTimeout(function () {
                        userMenu.addClass("hidden")
                    }, 100));
                });

        /* 表单获取焦点变色 */
        $("form").on("focus", "input",function () {
            $(this).addClass('focus');
        }).on("blur", "input", function () {
                    $(this).removeClass('focus');
                });
        $("form").on("focus", "textarea",function () {
            $(this).closest('label').addClass('focus');
        }).on("blur", "textarea", function () {
                    $(this).closest('label').removeClass('focus');
                });

        // 导航栏超出窗口高度后的模拟滚动条
        var sHeight = $(".sidebar").height();
        var subHeight = $(".subnav").height();
        var diff = subHeight - sHeight; //250
        var sub = $(".subnav");
        if (diff > 0) {
            $(window).mousewheel(function (event, delta) {
                if (delta > 0) {
                    if (parseInt(sub.css('marginTop')) > -10) {
                        sub.css('marginTop', '0px');
                    } else {
                        sub.css('marginTop', '+=' + 10);
                    }
                } else {
                    if (parseInt(sub.css('marginTop')) < '-' + (diff - 10)) {
                        sub.css('marginTop', '-' + (diff - 10));
                    } else {
                        sub.css('marginTop', '-=' + 10);
                    }
                }
            });
        }
    }();
</script>

	<?php if(isset($data)): ?><script type="text/javascript" src="/thinkox4/Public/static/jquery.dragsort-0.5.1.min.js"></script><?php endif; ?>
	<script type="text/javascript">
		$(function(){
			//导航高亮
			highlight_subnav('<?php echo U('Addons/hooks');?>');
		})
	</script>

</body>
</html>