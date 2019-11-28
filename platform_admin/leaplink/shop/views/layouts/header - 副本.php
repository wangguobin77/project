<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>近场业务平台</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="Shortcut Icon" href="images/favicon.ico" />
    <link rel="stylesheet" href="css/public/bootstrap.min.css">
    <link rel="stylesheet" href="css/public/font-awesome.min.css">
    <link rel="stylesheet" href="css/public/ionicons.min.css">
    <link rel="stylesheet" href="css/public/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/public/select2.min.css">
    <link rel="stylesheet" href="css/public/admin.css">
    <link rel="stylesheet" href="css/public/skin-blue.min.css">
    <link rel="stylesheet" href="css/public/table.css">
    <link rel="stylesheet" href="css/public/dialog.css">
    <link rel="stylesheet" href="css/public/ts.css">
    <link rel="stylesheet" href="css/login/register.css">
    <link rel="stylesheet" href="css/public/tongyong.css">

    <!-- 日期控件 -->
    <link type="text/css" rel="stylesheet" href="css/public/jeDate-test.css">
    <link type="text/css" rel="stylesheet" href="css/public/jedate.css">
    <script type="text/javascript" src="js/public/jedate.js"></script>
    <style type="text/css">.select-input>span.select2{
            width:34% !important;
        }</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::toRoute(['/'])?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            近场业务平台
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="images/logo_head.png"></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="ota/#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                            <span ><?=$this->context->loginUserInfo['phone']?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->

                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row">
<!--                                    <div class="col-xs-4 text-center">-->
<!--                                        <a href="javascript:void(0)">修改手机</a>-->
<!--                                    </div>-->
                                    <div class="col-xs-6 text-center">
                                        <a href="<?=Url::toRoute('/shop/up-password')?>">修改密码</a>
                                    </div>
                                    <div class="col-xs-6 text-center">
                                        <a href="<?=Url::toRoute('/shop/logout')?>">退出登录</a>
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
            <!-- Sidebar Menu -->
            <?php include_once(NAV_DIR."/nav.php");?>
            <!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>