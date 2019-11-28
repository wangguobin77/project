<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<link rel="stylesheet" type="text/css" href="/static/css/car_param/editVer.css">
<link rel="stylesheet" type="text/css" href="/static/css/car_param/relation.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><a href="javacript:;"><?=$info['file_name']?></a></li>
                <li><a href="javacript:;"><?=$category['cnname']?></a></li>
                <span>
                    <button type="button" class="btn go_back_list btn-default">返回</button>
                </span>
            </ol>
        </div>
        <!-- 内容区域-->
        <form id="mymessage-form">
            <?php foreach ($categoryList as $item):?>
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <input type="hidden" name="ini_id" value="<?=$info['id']?>">
                <input type="hidden" name="category" value="<?=$info['category_id']?>">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx" >
                        <label class='col-md-5 title-center' ><?=$item['category_name']?>:</label>
                        <select name="param[]"  class="form-control select2 select2-hidden-accessible col-md-7" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >请选择</option>
                            <?php foreach ($item['child'] as $v):?>
                                <option value="<?=$v['id']?>" ><?=$v['cnname']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
            <?php endforeach;?>
            <div class="col-md-12">
                <div class="form-group col-md-offset-11 col-md-1 input-xx">
                    <input type="button" class='btn btn-block btn-primary submit_btn' value="<?=Yii::t('app', 'SUBMIT')?>">
                </div>
            </div>
        </form>

        <div class="box-body table-responsive no-padding col-md-12">
            <table class="table table-hover">
                <tr>
                <?php foreach ($categoryList as $item):
                    $temp[] = array_column($item['child'], 'id');
                ?>
                    <th><?=$item['category_name']?></th>
                <?php endforeach;?>
                    <th>操作</th>
                </tr>
                <?php foreach ($relation as $item):
                    $array = json_decode($item['group_name'], true);
                    $ids = array_keys($array);
                ?>
                    <tr>
                        <?php foreach ($temp as $value):?>
                            <td>
                                <?php
                                    if ($id = array_intersect($value, $ids)) {
                                        echo $array[current($id)];
                                    } else {
                                        echo '(N/A)';
                                    }
                                ?>
                            </td>
                        <?php endforeach;?>
                        <td data-id="<?=$item['id']?>" class="submit_del_btn">删除</td>
                    </tr>
                <?php endforeach;?>
            </table>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>

<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2();
    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });
    $('.submit_btn').unbind('click').click(function () {
        var flag = false;
        $("select[name='param[]']").each(function () {
            if ($(this).val()) {
                flag = true;
                return false;
            }
        });
        if (!flag) {
            fail('请至少选择一个分类');
            return false;
        }
        var data = $('#mymessage-form').serialize();
        $.ajax({
            url:'<?=url::toRoute('relation_ajax')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                if(data.code == 0){
                    succ(data.message);
                    window.location.reload();
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    });

    // 删除提示框
    $('.submit_del_btn').unbind('click').click(function () {
        var id = $(this).attr('data-id');
        $('.delete').find('.confirm').attr('data-id', id);
        $('.delete').show();
    });

    $('.confirm').click(function(){
        $('.delete').hide();
        var id = $(this).attr('data-id');
        $.ajax({
            url:'<?=url::toRoute('delete_relation')?>',
            type:'post',
            dataType:'json',
            data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success:function (data) {
//                console.log(data); return false;
                if(data.code == 0){
                    $('td').each(function () {
                       if ($(this).attr('data-id') == id) {
                           $(this).parent().remove();
                       }
                    });
                    succ(data.message);
                }else{
                    // alert(data.message);
                    fail(data.message);
                }
            }
        })
    });
</script>