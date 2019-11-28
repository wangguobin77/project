<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<style type="text/css" media="screen">
    body{overflow-x:hidden;}
</style>
<link rel="stylesheet" href="css/v2/advertising/advertising.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
       <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <div>
            <section class='row'>
                <span class='yj'>广告</span><span class='yj'>/</span><span class='yj'>广告管理</span>
            </section>
            <h5 class="zhubt">广告管理</h5>
        </div>
    </div>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class='info ' style="background:#fff;padding:24px;flex-grow: 1;">
            <div class="">
                <div class="operate-btn" style="margin:20px 0">
                     <a style="line-height: 26px;" href="<?=Url::toRoute('broadcasts/add')?>" class="btn btn-primary save_adver mid-button">
                        添加广告
                    </a>
                </div>
                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="320px"  class="sl">标题 </th>
                        <th width="320px"  class="sl">优惠券</th>
                        <th width="320px"  class="sl">创建时间</th>
                        <th width="160px"  class="sl">状态</th>
                        <th width="300px"  class="sl" >操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($datas as $k=>$v):?>
                    <tr>
                        <td class="sl"><?=$v['title']?></td>
                        <td class="sl"><?=isset($coupon_list[$v['coupon_type_id']]['title'])?$coupon_list[$v['coupon_type_id']]['title']:'未绑定'?></td>
                        <td class="sl"><?=date('Y-m-d H:i:s',$v['created_at'])?></td>
                        <td class="sl"><?=Yii::$app->params['broadcast_status'][$v['status']]?></td>
                        <td class="sl">
                            <span class="label btn-primary cursor" data-id="<?=$v['id']?>" onclick="opencoupon(this)">绑定优惠券</span>
                            <a class="label btn-primary cursor" href="<?=Url::toRoute(['broadcasts/detail','bid'=>$v['id']])?>">查看</a>
                            <span class="label btn-primary cursor" data-id="<?=$v['id']?>" onclick="register_id(this)">推送</span>
                            <a class="label btn-primary cursor" href="<?=Url::toRoute(['schedule/list','bid'=>$v['id']])?>">推送计划</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <!-- 页码 开始 -->
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    <?= LinkPager::widget([
                        'pagination'    =>  $pages,
                        'nextPageLabel' =>  '下一页',
                        'prevPageLabel' =>  '上一页',
                        'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                        'hideOnSinglePage' => false,
                        'maxButtonCount' => 10
                    ]);?>
                </ul>
            </div>
            <!-- 页码end -->
        </div>
    </section>
</div>


<!-- 优惠券绑定表格 -->
<div class="coupon-dialog">
    <div class='coupon-div'>
        <table class="table">
            <thead>
            <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                <th width="320px"  class="sl">选择 </th>
                <th width="320px"  class="sl">名称</th>
                <th width="320px"  class="sl">价格</th>
                <th width="160px"  class="sl">价值</th>
                <th width="300px"  class="sl">有效期</th>

            </tr>
            </thead>
            <tbody>
            <form id="sub-form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <input type="hidden" name="b_id" class="b_id">
            <?php foreach ($coupon_list as $key=>$val):?>
            <tr>
                <td class="sl"><input type="checkbox" value="<?=$val['id']?>" name="coupon[<?=$key?>]"></td>
                <td class="sl"><?=$val['title']?></td>
                <td class="sl"><?=$val['price']?></td>
                <td class="sl"><?=$val['worth']?></td>
                <td class="sl"><?=date('Y-m-d H:i:s',$val['start_at'])?>至<?=date('Y-m-d H:i:s',$val['end_at'])?></td>
            </tr>
            <?php endforeach;?>
            </form>
            </tbody>
        </table>
        <div class='caozuo'>
            <button class="btn btn-default" onclick="cancelcoupon()">
                返回
            </button>
            <button class="btn btn-primary sure">
                确定
            </button>
        </div>
    </div>
    <!-- 页码 开始 -->
    <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </ul>
    </div>
    <!-- 页码end -->
</div>

<?php include_once(NAV_DIR."/footer.php");?>
<!-- jQuery 3 -->
<script  src="js/advertising/advertising.js"></script>
<script>
    $(document).ready(function(){
      //  $('.select2').select2()
    });
    /*
    jeDate("#testblue",{
        theme:{bgcolor:"#367fa9",pnColor:"#367fa9"},
        multiPane:false,
        range:" 至 ",
        format: "YYYY-MM-DD",
        donefun:function(obj) {
            console.log(obj)
        },
    });
    */

    var glo_v = {
        'b_id':0,
        'is_true':true
    }
    function opencoupon(obj){
        $('.b_id').val("");
     //   glo_v.b_id = $(obj).attr('data-id');//广告id
        $('.b_id').val($(obj).attr('data-id'));
        $('.coupon-dialog').show();
    }
    function cancelcoupon(){
        $('.coupon-dialog').hide();
    }


    // tijiao
    $('.sure').click(function(){
        if(!glo_v.is_true) return;
        if($('.b_id').val() == "")  fail('广告数据id缺失');
        var data =$('#sub-form').serializeObject();
        $.ajax({
            url:'<?=Url::toRoute(['broadcasts/bind-coupon-list'])?>',
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                console.log(data);
                if(data.code==0){
                    succ('提交成功','<?=Url::toRoute(['broadcasts/list'])?>');

                }else{
                    fail('提交失败')
                }
            }
        });
    })

    function register_id(obj){
        if(!glo_v.is_true) return;
        var reqid = $(obj).attr('data-id');
        $.ajax({
            url:'<?=Url::toRoute(['broadcasts/register_info'])?>',
            type:'post',
            dataType:'json',
            data:{'reqid':reqid,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                console.log(data);
                if(data.code==0){
                    succ('推送成功','<?=Url::toRoute(['broadcasts/list'])?>');
                }else{
                    fail('推送失败')
                }
            }
        });

    }

</script>
