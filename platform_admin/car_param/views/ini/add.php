<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!--<link rel="stylesheet" href="../../../bower_components/select2/dist/css/select2.min.css">-->

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-default go_back_list">
                <?=Yii::t('app', 'BACK')?>
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'CHOOSE_CATEGORY')?>:</label>
                        <select name="category" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="0"><?=Yii::t('app', 'PLEASE_CHOOSE')?></option>
                            <?php foreach ($category as $item):?>
                                <option value="<?=$item['id']?>"><?=$item['cnname']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'INI_FILE_NAME')?>:</label>
                        <input name="filename" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" placeholder="请输入ini文件名">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'INI_FILE_DESC')?>:</label>
                        <textarea type="textarea" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="desc" placeholder="请输入ini文件描述" ></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-5 title'><?=Yii::t('app', 'INI_FILE_CONTENT')?>:</label>
                        <textarea type="textarea" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="content" placeholder="请输入ini文件内容" ></textarea>
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

    $('.submit-btn').unbind('click').click(function () {
        if ($("select[name='category']").val() == 0) {
            fail('分类必选');
            return false;
        }
        var name = $.trim($("input[name='filename']").val());
        if (!name || name.length > 32) {
            fail('ini文件名支持1-128个字符');
            return false;
        }
        var desc = $.trim($("textarea[name='desc']").val());
        if (!desc || desc.length > 255) {
            fail('ini文件描述支持1-255个字符');
            return false;
        }
        var content = $.trim($("textarea[name='content']").val());
        if (!content || content.length > 6*1024) {
            fail('ini文件内容支持1-6*1024个字符');
            return false;
        }
        var data = $('#mymessage-form').serialize();
        $.ajax({
            url:'<?=Url::toRoute('add_ajax')?>',
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