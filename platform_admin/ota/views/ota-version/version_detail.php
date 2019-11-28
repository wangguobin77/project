<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <title>SENSEPLAY</title>
    <!-- kendo资源 -->
    <link href="../../web/css/public/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/static/css/public/fonts.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/all.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/reset.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/iconfont.css">
<!--     <link rel="stylesheet" type="text/css" href="../../web/css/header.css">
    <link rel="stylesheet" type="text/css" href="../../web/css/dialog.css"> -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
    <!-- <link rel="stylesheet" type="text/css" href="../../web/css/ts.css"> -->

</head>
<style type="text/css">
    /* 操作盒子*/
    .table .opr-box .czuo-box{
        width:115px;
        /*display:flex;
        align-items:center;*/
        float:left;
    }

/*
end 完
 */
</style>
<body style="overflow-y:auto;">

<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>版本管理  /  版本详情</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute(['version_list','pro_id'=>$data['pro_id']])?>">返回列表</a>

            </div>
            <div class="add-btn cursor btn-back">
                <a href="<?=url::toRoute(['version_add','pro_id'=>$data['pro_id']])?>">继续添加</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;margin-top: 150px;">
        <div class="row content-wrap" style="margin-bottom:0;">
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">版本名称&nbsp;：</h4>
                <div class="col-md-9 input-box gray ver_name" title="<?=$data['ver_name']?>">
                    <?php if(strlen($data['ver_name']) >50):?>
                        <?php echo  substr($data['ver_name'],0,50); ?>
                    <?php else:?>
                        <?=$data['ver_name']?>
                    <?php endif;?>
                </div>
            </div>

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品名称&nbsp;：</h4>
                <div class="col-md-9 input-box gray" disabled>
                    <?=$data['pro_name']?>
                </div>
            </div>

            <!-- 建议零售价 -->
            <!--<div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">是否初始化&nbsp;：</h4>
                <div class="col-md-9 input-box gray" style="height: 40px;line-height: 40px;">
                    <input name="is_init" type="radio" value="1" <?php /*if($data['is_init'] ==1){ echo 'checked';}*/?> />
                    <span >是</span>
                    <input type="radio" name="is_init" value="0"  <?php /*if($data['is_init'] ==0){ echo 'checked';}*/?>/>
                    <span >否</span>
                </div>
            </div>-->

            <!--<div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">版本发布状态&nbsp;：</h4>
                <select name="" id="status"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;" disabled>
                    <option value="">请选择...</option>
                    <option value="-1" <?php /*if($data['status'] == '-1'){ echo 'selected';}*/?>>禁用</option>
                    <option value="0" <?php /*if($data['status'] == '0'){ echo 'selected';}*/?> >未发布</option>
                    <option value="1" <?php /*if($data['status'] == '1'){ echo 'selected';}*/?> >已发布</option>
                </select>
            </div>-->
        </div>


    </div>
</div>
</body>
</html>