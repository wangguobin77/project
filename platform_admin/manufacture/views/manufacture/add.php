<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/manufacture/add-factory.css">
<link rel="stylesheet" href="/static/css/public/select2.min.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-default go_back_list">
                返回
            </button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="manufacture_form">

                <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                    <div class="row content-wrap">
                        <div class="col-md-6 row-xx">
                            <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">公司logo：</h4>
                            <div class="col-md-8 tpimg-b">
                                <div class='tpimg-box'>
                                    <div class="bg-icon">
                                        <i style="display:none;">
                                            <span class="close-icon"></span>
                                            <img style='width:100px;height:100px'  src="" align="top">
                                        </i>
                                        <input type="file" name="picurl" accept="image/png, image/jpg" onchange="imgUpload_sm(this)" data-width="800px" data-height="800px"/>
                                        <input type="hidden" name="logo">
                                        <img src="/static/images/addicon_big.png" alt="">
                                    </div>
                                </div>
                                <p class="img-des col-md-9">
                                    上传的文件大小不能超过2M
                                </p>
                                <!-- 上传图片提示信息 -->
                                <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                    *请上传满足要求的图片
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>中文名称:</label>
                        <input name='name' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入中文名称" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>英文名称:</label>
                        <input name='name_en' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入英文名称" >
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>登录帐号:</label>
                        <input name="login_name" type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入登录账号" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>联系人:</label>
                        <input  name="linkman" type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"
                                placeholder="请输入联系人" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>密码:</label>
                        <input name="password"  type="password" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入密码" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>确认密码:</label>
                        <input name="repeat_password" type="password" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入确认密码" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>联系电话(选填):</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  name="contact_info" placeholder="请输入联系电话" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>邮箱:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="email" placeholder="请输入邮箱" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>手机号:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="mobile" placeholder="请输入手机号" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>地址(选填):</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="address"  placeholder="请输入地址" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>网址(选填):</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="home_page" placeholder="请输入网址" >
                    </div>
                </div>
                <div class="col-md-6" style='height:54px'>

                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>备注(选填):</label>
                        <textarea type="textarea" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="description" placeholder="请输入备注" ></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="row col-md-12">
            <!-- 提交 按钮 -->
            <div class="col-md-6 col-md-offset-1">
                <div class="form-group col-md-6 input-xx">
                    <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>

<script type="text/javascript">
    //返回列表页面
    $('.go_back_list').unbind('click').click(function () {
        location.href = '<?=url::toRoute('list')?>';
    });

    $('.submit-btn').unbind('click').click(function(){
        //中文名称
        var name=$.trim($("input[name='name']").val());
        if( name.length > 128 || name.length < 2 ) {
            fail('中文名称应在2-128个字符之间');
            return false;
        }
        //英文名称
        var ename = $.trim($("input[name='name_en']").val());
        if( ename.length > 128 || ename.length < 2 ) {
            fail('英文名称应在2-128个字符之间');
            return false;
        }
        //登录名
        var login_name = $.trim($("input[name='login_name']").val());
        if( login_name.length > 32 || login_name.length < 4 || login_name.indexOf(" ") != -1) {
            fail('登录名应在4-32个字符之间，且不应有空格');
            return false;
        }
        //密码
        var password = $("input[name='password']").val(),
            reg_pwd=/^.{6,20}$/;// /^.{2,4}$/
        if(!reg_pwd.test(password) || password=='' || password.indexOf(" ") != -1) {
            fail('密码应在6-20个字符之间，且不能有空格');
            return false;
        }
        //确认密码
        if( password !== $("input[name='repeat_password']").val() ) {
            fail('两次密码输入不一致');
            return false;
        }

        //联系人
        var linkman = $.trim($("input[name='linkman']").val());
        if( linkman.length > 128 || linkman.length < 1 ) {
            fail('联系人应在1-128个字符之间');
            return false;
        }

        //邮箱
        var email = $("input[name='email']").val(),
            reg_email = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,8}$/;
        if( !reg_email.test(email) ) {
            fail('邮箱格式不正确');
            return false;
        }

        //手机 选填
        var mobile = $("input[name='mobile']").val(),
            reg_mobile = /^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/;
        if (!mobile) {
            fail('手机号不能为空');
            return false;
        }
        if(!reg_mobile.test(mobile) ) {
            fail('手机格式不正确');
            return false;
        }
        //地址
        var address = $("input[name='address']").val();
        if( address.length > 128 ) {
            fail('地址应小于128个字符');
            return false;
        }
        //主页
        var home_page = $("input[name='home_page']").val();
        if( home_page.length > 128 ) {
            fail('主页应小于129个字符');
            return false;
        }
        //备注
        var description = $("textarea[name='description']").val();
        if( description.length > 2*1024 ) {
            fail('描述最多支持2k的长度');
            return false;
        }

        var data = $('#manufacture_form').serialize();

        $.ajax({
            url:'<?=Url::toRoute('add')?>',
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if( data.code == 0 ) {
                    var src='<?=url::toRoute('list')?>';
                    succ(data.message,src);
                } else {
                    fail(data.message);
                }
            }
        });
    });

    //上传图片
    function imgUpload_sm(e) {
        var t = e.getAttribute("data-width"),
            a = e.getAttribute("data-height"),
            r = [],
            o = $(".imgs");
        r.push(o);
        var result = e.files[0];
        var s = result.size;
        if (s > 1024*1024*2){
            $('.img-des-zc').hide();
            $('.img-ts').show();
            return false;
        }else{
            $('.img-ts').hide();
        }
        var n = new FileReader;
        n.onload = function(o) {
            var l = o.target.result;
            var s = new Image;
            s.onload = function() {
//                var o = s.width + "px",
//                    n = s.height + "px";
//                if (o > t && n > a) return $('.img-ts').show(),
//                    $('input[name="picurl"]').val(null),
//                    !1;
                $.ajax({
                    url:'<?=Url::toRoute('logo')?>',
                    type:'post',
                    dataType:'json',
                    data:{'file':l,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
                    success: function(data) {
                        console.log(data);
                        if( data.code == 0 ) {
                            $(e).next().val(data.message);
                            $(e).prev().find('img').attr('src', data.message);
                            $(e).next().next().remove();
                            $(e).prev().show();
                        } else {
                            fail(data.message);
                        }
                    }
                });

            };
            s.src = l
        };
        n.readAsDataURL(result)
    }
</script>