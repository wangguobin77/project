

<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

    <link href="/css/public/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/public/fonts.css">
    <link rel="stylesheet" type="text/css" href="/css/public/all.css">
    <link rel="stylesheet" type="text/css" href="/css/public/reset.css">
    <link rel="stylesheet" type="text/css" href="/myfonts/iconfont.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">
    <link rel="stylesheet" type="text/css" href="/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="/css/ota/addChain.css">
    <link rel="stylesheet" type="text/css" href="/css/ts.css">


<!-- 内容区 -->
<div class="container-fluid" style="flex-grow:1;padding:0;width:100%;">

    <!-- 导航下标注 -->
    <div class="bottom-menu">
        <!-- <h4>视频管理  /  编辑视频</h4> -->
        <?php include_once(NAV_DIR."/bottom-menu.php");?>

        <div class="right-box" style="justify-content:flex-end;">
            <div class="add-btn cursor btn-back">
                <a href="<?=Url::toRoute('club_video/video_list')?>">返回列表</a>
            </div>
        </div>
    </div>
    <div class="row col-md-8 col-md-offset-2" style="padding-left:24px;padding-right:24px;">
        <div class="row content-wrap" style="margin-bottom:0;">

            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">CLIENT ID&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="client_id" placeholder="输入CLIENT ID" class="shuru sl"  value="<?=$data['client_id']?>" disabled>
                    <span class="ts">
                        *请输入正确的出参数
                    </span>
                </div>
            </div>
            <!--视频链接-->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">视频链接&nbsp;：</h4>
                <div class="col-md-9 input-box ">
                    <input type="text" name="video_uri" class="shuru sl"  value="<?=$data['video_uri']?>" disabled>
                    <span class="ts">
                        *请输入正确的出参数
                    </span>
                </div>
            </div>

            <!-- 建议零售价 -->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">STATUS&nbsp;：</h4>
                <select name="" id="status" disabled class="col-md-9 col-xs-12 input-box select-input" style="outline:none;flex-grow:0">
                    <option value="">请选择...</option>
                    <option value="0" <?php if($data['status'] ==0){echo 'selected'; } ?>>上传失败</option>
                    <option value="1" <?php if($data['status'] ==1){echo 'selected'; } ?> >上传成功</option>
                    <option value="-1" <?php if($data['status'] == '-1'){echo 'selected'; } ?> >视频删除</option>
                </select>
            </div>
            <!--是否推荐-->
            <div class="col-md-6 row-xx">
                <h4 class="col-md-3 T1RRTittle">是否推荐&nbsp;：</h4>
                <select name="" id="is_recommended" class="col-md-9 col-xs-12 input-box select-input" style="outline:none;flex-grow:0">
                    <option value="0" <?php if($data['is_recommended'] ==0){echo 'selected'; } ?>>否</option>
                    <option value="1" <?php if($data['is_recommended'] ==1){echo 'selected'; } ?> >是</option>
                </select>
            </div>

        </div>

        <input type="hidden" name="video_id" value="<?=$data['id']?>">

        <!-- 保存操作 -->
        <div class="save-box">
            <button type="button" class="btn-sub">确认修改</button>
        </div>

    </div>
</div>
<script src="/js/public/ts.js"></script>
<script>


    // 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

        var is_recommended = $("#is_recommended").val();
        var video_id = $("input[name='video_id']").val();
        var msg ='';

        var is_recommended_name = '';
        if(is_recommended == 0){
            is_recommended_name +='否';
        }else if(is_recommended ==1){
            is_recommended_name +='是';
        }
        if (is_recommended != '<?= $data['is_recommended']?>'){
            msg += '是否推荐设置为：'+is_recommended_name+'\n';
        }


        msg +="确定要修改吗？";
        if(confirm(msg)){

            $.ajax({
                url:'<?=Url::toRoute('club_video/video_submit')?>',
                type:'post',
                dataType:'json',
                data:{'is_recommended':is_recommended,'video_id':video_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);
                    }else {
                        // 保存成功
                        succ(data.message,function(){
                            window.location.href='<?=Url::toRoute('club_video/video_list')?>';
                         });

                     
                    }
                }
            })
        }

    })

    // 确认提交框操作end


</script>





