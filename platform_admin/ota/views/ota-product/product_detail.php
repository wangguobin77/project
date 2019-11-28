<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<head>

    <!-- <link rel="stylesheet" type="text/css" href="../../ota/web/css/dialog.css"> -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
 <!--     <link rel="stylesheet" type="text/css" href="../../ota/web/css/ts.css"> -->

    <!-- 新增加样式 -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/bottom_menu.css">
    <link rel="stylesheet" type="text/css" href="/static/css/ota/new_versionlist.css">
    <!-- <script src="../../ota/web/js/public/ts.js"></script> -->
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
                <a href="<?=Url::toRoute('add-product')?>">继续添加</a>
            </div>
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('product_list')?>">返回列表</a>

            </div>

        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;">
        <div class="row content-wrap" style="margin-bottom:0;">
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品名称&nbsp;：</h4>
                <div class="col-md-9 input-box ">

                    <input type="text" name="product_name" placeholder="输入产品名称" class="shuru sl" value="<?=$data['pro_name']?>" disabled>

                </div>

            </div>

            <!-- 建议零售价 -->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品类型&nbsp;：</h4>
                <select name="" id="type"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;" disabled>
                    <option value="">请选择...</option>
                    <?php foreach ($product_types as $k=>$item):?>
                        <option value="<?=$k?>" <?php if($k==$data['type']){ echo 'selected';} ?> ><?=$item?></option>
                    <?php endforeach;?>

                </select>

            </div>
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品CODE&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="product_code" placeholder="输入产品CODE" class="shuru sl" disabled value="<?=$data['pro_code']?>">
                </div>
            </div>
            <!-- 所属机构 -->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品描述&nbsp;：</h4>
                <textarea class="div-textarea col-md-9" style="outline:none;" id="desc" disabled><?=$data['desc']?></textarea>
            </div>
        </div>


    </div>
</div>
