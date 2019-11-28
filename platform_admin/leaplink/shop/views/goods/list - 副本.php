<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\goods\ARGoods;
use common\helpers\UtilsHelper;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="css/goods/list.css">
<style type="text/css">.btn {
    min-width: 80px;
    margin-left: 10px;
    outline: none !important;
    box-shadow: none !important;
}</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->

    <section class="content container-fluid">

    <!--     <form method="get" action="">
            <input type="hidden" name="r" value="goods/list">
            <input type="text" placeholder="核销码" name="upc" value="<?=isset($params['upc']) && $params['upc']?trim($params['upc']):''?>"/>
            <input type="text" placeholder="品名" name="name" value="<?=isset($params['name']) && $params['name']?trim($params['name']):''?>"/>
            <select name="check_status">
                <option value="">全部状态</option>
                <?php foreach (ARGoods::CEHCK_STATUS_LABLE as $k => $v):?>
                <option value="<?=$k?>" <?=isset($params['check_status']) && $params['check_status'] === (string)$k?'selected':''?>>
                    <?=$v?>
                </option>
                <?php endforeach;?>
            </select>
            <input type="submit" value="查询"/>
            <input type="reset" value="重置"/>
        </form>
 -->


        <form style="display:flex;flex-wrap:wrap;" class='operate-zone'>
            <input type="hidden" name="r" value="goods/list">
            <div class="col-md-2">
                <div class="form-group col-md-12 input-xx">
                    <input type="text" placeholder="核销码" name="upc" value="<?=isset($params['upc']) && $params['upc']?trim($params['upc']):''?>" class='form-control'/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group col-md-12 input-xx">
                     <input type="text" placeholder="品名" name="name" value="<?=isset($params['name']) && $params['name']?trim($params['name']):''?>" class='form-control'/>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group col-md-12 input-xx">

                    <select class="form-control select2 yyzz_selsct" name="check_status">
                        <option value="">全部状态</option>
                        <?php foreach (ARGoods::CEHCK_STATUS_LABLE as $k => $v):?>
                        <option value="<?=$k?>" <?=isset($params['check_status']) && $params['check_status'] === (string)$k?'selected':''?>>
                            <?=$v?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            <div class="">
                 <input type="submit" value="查询"  class="btn  btn-primary"/>
            </div>
            <div class="">
                 <input type="reset" value="重置"  class="btn  btn-primary"/>
            </div>
        </form>







       <!--  <div class="right-box p-b-20 row">
            <a href='<?=Url::toRoute(['goods/edit'])?>' class="btn  btn-primary btn-back-call" >
                添加
            </a>
            <a href='<?/*=Url::toRoute(['goods/edit'])*/?>' class="btn  btn-danger btn-back-call" >
                全部提交
            </a>
            <a href='<?/*=Url::toRoute(['goods/edit'])*/?>' class="btn  btn-warning btn-back-call" >
                导入
            </a>
            <a href='<?/*=Url::toRoute(['goods/edit'])*/?>' class="btn  btn-info btn-back-call" >
                导出
            </a>
        </div> -->
        <div class="">
            <div class="operate-btn" style="margin-bottom:20px">
                <a href='<?=Url::toRoute(['goods/edit'])?>' class="btn  btn-primary btn-back-call" >
                    添加
                </a>
               <!--  <button type="button" class="btn" onclick="all_submit()">全部提交</button>
                <button type="button" class="btn" onclick="lead()">导入</button>
                <button type="button" class="btn">导出</button> -->
            </div>
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="320px"  class="sl">核销码 </th>
                    <th width="320px"  class="sl">品名 </th>
                    <th width="160px"  class="sl">原价</th>
                    <th width="160px"  class="sl">售价</th>
                    <th width="320px"  class="sl" >状态</th>
                    <th width="160px"  class="sl" >更新时间</th>
                    <th width="160px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ((array)$datas as $item): ?>
                    <tr id="tr_<?=$item['id']?>">
                        <td class="sl"><?=$item['upc']?></td>
                        <td class="sl"><?=$item['name']?></td>
                        <td class="sl">￥<?=UtilsHelper::fen2yuan($item['worth'])?></td>
                        <td class="sl">￥<?=UtilsHelper::fen2yuan($item['price'])?></td>
                        <td class="sl check_status"><?php switch($item['check_status']){
                                case ARGoods::CHECK_STATUS0:
                                    echo ARGoods::CEHCK_STATUS_LABLE[ARGoods::CHECK_STATUS0];
                                    break;
                                case ARGoods::CHECK_STATUS1:
                                    echo ARGoods::CEHCK_STATUS_LABLE[ARGoods::CHECK_STATUS1];
                                    break;
                                case ARGoods::CHECK_STATUS2:
                                    echo ARGoods::CEHCK_STATUS_LABLE[ARGoods::CHECK_STATUS2];
                                    break;
                                case ARGoods::CHECK_STATUS3:
                                    echo ARGoods::CEHCK_STATUS_LABLE[ARGoods::CHECK_STATUS3];
                                    break;
                            }?></td>
                        <td class="sl"><?=date('Y-m-d H:i:s' ,$item['updated_at'])?></td>
                        <td class="sl">
                            <a class="label label-success cursor" href="<?=Url::toRoute(['goods/edit', 'id' => $item['id']])?>">编辑</a>
                            <?php if(ARGoods::CHECK_STATUS0 == $item['check_status'] || ARGoods::CHECK_STATUS1 == $item['check_status']): ?>
                                <a class="label  cursor btn-danger del_remove" onclick="del(<?=$item['id']?>)">删除</a>
                            <?php endif;?>
                            <?php if(ARGoods::CHECK_STATUS0 == $item['check_status']): ?>
                                <a class="label  cursor btn-info del_remove check_pass_remove" onclick="check_pass(<?=$item['id']?>)">提交</a>
                            <?php endif;?>
                        </td>

                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
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
        <!-- 页码end -->
    </section>
</div>
<!-- /.content-wrapper -->

<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close cursor"></span>
        <img src="images/warning-large.png" alt="">
        <h6>是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel btn btn-default"> 取消</div>
            <div class="cursor btn btn-primary confirm">确认</div>

        </div>

    </div>
</div>
<!--tishi-->

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/goods/list.js"></script>
<script type="text/javascript">
    var DEL_URL = "<?=Url::toRoute(['goods/del'])?>" //删除地址
    var CHECK_URL = "<?=Url::toRoute(['goods/check_pass'])?>" //提交审核地址
    var _csrf = "<?=Yii::$app->request->csrfToken?>"
</script>
</body>