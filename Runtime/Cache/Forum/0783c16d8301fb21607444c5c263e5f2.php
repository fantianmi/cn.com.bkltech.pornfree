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
    <script type="text/javascript" src="/Public/Forum/js/common.js"></script>
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
<div id="main-container" class="conatiner">
    <div class="container">
        <div class="col-md-9">
            <link type="text/css" rel="stylesheet" href="/Public/Forum/css/forum.css"/>
<?php if(ACTION_NAME == 'forum'): if($forum_id == 0): ?><div class="forum_header  text-center">
            <div class="row">
                <div class="col-md-12">
                    <i class="forum_logo_i"></i>
                </div>
            </div>
            <div class="row" style="margin-top: 35px">
                <div class="col-md-4">
                    板块总数：<?php echo ($count["forum"]); ?>
                </div>
                <div class="col-md-4">
                    主题总数：<?php echo ($count["post"]); ?>
                </div>
                <div class="col-md-4">
                    帖子总数：<?php echo ($count["all"]); ?>
                </div>
            </div>
            <div class="forum_left_tip">统计</div>
        </div>
        <?php else: ?>
        <div class="forum_header  text-center"
        <?php if($forum['logo']){ ?>
        style="background: url(<?php echo (getthumbimagebyid($forum["logo"],755,195)); ?>) no-repeat center !important"
        <?php } ?>
        >
        <div class="row">
            <div class="col-md-12">
                <a href="/index.php?s=/forum/index/forum/id/2" class="forum_logo"><?php echo ($forum["title"]); ?></a>
            </div>
        </div>
        </div><?php endif; endif; ?>
            <div class="container-fluid">
                
    <?php if($list_top): ?><div class="row fourm-posts common_block_border">
            <div class="row common_block_title">
                    置顶帖子
            </div>
            <div class="col-xs-12">
                <section id="contents">
                    <?php if(is_array($list_top)): $i = 0; $__LIST__ = $list_top;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$document): $mod = ($i % 2 );++$i; $user = query_user(array('avatar128','avatar64','nickname','uid','space_url','icons_html'), $document['uid']); ?>
<div class="row">
    <div class="col-md-2 col-xs-4 text-center">
        <p>
            <a href="<?php echo ($user["space_url"]); ?>">
                <img src="<?php echo ($user["avatar64"]); ?>" ucard="<?php echo ($user["uid"]); ?>" class="avatar-img" />
            </a>
        </p>
    </div>
    <div class="col-md-10 col-xs-12" >

        <p>

                <a class="forum_forum_name" href="<?php echo U('Forum/Index/forum',array('id'=>$document['forum_id']));?>">[<?php echo ($document["forum"]["title"]); ?>]</a><a class="forum-list-title-link" title="<?php echo (htmlspecialchars($document["title"])); ?>"
                                                                                                                       href="<?php echo U('Index/detail',array('id'=>$document['id']));?>"><?php echo (mb_substr(htmlspecialchars($document["title"]),0,30,'utf-8')); ?>
        </a><?php if(($document["is_top"]) == "2"): ?><i class="post_top">全站</i>
                    <?php else: ?>
                    <?php if(($document["is_top"]) == "1"): ?><i class="post_top_forum">版块</i><?php endif; endif; ?>


        </p>

        <p class="pull-right text-muted">
            <span>阅读（<?php echo ($document["view_count"]); ?>）</span>
            <span style="width: 1em; display: inline-block;">&nbsp;</span>
            <span>回复（<?php echo ($document["reply_count"]); ?>）</span>
        </p>

        <p class="text-muted author">
            <a href="<?php echo ($user["space_url"]); ?>" ucard="<?php echo ($user["uid"]); ?>"><?php echo (op_t($user["nickname"])); ?></a><?php echo ($user["icons_html"]); ?>
            发布：<?php echo (friendlydate($document["create_time"])); ?> |
            回复：<?php echo (friendlydate($document["last_reply_time"])); ?>
        </p>
    </div>
</div>


                        <?php if($i != count($list_top)): ?><hr class="forum-list-hr"/>
                            <?php else: ?>
                            <div class="forum-list-no-hr"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </section>
            </div>
        </div><?php endif; ?>
 <!--  板块幻灯片-->
<!--    <div class="bankuaippt row  fourm-posts forum_block_border">
        <div class="col-md-12">tg</div>
    </div>-->
    <div class="row fourm-posts common_block_border">

        <div class="row common_block_title">
                帖子列表
            <div class="pull-right text-right" style="margin-right: 15px">
                <div class="btn-group forum_order">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                       <?php if(($order) == "0"): ?>回复时间<?php else: ?>发表时间<?php endif; ?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu text-left" role="menu">
                        <li><a href="<?php echo U('forum',array('id'=>$forum_id,'order'=>'ctime'));?>">发表时间</a></li>
                        <li><a href="<?php echo U('forum',array('id'=>$forum_id,'order'=>'reply'));?>">回复时间</a></li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-xs-12">
            <section id="contents">
                <?php if(!$list): ?><div class="row">
                        <div class="col-xs-12">
                            <p class="text-muted" style="text-align: center; font-size: 3em;">
                                <br/><br/>
                                暂时没有帖子～
                                <br/><br/><br/>
                            </p>
                        </div>
                    </div><?php endif; ?>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$document): $mod = ($i % 2 );++$i; $user = query_user(array('avatar128','avatar64','nickname','uid','space_url','icons_html'), $document['uid']); ?>
<div class="row">
    <div class="col-md-2 col-xs-4 text-center">
        <p>
            <a href="<?php echo ($user["space_url"]); ?>">
                <img src="<?php echo ($user["avatar64"]); ?>" ucard="<?php echo ($user["uid"]); ?>" class="avatar-img" />
            </a>
        </p>
    </div>
    <div class="col-md-10 col-xs-12" >

        <p>

                <a class="forum_forum_name" href="<?php echo U('Forum/Index/forum',array('id'=>$document['forum_id']));?>">[<?php echo ($document["forum"]["title"]); ?>]</a><a class="forum-list-title-link" title="<?php echo (htmlspecialchars($document["title"])); ?>"
                                                                                                                       href="<?php echo U('Index/detail',array('id'=>$document['id']));?>"><?php echo (mb_substr(htmlspecialchars($document["title"]),0,30,'utf-8')); ?>
        </a><?php if(($document["is_top"]) == "2"): ?><i class="post_top">全站</i>
                    <?php else: ?>
                    <?php if(($document["is_top"]) == "1"): ?><i class="post_top_forum">版块</i><?php endif; endif; ?>


        </p>

        <p class="pull-right text-muted">
            <span>阅读（<?php echo ($document["view_count"]); ?>）</span>
            <span style="width: 1em; display: inline-block;">&nbsp;</span>
            <span>回复（<?php echo ($document["reply_count"]); ?>）</span>
        </p>

        <p class="text-muted author">
            <a href="<?php echo ($user["space_url"]); ?>" ucard="<?php echo ($user["uid"]); ?>"><?php echo (op_t($user["nickname"])); ?></a><?php echo ($user["icons_html"]); ?>
            发布：<?php echo (friendlydate($document["create_time"])); ?> |
            回复：<?php echo (friendlydate($document["last_reply_time"])); ?>
        </p>
    </div>
</div>


                    <?php if($i != count($list)): ?><hr class="forum-list-hr"/>
                        <?php else: ?>
                        <div class="forum-list-no-hr"></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                <div class="pull-right">
                    <?php echo getPagination($totalCount);?>
                </div>
            </section>
        </div>
    </div>


            </div>
        </div>
        <div class="col-md-3">
            
            
                <?php if($allow_publish): ?><div style="margin-bottom: 12px">
        <a type="button" class="btn btn-large btn-primary forum_post_btn"
           href="<?php echo U('Index/edit',array('forum_id'=>$forum_id));?>">

            发表新帖
        </a>
    </div>
    <!-- <div style="margin-bottom: 20px">
         <a type="button" class="btn btn-large btn_primary_tieba primary forum_post_btn"
            href="<?php echo U('Index/edit',array('forum_id'=>$forum_id));?>">

             创建板块
         </a>
     </div>--><?php endif; ?>

<div class="forum_module">
    <div class="forum_module_name">
        <div class="pull-left">论坛板块</div>
       <!-- <div class="pull-right"><a href="<?php echo U('Forum/Index/forums');?>" class="font_grey" style="font-size: 14px;">查看全部</a>
        </div>-->
    </div>
    <div class="forum_module_content clearfix" style="padding: 5px">
        <a  class="btn btn-default position <?php if(($forum_id) == "0"): ?>btn-primary<?php endif; ?>" href="<?php echo U('Index/forum');?>">全部帖子</a><?php if(is_array($forum_list)): $i = 0; $__LIST__ = $forum_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$each_forum): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Index/forum',array('id'=>$each_forum['id']));?>" class="btn btn-default  position <?php if(($forum_id) == $each_forum['id']): ?>btn-primary<?php endif; ?>"><?php echo (htmlspecialchars($each_forum["title"])); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>


    </div>
</div>

<?php echo W('Event/RecommendEvent/recommendEvent');?>

<?php echo W('HotPost/lists',array('forum_id'=>$forum_id));?>


            

        </div>
    </div>

    
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-xs-12">

            </div>
            <div class="col-sm-3 col-xs-12">


            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(window).resize(function () {
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    });
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