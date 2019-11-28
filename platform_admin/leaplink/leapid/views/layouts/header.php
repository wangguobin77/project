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
    <title>LeapFone</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="Shortcut Icon" href="/static/images/favicon.ico" />
    <link rel="stylesheet" href="/static/css/public/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/public/font-awesome.min.css">
    <link rel="stylesheet" href="/static/css/public/ionicons.min.css">
    <link rel="stylesheet" href="/static/css/public/select2.min.css">
    <link rel="stylesheet" href="/static/css/public/admin.css">
    <link rel="stylesheet" href="/static/css/public/skin-blue.min.css">
    <link rel="stylesheet" href="/static/css/public/dialog.css">
    <link rel="stylesheet" href="/static/css/public/table.css">
    <link rel="stylesheet" href="/static/css/public/ts.css">
    <link rel="stylesheet" href="/static/css/public/tongyong.css">
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="javascript:;" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            LeapFone
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="/static/images/logo_head.png"></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="../../leapid/web/#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="../../leapid/web/#" class="dropdown-toggle" data-toggle="dropdown">
                            <span><?=$_SESSION['uid']['username']?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->

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

                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

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
