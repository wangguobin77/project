
<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" type="text/css" href="/static/css/public/editVer.css">
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<style>
    .zuoce-box .zuoce-box-list li{
        padding-right:16px;
    }
    .zuoce-box-list li span{
        float:right;
        display:none;
    }

</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn go_back_list btn-default">
                <?=Yii::t('app', 'BACK')?>
            </button>
        </div>
        <!-- 内容区域-->
        <div class="container-fluid neirong-wrap"
             style="min-width:1200px;height: 1000px">
            <div class="row col-md-12 show_content">
                <div class="order-box"
                     style='min-height:1000px'>
                    <div class="zuoce-box">
                        <div class="ml-list">
                            <h4><?=Yii::t('app', 'RELATION_LIST')?></h4>
                            <span>（<?=count($relation)?>）</span>
                        </div>
                        <ul class='zuoce-box-list'>
                            <li class='sl add-commond cursor submit_del' style='justify-content: flex-end'> <a><?=Yii::t('app', 'DELETE')?></a>
                            <?php foreach ($relation as $item):?>
                                <li class='sl ml-item' data-id="<?=$item['id']?>"><?=implode('-', array_values(json_decode($item['group_name'], true)))?></li>
                            <?php endforeach;?>
                        </ul>

                    </div>

                    <!--  命令详情 右侧  -->
                    <form id="mymessage-form">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                    <input type="hidden" name="ini_id" value="<?=Yii::$app->request->get('id')?>">
                    <input type="hidden" name="category" value="<?=$info['category_id']?>">
                    <div class="youce-box youce-detail-box">
                        <h4><?=Yii::t('app', 'CHOOSE_CATEGORY')?></h4>
                        <div class="col-md-6"
                             style='padding-left: 0 !important'>
                            <div class="form-group col-md-12 input-xx">
                                <select name="" disabled class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        <option value="<?=$category['id']?>" ><?=$category['cnname']?></option>
                                </select>
                            </div>
                        </div>

                        <div class="row col-md-12 show_content">
                            <?php if($categoryList): foreach($categoryList as $value): ?>
                                <h3><?=$value['cnname']?></h3>
                                <div class="col-md-12">
                                    <div class="form-group col-md-12 input-xx">

                                        <div class='radio-inline' style='flex-wrap:wrap'>
                                            <?php foreach($value['child'] as $v): ?>
                                                <div class="col-md-3">
                                                    <input type="radio" id="<?=$v['parent_id']?>" name="param[]" value="<?=$v['id']?>" <?php if(in_array($v['id'], $relation)){echo 'checked="checked"';}?>/>
                                                    <label class='m-l-5 m-r-5 check_box_label'><?=$v['cnname']?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;?>

                                <div class="col-md-6">
                                    <div class="form-group col-md-6 input-xx">
                                        <input type="button" class='btn btn-block btn-primary submit_btn' value="<?=Yii::t('app', 'SUBMIT')?>">
                                    </div>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>

                    </form>
                </div>
            </div>

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
        radioClass: 'iradio_minimal-blue'
    });
    //
    $('input[type="radio"].minimal').on('ifChecked', function (event) {

    });

    $('.ml-item').click(function(){
        $(this).addClass('li-active').siblings('.ml-item').removeClass('li-active');
        // $(this).find('span').show();
        // $(this).siblings('.ml-item').find('span').hide();
        $('.youce-add-box').hide();
        $('.youce-detail-box').show();

    });

    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });
    $('.submit_btn').unbind('click').click(function () {
        var data = $('#mymessage-form').serialize();
        console.log(data);
        $.ajax({
            url:'<?=url::toRoute('relation_ajax')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                if(data.code == 0){
                    succ(data.message, location.href);
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    });
    $('.submit_del').unbind('click').click(function () {
        var id = false,obj;
        $('.ml-item').each(function () {
            if ($(this).hasClass('li-active')) {
                obj = this;
                id = $(this).attr('data-id');
            }
        });
        if (!id) return false;
        $.ajax({
            url:'<?=url::toRoute('delete')?>',
            type:'post',
            dataType:'json',
            data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success:function (data) {
//                console.log(data); return false;
                if(data.code == 0){
                    $(obj).remove();
                    succ(data.message);
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    });
</script>