<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/public/select2.min.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<style type="text/css">
    .select2-container--default .select2-selection--single{
        height:34px;
        box-sizing:border-box;
        border:1px solid #d2d6de;
    }
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default go_back_list">
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">
                <input type="hidden" name="id" value="<?=$info['id']?>">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>设备中文名称:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['name']?>"  placeholder="请输入中文名称(支持2-128位字符)" name='name'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>设备英文名称:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['name_en']?>"  placeholder="请输入英文名称(支持2-128位字符)）" name='name_en'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>设备型号:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['type']?>"  placeholder="请输入设备型号(支持2-128位字符)" name="type" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>归属大类:</label>
                        <select name="category_id"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach ($category as $item):?>
                                <option value="<?=$item['id']?>" <?php if ($item['id'] == $info['category_id']) echo 'selected';?> ><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>厂商:</label>
                        <select name="manufacture_id"   class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach ($manufacture as $item):?>
                                <option value="<?=$item['id']?>" <?php if ($item['id'] == $info['manufacture_id']) echo 'selected';?> ><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>

                    </div>
                </div>
                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <div class='radio-inline'>
                            <input name="status" type="radio" value='0' id="female1" name="r1" class="minimal" <?php if($info['status']==0) echo 'checked';?>>
                            <label for="female1" class='m-l-5 m-r-5'>
                                研发中
                            </label>
                            <input name="status" type="radio" value='1' id="female2" name="r1" class="minimal" <?php if($info['status']==1) echo 'checked';?>>
                            <label for="female2" class='m-l-5 m-r-5'>
                                已发布
                            </label>
                            <input name="status" type="radio" value='2' id="female3" name="r1" class="minimal" <?php if($info['status']==2) echo 'checked';?>>
                            <label for="female3" class='m-l-5 m-r-5'>
                                已停产
                            </label>
                        </div>

                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title-center' style='width:14%!important'>备注(选填):</label>
                        <textarea style='height:200px' type="text"  name="description" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息" ><?=$info['description']?></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'></label>
                        <input type="button" class='btn btn-block btn-primary submit-btn' value="保存">
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>
<script src="/static/js/public/dialog.js"></script>

<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2();
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    //
    $('input[type="radio"].minimal').on('ifChecked', function(event){

    });

    //返回列表
    $('.go_back_list').unbind('click').click(function () {
        var url;
        url = '<?=Yii::$app->request->get('mid')?>' ? '<?=url::toRoute(['list', 'mid'=>Yii::$app->request->get('mid')])?>' : '<?=url::toRoute('list')?>';
        location.href = url;
//        location.href = '<?//=url::toRoute('list')?>//';
    });

    //修改
    $('.submit-btn').unbind('click').click(function(){
        /* 中文名称*/
        var name = $.trim( $("input[name='name']").val() );
        if( !name || name.length > 128 || name.length < 2) {
            fail('中文名称长度应在2-128个字符之间');
            return false;
        }

        /*英文名称*/
        var name_en = $.trim( $("input[name='name_en']").val() );
        if( !name_en || name_en.length > 128 || name_en.length < 2) {
            fail('英文名称长度应在2-128个字符之间');
            return false;
        }

        /*设备型号*/
        var type = $.trim( $("input[name='type']").val() );
        if( !type || type.length > 128 || type.length < 2 ) {
            fail('类型长度应在2-128个字符之间');
            return false;
        }

        /*备注信息*/
        var description = $.trim( $("input[name='description']").val() );
        if( description.length > 6*1024 ) {
            fail('描述长度应小于6k');
            return false;
        }

        var data = $('#mymessage-form').serialize();

        $.ajax({
            url:'<?=url::toRoute('edit')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                console.log(data);
                if(data.code == 0){
                    succ(data.message,'');
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        });
    });
</script>