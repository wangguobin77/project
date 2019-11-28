<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
     <!-- 新增加样式 -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/bottom_menu.css">
    <link rel="stylesheet" type="text/css" href="/static/css/ota/new_versionlist.css">

<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>灰度组管理  /  添加灰度组</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box" style="justify-content: flex-end;">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('group_list')?>">返回列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;margin-top: 0px;">
        <div class="row content-wrap" style="margin-bottom:0;">
            <div class="col-md-6 row-xx">
                 <h4 class="col-md-3 T1RRTittle">灰度组名称&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="group_name" placeholder="输入灰度组名称,如：测试组" class="shuru sl">
                </div>
            </div>


<!---->
<!--            <div class="col-md-6 row-xx">-->
<!--                <h4 class="col-md-3 T1RRTittle">SN&nbsp;：</h4>-->
<!--                <div class="col-md-9 input-box " >-->
<!--                    <input type="text" name="sn" placeholder="输入SN,如：e10adc3949ba59abbe56e057f20f883e" class="shuru sl">-->
<!--                </div>-->
<!--            </div>-->

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组状态&nbsp;：</h4>
                <select name="" id="status"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;">
                    <option value="">请选择...</option>
<!--                    <option value="-1">删除</option>-->
                    <option value="0">禁用</option>
                    <option value="1">正常</option>
                </select>
            </div>
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组描述&nbsp;：</h4>

                <textarea class="div-textarea col-md-9" style="outline:none;" id="description"></textarea>

            </div>
        </div>
        <input type="hidden" name="user_id" value="<?=$user_id?>">
        <!-- 保存操作 -->
        <div class="save-box" style="margin-top: 100px;">
            <button type="button" class="btn-sub">提交</button>
        </div>

    </div>
</div>
<script>
// 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

        var status = $("#status").val();
        var description = $("#description").val();

        var group_name = $("input[name='group_name']").val();
        var user_id = $("input[name='user_id']").val();
        var sn = $("input[name='sn']").val();


        if(group_name ==''){
            fail('请填写灰度组名称！');return
        }
        var name_length = getStrLeng(group_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }
//        if(sn ==''){
//            alert('请填写序列号SN！');return
//        }
        if(status ==''){
            fail('请选择灰度组状态');return
        }
        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-group/group_submit')?>',
            type:'post',
            dataType:'json',
            data:{'group_name':group_name,'user_id':user_id,'status':status,'description':description,/*'sn':sn,*/'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message,'<?=Url::toRoute('ota-group/group_detail')?>&group_id='+data.data);


                }
            }
        })

    })
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


</script>





