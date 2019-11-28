<?php
use yii\helpers\Url;
use common\consequence\ErrorCode;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LeapFone</title>
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
    <link rel="stylesheet" href="css/public/tongyong.css">
    <link rel="stylesheet" href="css/login/register.css">
    <!-- 日期控件 -->
    <link type="text/css" rel="stylesheet" href="css/public/jeDate-test.css">
    <link type="text/css" rel="stylesheet" href="css/public/jedate.css">
    <link type="text/css" rel="stylesheet" href="layui/css/layui.css">
    <script type="text/javascript" src="js/public/jedate.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" style='background:#fff;'>
    <section class="content-header" style="padding:0 20px;background:#367fa9;">
        <h1 style="line-height:60px;color:#fff;">
            LeapFone
            <small style="color:#fff;">注册</small>
            <a style="float:right;font-size:18px;color:#fff;" href='javascript:history.back();'>
                返回
            </a>
        </h1>
    </section>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 560px;">
        <!-- Main content -->
        <form id="sub-form">
            <section class="content container-fluid">
                <div class="row col-md-12">

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title">账号:</label>
                            <input type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入账号" name="phone" id="mobile">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title">校验码:</label>
                            <div class="box-code form-control" >
                                <input type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入校验码" name="code" id="code_var" style="border:0;">
                                <button type="button" class='btn btn-primary  btn-flat fsyym verification cursor' id="code" >发送校验码</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title">密码:</label>

                            <input type="text" class="form-control my-colorpicker1 colorpicker-element password" autocomplete="off" placeholder="请输入密码" name="password">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title">名称:</label>

                            <input type="text" class="form-control my-colorpicker1 colorpicker-element name" autocomplete="off" placeholder="请输入名称" name="name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group col-md-12 input-xx">
                            <label class="col-md-3 title">商户类别</label>
                            <select class="form-control select2 class" style="width: 100%;" name='category'>
                                <?php
                                foreach ($category as $item):
                                    ?>
                                    <option value="<?=$item['category_id']?>"><?= $item['name']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx select-input">
                            <label class='col-md-3 title'>所在地区:</label>
                            <select name="code_p"  class="form-control  select2 select2-hidden-accessible col-md-3" id="province" onchange="change_p(this)"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" >请选择</option>

                            </select>
                            <select name="code_c"  class="form-control select2 select2-hidden-accessible col-md-3" id="city" onchange="change_c(this)" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" >请选择</option>

                            </select>
                            <select name="code_a"  class="form-control select2 select2-hidden-accessible col-md-3" id="area" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option value="" >请选择</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title">详细地址</label>
                            <input type="text" class="form-control detail_address" autocomplete="off" placeholder="详细地址" name="address">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class=" form-group col-md-12 input-xx" style="width:100%;display:flex;">
                            <label class="col-md-3 title">营业时间:</label>
                            <div class="jeinpbox form-control" >
                                <input type="text" class="jeinput time" id="testblue"  name='time' autocomplete="off" placeholder="请选择营业时间" style="border:0;" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 店铺图片上传 -->
                <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">店铺LOGO(选填):</h4>
                            <div class="col-md-8 tpimg-b">
                                <div class="tpimg-box">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="logo imgV" name="logo">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>
                                <!--       <p class="img-des col-md-9">
                                          上传的文件大小不能超过2M
                                      </p> -->
                                <!-- 上传图片提示信息 -->
                                <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                    *请上传满足要求的图片
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">店铺插图(选填):</h4>
                            <div class="col-md-8 tpimg-b">
                                <div class="tpimg-box dianpu-img">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="plate imgV" name="plate">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>

                                <div class="tpimg-box dianpu-img">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="plate imgV" name="plate">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>

                                <div class="tpimg-box dianpu-img">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="plate imgV" name="plate">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>

                                <div class="tpimg-box dianpu-img">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="plate imgV" name="plate">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>

                                <!-- 上传图片提示信息 -->
                                <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                    *请上传满足要求的图片
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- 营业执照 -->
                <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">营业执照:</h4>
                            <div class="col-md-8 tpimg-b">
                                <div class="tpimg-box">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="license imgV" name="license">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>
                                <!--       <p class="img-des col-md-9">
                                          上传的文件大小不能超过2M
                                      </p> -->
                                <!-- 上传图片提示信息 -->
                                <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                    *请上传满足要求的图片
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 经营许可证 -->
                <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">经营许可证:</h4>
                            <div class="col-md-8 tpimg-b">
                                <div class="tpimg-box">
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style="width:100px;height:100px" src="" align="top">
                                        </i>
                                        <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                        <input type="hidden" class="certificate imgV" name="certificate">
                                        <img src="images/addicon_big.png" class='add-img' alt="">
                                    </div>
                                </div>
                                <!--       <p class="img-des col-md-9">
                                          上传的文件大小不能超过2M
                                      </p> -->
                                <!-- 上传图片提示信息 -->
                                <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                    *请上传满足要求的图片
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row col-md-12">
                    <div class="row col-md-12">
                        <!-- 提交 按钮 -->
                        <div class="col-md-6 col-md-offset-1">
                            <div class="form-group col-md-6 input-xx">
                                <button type="button" class="btn btn-block btn-primary submit-btn" id="register-submit">提交</button>
                            </div>
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
<div class="succ" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:1000;">
    <div class="ts-box" style="z-index:10000;display:block;">
        <div class="ts-xx ">
            <span class="font_family icon-judge_success_small"></span>
            <p>保存成功</p>

        </div>
    </div>
</div>

<!-- 失败提示 -->
<div class="fail" style="position:fixed;top:0;bottom:0;left:0;right:0;display: none;z-index:1000;">
    <div class="fail-ts">
        <div class="ts-xx">
            <span class='font_family icon-warning_large'></span>
            <p>保存失败，请重新尝试！</p>
        </div>
    </div>
</div>

<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close cursor"></span>
        <img src="images/warning-large.png" alt="">
        <h6>是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default"> 取消</div>
            <div class="cursor btn btn-primary confirm">确认</div>

        </div>

    </div>
</div>
<!--tishi-->

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


