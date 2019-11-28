<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-default go_back_list">
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="mymessage-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>姓名或名称:</label>
                        <span  class="form-control sl"><?=$data['company']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>感玩工厂账号:</label>
                        <span  class="form-control sl"><?=$data['account']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>收件地址:</label>
                        <span  class="form-control sl "><?=$data['address']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>联系电话:</label>
                        <span  class="form-control sl"><?=$data['phone']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>邮箱地址:</label>
                        <span  class="form-control sl"><?=$data['email']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>寄回物流服务商:</label>
                        <span  class="form-control sl"><?=$data['logistics']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>寄回物流单号:</label>
                        <span  class="form-control sl"><?=$data['trackingnumber']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>产品型号:</label>
                        <span  class="form-control sl"><?=$data['productmodel']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>产品序列号(SN):</label>
                        <span  class="form-control sl"><?=$data['sn']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>寄回部件清单:</label>
                        <span  class="form-control sl"><?=$data['partlist']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>购买渠道:</label>
                        <span  class="form-control sl"><?=$data['shop']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>购买日期:</label>
                        <span  class="form-control sl"><?=$data['purchasedate']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>订单号:</label>
                        <span  class="form-control sl"><?=$data['ordernumber']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>服务信息:</label>
                        <span  class="form-control sl">
                            <?php if($data['weixiu'] == 1):?>维修
                            <?php elseif($data['weixiu'] == 2):?>换货
                            <?php elseif($data['weixiu'] == 3):?>退货
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>事故日期:</label>
                        <span  class="form-control sl"><?=$data['accidentdate']?></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>问题描述及应急措施:</label>
                        <textarea  class="form-control " disabled><?=$data['des']?></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-8 input-xx">
                        <label class='col-md-4 title'>申请时间:</label>
                        <span  class="form-control sl"><?=date('Y-m-d H:i:s', $data['created_at'])?></span>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>

<script type="text/javascript">
    //返回列表页面
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('list')?>';
    });
</script>