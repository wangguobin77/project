<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

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
                        <label class='col-md-4 title'>遥控器中文名称:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['name']?>"  placeholder="请输入中文名称(支持2-128位字符)" name='name'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>遥控器英文名称:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['name_en']?>"  placeholder="请输入英文名称(支持2-128位字符)" name='name_en'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>遥控器型号:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['type']?>" placeholder="请输入设备型号(支持2-128位字符)" name="type" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>厂商:</label>
                        <select name="manufacture_id"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach($manufacture as $item):?>
                                <option value="<?=$item['id']?>" <?php if($item['id']==$info['manufacture_id']) echo 'selected';?>><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>Key:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['key']?>"  placeholder="4位数字字母"  name="key"  >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>Code:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" value="<?=$info['code']?>"  placeholder="4位以0x开头的数字字母组成，不区分大小写" name="code"  >
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>屏幕:</label>
                        <select name="screen"   class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach(Yii::$app->params['screen'] as $key=>$val):?>
                                <option value="<?=$key?>" data-code="<?=$val?>" <?php if($info['screen']==$key) echo 'selected';?> ><?=$key?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>手持方式:</label>
                        <select name="carry_type"   class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach(Yii::$app->params['carry_type'] as $key=>$val):?>
                                <option value="<?=$key?>" data-code="<?=$val?>" <?php if($info['carry_type']==$key) echo 'selected';?> ><?=$key?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <div class='radio-inline'>
                            <input name="status" type="radio" value='0' id="female1" class="minimal" <?php if($info['status']==0) echo 'checked';?>>
                            <label for="female1" class='m-l-5 m-r-5'>
                                研发中
                            </label>

                            <input name="status" type="radio" value='1' id="female2" class="minimal" <?php if($info['status']==1) echo 'checked';?>>
                            <label for="female2" class='m-l-5 m-r-5'>
                                已发布
                            </label>

                            <input name="status" type="radio" value='2' id="female3" class="minimal" <?php if($info['status']==2) echo 'checked';?>>
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
                        <input type="button" value="提交" class='btn btn-block btn-primary submit-btn submit_btn'>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/dialog.js"></script>
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
    $('input[type="radio"].minimal').on('ifChecked', function(event){

    });

    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        var url;
        url = '<?=Yii::$app->request->get('mid')?>' ? '<?=url::toRoute(['list', 'mid'=>Yii::$app->request->get('mid')])?>' : '<?=url::toRoute('list')?>';
        location.href = url;
//        location.href = '<?//=url::toRoute('list')?>//';
    });

    $('.submit_btn').unbind('click').click(function () {
        var name = $.trim( $("input[name='name']").val() );
        if (name.length > 128 || name.length < 2) {
            fail('中文名称长度应在2-128个字符之间');
            return false;
        }

        var name_en = $.trim( $("input[name='name_en']").val() );
        if (name_en.length > 128 || name_en.length < 2) {
            fail('英文名称长度应在2-128个字符之间');
            return false;
        }

        var type = $.trim( $("input[name='type']").val() );
        if (type.length > 128 || type.length < 2) {
            fail('遥控器型号长度应在2-128个字符之间');
            return false;
        }

        var key = $.trim( $("input[name='key']").val() );
        if (key.length !==4) {
            fail('key必须是由4位数字字母组成');
            return false;
        }

        var code = $.trim( $("input[name='code']").val() ),
            reg_key = /^([0]{1})+([xX]{1})+([0-9a-fA-F]{2})$/;
        if( !reg_key.test(code)) {
            fail('code应是0x开头0-9a-fA-F结束之间的4位字符');
            return false;
        }

        var data = $('#mymessage-form').serialize();
        $.ajax({
            url:'<?=url::toRoute('edit')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                 console.log(data);
                if(data.code == 0){
                    succ(data.message);
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    })
</script>