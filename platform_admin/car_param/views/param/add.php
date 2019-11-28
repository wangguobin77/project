<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<link rel="stylesheet" href="/static/css/public/select2.min.css">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li>
                    <a href="javacript:;">
                        <?php
                            if (!Yii::$app->request->get('pid')) {
                                echo '顶级参数';
                            } else {
                                echo '上级参数：'.$parent['cnname'];
                            }
                        ?>
                    </a>
                </li>
                <span>
                    <button type="button" class="btn go_back_list btn-default" style="margin-top: -7px;"><?=Yii::t('app', 'BACK')?></button>
                </span>
            </ol>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <input type="hidden" name="parent_id" value="<?=isset($info['parent_id'])?$info['parent_id'] : (Yii::$app->request->get('pid')?:0)?>">
                <input type="hidden" name="id" value="<?=isset($info['id']) ? $info['id']:Yii::$app->request->get('id')?:0?>">
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'PARAM_CNNAME')?>:</label>
                        <input name="cnname" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入参数名（中文）">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'PARAM_NAME')?>:</label>
                        <input name="param_name" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入参数名（英文）">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'PARAM_VALUE')?>:</label>
                        <input name="param_value" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入参数值">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'></label>
                        <div class='btn btn-block btn-primary submit-btn'><?=Yii::t('app', 'SUBMIT')?></div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>

<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2();
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    //
    $('input[type="checkbox"].minimal').on('ifChecked', function(event){
        console.log( $(this).val())
    });
    $('input[type="checkbox"].Applicable').on('ifClicked', function(event){
        var that = this;
        setTimeout(function(){
            if($(that).is(':checked')){
                $('.check-box-all').show()
            }else{
                $('.check-box-all').hide()
            }
        },100)
    });

    $('.submit-btn').unbind('click').click(function () {
        var cname = $.trim($("input[name='cnname']").val());
        if (!cname || cname.length>128) {
            fail('参数名（中文）长度应在1-128个字符之间');
            return false;
        }
        var name = $.trim($("input[name='param_name']").val());
        if (!name || name.length>128) {
            fail('参数名（英文）长度应在1-128个字符之间');
            return false;
        }
        var value = $.trim($("input[name='param_value']").val());
        if (!value || value.length>128) {
            fail('参数值长度应在1-128个字符之间');
            return false;
        }
        var data = $('#mymessage-form').serialize();
        var url_ = '<?=Url::toRoute('add')?>';
        $.ajax({
            url:url_,
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                // console.log(data); return false;
                if(data.code != 0){
                    fail(data.message);
                }else{
                    succ(data.message,'<?=Url::toRoute('list')?>');
                }
            }
        })
    });
    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });

</script>