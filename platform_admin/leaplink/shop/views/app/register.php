<?php
use yii\helpers\Url;
use common\consequence\ErrorCode;
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
    <link rel="stylesheet" href="css/v2/public/bootstrap.min.css">
    <link rel="stylesheet" href="css/v2/public/font-awesome.min.css">
    <link rel="stylesheet" href="css/v2/public/ionicons.min.css">
    <link rel="stylesheet" href="css/v2/public/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/v2/public/select2.min.css">
    <link rel="stylesheet" href="css/v2/public/admin.css">
    <link rel="stylesheet" href="css/v2/public/skin-blue.min.css">
    <link rel="stylesheet" href="css/v2/public/table.css">
    <link rel="stylesheet" href="css/v2/public/dialog.css">
    <link rel="stylesheet" href="css/v2/public/ts.css">
    <link rel="stylesheet" href="css/v2/public/tongyong.css">
    <link rel="stylesheet" href="css/v2/partner/shopreg.css">
    <link type="text/css"  href="css/v2/public/font-awesome.min.css">
    <!-- 日期控件 -->
    <link type="text/css" rel="stylesheet" href="css/public/jeDate-test.css">
    <link type="text/css" rel="stylesheet" href="css/public/jedate.css">
    <link type="text/css" rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/public/jedate.js"></script>
    <style type="text/css">.select-input>span.select2{
       width:34% !important;
    }</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" style='background:#fff;'>
    <section class="head-title"></section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 560px; margin-left:0;width:1200px;margin: 0 auto;">
        <!-- Main content -->
        <form id="sub-form">
             <section class="content container-fluid" style="padding:30px;">
                <div class='info col-md-12'>
                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                        <div class="row col-md-12">
                           <div class="col-md-9 info-input">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>手机号码:</label>
                                    <input type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入账号" name="phone" id="mobile">
                                    <span class="ts-des">* 手机号码不存在</span>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group col-md-12 input-xx">
                                <label class="col-md-3 title"><i>*</i>校验码:</label>
                                <div class="box-code form-control" style="border:0;" >
                                    <input type="text" class="form-control code" autocomplete="off" placeholder="请输入校验码"  name="code" id="code_var" >
                                    <button type="button" class='fsyym verification cursor' id="code" style='width: 110px;height: 40px;border: 1px solid rgba(0,0,0,.15);color: #36F;background:#fff;'>发送验证码</button>
                                </div>
                            </div>
                            <span class="ts-des">* 验证码格式错误</span>
                        </div>

                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>登录密码:</label>
                                    <input type="password" id='pwd' class="form-control password" autocomplete="off" placeholder="请输入密码" name="password">
                                    <span class="ts-des">* 密码格式错误</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>商户名称:</label>
                                    <input id="name" type="text" class="form-control name" autocomplete="off" placeholder="请输入商户名称" name="name">
                                    <span class="ts-des">* 商户名称不能为空</span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>商户类型:</label>
                                    <select class="form-control select2 class" style="width:100%;height:40px;border-radius:4px;" name='category'>
                                         <?php
                                            foreach ($category as $item):
                                                ?>
                                                <option value="<?=$item['category_id']?>"><?= $item['name']?></option>
                                            <?php endforeach; ?>
                                    </select>
                                    <span class="ts-des">* 商户类型不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>省市区:</label>
                                    <select name="code_p"  class="form-control  select2 select2-hidden-accessible col-md-3" id="province" onchange="change_p(this)"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="" >请选择</option>

                                    </select>
                                    <select name="code_c"  class="form-control select2 select2-hidden-accessible col-md-3" id="city" onchange="change_c(this)" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="" >请选择</option>

                                    </select>
                                    <select name="code_a"  class="form-control select2 select2-hidden-accessible col-md-3" id="area" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="" >请选择</option>

                                    </select>
                                    <span class="ts-des">* 详细地址不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>详细地址:</label>
                                    <input id="rpassword" type="text" class="form-control detail_address" autocomplete="off" placeholder="请输入详细地址 " name="address">
                                    <span class="ts-des">* 请输入详细地址</span>
                                </div>
                            </div>
                        </div>


                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>营业时间:</label>
                                    <div class="jeinpbox form-control" >
                                        <input type="text" class="jeinput time" id="testblue" placeholder="营业时间" style="border:0;height:30px;" name='time' autocomplete="off">
                                    </div>
                                </div>
                                <span class="ts-des">* 请输入营业时间</span>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx tp-view">
                                    <label class="col-md-3 title">店铺LOGO/门头照:</label>
                                    <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                        <div class="bg-icon">
                                            <i style="display:none;" class='close_box'>
                                                <span class="close-icon"></span>
                                                <img id="sczp" style="width:88px;height:88px" src="" align="top">
                                            </i>
                                            <input type="file"    value="" class="imgDom">
                                            <input type="hidden" class="logo imgV" name="logo">
                                            <img src="images/v2/scicon.png" class='add-img' alt="">
                                        </div>
                                    </div>
                                    <div class='des'></div>
                                </div>
                                <span class="ts-des" >* LOGO格式不正确</span>
                            </div>
                        </div>
                    </div>

                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;">
                        <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx tp-view">
                                        <label class="col-md-3 title">店铺插图:</label>
                                        <div class="form-control tpimg-box dianpu-img" style="height:88px;">
                                            <div class="bg-icon">
                                                <i style="display:none;" class='close_box'>
                                                    <span class="close-icon"></span>
                                                    <img id="img1" style="width:88px;height:88px" src="" align="top">
                                                </i>
                                                <input type="file"   class="imgDom"  value="">
                                                <input type="hidden" class="plate imgV" name="plate">
                                                <img src="images/v2/scicon.png" class='add-img' alt="">
                                            </div>
                                            <div class="bg-icon">
                                                <i style="display:none;" class='close_box'>
                                                    <span class="close-icon"></span>
                                                    <img id="img2"   style="width:88px;height:88px" src="" align="top">
                                                </i>
                                                <input type="file"  class="imgDom"  value="">
                                               <input type="hidden" class="plate imgV" name="plate">
                                                <img src="images/v2/scicon.png" class='add-img' alt="">
                                            </div>
                                            <div class="bg-icon">
                                                <i style="display:none;" class='close_box'>
                                                    <span class="close-icon"></span>
                                                    <img id="img2"   style="width:88px;height:88px" src="" align="top">
                                                </i>
                                                <input type="file" class="imgDom"  value="">
                                                <input type="hidden" class="plate imgV" name="plate">
                                                <img src="images/v2/scicon.png" class='add-img' alt="">
                                            </div>
                                            <div class="bg-icon">
                                                <i style="display:none;" class='close_box'>
                                                    <span class="close-icon"></span>
                                                    <img id="img2"   style="width:88px;height:88px" src="" align="top">
                                                </i>
                                                <input type="file" class="imgDom"  value="">
                                                <input type="hidden" class="plate imgV" name="plate">
                                                <img src="images/v2/scicon.png" class='add-img' alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="ts-des">* 店铺插图格式不正确</span>
                                </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx tp-view">
                                    <label class="col-md-3 title"><i>*</i>营业执照:</label>
                                    <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                        <div class="bg-icon">
                                            <i style="display:none;" class='close_box'>
                                                <span class="close-icon"></span>
                                                <img id="license_img" style="width:88px;height:88px" src="" align="top">
                                            </i>
                                            <input type="file" name="picurl"  onchange="imgUpload_sm(this)"  value="">
                                            <input type="hidden"  class="license imgV"  name="license">
                                            <img src="images/v2/scicon.png" class='add-img' alt="">
                                        </div>
                                    </div>
                                    <div class='des'>
                                       <!--  <span>1. 三证合一证件无需上传；</span>
                                        <span>2. 组织机构代码证必须在有效期范围内；</span>
                                        <span>3. 格式要求：原件照片、扫描件或复印件加盖企</span>
                                        <span>业公章后的扫描件；</span> -->
                                    </div>
                                </div>
                                <span class="ts-des">* 请上传营业执照</span>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx tp-view">
                                    <label class="col-md-3 title"><i>*</i>经营许可证:</label>
                                    <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                        <div class="bg-icon">
                                            <i style="display:none;" class='close_box'>
                                                <span class="close-icon"></span>
                                                <img id="certificate" style="width:88px;height:88px" src="" align="top">
                                            </i>
                                            <input type="file"  class="imgDom"   value="">
                                            <input type="hidden" class="certificate imgV" name="certificate">
                                            <img src="images/v2/scicon.png" class='add-img' alt="">
                                        </div>
                                    </div>
                                    <div class='des'></div>
                                </div>
                                <span class="ts-des">* 请上传经营许可证</span>
                            </div>
                        </div>
                        <!-- 提交操作区域 -->
                        <div class="row col-md-12" style="padding-left:200px !important;">

                             <div class="form-group col-md-3 input-xx" style="margin-right:16px;"">
                                <button type="button" class="btn btn-block btn-back" onclick='javascript:history.back();'>返回</button>
                            </div>
                            <div class="form-group col-md-3 input-xx">
                               <button type="button" class="btn btn-block btn-primary submit-btn" id="register-submit">提交</button>
                            </div>
                        </div>
                    </div>
                    <!-- 点击提交以后出现已提交资质审核 -->
                    <div class='checkbox' style="display:none;">
                        <div class="flex sec-step">
                            <img src="images/v2/okstep.png" alt="">
                            <h4 class='sec-title'>已提交审核</h4>
                            <span class="sec-des">信息已提交审核，请耐心等待</span>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->


<!-- 成功提示 -->
<div class="succ">
    <div class="ts-box">
        <div class="ts-xx ">
            <span class="font_family icon-judge_success_small glyphicon glyphicon-ok-circle"></span>
            <p>保存成功</p>

        </div>
    </div>
</div>

<!-- 失败提示 -->
<div class="fail" >
    <div class="fail-ts">
        <div class="ts-xx">
            <span class='font_family icon-warning_large glyphicon glyphicon-remove-circle' ></span>
            <p>保存失败，请重新尝试！</p>
        </div>
    </div>
</div>
<!-- jQuery 3 -->
<script src="js/public/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="js/public/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="js/public/adminlte.min.js"></script>

<!-- Select2 -->
<script   src="js/public/select2.full.min.js"></script>

<script   src="js/public/iCheck/icheck.min.js"></script>

<script   src="js/public/area.js"></script>

<script src='js/public/common.js'></script>
<!-- 提示信息函数 -->
<script src='js/public/ts.js'></script>
<script src="js/register/register.js"></script>

<!-- 图片上传组件 -->
<script src="layui/layui.js" charset="utf-8"></script>
<script src="js/public/uploader.js"></script>

</body>
<script>
    jeDate("#testblue",{
        format: "hh:mm",
        multiPane:false,
        range:"-",
        theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
    });
    var UPLOAD_URL = 'http://test.cloud.leaplink.cn/api/fs/upload/upload?dir=shop'; //上传地址
    var STATIC_REMOTE_DOMAIN = 'http://test.cloud.leaplink.cn';  //静态资源域名地址

    var glo = {
        'register_button': true, //为 false 时不可用再次提交表单
        'istrue':true, //为false 时不可以再次请求手机验证码
    }
    // 发送验证码
    $('.fsyym').click(function() {
        if(!glo.istrue){
            return false;
        }
        var mobile = $('#mobile').val();//需要验证手机格式
        if(mobile==''){
            fail('手机号码不能为空！');
            return
        }
        if(!fun.isMobile(mobile)){
            fail('手机格式不正确！');
            return
        }
        glo.istrue = false;

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

        //发送验证码
        $.ajax({
            url: '<?=Url::toRoute('app/send-phone-code')?>',
            type: 'post',
            dataType: 'json',
            data: {'mobile':mobile,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
            success:function (data) {
                if(data.code == 0) { //发送成功
                    succ('验证码发送成功!');
                }else if(data.code == <?=ErrorCode::EXISTS_PHONE?>){
                    fail('手机号已注册！');//已注册
                }else{
                    fail('验证码发送失败！');//验证码发送失败
                }
            }
        })
    });
    $("#register-submit").click(function () {
        if(!glo.register_button){ //防多次点击同时提交
            return false;
        }

        reg()
        if(reg_flag==false){
            glo.register_button = true;
            return false
        }
        $.ajax({
            url:'<?=Url::toRoute('app/register')?>',
            type:'post',
            dataType:'json',
            data:{data,_csrf:'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code=='100601'){
                    fail('验证码错误')
                    return
                }else if(data.code==0){
                    succ('注册成功','<?=Url::toRoute('app/login')?>')
                }

            },
            complete: function () {
                glo.register_button = true;
            }
        });
    })


</script>


