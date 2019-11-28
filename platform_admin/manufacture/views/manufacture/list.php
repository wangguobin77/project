<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20">
            <button type="button" class="btn  btn-primary">新增</button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">名称</th>
                    <th width="320px"  class="sl">联系人</th>
                    <th width="160px"  class="sl">联系电话</th>
                    <th width="160px"  class="sl">状态 </th>
                    <th width="480px"  class="sl">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                <tr>
                    <td class="sl"><?=$item['name']?></td>
                    <td class="sl"><?=$item['linkman']?></td>
                    <td class="sl"><?=$item['mobile']?></td>
                    <?php if ($item['is_deleted'] == 1) :?>
                        <td class="sl zz-box"><span class="btn-danger">已删除</span></td>
                    <?php else:?>
                        <?php if($item['status'] == 1):?>
                            <td class="sl zz-box"><span class="zaizhi rk">正常</span></td>
                        <?php elseif ($item['status'] == 2):?>
                            <td class="sl zz-box"><span class="btn-danger">封停</span></td>
                        <?php else:?>
                            <td class="sl zz-box"><span class="btn-primary">未激活</span></td>
                        <?php endif;?>
                    <?php endif;?>

                    <td class="sl opr-box" >
                        <div class="czuo-box" style='width:fit-content'>
                            <a href="<?=url::toRoute(['edit','id'=>$item['id']])?>">修改</a>
                            <?php if($item['is_deleted'] == 0):?>
                                <span class='xian'></span>
                                <a href="javascript:;" class='set-btn del-btn submit_del_btn' data-id="<?=$item['id']?>">删除</a>
                            <?php endif;?>
                            <span class='xian'></span>
                            <a href="<?=url::toRoute(['/sn/create_short_view','mid'=>$item['id']])?>" class='set-btn'>缩写</a>
                            <span class='xian'></span>
                            <a href="<?=url::toRoute(['batch/batch_info_list','mid'=>$item['id']])?>" class='set-btn'>批次</a>
                            <span class='xian'></span>
                            <a href="<?=url::toRoute(['/remote_type/list','mid'=>$item['id']])?>" class='set-btn'>遥控器</a>
                            <span class='xian'></span>
                            <a href="<?=url::toRoute(['/device_type/list','mid'=>$item['id']])?>" class='set-btn'>终端</a>
                            <?php if($item['is_deleted'] == 1):?>
                                <span class='xian'></span>
                                <a href="javascript:;" class='set-btn del-btn submit_del_true_btn' data-id="<?=$item['id']?>">彻底删除</a>
                            <?php endif;?>
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
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>

    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/dialog.js"></script>

<script>
    //新增厂商
    $('.btn-primary').unbind('click').click(function(){
        location.href = '<?=url::toRoute('add')?>';
    });
    $('.submit_del_btn').unbind('click').click(function () {
        var id = $(this).attr('data-id');
        $('.delete').show();
        // 删除提示框
        $('.delete').find('.cancel').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.icon-close').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.confirm').unbind('click').click(function(){
            $('.delete').hide();
            $.ajax({
                url:'<?=Url::toRoute('delete')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    console.log(data);
                    if( data.code == 0 ) {
                        succ(data.message,'<?=url::toRoute('list')?>');
                    } else {
                        fail(data.message);
                    }
                }
            });
        });
        return false;
    });
    //彻底删除遥控器
    $('.submit_del_true_btn').unbind('click').click(function () {
        var id = $(this).attr('data-id');
        $('.delete').show();
        // 删除提示框
        $('.delete').find('.cancel').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.icon-close').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.confirm').unbind('click').click(function(){
            $('.delete').hide();
            $.ajax({
                url:'<?=Url::toRoute('delete_true')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    console.log(data);
                    if( data.code == 0 ) {
                        succ(data.message,'<?=url::toRoute('list')?>');
                    } else {
                        fail(data.message);
                    }
                }
            });
        });
    });
</script>


