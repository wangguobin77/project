<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>近场业务平台</title>
    <link rel="Shortcut Icon" href="images/favicon.ico" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="css/v2/public/bootstrap.min.css">
    <link rel="stylesheet" href="css/v2/public/font-awesome.min.css">
    <link rel="stylesheet" href="css/v2/public/tongyong.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="css/v2/public/ts.css">
    <link rel="stylesheet" href="css/v2/login/login.css">


</head>
<body class="hold-transition login-page">
<style type="text/css" media="screen">
    .ts-des{display:block;}
</style>
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>近场业务平台</b></a>
    </div>


    <div class="bottom flex">
        <img src="images/v2/rigister.png" alt="">
        <section class='right'>
            <h3>平台管理系统</h3>
            <span class="des"></span>
                <form action='<?=Url::toRoute("app/login")?>' method="post" id='login-form' >
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                    <section class="login-form">
                        <div class="form-control flex noborder" style="justify-content:space-between;padding-left:0;padding:0;">
                            <h4 class='mmlogin'>密码登录</h4>
                           <span class='mm-ts ts-des' style="color:red;font-size:12px;<?php if($data['code'] == 0) echo 'display:none;'; ?>" code="<?=$data['code']?>">*<?=$data['msg']?></span>
                        </div>
                        <div class="form-control">
                            <span class='glyphicon glyphicon-user'></span>
                            <input type="text" name="account" placeholder="用户名" id="phone" autocomplete="off"  value="<?=$data['account']?>">
                        </div>
                        <div class="form-control flex form-group">
                            <span class='glyphicon glyphicon-lock'></span>
                            <input type="password" name="password" placeholder="密码" autocomplete="off" value="<?=$data['password']?>" id="password">
                        </div>

                        <div class="form-control flex noborder" style="justify-content:space-between;padding-left:0;padding:0;">
                            <a class="reg"  href="<?=Url::toRoute("app/register")?>">注册</a>
                            <a class="forget" href="<?=Url::toRoute("app/findpwd")?>">忘记密码</a>
                        </div>

                        <div class="form-control flex noborder" style="justify-content:flex-start;padding-left:0;">
                            <button type="submit" class="btn btn-primary submit-btn sub-pass">登录</button>
                        </div>
                    </section>
                </form>
        </section>
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

