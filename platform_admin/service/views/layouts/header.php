<?php
/**
 * 头部
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/11
 * Time: 下午5:57
 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SENSEPLAY</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="Shortcut Icon" href="/static/images/favicon.ico" />
    <link rel="stylesheet" href="/static/css/public/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/public/font-awesome.min.css">
    <link rel="stylesheet" href="/static/css/public/ionicons.min.css">
    <link rel="stylesheet" href="/static/css/public/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/public/select2.min.css">
    <link rel="stylesheet" href="/static/css/public/admin.css">
    <link rel="stylesheet" href="/static/css/public/skin-blue.min.css">
    <link rel="stylesheet" href="/static/css/public/table.css">
    <link rel="stylesheet" href="/static/css/public/iconfont.css">
    <link rel="stylesheet" href="/static/css/public/all.css">

    <link rel="stylesheet"
          href="/static/css/public/dialog.css">
<!--    <link rel="stylesheet" href="../../keymap/web/css/public/dialog.css">-->
    <link rel="stylesheet" href="/static/css/public/ts.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="javascript:;" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><img src="../../manufacture/web/images/logomini.png"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="../../manufacture/web/images/logo_head.png"></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="../../manufacture/web/#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- 消息通知 提示图标-->
                    <li class="dropdown messages-menu" style="display:none;">
                        <!-- Menu toggle button -->
                        <a href="../../manufacture/web/#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 4 messages</li>
                            <li>
                                <!-- inner menu: contains the messages -->
                                <ul class="menu">
                                    <li><!-- start message -->
                                        <a href="../../manufacture/web/#">
                                            <div class="pull-left">
                                                <!-- User Image -->
                                                <img src="../../manufacture/web/images/logomini.png" class="img-circle" alt="User Image">
                                            </div>
                                            <!-- Message title and timestamp -->
                                            <h4>
                                                Support Team
                                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                            </h4>
                                            <!-- The message -->
                                            <p>Why not buy a new awesome theme?</p>
                                        </a>
                                    </li>
                                    <!-- end message -->
                                </ul>
                                <!-- /.menu -->
                            </li>
                            <li class="footer"><a href="../../manufacture/web/#">See All Messages</a></li>
                        </ul>
                    </li>
                    <!-- /.messages-menu -->

                    <!-- 头部消息通知图标 -->
                    <li class="dropdown notifications-menu" style="display:none;">
                        <!-- Menu toggle button -->
                        <a href="../../manufacture/web/#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 10 notifications</li>
                            <li>
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu">
                                    <li><!-- start notification -->
                                        <a href="../../manufacture/web/#">
                                            <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                        </a>
                                    </li>
                                    <!-- end notification -->
                                </ul>
                            </li>
                            <li class="footer"><a href="../../manufacture/web/#">View all</a></li>
                        </ul>
                    </li>
                    <!-- 头部 小红旗任务通知提示按钮 -->
                    <li class="dropdown tasks-menu" style="display:none;">
                        <!-- Menu Toggle Button -->
                        <a href="../../manufacture/web/#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">You have 9 tasks</li>
                            <li>
                                <!-- Inner menu: contains the tasks -->
                                <ul class="menu">
                                    <li><!-- Task item -->
                                        <a href="../../manufacture/web/#">
                                            <!-- Task title and progress text -->
                                            <h3>
                                                Design some buttons
                                                <small class="pull-right">20%</small>
                                            </h3>
                                            <!-- The progress bar -->
                                            <div class="progress xs">
                                                <!-- Change the css width attribute to simulate progress -->
                                                <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
                                                     aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="sr-only">20% Complete</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <!-- end task item -->
                                </ul>
                            </li>
                            <li class="footer">
                                <a href="../../manufacture/web/#">View all tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="../../manufacture/web/#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?=$_SESSION['uid']['username']?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="/static/images/logomini.png" class="img-circle" alt="User Image">
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="/rbac/web/index.php?r=user/rphone">修改手机</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="/rbac/web/index.php?r=user/rset_pwd">修改密码</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="/rbac/web/index.php?r=user/logout">退出登录</a>
                                    </div>
                                </div>
                                <!-- /.row -->
                            </li>
                            <!-- Menu Footer-->
                   <!--          <li class="user-footer" >
                                <div class="pull-left">
                                    <a href="../../manufacture/web/#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="../../manufacture/web/#" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li> -->
                        </ul>
                    </li>
                    <!-- 右侧侧滑菜单 -->
                    <li style="display:none;">
                        <a href="../../manufacture/web/#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- 用户头像名称信息-->
            <div class="user-panel" style="display:none;">
                <div class="pull-left image">
                    <img src="/static/images/logomini.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    admin
                </div>
            </div>
            <!-- 用户信息end -->

            <!--左侧菜单 start-->
            <?php include_once(NAV_DIR."/nav.php");?>
            <!--左侧菜单 end-->

        </section>
        <!-- /.sidebar -->
    </aside>

<!-- 搜索时没有此信息时显示 -->
<div class="none-info" style="display:none;">
     <div class="row" style="display:flex;flex-wrap: wrap;justify-content: flex-start;padding:0 24px;">
         <div class="none-info" style="margin:0 auto;">
             <img src="/static/images/errorview-empty.png" alt="" >
             <h5 class="non-message">没有找到相关信息~</h5>
         </div>
     </div>
</div>
<!-- 无此消息提示 end -->
