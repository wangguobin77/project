<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/select2.min.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div style="display:flex;justify-content:flex-end">
            <div class="col-md-3" style='float: right;text-align:right;margin-bottom:15px;'>
                <input type="button" value="添加遥控器" class="btn btn-primary add_remote_btn">
            </div>
        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">遥控器中文名称</th>
                    <th width="160px"  class="sl">遥控器英文名称	</th>
                    <th width="160px"  class="sl">型号</th>
                    <th width="100px"  class="sl">厂商</th>
                    <th width="100px"  class="sl">状态 </th>
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $value):?>
                    <tr>
                        <td class="sl"><?=$value['name']?></td>
                        <td class="sl"><?=$value['name_en']?></td>
                        <td class="sl"><?=$value['type']?></td>
                        <td class="sl"><?=$value['manufacture_name']?></td>
                        <?php if ($value['is_deleted'] == 1):?>
                            <td class="sl zz-box"><span class="btn-danger">已删除</span></td>
                        <?php else:?>
                            <?php if ($value['status'] == 1):?>
                                <td class="sl zz-box"><span class="zaizhi rk">已发布</span></td>
                            <?php elseif($value['status'] == 2):?>
                                <td class="sl zz-box"><span class="btn-warning">已停产</span></td>
                            <?php else:?>
                                <td class="sl zz-box"><span class="btn-primary">研发中</span></td>
                            <?php endif;?>
                        <?php endif;?>

                        <td class="sl opr-box" >
                            <div class="czuo-box" style='width:fit-content'>
                                <?php if ($value['is_deleted'] == 0):?>
                                    <a href="javascript:;" class='set-btn delete_btn' data-id="<?=$value['id']?>">删除</a>
                                    <span class='xian'></span>
                                <?php endif;?>
                                <a href="<?=url::toRoute(['edit', 'id'=>$value['id'], 'mid'=>Yii::$app->request->get('mid')])?>" class='set-btn'>基本信息</a>
                                <span class='xian'></span>
                                <a href="<?='/keymap/web/index.php?r=keymap/keymap_list&rc='.$value['id']?>" class='set-btn'>Keymap设置</a>
                                <span class='xian'></span>
                                <a href="<?=url::toRoute(['keyset', 'id'=>$value['id'], 'mid'=>Yii::$app->request->get('mid')])?>" class='set-btn'>按键配置</a>
                                <?php if ($value['is_deleted'] == 1):?>
                                    <span class='xian'></span>
                                    <a href="javascript:;" class='set-btn submit_del_true_btn' data-id="<?=$value['id']?>">彻底删除</a>
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
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/dialog.js"></script>

<script>
    $('.select2').select2();
    //新增遥控器
    $('.add_remote_btn').unbind('click').click(function(){
        var url;
        url = '<?=Yii::$app->request->get('mid')?>' ? '<?=url::toRoute(['add', 'mid'=>Yii::$app->request->get('mid')])?>' : '<?=url::toRoute('add')?>';
        location.href = url;
//        location.href = '<?//=url::toRoute('add')?>//';
    });

    //删除遥控器
    $('.delete_btn').unbind('click').click(function () {
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


