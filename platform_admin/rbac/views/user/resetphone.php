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
    <link rel="Shortcut Icon" href="/static/images/favicon.ico"  />
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

    <!-- 提示信息 -->
   <link rel="stylesheet" href="/static/css/public/ts.css">

    <link rel="stylesheet" href="/static/css/public/findpwd.css">

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
        <p class="login-box-msg">修改手机号</p>
        <!-- 填写手机号码  验证码 -->
        <div id="sub-one">
            <!-- 账户名称 -->
            <div class="row info">
                <div class="form-group has-feedback col-md-8 col-xs-8">
                    <input type="mobile" class="form-control" name="mobile" id="mobile" placeholder="新手机号码" autocomplete="off">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <!-- 提示信息 -->
                    <span class="sj-ts glyphicon glyphicon-remove-sign ts_mobile warning ts_mobile">手机号码不正确</span>
                </div>
                <button class="col-md-3 fsyym verification cursor col-xs-4" id="code">验证码</button>
            </div>
            <!-- 密码 -->
            <div class="row info">
                <div class="form-group has-feedback col-md-8 col-xs-8">
                    <input type="text" class="form-control"  id="mobile_code" placeholder="验证码" autocomplete="off">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <span class="yzm-ts glyphicon glyphicon-remove-sign warning" >验证码不正确</span>
                </div>
                <span class="col-md-3 col-xs-4"></span>
            </div>

            <div class="row info">
                <div class="form-group col-md-8 col-xs-4" style="margin-top: 10px;">
                    <button type="button" class="btn btn-primary btn-block btn-flat submit-btn sub-one" style="line-height: 34px;">确认</button>
                </div>
                <div class="col-md-3 col-xs-8">

                </div>
            </div>
        </div>
  </div>
</div>
<!-- 信息提示框 -->
<div class="ts-box succ">
    <div class="ts-xx">
        <p>保存成功</p>

    </div>
</div>
<!-- 失败提示 -->
<div class="fail-ts ">
    <div class="ts-xx">
        <p>保存失败，请重新尝试！</p>

    </div>
</div>

<!-- jQuery 3 -->
<script src="/static/js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/static/js/public/bootstrap.min.js"></script>
<!-- 信息验证js -->
<script src='/static/js/public/common.js'></script>
<!-- 提示信息函数 -->
<script src="/static/js/public/ts.js"></script>
<script>
    var glo = {
        'istrue':true, //为false 时不可以再次请求手机验证码
    }

    var reg = /^[1][3,4,5,7,8][0-9]{9}$/;
    // 发送验证码
    $('.fsyym').click(function() {

        if(!glo.istrue){
            return false;
        }
        // debugger;
        var mobile = $('#mobile').val();//需要验证手机格式
        if(mobile==''){
            $('.sj-ts').html('手机号码不能为空！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        if(!reg.test(mobile)){
            $('.sj-ts').html('手机格式不正确！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }



        glo.istrue = false;
        $.ajax({
            url: '<?=Url::toRoute('user/is-check-mobile')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
            success:function (data) {
                if(data.code != 0){
                    $('.ts_mobile').css('display','none');

                    $.ajax({
                        url: '<?=Url::toRoute('user/get-mobile-code')?>',
                        type: 'post',
                        dataType: 'json',
                        data: {'mobile':mobile,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
                        success:function (data) {
                            glo.istrue = true;
                            if(data.code != 0){
                                $('.yzm-ts').html('验证码发送失败！');//验证码发送失败
                                $('.yzm-ts').show();

                            }else{

                                var m=document.querySelector('#code');
                                var T=60;

                                $('#code').attr('disabled', 'true');
                                var t=setInterval(function(){
                                    T--;
                                    m.innerHTML=T;
                                    if(T===0){
                                        $('#code').removeAttr('disabled', 'true');
                                        clearInterval(t);
                                        $('#code').html('重新发送');
                                    }else {
                                        $('#code').html(  T + 's');
                                    }
                                },1000)
                            }

                        }
                    })

                }else{
                    $('.ts_mobile').html('手机号码已存在！');//用户信息已存在 因为更换手机换号

                    $('.ts_mobile').css('display','block');
                    glo.istrue = true;
                }

            }
        })

    })


/*
    sub-one 提交按钮
 */
    $('.sub-one').click(function(){
        if(!glo.istrue){
            return false;
        }
        var mobile = $('#mobile').val();

        var mobile_code = $('#mobile_code').val();//判断是否为空 验证手机格式

        if(mobile==''){
            $('.sj-ts').html('手机号码不能为空！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        if(!reg.test(mobile)){
            $('.sj-ts').html('手机格式不正确！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        if(mobile_code==''){
            $('.yzm-ts').html('验证码不能为空！');
            $('.yzm-ts').show();
            return false;
        }else{
            $('.yzm-ts').hide();
        }
        glo.istrue = true;
        $.ajax({
            url: '<?=Url::toRoute('user/uphone')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'authcode':mobile_code,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
            success:function (data) {
                glo.istrue = true;
                if(data.code != 0){
                    // alert(data.message);//验证码不正确
                    $('.yzm-ts').html('验证码不正确');
                    $('.yzm-ts').show();

                }else{
                    //修改成功
                    succ('手机修改成功，稍后返回登录');
                    back_url('<?=Url::toRoute('index/index')?>');//参数为login的地址 跳回到登录 重新登录
                }

            }
        })

    });

</script>
</body>
</html>
