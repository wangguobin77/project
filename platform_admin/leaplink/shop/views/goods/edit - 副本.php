<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

use common\helpers\UtilsHelper;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="css/coupon/add.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->

    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default btn-back-call" onclick='javascript:history.back();'>
                返回
            </button>
        </div>
        <form id="sub-form" action="" method="post">
            <div class='row'>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>状态:</label>
                        <input  type="text" class="form-control status" disabled placeholder="状态" name="status" value="<?=isset($data['id'])?'编辑':'新建'?>">
                        <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>核销码:</label>
                        <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入用于商品核销的代码" name='upc' value="<?= isset($data['upc'])?$data['upc']:''?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>品名:</label>
                        <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入商品品名" name='name' value="<?= isset($data['name'])?$data['name']:''?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>原价:</label>
                        <input  type="number" class="form-control val" autocomplete="off"  placeholder="请输入商品原价" name='worth' value="<?= isset($data['worth'])?UtilsHelper::fen2yuan($data['worth']):''?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>售价:</label>
                        <input  type="number" class="form-control price" autocomplete="off"  placeholder="请输入商品售价" name='price' value="<?= isset($data['price'])?UtilsHelper::fen2yuan($data['price']):''?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">商品图片:</label>
                        <div class="col-md-9" style="display: block;width: 100%;margin: 0;">
                            <span class="coupon-bg col-md-6" style="display: block;margin: 5px 0;">
                                <img class="img-thumbnail" src="images/coupon_bg.jpg">
                                <input type="hidden" name="resource_id">
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row col-md-12">
                    <div class="row col-md-12">
                        <div class="col-md-3 col-md-offset-1">
                            <div class="form-group col-md-6 input-xx">
                                <button type="button" class="btn btn-block btn-primary" onclick="put()">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/goods/add.js"></script>
<script type="text/javascript">
    var POST_URL = "<?= Url::toRoute(['goods/edit'])?>";
    const put = () =>{
        var data =$('#sub-form').serializeObject();
        $.ajax({
            url:POST_URL,
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(data.code==0){
                    succ('提交成功')
                    location.href = "<?=Url::toRoute(['goods/list'])?>"
                }else{
                    fail('提交失败')
                }
            }
        });

    };
</script>
</body>