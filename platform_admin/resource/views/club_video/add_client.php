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
    <link href="/css/public/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/public/fonts.css">
    <link rel="stylesheet" type="text/css" href="/css/public/all.css">
    <link rel="stylesheet" type="text/css" href="/css/public/reset.css">
    <link rel="stylesheet" type="text/css" href="/myfonts/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="/css/ota/addChain.css">
    <link rel="stylesheet" type="text/css" href="/css/ts.css">
</head>
<body style="overflow-y:auto;">

<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>产品管理  /  添加产品</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box" style="justify-content:flex-end;">

            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('club_client/client_list')?>">返回列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;">
        <div class="row content-wrap" style="margin-bottom:0;">

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">CLIENT ID&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="client_id" placeholder="输入CLIENT ID" class="shuru sl">
                    <span class="ts">
                        *请输入正确的参数
                    </span>
                </div>
            </div>

            <div class="col-md-6 row-xx">
                 <h4 class="col-md-3 T1RRTittle">CLIENT NAME ：</h4>
                <div class="col-md-9 input-box ">

                    <input type="text" name="client_name" placeholder="输入CLIENT NAME" class="shuru sl">
                    <span class="ts">
                        *请输入正确的参数
                    </span>
                </div>

            </div>

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">状态 ：</h4>

                <select name="" id="status"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;flex-grow:0">
                    <option value="">请选择...</option>
                    <option value="0" >禁用</option>
                    <option value="1" selected>启用</option>
                </select>

            </div>


        </div>
        <!-- 保存操作 -->
        <div class="save-box " style="margin-top: 100px;">
            <button type="button" class="btn-sub">提交</button>
        </div>

    </div>
</div>

</body>
</html>
<script>
// 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){
        var client_name = $("input[name='client_name']").val();
        var client_id = $("input[name='client_id']").val();
        var status = $("#status").val();
        /*var user_id = $("input[name='user_id']").val();
        var user_name = $("input[name='user_name']").val();*/
        if(client_name ==''){
            fail('请填写CLIENT NAME！');return
            //$("input[name='product_name']").next().show();return
        }
        if(client_id ==''){
            fail('请填写CLIENT ID！');return
            //$("input[name='product_code']").next().show();return
        }

        var name_length = getStrLeng(client_name);
        var code_length = getStrLeng(client_id);

        if( name_length > 128 || code_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('club_client/client_submit')?>',
            type:'post',
            dataType:'json',
            data:{'client_name':client_name,'client_id':client_id,'status':status,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    // alert(data.message);return
                    fail(data.message);return
                }else {
                    console.log(data);
                    // 保存成功
                    // alert(data.message);
                    // window.location.href='<?=Url::toRoute('client_detail')?>?pro_id='+data.data;
                    var src='<?=Url::toRoute('club_client/client_detail')?>&client_id='+data.data;
                    succ(data.message,src);
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





