<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\UtilsHelper;
use app\models\coupon\ARCouponType;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="css/v2/product/list.css">
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
<div class="content-wrapper"  style="min-width:870px;">
    <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <div>
            <section class='row'>
                <span class='yj'>优惠券</span><span class='yj'>/</span><span class='yj'>优惠券管理</span>
            </section>
            <h5 class="zhubt">优惠券管理</h5>
        </div>
    </div>

    <!-- Main content -->
    <section class="content container-fluid" style="background: #F0F2F5;padding:24px;">
        <div class='info ' style="background:#fff;padding:24px;">
            <form style="display:flex;flex-wrap:wrap;" class='operate-zone'>
                <input type="hidden" name="r" value="coupon/list">
                <div class="col-md-2">
                    <div class="form-group col-md-12 input-xx">
                        <input type="text" class="form-control " autocomplete="off" placeholder="优惠券名称" name="title" value="<?=isset($params['title']) && $params['title']?trim($params['title']):''?>"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group col-md-12 input-xx">
                        <select class="form-control select2 yyzz_selsct"  name="check_status">
                            <option value="">全部状态</option>
                                <?php foreach (ARCouponType::CEHCK_STATUS_LABLE as $k => $v):?>
                                <option value="<?=$k?>" <?=isset($params['check_status']) && $params['check_status'] === (string)$k?'selected':''?>>
                                    <?=$v?>
                                </option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>
                <div class="">
                    <input type="submit" value="查询" class="btn btn-primary min-button"/>
                </div>
                <div class="">
                    <input type="reset" value="重置"  class="btn btn-primary min-button"/>
                </div>
            </form>
            <div class="">
                <div class="operate-btn" style="margin-bottom:20px">
                    <a href='<?=Url::toRoute(['coupon/edit'])?>' type="button" class="btn btn-primary mid-button" style='line-height: 28px'>添加</a>
                </div>
                <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="320px"  class="sl">优惠券名称 </th>
                    <th width="160px"  class="sl">价值</th>
                    <th width="160px"  class="sl">价格</th>
                    <th width="320px"  class="sl">默认投放数量</th>
                    <th width="160px"  class="sl" >审核状态</th>
                    <th width="160px"  class="sl" >上架状态</th>
                    <th width="160px"  class="sl" >更新时间</th>
                    <th width="160px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ((array)$datas as $item): ?>
                    <tr id="tr_<?=$item['id']?>">
                        <td class="sl"><?=$item['title']?></td>
                        <td class="sl">￥<?=UtilsHelper::fen2yuan($item['worth'])?></td>
                        <td class="sl">￥<?=UtilsHelper::fen2yuan($item['price'])?></td>
                        <td class="sl"><?=$item['number']?></td>
                        <td class="sl check_status"><?php switch($item['check_status']){
                                case ARCouponType::CHECK_STATUS0:
                                    echo ARCouponType::CEHCK_STATUS_LABLE[ARCouponType::CHECK_STATUS0];
                                    break;
                                case ARCouponType::CHECK_STATUS1:
                                    echo ARCouponType::CEHCK_STATUS_LABLE[ARCouponType::CHECK_STATUS1];
                                    break;
                                case ARCouponType::CHECK_STATUS2:
                                    echo ARCouponType::CEHCK_STATUS_LABLE[ARCouponType::CHECK_STATUS2];
                                    break;
                                case ARCouponType::CHECK_STATUS3:
                                    echo ARCouponType::CEHCK_STATUS_LABLE[ARCouponType::CHECK_STATUS3];
                                    break;
                                case ARCouponType::CHECK_STATUS4:
                                    echo ARCouponType::CEHCK_STATUS_LABLE[ARCouponType::CHECK_STATUS4];
                                    break;
                            }?></td>
                        <td class="sl status"><?php switch($item['status']){
                                case ARCouponType::STATUS0:
                                    echo ARCouponType::STATUS_LABLE[ARCouponType::STATUS0];
                                    break;
                                case ARCouponType::STATUS1:
                                    echo ARCouponType::STATUS_LABLE[ARCouponType::STATUS1];
                                    break;
                            }?></td>
                        <td class="sl"><?=date('Y-m-d H:i:s' ,$item['updated_at'])?></td>
                        <td class="sl">
                            <a class="label label-success cursor" href="<?=Url::toRoute(['coupon/edit', 'id' => $item['id']])?>">编辑</a>
                            <?php if(ARCouponType::CHECK_STATUS0 == $item['check_status'] || ARCouponType::CHECK_STATUS1 == $item['check_status']): ?>
                                <a class="label  cursor btn-danger del_remove" onclick="del('<?=$item['id']?>')">删除</a>
                            <?php endif;?>
                            <?php if(ARCouponType::STATUS0 == $item['status']): ?>
                                <a class="label  cursor btn-primary status_remove" onclick="status_rm('<?=$item['id']?>')">下架</a>
                            <?php endif;?>
                            <?php if(ARCouponType::CHECK_STATUS0 == $item['check_status']): ?>
                                <a class="label  cursor btn-info del_remove check_pass_remove" onclick="check_pass('<?=$item['id']?>')">提交</a>
                            <?php endif;?>
                        </td>

                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
                 <!-- 页码 开始 -->
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
            </div>
            <!-- 查询无结果 -->
            <section class='search-none flex none'>
                <img src="images/v2/searchnone.png" alt="">
                <h4>暂无商品</h4>
            </section>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->
<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/coupon/list.js"></script>
<script type="text/javascript">
    var DEL_URL = "<?=Url::toRoute(['coupon/del'])?>" //删除地址
    var CHECK_URL = "<?=Url::toRoute(['coupon/check_pass'])?>" //提交审核地址
    var STATUS_URL = "<?=Url::toRoute(['coupon/status_rm'])?>" //提交下架地址
    var _csrf = "<?=Yii::$app->request->csrfToken?>"
</script>
</body>