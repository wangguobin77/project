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
    <link rel="stylesheet" href="css/v2/public/ionicons.min.css">

    <link rel="stylesheet" href="css/v2/public/ts.css">
    <link rel="stylesheet" href="css/v2/public/tongyong.css">
    <link rel="stylesheet" type="text/css" href="css/v2/login/forget.css">
    <link type="text/css" rel="stylesheet" href="css/v2/public/font-awesome.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
     <div class="wrapper" style="background:#fff;">
        <section class="head-title"></section>
        <section class="info-wrap flex">
            <section class="sub-form flex">
                <section class='info-xx info-xx-title flex'>
                    忘记密码
                </section>
                <div id="sub-one">
                    <section class='info-xx'>
                        <span class='des-box'>
                            <i class='xing'>*</i>
                            <span class='info-des'>请输入手机号：</span>
                        </span>
                        <input type="text" name="mobile" placeholder="请输入手机号码"  id='mobile'>
                        <span class="ts-des tishi sj-ts ts_mobile warning">* 手机号码不存在</span>
                    </section>

                    <section class='info-xx'>
                        <span class='des-box'>
                            <i class='xing'>*</i>
                            <span class='info-des'>请输入验证码：</span>
                        </span>
                        <section class='yzm-box'>
                            <input type="text" name="" placeholder="请输入验证码" id='mobile_code'>
                            <button type="button" cursor id='code' class='fsyym'>获取验证码</button>
                             <span class="ts-des tishi yzm-ts">*验证码不正确</span>
                        </section>
                    </section>
                    <section class='info-xx info-operate'>
                        <button type="button" class='submit sub-one'>下一步</button>
                        <button type="button" class='back'   onclick='javascript:history.back();'>返回</button>
                    </section>
                </div>

                <!-- next  填写新密码  确认密码 -->
                <div id="sub-two" style="display:none;">
                    <section class='info-xx'>
                        <span class='des-box'>
                            <i class='xing'>*</i>
                            <span class='info-des'>设置登录密码：</span>
                        </span>
                        <input type="password" id="pwd"  placeholder="输入6-20位密码(不包含空格)" autocomplete="off">
                        <span class="ts-des tishi mm-ts">* 密码格式不正确</span>
                    </section>
                    <section class='info-xx'>
                        <span class='des-box'>
                            <i class='xing'>*</i>
                            <span class='info-des'>确认密码：</span>
                        </span>
                        <input type="password" name="" placeholder="请再次输入密码" id="rep_pwd">
                        <span class="ts-des tishi">* 两次密码输入不一致</span>
                    </section>

                    <section class='info-xx info-operate'>
                        <button type="button" class='submit sub-two'>确认</button>
                        <button type="button" class='back'   onclick='javascript:history.back();'>返回</button>
                    </section>

                </div>





            </section>
        </section>
    </div>
    <!-- 成功提示 -->
    <div class="succ" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:1000;">
        <div class="ts-box" style="z-index:10000;display:block;">
            <div class="ts-xx ">
                <span class="font_family icon-judge_success_small glyphicon glyphicon-ok-circle"></span>
                <p>保存成功</p>

            </div>
        </div>
    </div>

    <!-- 失败提示 -->
    <div class="fail" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:10000000;">
        <div class="fail-ts" style="display:block;">
            <div class="ts-xx">
                <span class='font_family icon-warning_large glyphicon glyphicon-remove-circle' style="color:red;"></span>
                <p>保存失败，请重新尝试！</p>
            </div>
        </div>
    </div>


<!-- jQuery 3 -->
<script src="js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="js/public/bootstrap.min.js"></script>
<script src='js/public/common.js'></script>
<!-- 提示信息函数 -->
<script src='js/public/ts.js'></script>
<script>
    var glo = {
        'sub_button': true,  //禁止二次提交
        'istrue':true, //为false 时不可以再次请求手机验证码
        'data_token':''
    }
    // 发送验证码
    $('button.fsyym').unbind('click').click(function() {
        if(!glo.istrue){
            return false;
        }
        var mobile = $('#mobile').val();//需要验证手机格式
        if(mobile==''){
            $('.sj-ts').html('手机号码不能为空！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        if(!fun.isMobile(mobile)){
            $('.sj-ts').html('手机格式不正确！').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        glo.istrue = false;


        $.ajax({
            url: '<?=Url::toRoute('app/send-find-pwd-code')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'_csrf':'<?= Yii::$app->request->csrfToken ?>'},
            success:function (data) {
                glo.istrue=true;
                if(data.code == 0){ //发送成功 用户存在的情况下
                    $('.sj-ts').hide();
                    var m=document.querySelector('#code');
                    var T=60;
                    $('#code').attr('disabled', 'true');
                    var t=setInterval(function(){
                        T--;
                        m.innerHTML=T;
                        if(T===0){
                            glo.istrue = true;
                            $('#code').removeAttr('disabled', 'true');
                            clearInterval(t);
                            $('#code').html('重新发送');
                        }else {
                            $('#code').html(  T + 's');
                        }
                    },1000);
                }else if(data.code==100402){
                    $('.sj-ts').html('手机号不存在').show();
                    glo.istrue = true;
                    return false;
                }else{
                    fail('验证码发送失败！');//验证码发送失败
                }
            },

        });
    })

    //sub-one  输入手机号码  验证码
    $('.sub-one').unbind('click').click(function(){
        if(!glo.istrue){
            return false;
        }
        var mobile = $('#mobile').val();
        if(mobile==''){
            $('.sj-ts').html('手机号码不能为空！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }
        if(!fun.isMobile(mobile)){
            $('.sj-ts').html('手机格式不正确！');
            $('.sj-ts').show();
            return false;
        }else{
            $('.sj-ts').hide();
        }

        var mobile_code = $('#mobile_code').val();//判断是否为空 验证手机格式
        if(mobile_code.length==''){
            $('.yzm-ts').html('验证码不能为空！');
            $('.yzm-ts').show();
            return false
        }else{
            $('.yzm-ts').hide();
        }
        if(mobile_code.length !==6){
            $('.yzm-ts').html('验证码格式不正确！');
            $('.yzm-ts').show();
            return false
        }else{
            $('.yzm-ts').hide();
        }

        glo.istrue = true;

        $.ajax({
            url: '<?=Url::toRoute('app/find-pwd-check')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'code':mobile_code, '_csrf':'<?= Yii::$app->request->csrfToken ?>'},
            success:function (data) {
                if(data.code == 0){ //发送成功
                    //todo 验证成功这里会返回 data.data._token,用作下一步参数
                    glo.data_token=data.data._token;
                    $('#sub-one').hide();
                    $('#sub-two').show();
                }else if(data.code==100402){
                    $('.sj-ts').html('手机号不存在！');
                    $('.sj-ts').show();
                }else if(data.code==100601){
                    $('.yzm-ts').html('验证码格式不正确！');
                    $('.yzm-ts').show();
                }else{
                    $('.yzm-ts').html('验证码发送失败！');
                    $('.yzm-ts').show();
                }
            },

        });

    });



    // 输入 密码   确认密码 sub-two
    $('.sub-two').unbind('click').click(function(){
        if(!glo.istrue){
            return false;
        }
        var mobile = $('#mobile').val();

        var pwd = $('#pwd').val();//密码

        var rep_pwd = $('#rep_pwd').val();//确认密码
        if ( pwd==''){
            $('.mm-ts').html('密码不能为空！');
            $('.mm-ts').show();
            return false;
        }else{
            $('.mm-ts').hide();
        }
        if ( !fun.pwd(pwd)){
            $('.mm-ts').html('密码格式不正确！');
            $('.mm-ts').show();
            return false;
        }else{
            $('.mm-ts').hide();
        }
        // 密码中不含空格
        if (pwd.indexOf(" ") != -1){
            $('.mm-ts').html('密码中不能含有空格');
            $('.mm-ts').show();
            return false;
        }else{
            $('.mm-ts').hide();
        }
        if(pwd != rep_pwd){
            $('.mm-ts').html('两次密码不一致');
            $('.mm-ts').show();
            return false;
        }else{
            $('.mm-ts').hide();
        }

        glo.istrue = true;
        $.ajax({
            url: '<?=Url::toRoute('app/dis-pwd')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'_token':glo.data_token,'pwd':pwd,'rep_pwd':rep_pwd,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
            success:function (data) {
                glo.istrue = true;
                if(data.code != 0){
                    fail('密码修改失败')
                }else if(data.code==0){
                    $('.sub-two').css('background','#1890FF')
                    $('#sub-two').css('display','none');
                    // 提示登录 成功 跳转 到login
                    succ('密码设置成功，稍后返回登录');
                    back_url('<?=Url::toRoute("app/login")?>');//参数为login的地址 跳回到登录

                }

            }
        })

    });
</script>


</body>
</html>

