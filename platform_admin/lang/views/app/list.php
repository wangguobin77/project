<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20">
            <button type="button" class="btn lang_app_add_btn btn-primary">添加</button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="320px"  class="sl">域名</th>
                    <th width="160px"  class="sl">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                    <tr>
                        <td class="sl"><?=$item['app_name']?></td>
                        <td class="sl opr-box" >
                            <div class="czuo-box" style='width:fit-content' data-id="<?=$item['id']?>">
                                <a href="<?=url::toRoute(['edit', 'id'=>$item['id']])?>" class='edit-btn'>编辑</a>
                                <span class='xian'></span>
                                <?php if($item['is_delete'] == 0):?>
                                    <a href="javascript:;" class='delete_submit_btn' >删除</a>
                                <?php else:?>
                                    <a href="javascript:;" class='recycle_submit_btn'>恢复</a>
                                    <span class='xian'></span>
                                    <a href="javascript:;" class='delete_true_submit_btn'>删除</a>
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
<!-- /.content-wrapper end-->

<!-- REQUIRED JS SCRIPTS -->
<script src="/static/js/public/jquery.min.js"></script>
<script src="/static/js/public/bootstrap.min.js"></script>
<script src="/static/js/public/adminlte.min.js"></script>
<script src='/static/js/public/ts.js'></script>
<script type="text/javascript">
    //删除
    $('.opr-box').delegate('.delete_submit_btn', 'click', function() {
        var obj = $(this);
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
                data:{'id':$(obj).parent().attr('data-id'), '_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success:function (data) {
                    if(data.code != 0){
                        fail(data.message);
                    }else{
                        succ(data.message);
                        var str = '';
                        str += '<a href="javascript:;" class="recycle_submit_btn">恢复</a>';
                        str += '<span class="xian"></span>';
                        str += '<a href="javascript:;" class="delete_true_submit_btn">彻底删除</a>';
                        $(obj).parent().append(str);
                        $(obj).remove();
                    }
                }
            })
        });
    });

    //彻底删除
    $('.opr-box').delegate('.delete_true_submit_btn', 'click', function() {
        var obj = $(this);
        $('.delete h6').html('是否确认彻底删除？');
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
                data:{'id':$(obj).parent().attr('data-id'), '_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success:function (data) {
                    if(data.code != 0){
                        fail(data.message);
                    }else{
                        succ(data.message);
                        $(obj).parent().parent().parent().remove();
                    }
                }
            })
        });
    });

    //恢复
    $('.opr-box').delegate('.recycle_submit_btn', 'click', function() {
        var obj = $(this);
        $('.delete h6').html('是否确认恢复？');
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
                url:'<?=Url::toRoute('recycle')?>',
                type:'post',
                dataType:'json',
                data:{'id':$(obj).parent().attr('data-id'), '_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success:function (data) {
                    if(data.code != 0){
                        fail(data.message);
                    }else{
                        succ(data.message);
                        $(obj).parent().append('<a href="javascript:;" class="delete_submit_btn" >删除</a>');
                        $(obj).next().remove();
                        $(obj).next().remove();
                        $(obj).remove();
                    }
                }
            })
        });
    });

    //添加
    $('.lang_app_add_btn').unbind('click').click(function(){
        location.href = '<?=url::toRoute('add')?>';
    });
</script>


