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
    <link rel="Shortcut Icon" href="/images/favicon.ico" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/css/admin.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/css/skin-blue.min.css">

    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" type="text/css" href="/css/public/ts.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>

  <![endif]-->

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:;"><b>SENSEPLAY</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎使用后台管理系统</p>
        <form action="/oauth/authorize?response_type=code&client_id=<?=$client_id?>&state=<?=$state?>&redirect_uri=<?=$redirect_uri?>&language=<?=Yii::$app->language?>" method="post" onsubmit="return toVaild(this);">
        <input type="hidden" name="authorized" value="yes">
            <!-- 账户名称 -->
            <div class="form-group has-feedback">
                <input type="text" name="LoginForm[username]" id="u_p"  class="form-control" placeholder="用户名">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <!-- 密码 -->
            <div class="form-group has-feedback">
                <input type="password" name="LoginForm[password]"  class="form-control" id="pwd" placeholder="密码">

                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                 <div class="col-xs-8">
                     <span class='mm-ts' style="color:red;font-size:12px;display:none;">*用户名不能为空</span>
                </div>
              <!-- /.col -->
              <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat submit-btn">登录</button>
              </div>
              <!-- /.col -->
            </div>
        </form>

        <a href="<?=Yii::$app->params['find_pwd_url']?>/rbac/web/index.php?r=user/findpwd">忘记密码</a><br>
  </div>
</div>

<!-- jQuery 3 -->
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src='/js/public/ts.js'></script>
<script src='/js/public/ts.js'></script>
<script>

    //enter键操作
    $(document).keyup(function(event){
        if(event.keyCode ==13){
            $(".btn-save").trigger("click");
        }
    });

    /*验证表单字段值的合法性*/
    function toVaild()
    {
        var username = $('#u_p').val();//用户

        var pwd = $('#pwd').val();

        if(!username){
            //todo提示框
            $('.mm-ts').html('用户名不能为空');
            $('.mm-ts').show();
            return false;
        }

        if(isPhoneVerify(username)){
            //处理其他信息
        }else{
            //todo提示框
            $('.mm-ts').html('手机号码不正确');
            $('.mm-ts').show();
            return false;
        }

        if(!pwd){
            //todo提示框
             $('.mm-ts').html('密码不能为空');
             $('.mm-ts').show();
            return false;
        }
        if (pwd.indexOf(" ") != -1) {
            //todo提示框
             $('.mm-ts').html('密码不能有空格');
             $('.mm-ts').show();
            return false;
        }
        $('.mm-ts').hide();
        return true;
    }


    /**
     * 错误提示信息
     * @type {string}
     */
    var sen_value = '<?=Yii::$app->getSession()->getFlash('error-login')?>';
    if(sen_value){
        //todo提示框
        alert(sen_value);
    }

    //手机验证
    function isPhoneVerify(phone)
    {
        var reg = /^[1][3,4,5,7,8][0-9]{9}$/;

        if(!reg.test(phone)){
            return false;
        }

        return true;
    }
</script>


</body>
</html>
