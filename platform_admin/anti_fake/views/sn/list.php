<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20">
            <button type="button" class="btn  btn-default go_back_list">
                返回
            </button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">序号</th>
                    <th width="320px"  class="sl">sn</th>
                    <th width="160px"  class="sl">key</th>
                    <th width="160px"  class="sl">batch id</th>
                    <th width="160px"  class="sl">batch serial</th>
                    <th width="480px"  class="sl">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                    <tr>
                        <td class="sl"><?=$item['id']?></td>
                        <td class="sl"><?=$item['sn']?></td>
                        <td class="sl"><?=$item['key']?></td>
                        <td class="sl"><?=$item['batch_id']?></td>
                        <td class="sl"><?=$item['batch_serial']?></td>

                        <td class="sl opr-box" >
                            <div class="czuo-box" style='width:fit-content'>
                                <a href="javascript:;" class='set-btn'>查看</a>
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
    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('/batch/list')?>';
    });
</script>


