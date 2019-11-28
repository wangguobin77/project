<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<head>

    <link rel="stylesheet" type="text/css" href="../../club/web/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="../../club/web/css/ota/addChain.css">
    <link rel="stylesheet" type="text/css" href="../../club/web/css/ts.css">
    <script src="../../club/web/js/public/ts.js"></script>
</head>
<style type="text/css">
    /* 操作盒子*/
    .table .opr-box .czuo-box{
        width:115px;
        /*display:flex;
        align-items:center;*/
        float:left;
    }
    input:disabled{
        background: transparent;
    }

/*
end 完
 */
</style>


<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>产品管理  /  产品详情</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('add_client')?>">继续添加</a>
            </div>
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('client_list')?>">返回列表</a>

            </div>

        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;">
        <div class="row content-wrap" style="margin-bottom:0;">
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">CLIENT NAME&nbsp;：</h4>
                <div class="col-md-9 input-box ">

                    <input type="text" name="product_name" placeholder="输入产品名称" class="shuru sl" value="<?=$data['client_name']?>" disabled>

                </div>

            </div>


            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">CLIENT ID&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="product_code" placeholder="输入产品CODE" class="shuru sl" disabled value="<?=$data['client_id']?>">
                </div>
            </div>
            <!-- 所属机构 -->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">STATUS&nbsp;：</h4>
                <select name="" id="status" DISABLED  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;flex-grow:0">
                    <option value="">请选择...</option>
                    <option value="0" <?php if($data['status'] ==0){echo 'selected'; } ?>>禁用</option>
                    <option value="1" <?php if($data['status'] ==1){echo 'selected'; } ?> >启用</option>
                </select>
            </div>
        </div>


    </div>
</div>
