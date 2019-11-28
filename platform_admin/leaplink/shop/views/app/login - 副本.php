<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LeapFone</title>
    <link rel="Shortcut Icon" href="images/favicon.ico" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="css/public/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/public/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/public/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="css/public/admin.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="css/public/skin-blue.min.css">

    <link rel="stylesheet" href="css/login/login.css">
    <link rel="stylesheet" type="text/css" href="css/public/ts.css">


</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>LeapFone</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎使用后台管理系统</p>

        <form action='<?=Url::toRoute("app/login")?>' method="post" id='login-form' >
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
            <!-- 账户名称 -->
            <div class="form-group has-feedback">
                <input type="text" name="account" class="form-control" placeholder="用户名" autocomplete="off" value="<?=$data['account']?>" id="phone">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <!-- 密码 -->
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control" placeholder="密码" autocomplete="off" value="<?=$data['password']?>" id="password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8 " >
                    <span class='mm-ts' style="color:red;font-size:12px;<?php if($data['code'] == 0) echo 'display:none;'; ?>" code="<?=$data['code']?>">*<?=$data['msg']?></span>
                </div>
            </div>
            <div class="row">
                <div class="form-group has-feedback register-group">
                    <a href="<?=Url::toRoute("app/register")?>">用户注册</a>
                    <a href="<?=Url::toRoute("app/findpwd")?>">忘记密码</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-btn">登录</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
</div>

<!-- jQuery 3 -->
<script src="js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="js/public/bootstrap.min.js"></script>
<script src="js/public/common.js"></script>
<script src='js/public/ts.js'></script>
<script>
    $('input').focus(function () {
        $('.mm-ts').hide();
    });
</script>


</body>
</html>

