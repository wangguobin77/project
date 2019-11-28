<?php
use yii\helpers\Url;
use common\consequence\ErrorCode;
?>

<?php include_once(NAV_DIR."/header.php");?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 560px;">
    <!-- Main content -->
    <section class="content container-fluid">
        <form id="sub-form">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
            <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">登录密码:</label>
                        <input type="password" class="form-control my-colorpicker1 colorpicker-element name" name="old-pwd" id="old-pwd">
                    </div>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">新密码:</label>
                        <input type="password" class="form-control my-colorpicker1 colorpicker-element name" name="new-pwd" id="new-pwd">
                    </div>
                </div>
            </div>
            <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">确认新密码:</label>
                        <input type="password" class="form-control my-colorpicker1 colorpicker-element name" name="renew-pwd" id="renew-pwd">
                    </div>
                </div>
            </div>

        </form>
        <div class="row col-md-12">
            <div class="row col-md-12">
                <!-- 提交 按钮 -->
                <div class="col-md-6 col-md-offset-1">
                    <div class="form-group col-md-1 input-xx">
                        <button type="button" class="btn btn-block btn-primary submit-btn" onclick="put()">提交</button>
                    </div>
                    <div class="form-group col-md-1 input-xx">
                        <button type="button" class="btn btn-block btn-primary submit-btn" onclick="reset_form()">重置</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->

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