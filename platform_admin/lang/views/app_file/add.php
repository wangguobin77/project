<?php
use yii\helpers\Url;
?>
<link rel="stylesheet" href="/static/css/public/department-add.css">
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/lang/add-factory.css">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn go_back_list btn-default">返回</button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="app_file_form">
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class="col-md-3 title">所属项目:</label>
                        <select name="app_id" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach($app as $val):?>
                                <option value="<?= $val['id']?>"><?= $val['app_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-3 title'>文件地址:</label>
                        <input name='file_path' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入文件地址" >
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-3 title'>文件名:</label>
                        <input name="file_name" type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入文件名" >
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class="col-md-3 title">类型:</label>
                        <select name="type" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="1">php</option>
                            <option value="2">Android</option>
                            <option value="3">U3D</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="row col-md-12">
            <!-- 提交 按钮 -->
            <div class="col-md-6 col-md-offset-1">
                <div class="form-group col-md-6 input-xx">
                    <button type="button" class="btn btn-block btn-primary submit-btn lang_app_file_submit_btn">提交</button>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- REQUIRED JS SCRIPTS -->
<script src="/static/js/public/jquery.min.js"></script>
<script src="/static/js/public/bootstrap.min.js"></script>
<script src="/static/js/public/adminlte.min.js"></script>
<script src="/static/js/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>
<script src="/static/js/public/dialog.js"></script>
<script src='/static/js/public/ts.js'></script>
<script src='/static/js/public/common.js'></script>
<script type="text/javascript">
    $('.select2').select2();
    /*
    提交按钮事件
     */
    //添加
    $('.lang_app_file_submit_btn').unbind('click').click(function () {
        var data={};
        data.file_path = $.trim($("input[name='file_path']").val());
        if (data.file_path.length < 1 || data.file_path.length > 255) {
            fail('请输入1-255位的文件地址');
            return false;
        }
        data.file_name = $.trim($("input[name='file_name']").val());
        if (data.file_name.length < 1 || data.file_name.length > 255) {
            fail('请输入1-255位的文件名称');
            return false;
        }
        data.type = $("select[name='type']").val();
        data.app_id = $("select[name='app_id']").val();
        if (!data.app_id) {
            fail('所属项目必选');
            return false;
        }
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
        $.ajax({
            url:'<?=Url::toRoute('add')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                if(data.code != 0){
                    fail(data.message);
                }else{
                    succ(data.message, '<?=url::toRoute('list')?>');
                }
            }
        })
    });

    //返回列表
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });
</script>