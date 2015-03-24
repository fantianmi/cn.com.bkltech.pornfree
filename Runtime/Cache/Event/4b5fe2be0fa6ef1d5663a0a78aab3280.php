<?php if (!defined('THINK_PATH')) exit();?><div class="container sub_menu">
    <nav class="navbar navbar-default" role="navigation">
        <!-- We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns. -->
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-7">
                    <span class="sr-only">打开导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand"><?php echo ($brand); ?></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-7">
                <ul class="nav navbar-nav">
                    <?php if(is_array($menu_list["left"])): $i = 0; $__LIST__ = $menu_list["left"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; $class=($current==$menu['tab']?'active':''); ?>
                        <?php if($menu['children']): ?><!--二级菜单-->
                            <li class="dropdown <?php echo ($class); ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php if(($menu["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($menu["icon"]); ?>"></i><?php endif; ?>
                                    <?php echo ($menu["title"]); ?><span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <?php if(is_array($menu["children"])): $i = 0; $__LIST__ = $menu["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($child["href"]); ?>">
                                            <?php if(($child["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($child["icon"]); ?>"></i><?php endif; ?>
                                            <?php echo ($child["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                                </ul>
                            </li>
                            <?php else: ?>
                            <!--一级菜单-->
                            <li class="<?php echo ($class); ?>"
                                    ><a href="<?php echo ($menu["href"]); ?>">
                                <?php if(($menu["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($menu["icon"]); ?>"></i><?php endif; ?>
                                <?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
                <?php if($menu_list['right'] != null): ?><ul class="nav navbar-nav navbar-right">
                        <?php if(is_array($menu_list["right"])): $i = 0; $__LIST__ = $menu_list["right"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i; $class=($current==$menu['tab']?'active':''); ?>
                            <?php if($menu['children']): ?><!--二级菜单-->
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php if(($menu["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($menu["icon"]); ?>"></i><?php endif; ?>
                                        <?php echo ($menu["title"]); ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php if(is_array($menu["children"])): $i = 0; $__LIST__ = $menu["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li class="<?php echo ($class); ?>"><a href="<?php echo ($child["href"]); ?>">
                                                <?php if(($child["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($child["icon"]); ?>"></i><?php endif; ?>
                                                <?php echo ($child["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                                    </ul>
                                </li>
                                <?php else: ?>
                                <!--一级菜单-->
                                <li class="<?php echo ($class); ?>"
                                        ><a href="<?php echo ($menu["href"]); ?>">
                                    <?php if(($menu["icon"]) != ""): ?><i class="glyphicon glyphicon-<?php echo ($menu["icon"]); ?>"></i><?php endif; ?>
                                    <?php echo ($menu["title"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
            </div>
            <!-- /.navbar-collapse -->
        </div>
    </nav>
</div>