<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<link rel="stylesheet" href="/static/css/anti_fake/load.css">


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
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-4 title'>分类:</label>
                        <select name="category_id"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach($category as $item):?>
                                <option value="<?=$item['id']?>"><?=$item['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-4 title'>准备上传的文件:</label>
                        <input type="text" id="csvx" placeholder="请选择文件···" readonly="readonly" style="vertical-align: middle;" class="form-control col-md-9"/>
                        <input type="file" name="csv" id="csv"/ style='display:none;'>
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
    $(function(){
        $("#csvx").click(function(){
            $("input[type='file']").trigger('click');
        });
        $("input[type='file']").change(function(){
            $("#csvx").val($(this).val());
        });
    });
</script>
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
        var fd = new FormData(document.getElementById("mymessage-form"));
        $.ajax({
            url: "<?=url::toRoute('csv_load')?>",
            type: "POST",
            data: fd,
            processData: false,  // 告诉jQuery不要去处理发送的数据
            contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
            success:function (data) {
//                 console.log(data);
                if(data.code == 0){
                    succ(data.message, '<?=url::toRoute('list')?>');
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        });
    })
</script>