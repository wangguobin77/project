<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
<!-- 新增加样式 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>灰度组管理  /  灰度组详情</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('group_list')?>">返回列表</a>

            </div>
            <div class="add-btn cursor btn-back">

                <a href="<?=Url::toRoute('group_add')?>">继续添加</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;margin-top: 150px;">
        <div class="row content-wrap" style="margin-bottom:0;">

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组名称&nbsp;：</h4>
                <div class="col-md-9 input-box " style="line-height: 40px;height: 40px;">
                    <?=$data['group_name']?>
                </div>
            </div>
           <!-- <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">SN&nbsp;：</h4>
                <div class="col-md-9 input-box " style="line-height: 40px;height: 40px;" >
                    <?/*=$data['sn']*/?>
                </div>
            </div>-->

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组状态&nbsp;：</h4>
                <select name="" id="status"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;" disabled>
                    <option value="">请选择...</option>
                    <!--<option value="-1" <?php /*if($data['status'] == '-1'){ echo 'selected';}*/?>>删除</option>-->
                    <option value="0" <?php if($data['status'] == '0'){ echo 'selected';}?> >禁用</option>
                    <option value="1" <?php if($data['status'] == '1'){ echo 'selected';}?> >正常</option>
                </select>
            </div>
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组描述&nbsp;：</h4>
                <div class="col-md-9 input-box " style="line-height: 40px;height: 40px;" >
                    <?=$data['description']?>
                </div>
            </div>

        </div>


    </div>
</div>
