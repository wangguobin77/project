<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

    <!-- <link rel="stylesheet" type="text/css" href="../../ota/web/css/dialog.css"> -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">
 <!--     <link rel="stylesheet" type="text/css" href="../../ota/web/css/ts.css"> -->
<style> .checkbox { position: relative; height: 30px; } .checkbox input[type='checkbox'] { position: absolute; left: 0; top: 0; width: 20px; height: 20px; opacity: 0; } .checkbox label { position: absolute; left: 30px; top: 0; height: 20px; line-height: 20px; } .checkbox label:before { content: ''; position: absolute; left: -30px; top: 0; width: 20px; height: 20px; border: 1px solid #ddd; border-radius: 50%; transition: all 0.3s ease; -webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; } .checkbox label:after { content: ''; position: absolute; left: -22px; top: 3px; width: 6px; height: 12px; border: 0; border-right: 1px solid #fff; border-bottom: 1px solid #fff; background: #fff; transform: rotate(45deg); -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); -ms-transform: rotate(45deg); transition: all 0.3s ease; -webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; } .checkbox input[type='checkbox']:checked + label:before { background: #4cd764; border-color: #4cd764; } .checkbox input[type='checkbox']:checked + label:after { background: #4cd764; } </style>

<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
<!--         <h4>版本管理  / 差分包管理 /  编辑灰度组</h4>
 -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>
        <div class="right-box">

            <div class="add-btn cursor btn-back" style="width: 110px;">

                <a href="<?=url::toRoute(['package_list','ver_id'=>$pack_data['to_ver_id']])?>">返回差分包列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;margin-top: 0px;">
        <div class="row content-wrap" style="margin-bottom:0;">

            <div class="col-md-12 row-xx">
                <h4 class="col-md-3 T1RRTittle">灰度组&nbsp;：</h4>
                <div class="col-md-9 select-input " style="height: 40px;line-height: 40px;">
                    <?php foreach ($group_data as $key=>$item):?>
                        <input type='checkbox' id='checkbox' value="<?=$key?>"  <?php
                            if(isset($item['key']) && !empty($item['key']) && $item['key'] == $key ){
                                echo 'checked';
                            }
                        ?> name='group_checkbox'> <label for='checkbox2'><?=$item['group_name']?></label>
                    <?php endforeach;?>
                </div>
            </div>
        </div>

        <input type="hidden" name="to_ver_id" value="<?=$ver_id?>">
        <input type="hidden" name="pack_id" value="<?=$pack_id?>">
<!--        <input type="hidden" name="data_json" value="--><?//=$data_json?><!--">-->

        <!-- 保存操作 -->
        <div class="save-box" style="margin-top: 100px;">
            <button type="button" class="btn-sub">保存</button>
        </div>

    </div>
</div>
<script>


// 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){
        var pack_id = $("input[name='pack_id']").val();
        var data_json = $("input[name='data_json']").val();
        var obj = document.getElementsByName("group_checkbox");
        var check_val = [];
        var row = {};

        for(k in obj){
            if(obj[k].checked)
                check_val.push(obj[k].value);
        }

        var jsonStr = JSON.stringify(check_val);

        if(jsonStr === '[]' ){
            fail('请选择灰度测试组！');return
        }

        $.ajax({
            url:'<?=Url::toRoute('ota-package/package_group_submit')?>',
            type:'post',
            dataType:'json',
            data:{
                'gray_group':jsonStr,'pack_id':pack_id,
                '_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message);
                    setTimeout(refresh(),1600);
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

// 确认提交框操作end




</script>





