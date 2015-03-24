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

    <link href="/Public/Event/css/event.css" rel="stylesheet" type="text/css"/>
    <link type="text/css" rel="stylesheet" href="/Public/Core/js/ext/magnific/magnific-popup.css"/>


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
        
<!--导航-->
<?php echo W('Common/SubMenu/render',array($sub_menu,$current,'活动',''));?>






    <div class="col-md-9">



        <div class="forum_module" style="width: 100%;min-height: 800px;">

            <div class="row" style="padding: 10px 10px 0 0 ">
                <div class="col-md-12">
                    <div class="event_header_title">
                        <div class="pull-left">活动首页</div>
                        <div class="btn-group btn-group-sm pull-right" style="margin-bottom:5px;">
                            <a href="<?php echo U('index',array('norh'=>'new','type_id'=>$type_id));?>"
                               class="btn btn-default <?php if($norh == 'new'): ?>active<?php endif; ?>">最新</a>
                            <a href="<?php echo U('index',array('norh'=>'hot','type_id'=>$type_id));?>"
                               class="btn btn-default <?php if($norh == 'hot'): ?>active<?php endif; ?>">最热</a>
                        </div>
                    </div>

                </div>

            </div>
            <?php if(is_array($contents)): $i = 0; $__LIST__ = $contents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="clearfix event_list">
                    <div class="col-md-5" style="padding: 0">
                        <?php if(time() < $vo['eTime']): ?><div class="event_state" style="background: #d61f39">
                                正在进行
                            </div>
                            <?php else: ?>
                            <div class="event_state" style="background: #000;">
                                已结束
                            </div><?php endif; ?>

                        <a class="pull-left" href="<?php echo U('Event/Index/detail',array('id'=>$vo['id']));?>"> <img
                                class=""
                                src="<?php echo (getthumbimagebyid($vo["cover_id"],320,210)); ?>" style="width: 320px;height: 210px;"/></a>
                    </div>
                    <div class="pull-left col-md-7">
                        <div class="mgl20 event_title"><a href="<?php echo U('Event/Index/detail',array('id'=>$vo['id']));?>"
                                                          class="text-more" style="width: 100%"><?php echo ($vo["title"]); ?></a></div>
                        <div class="event_fq_count pull-left">
                            <div class="pull-left mgb10 col-md-12">
                                <div class="pull-left col-md-6 " style="padding-left: 5px;">by&nbsp; <a class=""
                                                                                                        href="<?php echo ($vo["user"]["space_url"]); ?>">
                                    <span class="event_count"><?php echo ($vo["user"]["nickname"]); ?></span></a></div>
                                <div title="报名人数" class="pull-left col-md-3">
                                    <div class="event_logo" style=""></div>
                                    <span class="event_count">&nbsp;<?php echo ($vo["signCount"]); ?>人</span></div>
                                <div class="pull-left col-md-3">
                                    <div class="event_logo" style="background-position: -20px 0px;"></div>
                                    <span class="event_count">&nbsp;<?php echo ($vo["view_count"]); ?></span></div>
                            </div>
                            <div class="pull-left mgb10">
                                <div class="pull-left mgl20 " style="font-size: 14px;">时间：
                                    <?php echo date('Y-m-d',$vo['sTime']);?>--<?php echo date('Y-m-d',$vo['eTime']);?> &nbsp;&nbsp;
                                    <?php echo ($vo["type"]["title"]); ?>
                                </div>
                            </div>

                            <div class="pull-left mgb10">
                                <div class="pull-left mgl20 " style="font-size: 14px;">报名截止时间：
                                    <?php echo date('Y-m-d',$vo['deadline']);?> &nbsp;&nbsp;

                                </div>
                            </div>
                            <div class="word-wrap pull-left mgl20 event_short_explain" style="width: 80%">简介：
                                <?php echo (getshortsp(op_t($vo["explain"]),30)); ?>
                            </div>
                            <div class="pull-left mgl20 mgt10" style=" ">
                                <div class="btn-group">
                                    <?php if(($vo['uid'] == is_login()) OR is_administrator(is_login())): if(time() < $vo['eTime']): ?><a class="btn btn-default endEvent" data-eventID="<?php echo ($vo["id"]); ?>">提前结束</a><?php endif; ?>

                                        <a href="<?php echo U('edit',array('id'=>$vo['id']));?>" class="btn btn-default">编辑活动</a>
                                        <a class="btn btn-default delEvent" data-eventID="<?php echo ($vo["id"]); ?>">删除活动</a>
                                        <?php else: ?>
                                        <?php if(!$vo['check_isSign']){ ?>

                                        <?php if($vo['deadline'] < time()){ ?>
                                        <a class="btn btn-default  " href="javascript:"
                                           onclick="toast.error('报名已截止')">报名已截止 </a>
                                        <?php }else{ ?>
                                        <?php if(is_login() == 0): ?><a class="btn btn-info  " href="javascript:" style="color: #fff"
                                               onclick="toast.error('请登陆后再报名')">报名参加 </a>
                                            <?php else: ?>
                                            <a class="btn btn-primary event_sign " style="color: #fff"
                                               href="<?php echo U('ajax_sign',array('event_id'=>$vo['id']));?>">报名参加 </a><?php endif; ?>
                                        <?php } ?>
                                        <?php }else{ ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-default unSign"
                                                                   data-eventID="<?php echo ($vo["id"]); ?>" href="javascript:"
                                                                   id="unSign">取消报名 </a>

                                        <span style="line-height: 34px;"> 已报名&nbsp;&nbsp;&nbsp;&nbsp;<?php if($vo['check_isSign'][0]['status'] == 1): ?>已审核
                                            <?php else: ?>
                                            未审核<?php endif; ?></span>

                                        <?php } endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="row issue_list">


                <?php if(count($contents) == 0): ?><div style="font-size:3em;padding:2em 0;color: #ccc;text-align: center">此分类下暂无内容哦。O(∩_∩)O~</div><?php endif; ?>

            </div>
        </div>

        <div>
            <div class="pull-right">

                <?php echo getPagination($totalPageCount,10);?>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="z-index: 99">
        <div style="margin-bottom: 12px">
    <?php if(is_login() == 0): ?><button class="btn btn-primary btn-large event_btn" onclick="toast.error('请登陆后再发布。','温馨提示')">发起活动
        </button>
        <?php else: ?>
        <a class="btn btn-primary btn-large event_btn " href="<?php echo U('Event/Index/add');?>">发起活动
        </a><?php endif; ?>
</div>
<div class="common_block_border event_right">
    <div class="common_block_title_right">活动分类</div>

    <div class="event_type clearfix" style="padding: 0 5px;margin-bottom: 5px">
        <a style="float: left" href="<?php echo U('index',array('norh'=>$norh));?>"
        <?php if(($type_id) == ""): ?>class="cur"<?php endif; ?>
        >全部分类</a>
        <?php if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?><a style="float: left"
            <?php if(($type_id) == $top['id']): ?>class="cur"<?php endif; ?>
            href="<?php echo U('index',array('type_id'=>$top['id'],'norh'=>$norh));?>" data="<?php echo ($top["id"]); ?>"><?php echo ($top["title"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>




<?php if(ACTION_NAME == 'detail' ){ ?>
<div class="forum_module " style="width:100%;float: left;">
    <h2 class="event_type_name">参与活动的人 (<?php echo ($content["attentionCount"]); ?>人) <a class="pull-right"
                                                                       href="<?php echo U('member',array('id'=>$content['id']));?>"
                                                                       style="font-size: 14px;margin-right: 10px;color: #848484">全部</a>
    </h2>

    <div style="width: 100%">
        <?php if(is_array($content['member'])): $i = 0; $__LIST__ = array_slice($content['member'],0,20,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="" style="text-align: center;width:75px;float: left;height: 75px;line-height: 80px;">
                <a href="<?php echo ($vo["space_url"]); ?>">
                    <img ucard="<?php echo ($vo["id"]); ?>" src="<?php echo ($vo["avatar64"]); ?>" class="avatar-img" style="width: 64px"/>
                </a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>

</div>
<?php } ?>

<?php echo W('RecommendEvent/recommendEvent');?>
    </div>




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


    <script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script type="text/javascript" src="/Public/Event/js/event.js"></script>
    <script type="text/javascript" src="/Public/Core/js/ext/magnific/jquery.magnific-popup.min.js"></script>
    <script>
        $(function () {
            $('#top_nav >li >a ').mouseenter(function () {
                $('.children_nav').hide();
                $('#children_' + $(this).attr('data')).show();
            });

            bindSupport();


        })


    </script>

    <script type="text/javascript">
        var SUPPORT_URL = "<?php echo addons_url('Support://Support/doSupport');?>";

    </script>
 <!-- 用于加载js代码 -->
<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
<?php echo hook('pageFooter', 'widget');?>
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
    
</div>

	<!-- /底部 -->
</body>
</html>