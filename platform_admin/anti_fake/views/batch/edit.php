<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">


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
                <input type="hidden" name="id" value="<?=$info['id']?>">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <select name="status" <?php if($info['status'] != 0) echo 'disabled';?>  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
<!--                            <option value="">待审批</option>-->
                            <option value="1" <?php if($info['status'] == 1) echo 'selected';?> >通过</option>
                            <option value="2" <?php if($info['status'] == 1) echo 'selected';?> >不通过</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>序号:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" disabled autocomplete="off" value="<?=$info['id']?>" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>分类:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" disabled autocomplete="off" value="<?=$info['name']?>" name="category_id" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>数量:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" disabled autocomplete="off" value="<?=$info['quantity']?>" name="quantity" >
                    </div>
                </div>
                <?php if($info['status'] == 0):?>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-4 title'></label>
                            <input type="button" value="提交" class='btn btn-block btn-primary submit-btn submit_btn'>
                        </div>
                    </div>
                <?php endif;?>
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
        location.href = '<?=url::toRoute('list')?>';
    });

    $('.submit_btn').unbind('click').click(function () {
        var data = $('#mymessage-form').serialize();
        $.ajax({
            url:'<?=url::toRoute('review')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                 console.log(data);
                if(data.code == 0){
                    succ(data.message, '<?=url::toRoute('list')?>');
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    })
</script>
</body>
</html>