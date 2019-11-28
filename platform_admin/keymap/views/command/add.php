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
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'>Key:</label>
                        <input name="key" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入由(a-zA-Z0-9-_. )组成的4-32位字符">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'>Code:</label>
                        <input name="code" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入0x开头0-9a-f组成的4位字符">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'>版本:</label>
                        <input name="version" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入0x开头0-9a-f组成的4位字符">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'>模拟参数:</label>
                        <input name="analog_params" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入大于等于零的数字">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title'>正常参数:</label>
                        <input name="normal_params" type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入大于等于零的数字">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx" >
                        <label class='col-md-5 title-center' >供keymap使用:</label>
                        <select name="can_map"  class="form-control select2 select2-hidden-accessible col-md-7" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1" >是</option>
                            <option value="0" >否</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title sl'>只能在keymap中使用一次:</label>
                        <select name="canmap_once"  class="form-control select2 select2-hidden-accessible col-md-4" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1" >是</option>
                            <option value="0" >否</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-5 title-center' >操作手:</label>
                        <select name="op_style"  class="form-control select2 select2-hidden-accessible col-md-7" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1" >有</option>
                            <option value="0" >无</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-12 ">
                        <label class='col-md-12 title-center' style='text-align: left'>适用大类 :</label>
                        <div class="form-group col-md-12 input-xx">

                            <div class='radio-inline' style='flex-wrap:wrap'>
                                <div class="col-md-10">
                                    <input type="checkbox" name="category_id[]" class="minimal Applicable" value="0" id="settop"  />
                                    <label class='m-l-5 m-r-5'>
                                        通用(适用于所有大类)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 input-xx check-box-all">

                            <div class='radio-inline' style='flex-wrap:wrap'>
                                <?php foreach($category as $val):?>
                                    <div class="col-md-3 no-break ">
                                        <input type="checkbox" name="category_id[]" class="minimal" value="<?=$val['id']?>" id="settop"  />
                                        <label class='m-l-5 m-r-5 check_box_label'>
                                            <?=$val['name_en']?>
                                        </label>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'></label>
                        <div class='btn btn-block btn-primary submit-btn'>提交</div>
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
    var checkboxes = $('input[type="checkbox"].minimal');
    $('input[type="checkbox"].Applicable').on('ifClicked', function(event){
        var that = this;
        if(!$(that).is(':checked')){
            $('.check-box-all').hide()
            checkboxes.iCheck('uncheck');
        }else{
            $('.check-box-all').show()
        }

    });

    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });

    $('.submit-btn').unbind('click').click(function () {
        var key = $.trim($("input[name='key']").val()),
            key_reg = /^[a-zA-Z0-9\-\_\. ]{4,32}$/;
        if (!key_reg.test(key)) {
            fail('key是由(a-zA-Z0-9-_. )组成的4-32位字符');
            return false;
        }
        var reg = /^0x[0-9a-fA-F]{2}$/;
        if (!reg.test($.trim($("input[name='code']").val()))) {
            fail('code是0x开头0-9a-f组成的4位字符');
            return false;
        }
        if (!reg.test($.trim($("input[name='version']").val()))) {
            fail('版本是0x开头0-9a-f组成的4位字符');
            return false;
        }
        var num_reg = /^[0-9]{1,99}$/;
        if (!num_reg.test($.trim($("input[name='analog_params']").val()))) {
            fail('模拟参数必须是大于或者等于零的数字');
            return false;
        }
        if (!num_reg.test($.trim($("input[name='normal_params']").val()))) {
            fail('正常参数必须是大于或者等于零的数字');
            return false;
        }
        var check_flag = false;
        $.each($("input[name='category_id[]']"), function () {
            if ($(this).is(':checked')) {
                check_flag = true;
                return false;
            }
        });
        if (!check_flag) {
            fail('适用大类必选');
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
    })
</script>