<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/select2.min.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 搜索 -->
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-primary command_add_btn">
                <?=Yii::t('app', 'ADD_INI_FILE')?>
            </button>
        </div>
        <!-- 搜索end -->
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl"><?=Yii::t('app', 'SERIAL_NUMBER')?></th>
                    <th width="200px"  class="sl"><?=Yii::t('app', 'FILE_NAME')?></th>
                    <th width="160px"  class="sl"><?=Yii::t('app', 'DESCRIPTION')?></th>
                    <th width="160px"  class="sl"><?=Yii::t('app', 'CRC32')?></th>
                    <th width="200px"  class="sl"><?=Yii::t('app', 'OPERATION')?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $v):?>
                    <tr>
                        <td class="sl"><?=$v['id']?></td>
                        <td class="sl"><?=$v['file_name']?></td>
                        <td class="sl"><?=$v['desc']?></td>
                        <td class="sl"><?=$v['crc32']?></td>
                        <td class="sl opr-box">
                            <div class="czuo-box" style='width:fit-content'>
                                <a href="javascript:;" class='set-btn del-btn' data-id="<?=$v['id']?>"><?=Yii::t('app', 'DELETE')?></a>
                                <span class='xian'></span>
                                <a href="<?=url::to(["/ini/edit", 'id'=>$v['id']])?>"><?=Yii::t('app', 'EDIT')?></a>
                                <span class='xian'></span>
                                <a href="<?=url::to(["/ini/relation", 'id'=>$v['id']])?>"><?=Yii::t('app', 'RELATION')?></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  Yii::t('app', 'NEXT_PAGE'),
                'prevPageLabel' =>  Yii::t('app', 'PREV_PAGE'),
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script>
    $('.btn-ss').click(function(){

        var filename = $('input[name="filename"]').val();

        window.location.href = '<?=url::toRoute("/ini/list")?>&filename='+filename;
    });

    // 删除提示框
    $('.del-btn').click(function(){
        var id = $(this).attr('data-id');
        $('.delete').find('.confirm').attr('data-id', id);
        $('.delete').show();
    });

    $('.confirm').click(function(){
        $('.delete').hide();
        var id = $(this).attr('data-id');
        $.ajax({
            url:'<?=Url::toRoute('delete')?>',
            type:'post',
            dataType:'json',
            data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                console.log(data);
                if(data.code == 0){
                    // 保存成功
                    var src='<?=url::toRoute('list')?>';
                    succ(data.message,src);
                }else{
                    fail(data.message);
                }
            }
        })
    });

    //添加
    $('.command_add_btn').unbind('click').click(function(){
        location.href = '<?=url::toRoute('add')?>';
    })
</script>


