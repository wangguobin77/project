<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SENSEPLAY</title>
     <link rel="Shortcut Icon" href="/static/images/favicon.ico" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
     <link rel="stylesheet" href="/static/css/public/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet"  href="/static/css/public/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet"  href="/static/css/public/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet"  href="/static/css/public/admin.css">
    <!-- iCheck -->
    <link rel="stylesheet"  href="/static/css/public/skin-blue.min.css">

    <link rel="stylesheet" href="/static/css/login/login.css">

    <link rel="stylesheet" type="text/css" href="/static/css/public/ts.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>SENSEPLAY</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎使用后台管理系统</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => Url::toRoute('site/login')]); ?>
            <!-- 账户名称 -->
            <div class="form-group has-feedback">
                <?= $form->field($model, 'username')
                    ->textInput([
                        'autofocus' => true,
                        'class' => 'form-control',
                        'autocomplete'=>'off',
                        'placeholder' => '用户名'
                    ])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <!-- 密码 -->
            <div class="form-group has-feedback">
                <?= $form->field($model, 'password')
                    ->textInput([
                        'class' => 'form-control',
                        'type' =>'password',
                        'autocomplete'=>'off',
                        'placeholder' => '密码'
                    ])
                    ->label(false) ?>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                </div>
              <!-- /.col -->
              <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat submit-btn">登录</button>
              </div>
              <!-- /.col -->
            </div>
        <?php ActiveForm::end(); ?>

        <a href="<?=Url::toRoute('user/findpwd')?>">忘记密码</a><br>
  </div>
</div>

<!-- jQuery 3 -->
<script src="/static/js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/static/js/public/bootstrap.min.js"></script>
<script src="/static/js/public/ts.js"></script>

<script>
    var glo = {
        'is_true':true
    };
    //提交用户信息表单
    $('.submit-btn').click(function(){
        if(!glo.is_true){
            return false;
        }
        glo.is_true = false;
        var data = $('#login-form').serialize();

        $.ajax({
            url:'<?=Url::toRoute('site/login')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                glo.is_true = true;
//                glo.is_true = true;
//                layer.msg(data.message,'',function () {
//                if (data.code == 0 ){
//                window.location.href='<?//=Url::toRoute('admin-user/index')?>//';
//                }
//                });
            }
        })
    });
</script>


</body>
</html>
