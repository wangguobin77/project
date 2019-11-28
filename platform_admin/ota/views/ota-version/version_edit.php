

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
<!--     <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="../../web/css/dialog.css"> -->
    <link rel="stylesheet" type="text/css" href="/static/css/ota/addChain.css">

</head>
<body style="overflow-y:auto;">

<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>版本管理  /  编辑版本</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box" style="justify-content:flex-end;">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute(['version_list','pro_id'=>$data['pro_id']])?>">返回列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;">
        <div class="row content-wrap" style="padding-left:24px;padding-right:24px;margin-top: 10px;">
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">版本名称&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="ver_name" placeholder="输入版本名称，如：v0.0.1" class="shuru sl" value="<?=$data['ver_name']?>" >
                </div>
            </div>

            <!-- 建议零售价 -->
            <div class="col-md-6 row-xx" >
                <h4 class="col-md-3 T1RRTittle">产品名称&nbsp;：</h4>
                <select name="" id="pro_name"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;" disabled>

                    <?php /*foreach ($version_types as $k=>$item):*/?><!--
                        <option value="<?/*=$item*/?>" <?php /*if($item==$data['pro_name']){ echo 'selected';} */?> pro_name= "<?/*=$item*/?>" ><?/*=$item*/?></option>
                    --><?php /*endforeach;*/?>
                    <option value="<?=$data['pro_id']?>"><?=$data['pro_name']?></option>
                </select>
            </div>

            <!--<div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">是否初始化&nbsp;：</h4>
                <div class="col-md-9 input-box gray" style="height: 40px;line-height: 40px;">
                    <input name="is_init" type="radio" value="1" <?php /*if($data['is_init'] == '1'){ echo 'checked';}*/?> is_init_name="是" />
                    <span >是</span>
                    <input type="radio" name="is_init" value="0" <?php /*if($data['is_init'] == '0'){ echo 'checked';}*/?> is_init_name="否"/>
                    <span >否</span>
                </div>
            </div>-->

            <!--<div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">版本发布状态&nbsp;：</h4>
                <select name="" id="status"  class="col-md-9 col-xs-12 input-box select-input" style="outline:none;">
                    <option value="">请选择...</option>
                    <option value="-1" <?php /*if($data['status'] == '-1'){ echo 'selected';}*/?> status_name="禁用">禁用</option>
                    <option value="0" <?php /*if($data['status'] == '0'){ echo 'selected';}*/?> status_name="未发布" >未发布</option>
                    <option value="1" <?php /*if($data['status'] == '1'){ echo 'selected';}*/?> status_name="已发布">已发布</option>
                </select>
            </div>-->
        </div>
        <input type="hidden" name="user_id" value="<?=$user_id?>">

        <input type="hidden" name="ver_id" value="<?=$data['ver_id']?>">
        <!-- 保存操作 -->
        <div class="save-box">
            <button type="button" class="btn-sub">确认修改</button>
        </div>

    </div>
</div>

</body>
</html>
<script>


    // 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

        var pro_name = $("#pro_name").val();
        //var pro_name =$("#pro_name").find("option:selected").attr('pro_name');
        var status_name =$("#status").find("option:selected").attr('status_name');
        var is_init_name =$("input[name='is_init']:checked").attr('is_init_name');

        var status = $("#status").val();

        var ver_name = $("input[name='ver_name']").val();
        var user_id = $("input[name='user_id']").val();
        var ver_id = $("input[name='ver_id']").val();
        var old_data_json = $("input[name='old_data_json']").val();

        if(ver_name.replace(/(^s*)|(s*$)/g, "").length ==0 || typeof(ver_name) == "undefined" || ver_name == ''){
            fail('请填写版本名称！');return
        }


        if(typeof(pro_name) == "undefined" ){
            fail('请填写产品名称！');return
        }
        var name_length = getStrLeng(ver_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        var msg ='您将要修改的信息如下，请确认';

        if(ver_name != '<?=$data['ver_name']?>'){
            msg += '\n版本名称：'+ver_name+'\n';

        }
        //if(pro_id != '<?//=$data['pro_id']?>//'){
        //    msg += '\n产品名称：'+pro_name+'\n';
        //}

        msg +="确定要修改吗？";
        if(confirm(msg)){
            $.ajax({
                // 验证当前单号是否存在
                url:'<?=Url::toRoute('ota-version/version_edit_submit')?>',
                type:'post',
                dataType:'json',
                data:{'ver_name':ver_name,'user_id':user_id,'ver_id':ver_id,'pro_name':pro_name,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);
                    }else {
                        // 保存成功
                        succ(data.message);
                        window.location.href='<?=Url::toRoute('ota-version/version_detail')?>&ver_id='+data.data;
                    }
                }
            })
        }

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





