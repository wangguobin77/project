<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-default" id="backToList">
                返回列表
            </button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">

                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input name="mid" type="hidden" id="mid" value="<?= $mid ?>">

                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>状态:</label>
                        <div class='radio-inline'>
                            <input type="radio" name="h_type" value="1" checked class="minimal">
                            <label class='m-l-5 m-r-5'>
                                终端设备
                            </label>
                            <input type="radio" name="h_type" value="2" class="minimal">
                            <label class='m-l-5'>
                                遥控设备
                            </label>

                        </div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title-center'>设备类型:</label>
                        <select name="h_id"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >请选择</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>生产数量:</label>
                        <input id='num' type="number" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" min="1" max="99999" name="batch_count"  placeholder="生产数量不大于99999">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>备注:</label>
                        <textarea maxlength="100" placeholder="限100字符" name="comment" class="form-control"></textarea>
                    </div>
                </div>

                <!--<div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title-center'>关联UCP:</label>
                        <input type="text" name="upc_code" class="form-control my-colorpicker1 colorpicker-element upc_code" autocomplete="off"  placeholder="请输入关联商品的upc">
                    </div>
                </div>-->

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'></label>
                        <button type="button" class='btn btn-block btn-primary' id="save">提交</button>
                    </div>
                </div>

            </form>

        </div>
    </section>
</div>
<!-- /.content-wrapper end-->

<?php include_once(NAV_DIR."/footer.php");?>

<!-- REQUIRED JS SCRIPTS -->
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>

<script>
    (function () {
        //返回列表
        $("#backToList").click(function () {
            window.location.href = "<?=url::toRoute(['batch/batch_info_list','mid'=>$mid])?>";
        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass   : 'iradio_minimal-blue'
        })

        //终端设备和遥控设备选项
        let _list = {
            "device": <?= json_encode($device_list) ?>,
            "remote": <?= json_encode($remote_list) ?>
        };

        //根据选择切换选项
        $('input[type="radio"].minimal').on('ifChecked', function(event){
            selectOption($(this).val());
        });

        //初始化页面内容
        let init = function () {
            //绑定事件
            bindEvent();

            //默认选择终端设备
            selectOption("1");

        };

        //绑定事件
        let bindEvent = function()
        {
            $("#save").bind('click', save);
        };

        let selectOption = function(type){
            let select = $("select[name='h_id']");
            select.empty();
            select.append('<option value="" >请选择</option>');
            switch (type) {
                case "1": //终端设备
                    $.each(_list.device, function (i, v) {
                        select.append('<option value="' + v['id'] + '">' + v['name'] + '</option>');
                    });
                    break;
                case "2": //遥控设备
                    $.each(_list.remote, function (i, v) {
                        select.append('<option value="' + v['id'] + '">' + v['name'] + '</option>');
                    });
                    break;
            }
            select.select2();
        };

        //保存
        let save = function()
        {
            // 选择类型 RC 遥控器
            if($("select[name='h_id']").val() == ''){
                fail('请选择设备类型!');
                return false;
            }

            // 数量限制
            let num=$('#num').val(),
                reg=/^[+]?\d+$/;
            if(num==''){
                fail('生产数量不能为空！');
                return false;
            }
            if(num<0 || num > 99999 || !reg.test(num)){
                fail('生产数量参数不正确！');
                return false;
            }

            // upc_code
            /*let upc_code=$('.upc_code').val();
            if(upc_code==''){
                fail('upc不能为空');
                return false;

            }*/
            /*if(upc_code.length!=13){
                fail('upc长度不正确');
                return false;
            }*/

            let data = $('#mymessage-form').serialize();
            $.ajax({
                url: '<?=Url::toRoute('batch/check_number')?>',
                type: 'post',
                dataType: 'json',
                data: data,
                success:function (data) {
                    if(data.code==0){
                        let src='<?=Url::toRoute(['batch/batch_info_list','mid'=>$mid])?>';
                        succ(data.message,src);
                    }else{
                        fail(data.message);
                    }
                }
            })

        };

        init();
    })();

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->


