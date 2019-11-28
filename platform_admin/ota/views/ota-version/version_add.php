<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" comtent="webkit"/>
    <meta http-equiv="X-UA-COMPATIBLE" content="IE=EDGE"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0"/>
    <title>Senseplay</title>
    <link rel="stylesheet" type="text/css" href="/static/css/public/fonts.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/all.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/reset.css">
    <link rel="stylesheet" type="text/css" href="/static/css/public/iconfont.css">
    <!-- <link rel="stylesheet" type="text/css" href="../../web/css/header.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="../../web/css/dialog.css"> -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
</head>
<body style="overflow-y:auto;">

<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>版本管理  /  添加版本</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box">

            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute(['ota-version/version_list','pro_id'=>$pro_data['pro_id']])?>">返回列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;margin-top: 0px;">
        <div class="row content-wrap" style="margin-bottom:0;">
            <div class="col-md-6 row-xx">
                 <h4 class="col-md-3 T1RRTittle">版本名称&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="ver_name" placeholder="输入版本名称，如：v0.0.1" class="shuru sl">
                </div>

            </div>

            <!-- 建议零售价 -->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">产品名称&nbsp;：</h4>
                <select name="" id="pro_name" disabled class="col-md-9 col-xs-12 input-box select-input" style="outline:none;">
                    <option value="<?=$pro_data['pro_id']?>" ><?=$pro_data['pro_name']?></option>
                </select>
            </div>

        </div>
        <input type="hidden" name="user_id" value="<?=$user_id?>">
        <!-- 保存操作 -->
        <div class="save-box" style="margin-top: 100px;">
            <button type="button" class="btn-sub">提交</button>
        </div>

    </div>
</div>

</body>
</html>
<script>


// 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

            var pro_name = $("#pro_name").val();

            var ver_name = $("input[name='ver_name']").val();
        var user_id = $("input[name='user_id']").val();
        var user_name = $("input[name='user_name']").val();
        var name_length = getStrLeng(ver_name);

        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        if(ver_name ==''){
            fail('请输入版本名称！');return
        }
        if(pro_name ==''){
            fail('请选择产品名称！');return
        }

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-version/version_submit')?>',
            type:'post',
            dataType:'json',
            data:{'ver_name':ver_name,'user_id':user_id,'pro_id':pro_name,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message);
                    window.location.href='<?=Url::toRoute('ota-version/version_detail')?>&ver_id='+data.data;
                }
            }
        })

    })

    function getStrLeng(str){
        var realLength = 0;
        var len = str.length;
        var charCode = -1;
        for(var i = 0; i < len; i++){
            charCode = str.charCodeAt(i);
            if (charCode >= 0 && charCode <= 128) {
                realLength += 1;
            }else{
    // 如果是中文则长度加3
                realLength += 3;
            }
        }
        return realLength;
    }
    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.confirm').click(function(){
        window.location.href="auditDetail.html";
    })
// 确认提交框操作end




</script>





