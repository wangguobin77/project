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
                    <button type="button" class="btn btn-primary mid-button add">批次创建</button>
                </div>
                <div class="table-con ">
                    <table class="table table-expandable">
                        <thead>
                        <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                            <th width="200px"  class="sl">id </th>
                            <th width="320px"  class="sl">批次号</th>
                            <th width="270px"  class="sl">型号</th>
                            <!-- <th width="320px"  class="sl">批次号</th> -->
                            <th width="320px"  class="sl">批次数量</th>
                            <th width="200px"  class="sl">创建时间</th>
                            <th width="200px"  class="sl">审批时间</th>
                            <th width='460px'  class="sl" >状态</th>
                            <th width="300px"  class="sl" >操作</th>
                            <th width="80px"   class="sl" ></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ((array)$datas as $item): ?>
                        <tr id="tr_<?=$item['id']?>">
                            <td class='sl'><?=$item['id']?></td>
                            <td class='sl'><?=$item['batch_date'].$item['batch_no']?></td>
                            <td class='sl'><?=$item['chip_type']?></td>
                            <td class='sl'><?=$item['batch_count']?></td>
                            <td class='sl'><?=date('Y-m-d H:i:s', $item['created_ts'])?></td>
                            <td class="sl check_ts"><?=$item['check_ts']?date('Y-m-d H:i:s', $item['check_ts']):""?></td>
                            <td class="sl status-box">
                                <?php
                                switch ($item['check_status']) {
                                    case ARBatch::CHECK_STATUS1:
                                        echo "<span class='shenhe'></span>";
                                        break;
                                    case ARBatch::CHECK_STATUS2:
                                        echo "<span class='shanchu'></span>";
                                        break;
                                    default:
                                        echo "<span class='shengxiao'></span>";
                                }


                                foreach (ARBatch::CHECK_STATUS_LABLES as $ci => $cv) {
                                    if ($ci & $item['check_status']){
                                        if($ci >= ARBatch::CHECK_STATUS4) echo "-";
                                        echo ARBatch::CHECK_STATUS_LABLES[$ci];
                                    }

                                }
                                ?>
                            </td>
                            <td class="sl opr-box" >
                                <div class="czuo-box" style='width:fit-content'>
                                    <?php if (ARBatch::CHECK_STATUS1 == $item['check_status']): ?>
                                        <a  class='set-btn czuo-shenhe' onclick="sub_commit('<?=$item['id']?>')">审核</a>
                                        <div class='xian'></div>
                                    <?php endif; ?>
                                    <?php if ($item['check_status'] & ARBatch::CHECK_STATUS4): ?>
                                        <a  class='set-btn' onclick="cache('<?=$item['id']?>', '<?= ARBatch::TYPE_CACHE1?>')">刷缓存</a>
                                        <div class='xian'></div>
                                        <a  class='set-btn' onclick="cache('<?=$item['id']?>', '<?= ARBatch::TYPE_CACHE2?>')">生成bin</a>
                                        <div class='xian'></div>
                                    <?php endif; ?>
                                    <?php if ($item['check_status'] & ARBatch::CHECK_STATUS6): ?>
                                        <a  class='set-btn' onclick="down_bin('<?=$item['id']?>')">下载bin</a>
                                        <div class='xian'></div>
                                    <?php endif; ?>
                                    <a  class='set-btn'  href="<?=Url::toRoute(['leapid/list' , 'batch_id' => $item['id']])?>" >Leapid</a>
                                </div>
                            </td>
                        </tr>
                        <tr class='detail-info'>
                            <td colspan="11" >
                                <p class='detail'>
                                    <span>
                                        <span class='detail-title'>是否删除：</span>
                                        <span class='detail-con'><?=ARBatch::DELETE_STATUS_LABLES[$item['is_delete']]?></span>
                                    </span>
                                    <span>
                                        <span class='detail-title'>批次备注：</span>
                                        <span class='detail-con'><?=$item['info']?></span>
                                    </span>
                                </p>
                            </td>
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
        $('.add').on('click', function () {
            location.href = '<?= Url::toRoute(["batch/add"])?>';
        })
    });
    const del=()=>{
        stopPropagation()
        tips_warning('确认要删除吗？')
    }
    const sub_commit=(id)=>{
        stopPropagation()
        $('.shenpi').data('id', id).show()
    }

    const edit=()=>{
        stopPropagation()
    }

    const down_bin=(id)=>{
        stopPropagation()
        // tips_warning('确认要下载bin文件吗？')
        window.open("<?=Url::toRoute(['batch/download'])?>" + "&batch_id="+id)
    }

    const cache=(id,t)=>{
        stopPropagation()
        $.ajax({
            url: "<?=Url::toRoute(['batch/cache'])?>",
            type:'post',
            dataType:'json',
            data:{id:id, type:t, status:status, _csrf:$('#_csrf').val()},
            success: function(data) {
                if(data.code==0){
                    succ('提交成功,正在刷新缓存...');
                }else{
                    fail(data.message)
                }
            }
        });
    }

    // 弹框确认按键
    $('.shenpi').find('.cancel').click(function(){
        var id = $(".shenpi").data('id');
        var status = $(this).data('status');
        var fill_html = $(this).attr('fill_html')
        $.ajax({
            url: "<?=Url::toRoute(['batch/check-pass'])?>",
            type:'post',
            dataType:'json',
            data:{id:id, status:status, _csrf:$('#_csrf').val()},
            success: function(data) {
                if(data.code==0){
                    var html;
                    succ('提交成功');
                    var $p = $('#tr_'+id);
                    $p.find('.status-box').html(fill_html);
                    $p.find('.check_ts').html(data.data.check_ts);
                    $p.find('.czuo-shenhe').next().remove();
                    $p.find('.czuo-shenhe').remove();
                }else{
                    fail(data.message)
                }
            }
        });

    })
</script>



