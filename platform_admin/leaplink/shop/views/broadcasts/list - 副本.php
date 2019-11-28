<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<style type="text/css" media="screen">
    body{overflow-x:hidden;}
</style>
<link rel="stylesheet" href="css/advertising/advertising.css">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->

        <section class="content container-fluid">

            <div class='row'>
                <!--
                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class=" form-group col-md-12 input-xx" style="width:100%;display:flex;">
                        <label class="col-md-3 title">创建日期:</label>
                        <div class="jeinpbox form-control" style="margin-right:0">
                            <input type="text" class="jeinput" id="testblue" placeholder="起始日期 至 结束日期">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>广告标题:</label>
                        <input  type="text" class="form-control " autocomplete="off"  placeholder="请输入描述广告标题" >
                    </div>
                </div>

                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group form-group col-md-12 input-xx">
                        <label class="col-md-3 title">绑定优惠券:</label>
                        <select class="form-control select2" style="width: 100%;">
                            <option selected="selected">Alabama</option>
                            <option>Alaska</option>
                            <option>California</option>
                            <option>Delaware</option>
                            <option>Tennessee</option>
                            <option>Texas</option>
                            <option>Washington</option>
                        </select>
                    </div>
                </div>
                -->
               <!--  <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx col-md-offset-1" >

                        <div class="col-md-1 col-xs-12">
                            <button class="btn btn-primary save_adver">
                                重置条件
                            </button>
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <button class="btn btn-primary save_adver">
                                搜索广告
                            </button>
                        </div>

                        <div class="col-md-4 col-xs-12">
                            <a class="btn btn-primary save_adver" style="float:right;" >
                                创建广告
                            </a>
                        </div>
                    </div>
                </div> -->


            </div>

            <div class="">
                <div class="" style="margin-bottom:20px">
                    <a href="<?=Url::toRoute('broadcasts/add')?>" class="btn btn-primary save_adver">
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
                            <a class="label label-success cursor" href="<?=Url::toRoute(['broadcasts/detail','bid'=>$v['id']])?>">查看</a>
                            <span class="label label-success cursor" data-id="<?=$v['id']?>" onclick="register_id(this)">推送</span>
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
        </section>
    </div>
    <!-- /.content-wrapper -->
<!-- ./wrapper -->
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

    <!-- jQuery 3 -->
    <?php include_once(NAV_DIR."/footer.php");?>
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
                    succ('提交成功')
                    location.href = "<?=Url::toRoute(['broadcasts/list'])?>"
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
                    succ('提交成功')
                    location.href = "<?=Url::toRoute(['broadcasts/list'])?>"
                }else{
                    fail('提交失败')
                }
            }
        });

    }

</script>
