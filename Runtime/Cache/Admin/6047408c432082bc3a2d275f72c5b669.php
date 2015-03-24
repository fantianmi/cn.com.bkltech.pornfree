<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>管理平台</title>
    <link href="/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
    <!--[if lt IE 9]>
    <script type="text/javascript" src="/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
    <link rel="stylesheet" href="/Public/Admin/js/codemirror/codemirror.css">
    <link rel="stylesheet" href="/Public/Admin/js/codemirror/theme/<?php echo C('codemirror_theme');?>.css">
    <style>
        .CodeMirror, #preview_window {
            width: 700px;
            height: 500px;
        }

        #preview_window.loading {
            background: url('/Public/static/thinkbox/skin/default/tips_loading.gif') no-repeat center;
        }

        #preview_window textarea {
            display: none;
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
    <div class="main-title">
        <h2>快捷操作</h2>
    </div>
    <!-- /标题栏 -->

    <div class="cf">
        <a id="addpack" class="btn" href="<?php echo U('addpack');?>">新增补丁</a><!--
        <a id="use" class="btn" href="" autocomplete="off">使用补丁</a>-->
    </div>

    <!-- 应用列表 -->
    <div class="data-table table-striped">
        <table>
            <thead>
            <tr>
                <th width="200">名称</th>
                <th>用途介绍</th>
                <th width="200">作者</th>
                <th width="80">数据大小</th>
                <th width="300">补丁创建时间</th>

                <th width="300">最后修改时间</th>
                <th width="150">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                    <td><a href="<?php echo U('addpack?id='.$data['id']);?>"><?php echo ($data["title"]); ?></a></td>
                    <td><?php echo ($data["des"]); ?></td>
                    <td><?php echo ($data["author"]); ?></td>
                    <td><?php echo ($data["size"]); ?></td>
                    <td><?php echo ($data["ctime"]); ?></td>
                    <td><?php echo ($data["mtime"]); ?></td>

                    <td class="action">

                        <a class="ajax-get confirm use db-import" style="cursor: pointer;color: red;font-weight: bold" href="<?php echo U('use_pack?id='.$data['id']);?>">使用</a>&nbsp;
                        <a class=" use " href="<?php echo U('addpack?id='.$data['id']);?>">编辑</a>&nbsp;
                        <a id="" class="db-import view" style="cursor: pointer" data="<?php echo ($data["id"]); ?>"
                           type="button">查看</a>&nbsp;
                        <a class="ajax-get confirm" href="<?php echo U('del_pack?id='.$data['id']);?>"
                                >删除</a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /应用列表 -->

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
            "ROOT": "", //当前网站地址
            "APP": "/index.php?s=", //当前项目地址
            "PUBLIC": "/Public", //项目公共目录地址
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
</script>
<script type="text/javascript" src="/Public/static/think.js"></script>
<script type="text/javascript" src="/Public/Admin/js/common.js"></script>
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

    <script type="text/javascript" src="/Public/Admin/js/codemirror/codemirror.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/codemirror/clike.js"></script>
    <script type="text/javascript" src="/Public/Admin/js/codemirror/sql.js"></script>
    <script type="text/javascript" src="/Public/static/thinkbox/jquery.thinkbox.js"></script>
    <script type="text/javascript">


        $(function () {

            $('.view').click(function () {
                var preview_url = '<?php echo U("view");?>';
                var title = $(this).attr('data');
                $.post(preview_url, {title: title}, function (data) {
                    $.thinkbox('<div id="preview_window" class="loading"><textarea></textarea></div>', {
                        afterShow: function () {
                            var codemirror_option = {
                                lineNumbers: true,
                                matchBrackets: true,
                                mode: "sql",
                                indentUnit: 4,
                                gutter: true,
                                fixedGutter: true,
                                indentWithTabs: true,
                                readOnly: true,
                                lineWrapping: true,
                                height: 500,
                                enterMode: "keep",
                                tabMode: "shift",
                                theme: "<?php echo C('CODEMIRROR_THEME');?>"
                            };
                            var preview_window = $("#preview_window").removeClass(".loading").find("textarea");
                            var editor = CodeMirror.fromTextArea(preview_window[0], codemirror_option);
                            editor.setValue(data);
                            $(window).resize();
                        },

                        title: '查看内容',
                        unload: true,
                        actions: ['close'],
                        drag: true
                    });
                });
                return false;
            });


            var $form = $("#export-form"), $export = $("#export"), tables
            $optimize = $("#optimize"), $repair = $("#repair");

            $optimize.add($repair).click(function () {
                $.post(this.href, $form.serialize(), function (data) {
                    if (data.status) {
                        updateAlert(data.info, 'alert-success');
                    } else {
                        updateAlert(data.info, 'alert-error');
                    }
                    setTimeout(function () {
                        $('#top-alert').find('button').click();
                        $(that).removeClass('disabled').prop('disabled', false);
                    }, 1500);
                }, "json");
                return false;
            });

            $export.click(function () {
                $export.parent().children().addClass("disabled");
                $export.html("正在发送备份请求...");
                $.post(
                        $form.attr("action"),
                        $form.serialize(),
                        function (data) {
                            if (data.status) {
                                tables = data.tables;
                                $export.html(data.info + "开始备份，请不要关闭本页面！");
                                backup(data.tab);
                                window.onbeforeunload = function () {
                                    return "正在备份数据库，请不要关闭！"
                                }
                            } else {
                                updateAlert(data.info, 'alert-error');
                                $export.parent().children().removeClass("disabled");
                                $export.html("立即备份");
                                setTimeout(function () {
                                    $('#top-alert').find('button').click();
                                    $(that).removeClass('disabled').prop('disabled', false);
                                }, 1500);
                            }
                        },
                        "json"
                );
                return false;
            });

            function backup(tab, status) {
                status && showmsg(tab.id, "开始备份...(0%)");
                $.get($form.attr("action"), tab, function (data) {
                    if (data.status) {
                        showmsg(tab.id, data.info);

                        if (!$.isPlainObject(data.tab)) {
                            $export.parent().children().removeClass("disabled");
                            $export.html("备份完成，点击重新备份");
                            window.onbeforeunload = function () {
                                return null
                            }
                            return;
                        }
                        backup(data.tab, tab.id != data.tab.id);
                    } else {
                        updateAlert(data.info, 'alert-error');
                        $export.parent().children().removeClass("disabled");
                        $export.html("立即备份");
                        setTimeout(function () {
                            $('#top-alert').find('button').click();
                            $(that).removeClass('disabled').prop('disabled', false);
                        }, 1500);
                    }
                }, "json");

            }


        });


        function showmsg(id, msg) {
            $form.find("input[value=" + tables[id] + "]").closest("tr").find(".info").html(msg);
        }
    </script>

</body>
</html>