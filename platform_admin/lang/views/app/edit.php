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
            <button type="button" class="btn  btn-default go_back_list">返回</button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="lang_app_form">
                <input type="hidden" name="id" value="<?=$info['id']?>">
                <div class="col-md-12">
                    <div class="form-group col-md-6 input-xx">
                        <label class='col-md-3 title'>域名:</label>
                        <input name='app_name' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" value="<?=$info['app_name']?>" autocomplete="off"  placeholder="请输入域名名称" >
                    </div>
                </div>

                <div class="col-md-12">
                    <h3 style='border-bottom: 1px solid #E4E4E4;
'>语言</h3>
                    <div class="form-group col-md-12 input-xx">
                        <div class='radio-inline' style='flex-wrap:wrap'>
                            <?php foreach ($language as $item):?>
                                <div class="col-md-3">
                                    <input type="checkbox" name="language[]" class="minimal" value="<?=$item['id']?>" <?php if(in_array($item['id'], $info['language'])) echo 'checked';?> />
                                    <label class='m-l-5 m-r-5 check_box_label'><?=$item['lang_show']?></label>
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row col-md-12">
            <!-- 提交 按钮 -->
            <div class="col-md-6 col-md-offset-1">
                <div class="form-group col-md-6 input-xx">
                    <div type="button" class="btn btn-block btn-primary submit-btn lang_app_submit_btn">提交</div>
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

<script type="text/javascript">
    $('.select2').select2();
    /*
    提交按钮事件
     */
    //添加
    $('.lang_app_submit_btn').unbind('click').click(function () {
        var data={};
        data.app_name = $.trim($("input[name='app_name']").val());
        if (data.app_name.length < 1 || data.app_name.length > 64) {
            fail('请输入1-64位的域名');
            return false;
        }
        data.language = [];
        $.each($("input[name='language[]']"), function () {
            if ($(this).is(':checked')) {
                data.language.push($(this).val());
            }
        });
        if (data.language.length == 0) {
            fail('语言必选');
            return false;
        }
        data.id = $("input[name='id']").val();
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
        $.ajax({
            url:'<?=Url::toRoute('edit')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                if(data.code != 0){
                    fail(data.message);
                }else{
                    succ(data.message);
                }
            }
        })
    });

    //返回列表
    $('.go_back_list').unbind('click').click(function(){
       location.href = '<?=url::toRoute('list')?>';
    });

</script>
</body>
</html>