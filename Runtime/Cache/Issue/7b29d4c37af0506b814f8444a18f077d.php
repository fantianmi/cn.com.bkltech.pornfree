<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit">

<?php echo hook('syncMeta');?>

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
    <?php else: ?>
    <title><?php echo C('WEB_SITE_TITLE');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>"/><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>"/><?php endif; ?>

<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link href="/Public/static/bootstrap/css/bootstrap.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="/Public/static/qtip/jquery.qtip.css"/>
<link type="text/css" rel="stylesheet" href="/Public/Core/js/ext/toastr/toastr.min.css"/>
<link href="/Public/Core/css/oneplus.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="/Public/Core/js/ext/magnific/magnific-popup.css"/>

    <link href="/Public/Issue/css/issue.css" rel="stylesheet" type="text/css"/>


<!-- 增强IE兼容性 -->
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/Public/static/bootstrap/js/html5shiv.js"></script>
<script src="/Public/static/bootstrap/js/respond.js"></script>
<![endif]-->

<!-- jQuery库 -->
<!--[if lt IE 9]>
<script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
<!--<![endif]-->

<!--合并前的js-->
<!-- Bootstrap库 -->
<script type="text/javascript" src="/Public/static/bootstrap/js/bootstrap.min.js"></script>

<!-- 其他库-->
<script src="/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/Public/static/jquery.iframe-transport.js"></script>
<!--CNZZ广告管家，可自行更改-->
<!--<script type='text/javascript' src='http://js.adm.cnzz.net/js/abase.js'></script>-->
<!--CNZZ广告管家，可自行更改end
 自定义js-->
<script type="text/javascript" src="/Public/Core/js/core.js"></script>
<!--合并前的js-->
<?php $config = api('Config/lists'); C($config); $icp=C('WEB_SITE_ICP'); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
    var ThinkPHP = window.Think = {
        "ROOT": "", //当前网站地址
        "APP": "/index.php?s=", //当前项目地址
        "PUBLIC": "/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }
</script>

<!-- Bootstrap库 -->
<!--
<?php $js[]=urlencode('/static/bootstrap/js/bootstrap.min.js'); ?>

&lt;!&ndash; 其他库 &ndash;&gt;
<script src="/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/Public/static/jquery.iframe-transport.js"></script>
-->
<!--CNZZ广告管家，可自行更改-->
<!--<script type='text/javascript' src='http://js.adm.cnzz.net/js/abase.js'></script>-->
<!--CNZZ广告管家，可自行更改end-->
<!-- 自定义js -->
<!--<script src="/Public/js.php?get=<?php echo implode(',',$js);?>"></script>-->


<script>
    //全局内容的定义
    var _ROOT_ = "";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var initNum = "<?php echo C('WEIBO_WORDS_COUNT');?>";
</script>

<audio id="music" src="" autoplay="autoplay"></audio>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>
</head>
<body>
	<!-- 头部 -->
	<?php if((is_login()) ): ?><div id="right_panel" class="friend_panel visible-md visible-lg" style="display: none;">
        <a class="btn-pull" onclick="show_panel()"> <img style="width: 30px" src="/Public/Core/images/friend.png"/> </i>
            <script>
                function show_panel() {
                    var $right_panel = $('#right_panel_main');
                    if ($right_panel.text()) {
                        $right_panel.load(U('Usercenter/Session/panel'));
                        $right_panel.toggle();
                    } else {
                        $right_panel.toggle();
                    }

                }
            </script>

            <i id="friend_has_new"
            <?php $map_mid=is_login(); $modelTP=D('talk_push'); $has_talk_push=$modelTP->where("(uid = ".$map_mid." and status = 1) or (uid = ".$map_mid." and status = 0)")->count(); $has_message_push=D('talk_message_push')->where("uid= ".$map_mid." and (status=1 or status=0)")->count(); if($has_talk_push || $has_message_push){ ?>
            style="display: inline-block"
            <?php } ?>
            ></i>

        </a>
        <?php if(count($currentSession) == 0): ?><div id="right_panel_main" style="display: none;">
                <div style="color: white;line-height: 500px;font-size: 16px;padding:10px;">
                    <img src="/Public/Core/images/loading.gif"/>
                </div>
            </div>
            <?php else: ?>
            <div id="right_panel_main" style="display: none;" >
                <div style="color: white;line-height: 500px;font-size: 16px;padding:10px;">
                    <img src="/Public/Core/images/loading.gif"/>
                </div>
            </div><?php endif; ?>


    </div>
    <!--开始聊天板-->
    <div id="chat_box" style="display: none" class="chat_panel">
        <div class="panel_title"><img id="chat_ico" class="chat_avatar avatar-img" src="<?php echo ($friend["avatar64"]); ?>">

            <div id="chat_title" class="title pull-left text-more"></div>
            <div class="control_btns pull-right"><a><i onclick="$('#chat_box').hide();"
                                                       class="glyphicon glyphicon-minus"></i></a><!-- <a
                ><i class="glyphicon glyphicon-off"></i></a>--></div>
        </div>
        <div class="row talk-body ">
            <div id="scrollArea_chat" class="row ">
                <div id="scrollContainer_chat">
                </div>
            </div>

        </div>

        <div class="send_box">
            <input id="chat_id" type="hidden" value="0">
            <?php $talk_self=query_user(array('avatar128')); ?>
            <script>
                var myhead = "<?php echo ($talk_self["avatar128"]); ?>";
            </script>
            <textarea id="chat_content" class="form-control"></textarea>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class=" btn btn-danger" onclick="talker.exit()"
                        style="margin: 10px 10px" title="退出聊天"><i class="glyphicon glyphicon-off"></i>
                </button>
                <!--  <button class=" btn btn-success" onclick="chat_exit()"
                          style="margin: 10px 10px" title="邀请好友"><i class="glyphicon glyphicon-plus"></i>
                  </button>-->

            </div>
            <div class="col-md-6">
                <button class="pull-right btn btn-primary" onclick="talker.post_message()"
                        style="margin: 10px 10px"> 发送 Ctrl+Enter
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div id="right_panel" class="friend_panel visible-md visible-lg" style="display: none;">
        <a class="btn-pull" onclick="toast.error('请登陆后使用好友面板。','温馨提示')"> <img style="width: 30px" src="/Public/Core/images/friend.png"/> </i>
        </a>
    </div><?php endif; ?>

<?php D('Home/Member')->need_login(); ?>
<!--[if lt IE 8]>
<div class="alert alert-danger" style="margin-bottom: 0">您正在使用 <strong>过时的</strong> 浏览器. 是时候 <a target="_blank" href="http://browsehappy.com/">更换一个更好的浏览器</a> 来提升用户体验.</div>
<![endif]-->
<div id="top_bar" class="top_bar">
    <div class="container">
        <div class="row  ">
            <?php if(is_login()): else: ?>
                <div class="col-xs-6 text-center visible-xs">
                    <a href="<?php echo U('Home/User/login');?>" style="padding-top: 10px;display: block;font-size: 16px;color: #ccc !important;">登录</a>
                </div>
                <div class="col-xs-6 text-center visible-xs">
                    <a href="<?php echo U('Home/User/register');?>" style="padding-top: 10px;display: block;font-size: 16px;color: #ccc!important;">注册</a>
                </div><?php endif; ?>
            <div class="col-md-6 col-sm-6 hidden-xs">
               <?php if(C('SHARE_WEIBO_ID') != ''): ?>分享<a class="share_weibo" id="weibo_shareBtn" target="_blank"></a>
                   <script>
                       $(function () {
                           weiboShare();//处理微博分享
                       })
                   </script><?php endif; ?>
            </div>
            <div class="col-md-6 col-xs-12  text-right top_right">
                <?php $unreadMessage=D('Common/Message')->getHaventReadMeassageAndToasted(is_login()); ?>

                <ul class="nav navbar-nav navbar-right">
                    <!-- <li>
                         &lt;!&ndash;换肤功能预留&ndash;&gt;
                        <a>换肤</a>
                        &lt;!&ndash;换肤功能预留end&ndash;&gt;
                    </li>-->
                    <!--登陆面板-->
                    <?php if(is_login()): ?><li class="dropdown op_nav_ico hidden-xs hidden-sm">
                            <div></div>
                            <a id="nav_info" class="dropdown-toggle text-left" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-bell"></span>
                                <span id="nav_bandage_count"
                                <?php if(count($unreadMessage) == 0): ?>style="display: none"<?php endif; ?>
                                class="badge pull-right"><?php echo count($unreadMessage);?></span>
                                &nbsp;
                            </a>
                            <ul class="dropdown-menu extended notification">
                                <li style="padding-left: 15px;padding-right: 15px;">
                                    <div class="row nav_info_center">
                                        <div class="col-xs-9 nav_align_left"><span
                                                id="nav_hint_count"><?php echo count($unreadMessage);?></span> 条未读
                                        </div>
                                        <div class="col-xs-3"><i onclick="setAllReaded()"
                                                                 class="set_read glyphicon glyphicon-ok"
                                                                 title="全部标为已读"></i></div>
                                    </div>
                                </li>
                                <li>
                                    <div style="position: relative;width: auto;overflow: hidden;max-height: 250px ">
                                        <ul id="nav_message" class="dropdown-menu-list scroller "
                                            style=" width: auto;">
                                            <?php if(count($unreadMessage) == 0): ?><div style="font-size: 18px;color: #ccc;font-weight: normal;text-align: center;line-height: 150px">
                                                    暂无任何消息!
                                                </div>
                                                <?php else: ?>
                                                <?php if(is_array($unreadMessage)): $i = 0; $__LIST__ = $unreadMessage;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$message): $mod = ($i % 2 );++$i;?><li>
                                                        <a data-url="<?php echo ($message["url"]); ?>"
                                                           onclick="readMessage(this,<?php echo ($message["id"]); ?>)">
                                                            <i class="glyphicon glyphicon-bell"></i>
                                                            <?php echo ($message["title"]); ?>
                                            <span class="time">
                                            <?php echo ($message["ctime"]); ?>
                                            </span>
                                                        </a>
                                                    </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>

                                        </ul>
                                    </div>
                                </li>
                                <li class="external">
                                    <a href="<?php echo U('Usercenter/Message/message');?>">
                                        消息中心 <i class="glyphicon glyphicon-circle-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a style="margin-right: 15px;" title="修改资料" href="<?php echo U('Usercenter/Config/index');?>"><i
                                    class="glyphicon glyphicon-cog"></i></a>
                        </li>
                        <li class="top_spliter hidden-xs"></li>
                        <li class="dropdown">
                            <?php $common_header_user = query_user(array('nickname')); ?>
                            <a role="button" class="dropdown-toggle dropdown-toggle-avatar" data-toggle="dropdown">
                                <?php echo ($common_header_user["nickname"]); ?>&nbsp;<i style="font-size: 12px"
                                                                       class="glyphicon glyphicon-chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu text-left" role="menu">
                                <li><a href="<?php echo U('UserCenter/Index/index');?>"><span
                                        class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;个人主页</a>
                                </li>
                                <li><a href="<?php echo U('Usercenter/Message/collection');?>"><span
                                        class="glyphicon glyphicon-star"></span>&nbsp;&nbsp;我的收藏</a>
                                </li>
                                <?php if(is_administrator()): ?><li><a href="<?php echo U('Admin/Index/index');?>" target="_blank"><span
                                            class="glyphicon glyphicon-dashboard"></span>&nbsp;&nbsp;管理后台</a></li><?php endif; ?>
                                <li><a event-node="logout"><span
                                        class="glyphicon glyphicon-off"></span>&nbsp;&nbsp;注销</a>
                                </li>
                            </ul>
                        </li>
                        <li class="top_spliter hidden-xs"></li>
                        <?php else: ?>
                        <li class="top_spliter hidden-xs"></li>
                        <li class="hidden-xs">
                            <a href="<?php echo U('Home/User/login');?>">登录</a>
                        </li>
                        <li class="hidden-xs">
                            <a href="<?php echo U('Home/User/register');?>">注册</a>
                        </li>
                        <li class="spliter hidden-xs"></li><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="logo_bar" class="logo_bar" style="background: #03AE87">
    <div class="container">
        <div class="row logo">
            <div class="col-md-9">
                <a href="<?php echo U('Home/Index/index');?>"><img src="/Public/Core/images/logo.png"/></a>
            </div>
            <div class="col-md-3 hidden-xs">
                    <div class="pull-right text-right" style="padding-top:4px;">
                        <form class="navbar-form navbar-right search_bar" role="search" id="forum_search" method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="keywords" placeholder="查找">

                                    <div class="input-group-btn text-left">
                                        <button type="button" class="btn btn-default dropdown-toggle"
                                                style="border-left: none;border-top-left-radius: 0;border-bottom-left-radius: 0"
                                                data-toggle="dropdown"><span class="glyphicon glyphicon-search"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right" role="menu">
                                            <li><a class="submit_search weibo_search" url="<?php echo U('Weibo/Index/search');?>">微博</a></li>
                                            <li><a class="submit_search" url="<?php echo U('Forum/Index/search');?>">论坛</a></li>
                                            <!-- <li><a class="submit_search">活动</a></li>-->
                                            <li><a class="submit_search" url="<?php echo U('People/Index/find');?>">会员</a></li>
                                        </ul>
                                    </div>
                                    <script>
                                        $(function () {
                                            $('#forum_search').attr('action', $('.weibo_search').attr('url'));
                                            $('.submit_search').click(function () {
                                                $('#forum_search').attr('action', $(this).attr('url'));
                                                $('#forum_search').submit();
                                            });
                                        })
                                    </script>
                                </div>
                            </div>
                        </form>
                    </div>
            </div>

        </div>
    </div>
</div>
<div id="nav_bar" class="nav_bar " style="margin-bottom: 25px;">
    <nav class="container" id="nav_bar_container" role="navigation">
        <div class="collapse navbar-collapse " id="nav_bar_main">

            <ul class="nav navbar-nav  " style="font-size: 16px">
                <?php $__NAV__ = M('Channel')->field(true)->where("status=1")->order("sort")->select(); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav["pid"]) == "0"): $children=D('Channel')->where(array('pid'=>$nav['id']))->order('sort asc')->select(); if($children){ ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle nav_item" data-toggle="dropdown" href="#" style="color:<?php echo ($nav["color"]); ?>">

                                <?php echo ($nav["title"]); ?> <span class="caret"></span><?php if(($nav["band_text"]) != ""): ?><span class="badge" style="background: <?php echo ($nav["band_color"]); ?>"><?php echo ($nav["band_text"]); ?></span><?php endif; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if(is_array($children)): $i = 0; $__LIST__ = $children;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$subnav): $mod = ($i % 2 );++$i;?><li role="presentation"><a role="menuitem" tabindex="-1" style="color:<?php echo ($subnav["color"]); ?>"
                                                               href="<?php echo (get_nav_url($subnav["url"])); ?>"
                                                               target="<?php if(($subnav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>"><?php echo ($subnav["title"]); if(($subnav["band_text"]) != ""): ?><span class="badge" style="background: <?php echo ($subnav["band_color"]); ?>"><?php echo ($subnav["band_text"]); ?></span><?php endif; ?></a>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li>
                        <?php }else{ ?>
                        <li class="<?php if((get_nav_active($nav["url"])) == "1"): ?>active<?php else: endif; ?>">
                            <a href="<?php echo (get_nav_url($nav["url"])); ?>"
                               target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>" style="color:<?php echo ($nav["color"]); ?>"><?php echo ($nav["title"]); if(($nav["band_text"]) != ""): ?><span class="badge" style="background: <?php echo ($nav["band_color"]); ?>"><?php echo ($nav["band_text"]); ?></span><?php endif; ?></a>
                        </li>
                        <?php } endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>

        </div>

        <!--导航栏菜单项-->

        <div class="row visible-xs">
            <div class="navbar-header col-xs-3 pull-right text-left">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav_bar_main">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
        </div>


    </nav>
</div>

<a id="goTopBtn"></a>
	<!-- /头部 -->
	
	<!-- 主体 -->
	
<div id="main-container" class="container">
    <div class="row" >
        
    <div class="col-md-12 app_head">
        <div class="app_title">
            <i class="app_ico_i glyphicon glyphicon-th-large"> </i>专辑
        </div>
        <div>
            <div class="aline"></div>
        </div>
        <div class="sub_nav">
            <ul id="top_nav" class="nav_list clearfix">
                <li>
                    <a href="<?php echo U('index');?>" <?php if(($top_issue) == ""): ?>class="cur"<?php endif; ?>>全部分类</a>
                </li>
                <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; if($top['status'] == 1): ?><li>
                            <a <?php if(($top_issue) == $top['id']): ?>class="cur"<?php endif; ?> href="<?php echo U('index',array('issue_id'=>$top['id']));?>" data="<?php echo ($top["id"]); ?>"><?php echo ($top["title"]); ?></a>
                        </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                <li class="pull-right btn-submit">

                    <?php if(is_login() == 0): ?><button class="btn btn-primary" onclick="toast.error('请登陆后再投稿。','温馨提示')" ><i
                                class="glyphicon glyphicon-send"></i>&nbsp;投稿
                        </button>
                        <?php else: ?><button class="btn btn-primary open-popup-link" href="#frm-post-popup"><i
                            class="glyphicon glyphicon-send"></i>&nbsp;投稿
                    </button><?php endif; ?>
                </li>
            </ul>

        </div>
        <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; if($top['status'] == 1): ?><div <?php if(($top_issue) == $top['id']): ?>style="display:block;"<?php endif; ?>  class="children_nav" id="children_<?php echo ($top["id"]); ?>">
                <ul class="nav_list clearfix">
                    <li><a href="<?php echo U('index',array('issue_id'=>$top['id']));?>" <?php if(($issue_id) == $top['id']): ?>style="font-weight:bold;color:#000"<?php endif; ?> class="cur">全部分类</a></li>
                    <?php if(is_array($top["_"])): $i = 0; $__LIST__ = $top["_"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$children): $mod = ($i % 2 );++$i;?><li><a <?php if($issue_id == $children['id']): ?>style='color:#000;font-weight:bold'<?php endif; ?> href="<?php echo U('index',array('issue_id'=>$children['id']));?>"><?php echo ($children["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                </ul>
            </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>


    </div>


<div class=" issue_list">
    <?php if(is_array($contents)): $i = 0; $__LIST__ = $contents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="item_inner" <?php if($i % 4 == 0): ?>style="margin-right:0"<?php endif; ?> >
            <div class="item_core">
                <div class="item_type"><?php echo ($vo["issue"]["title"]); ?></div>
                <a href="<?php echo U('Issue/Index/issueContentDetail',array('id'=>$vo['id']));?>"> <img class="cover" src="<?php echo (getthumbimagebyid($vo["cover_id"],280,210)); ?>"/></a>

                <div><h3><a href="<?php echo U('Issue/Index/issueContentDetail',array('id'=>$vo['id']));?>" class="text-more" style="width: 100%"><?php echo ($vo["title"]); ?></a></h3></div>
                <div class="spliter"></div>
                <div>
                    <ul class="operation clearfix">
                        <li><i class="glyphicon glyphicon-stats"></i><?php echo ($vo["view_count"]); ?></li>
                        <li><?php echo Hook('support',array('table'=>'issue_content','row'=>$vo['id'],'app'=>'Issue','uid'=>$vo['uid'],'jump'=>'no'));?></li>
                        <li><i class="glyphicon glyphicon-comment"></i><?php echo ($vo["reply_count"]); ?></li>
                    </ul>
                </div>
                <div class="spliter"></div>
                <div><a class="author" href="<?php echo ($vo["user"]["space_url"]); ?>">
                    <img src="<?php echo ($vo["user"]["avatar128"]); ?>"
                         ucard="<?php echo ($vo["uid"]); ?>" class="avatar-img" ><?php echo ($vo["user"]["nickname"]); ?>
                </a>
                    <div class="pull-right ctime"><?php echo (friendlydate($vo["create_time"])); ?></div></div>
            </div>

        </div><?php endforeach; endif; else: echo "" ;endif; ?>

<?php if(count($contents) == 0): ?><div style="font-size:3em;padding:2em 0;color: #ccc;text-align: center">此分类下暂无内容哦。O(∩_∩)O~</div><?php endif; ?>

</div>
    <div>
        <div class="pull-right">

            <?php echo getPagination($totalPageCount,16);?>
        </div>
    </div>


<!-- Modal -->
<div id="frm-post-popup" class="white-popup mfp-hide" style="max-width: 745px">
    <h2>投稿</h2>

    <div class="aline" style="margin-bottom: 10px"></div>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="controls">
                    <input type="file" id="upload_picture_cover">
                    <div class="upload-img-box" style="margin-top: 20px;width: 250px">
                        <div style="font-size:3em;padding:2em 0;color: #ccc;text-align: center">暂无封面</div>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <form class="form-horizontal  ajax-form" role="form"  action="<?php echo U('Issue/Index/doPost');?>" method="post">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">标题</label>

                        <div class="col-sm-10">
                            <input id="title" name="title" type="" class="form-control" value="" placeholder="标题"/>
                        </div>

                        <input type="hidden" name="cover_id" id="cover_id_cover" value="<?php echo ($data['cover']); ?>"/>
                    </div>
                    <div class="form-group">
                        <label for="issue_top" class="col-sm-2 control-label">分类</label>
                        <div class="col-sm-5">
                            <select id="issue_top" name="issue" class="form-control">
                                <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>">
                                        <?php echo ($top["title"]); ?>
                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>

                        <script>
                            $(function(){

                                $('#issue_top').change(function(){
                                    var pid=$(this).val();
                                    $.post("<?php echo U('Issue/Index/selectDropdown');?>",{pid:pid},function(data){
                                        $('#issue_second').html('');
                                        $.each(data,function(index,element){

                                                    $('#issue_second').append('<option value="'+element.id+'">'+element.title+'</option>')
                                                }
                                        )
                                    },'json');
                                });
                                $('#issue_top').change();
                            })

                        </script>
                        <div class="col-sm-5">
                            <select id="issue_second" name="issue_id" class="form-control">
                                <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><option value="<?php echo ($top["id"]); ?>">
                                        <?php echo ($top["title"]); ?>
                                    </option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="url" class="col-sm-2 control-label">网址</label>

                        <div class="col-sm-10">

                            <input id="url" name="url" type="text" class="form-control" value="http://" placeholder="输入以http://开头的网址"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content" class="col-sm-2 control-label">介绍</label>

                        <div class="col-sm-10">

                            <?php $config='toolbar:[\'bold italic underline fontsize forecolor justifyleft |  map image emotion\']'; ?>
                            <?php echo W('UeditorMini/editor',array('myeditor','content',$post['content'],'378px','250px',$config));?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary " href="<?php echo U('Issue/Index/doPost');?>">提交</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

</div>
<!-- /.modal -->

    <script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script>
        $(function () {
            $('#top_nav >li >a ').mouseenter(function () {
                $('.children_nav').hide();
                $('#children_' + $(this).attr('data')).show();
            });
            $('.open-popup-link').magnificPopup({
                type: 'inline',
                midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
                closeOnBgClick:false
            });

            $('.open-popup-ajax').magnificPopup({
                type: 'iframe',
                midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
                closeOnBgClick:false

            });




            bindSupport();
        })
    </script>

    <script type="text/javascript">
        var SUPPORT_URL="<?php echo addons_url('Support://Support/doSupport');?>";
        //上传图片
        /* 初始化上传插件 */
        $("#upload_picture_cover").uploadify({
            "height"          : 30,
            "swf"             : "/Public/static/uploadify/uploadify.swf",
            "fileObjName"     : "download",
            "buttonText"      : "上传封面",
            "buttonClass":"uploadcover",
            "uploader"        : "<?php echo U('Home/File/uploadPicture',array('session_id'=>session_id()));?>",
            "width"           : 250,
            'removeTimeout'	  : 1,
            'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
            "onUploadSuccess" : uploadPicturecover,
            'overrideEvents':['onUploadProgress','onUploadComplete','onUploadStart','onSelect'],
            'onFallback' : function() {
                alert('未检测到兼容版本的Flash.');
            }, 'onUploadProgress': function (file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
                $("#cover_id_cover").parent().find('.upload-img-box').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
            },'onUploadComplete' : function(file) {
                //alert('The file ' + file.name + ' finished processing.');
            },'onUploadStart' : function(file) {
                //alert('Starting to upload ' + file.name);
            },'onQueueComplete' : function(queueData) {
                // alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
            }
        });
        function uploadPicturecover(file, data){
            var data = $.parseJSON(data);
            var src = '';
            if(data.status){
                $("#cover_id_cover").val(data.id);
                src = data.url ||  data.path
                $('.upload-img-box').html(
                        '<div class="upload-pre-item"><img src="' + src + '"/></div>'
                );
            } else {
                toast.error('封面上传失败。','温馨提示');
            }
        }
    </script>


    </div>
</div>

<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>
	<!-- /主体 -->

	<!-- 底部 -->
	<!-- 底部
================================================== -->
<div style="padding: 5px"></div>
<div class="footer-jumbotron footer_bar">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div><h2><a href="http://www.ourstu.com" target="_blank"><?php echo C('FOOTER_TITLE');?></a></h2>
                    <p class="han_p"><?php echo C('FOOTER_SUMMARY');?>
                    </p>
                    <div class="row">



                        <?php if(!empty($icp)): ?><div class="col-xs-6">备案号：<a href="http://www.miitbeian.gov.cn/" target="_blank"><?php echo ($icp); ?></a></div><?php endif; ?>
                        <div class="col-xs-6 text-right">
                        <!--// 如未获得thinkox官方授权，请勿删除此处的文字和链接 购买请查看 http://tox.ourstu.com/fee.html -->
                            <a href="http://tox.ourstu.com/" target="_blank">Powered By ThinkOX</a>
                        <!--// 如未获得thinkox官方授权，请勿删除此处的文字和链接 购买请查看 http://tox.ourstu.com/fee.html end -->
                        </div>
                        <div class="col-md-12">
                            <?php echo ($count_code); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="footer_right">
                  <?php echo C('FOOTER_RIGHT');?>
                </div>
            </div>
            <div class="col-md-2">
               <?php echo C('FOOTER_QCODE');?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/Public/Core/js/ext/magnific/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/placeholder/placeholder.js"></script>
<script type="text/javascript" src="/Public/Core/js/ext/atwho/atwho.js"></script>
<link type="text/css" rel="stylesheet" href="/Public/Core/js/ext/atwho//atwho.css"/>


 <!-- 用于加载js代码 -->
<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
<?php echo hook('pageFooter', 'widget');?>
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
    
</div>

	<!-- /底部 -->
</body>
</html>