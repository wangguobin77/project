<?php
use yii\helpers\Url;
use common\consequence\ErrorCode;
?>
<link rel="stylesheet" type="text/css" href="css/v2/partner/reg.css">
<?php include_once(NAV_DIR."/header.php");?>

<!-- Content Wrapper. Contains page content -->

 <div class="content-wrapper" style="min-height: 560px;">
    <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <section class='row'>
            <span class='yj'>设置</span><span class='yj'>/</span><span class='yj'>修改密码</span>
        </section>
        <h5 class="zhubt">修改密码</h5>

    </div>
    <!-- Main content -->
    <section class="content container-fluid">
        <div class='info col-md-12' style="background:#fff;flex-direction:flex;">
            <form class="info-form" id="sub-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class='col-md-12 info-wrap'>
                    <div class='col-md-12 info-wrap'>
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>登录密码:</label>
                                    <input name="old-pwd" id="old-pwd" type="password" class="form-control " autocomplete="off" placeholder="请输入登录密码">
                                </div>
                                <span class="ts-des">* 登录密码不正确</span>
                            </div>
                        </div>
                        <!-- 登录密码 -->
                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>新密码:</label>
                                    <input name="new-pwd" id="new-pwd" type="password" class="form-control " autocomplete="off" placeholder="请输入新密码">
                                </div>
                                <span class="ts-des">* 密码格式不正确</span>
                            </div>
                        </div>

                        <div class="row col-md-12">
                           <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>确认新密码:</label>
                                    <input name="renew-pwd" id="renew-pwd" type="password" class="form-control " autocomplete="off" placeholder="请再次输入密码">
                                </div>
                                <span class="ts-des">* 两次密码输入不一致</span>
                            </div>
                        </div>

                        <!-- 提交操作区域 -->
                        <div class="row col-md-12" style="padding-left:200px !important;margin-top:40px;">
                            <div class="form-group col-md-3 input-xx">
                                <button type="button" class="btn btn-block btn-primary"  onclick="put()">确定</button>
                            </div>
                             <div class="form-group col-md-3 input-xx" style="margin-right:16px;"">
                                <button type="button" class="btn btn-block btn-back" onclick="reset_form()">重置</button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </section>
</div>

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

</body>

<script type="text/javascript">
    /**
     * 信息提交
     */
    const put = () =>{

        var old_pwd = $('#old-pwd').val();//登录密码
        var new_pwd = $('#new-pwd').val();//新密码
        var renew_pwd = $('#renew-pwd').val();//确认新密码

        if ( old_pwd==''){
            fail('登录密码不能为空');
            return false;
        }
        if ( new_pwd==''){
            fail('新密码不能为空');
            return false;
        }
        if ( renew_pwd==''){
            fail('确认新密码不能为空');
            return false;
        }
        if ( renew_pwd!=new_pwd){
            fail('确认新密码和新密码不一致');
            return false;
        }

        var data =$('#sub-form').serializeObject();
        $.ajax({
            url:'<?=Url::toRoute('shop/up-password')?>',
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(data.code==0){
                    succ('修改成功')
                    location.href = ""
                }else if(data.code == <?=ErrorCode::CORRECT_PASSWORD?>){
                    fail('登录密码错误')
                }else {
                    fail('修改失败')
                }
            }
        })
    };

    const reset_form = () => {
        $('input:visible').val('');
    };

</script>