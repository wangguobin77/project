<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-10-30
 * Time: 14:40
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\batch\ARBatch;
use app\models\batch\ARLeapid;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/bootstrap-table-expandable.css">
<link rel="stylesheet" href="/static/css/batch/list.css">
<style type="text/css">.select-input>
    span.select2{
        width:34% !important;
    }
    .btn{
        min-width:80px;
        margin-left:10px;
        outline:none !important;
        box-shadow:none !important;
    }
    .operate-zone{
        margin-bottom:15px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-width:870px;">
    <!-- Main content -->
    <section class="content container-fluid">
        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>" id="_csrf">
        <div class="">
            <div class="operate-btn" style="margin-bottom:20px">
                <a type="button" class="btn mid-button  btn-primary" style="line-height:28px;" href="<?=Url::toRoute(['leapid/set-use', 'batch_id' => $params['batch_id']])?>">批量设置</a>
                <button type="button" class="btn  mid-button add" onclick=" window.history.go(-1);">返回</button>
            </div>
            <div class="table-con ">
                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="200px"  class="sl">leapid </th>
                        <th width="390px"  class="sl">批次号</th>
                        <th width="320px"  class="sl">批次流水</th>
                        <th width='430px'>创建时间</th>
<!--                        <th width='430px'>激活时间</th>-->
                        <th width='430px'>用途</th>
<!--                        <th width="300px"  class="sl" >操作</th>-->
                    </tr>
                            </div>
                        </td>
                    </thead>
                    <tbody>
                    <?php foreach ((array)$datas as $item): ?>
                    <tr>
                        <td class='sl'><?=$item['id']?></td>
                        <td class='sl'><?=$item['batch_id']?></td>
                        <td class='sl'><?=$item['batch_serial']?></td>
                        <td class="sl"><?=date('Y-m-d H:i:s', $item['create_ts'])?></td>
<!--                        <td class="sl">--><?//=$item['activate_ts']?date('Y-m-d H:i:s', $item['activate_ts']):""?><!--</td>-->
                        <td class="sl"><?= key_exists($item['using'],ARLeapid::USING_LABELS)?ARLeapid::USING_LABELS[$item['using']]:"未设置" ?></td>
<!--                        <td class="sl opr-box" >-->
<!--                            <div class="czuo-box" style='width:fit-content'>-->
<!--                                <a  class='set-btn'>设置leapid用途</a>-->
<!--                        </td>-->
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="box-footer clearfix">
                    <?= LinkPager::widget([
                        'pagination'    =>  $pages,
                        'nextPageLabel' =>  '»',
                        'prevPageLabel' =>  '«',
                        'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                        'hideOnSinglePage' => false,
                        'maxButtonCount' => 10
                    ]);?>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->

<!-- 审核提示框 -->
<div class="del-box shenpi">
    <div class="dialog">
        <span class="font_family icon-close fa fa-close"></span>
        <img src="/static/images/warning-large.png" alt="">
        <h6 class="del-title">确认要审核吗?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default" data-status="<?=ARBatch::CHECK_STATUS2?>" fill_html="<span class='shanchu'></span><?=ARBatch::CHECK_STATUS_LABLES[ARBatch::CHECK_STATUS2]?>">作废</div>
            <div class="cursor cancel btn btn-primary" data-status="<?=ARBatch::CHECK_STATUS3?>" fill_html="<span class='shengxiao'></span><?=ARBatch::CHECK_STATUS_LABLES[ARBatch::CHECK_STATUS3]?>">通过</div>
        </div>

    </div>
</div>

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script>
    $(document).ready(function(){
        $('.select2').select2()
    });
    const edit=()=>{
        stopPropagation()
    }

</script>



