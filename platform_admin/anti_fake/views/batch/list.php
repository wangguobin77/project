<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20">
            <button class="btn btn-warning" id="filePicker">CSV导入</button>
            <button type="button" class="btn btn-primary">
                申请批次
            </button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">序号</th>
                    <th width="320px"  class="sl">批次</th>
                    <th width="160px"  class="sl">大类</th>
                    <th width="160px"  class="sl">数量</th>
                    <th width="160px"  class="sl">状态</th>
                    <th width="480px"  class="sl">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                    <tr>
                        <td class="sl"><?=$item['id']?></td>
                        <td class="sl"><?=$item['batch_num']?></td>
                        <td class="sl"><?=$item['category']?></td>
                        <td class="sl"><?=$item['quantity']?></td>
                        <?php if($item['status'] == 1):?>
                            <td class="sl zz-box"><span class="zaizhi rk">审批通过</span></td>
                        <?php elseif ($item['status'] == 2):?>
                            <td class="sl zz-box"><span class="btn-danger">审批未通过</span></td>
                        <?php else:?>
                            <td class="sl zz-box"><span class="btn-primary">待审批</span></td>
                        <?php endif;?>

                        <td class="sl opr-box" >
                            <div class="czuo-box" style='width:fit-content'>
                                <?php if($item['status'] == 1) :?>
                                    <a href="<?=url::toRoute(['/sn/download','id'=>$item['id']])?>">下载</a>
                                    <span class='xian'></span>
                                    <a href="<?=url::toRoute(['/sn/list','id'=>$item['id']])?>" class='set-btn' data-id="<?=$item['id']?>">SN</a>
                                    <span class='xian'></span>
                                <?php endif;?>
                                <?php if($item['status'] == 0) :?>
                                    <a href="<?=url::toRoute(['review','id'=>$item['id']])?>" class='set-btn'>审批</a>
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
    //新增厂商
    $('#filePicker').click(function(){
        location.href = '<?=url::toRoute('csv_load')?>';
    });
</script>