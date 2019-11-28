<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/public/add-dialog.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content container-fluid">
            <div class="right-box p-b-20">
                <button type="button" class="btn  btn-default back-fh">返回列表</button>
                <button type="button" class="btn btn-primary">
                    <a href="<?=url::toRoute(['batch/apply_view','mid'=>$mid])?>" style="color:#fff;">申请批次</a>
                </button>
            </div>
            <div class="">
                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="160px"  class="sl">厂商名</th>
                        <th width="160px"  class="sl">设备型号</th>
                        <th width="160px"  class="sl">设备类型</th>
                        <th width="100px"  class="sl">生产年月份</th>
                        <th width="160px"  class="sl">生产批次号 </th>
                        <th width="100px"  class="sl">生产数量 </th>
                        <th width="200px"  class="sl">备  注</th>
                        <th width="156px"  class="sl">申请时间</th>
                        <th width="156px"  class="sl">审核时间</th>
                        <th width="156px"  class="sl">审核状态</th>
                        <th width="160px"  class="sl" >操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if($data):?>
                        <?php foreach($data as $key=>$val):?>
                            <tr>
                                <!-- <td class="sl"><?/*=$val['m_id']*/?></td>-->
                                <td class="sl"><?=$val['manufacture_name']?></td>
                                <?php if($val['h_type'] == 1){ ?>
                                    <td class="sl">终端</td>
                                <?php }else if($val['h_type'] == 2){?>
                                    <td class="sl">遥控器</td>
                                <?php }else{?>
                                    <td class="sl">未知</td>
                                <?php }?>
                                <td class="sl"><?=isset($val['facility_name'])?$val['facility_name']:'未设置'?></td>
                                <td class="sl"><?=$val['batch_year']?></td>
                                <td class="sl"><?=$val['batch_no']?></td>
                                <td class="sl sl-num"><?=$val['batch_count']?></td>
                                <td class="sl"><?=$val['comment']?></td>
                                <td class="sl"><?=($val['created_ts'])?date('Y-m-d H:i:s',$val['created_ts']):0?></td>
                                <td class="sl"><?=($val['check_ts'])?date('Y-m-d H:i:s',$val['created_ts']):0?></td>
                                <!-- 状态  -->
                                <?php if($val['check_status'] == 1){ ?>
                                    <td class="sl">
                                        <label class='label label-warning' >等待审批</label>
                                   
                                    </td>
                                <?php }else if($val['check_status'] == 2){?>
                                    <td class="sl">
                                    <label class='label label-danger' > 审批未通过(作废)</label>
                                   
                                    </td>
                                <?php }else if($val['check_status'] == 3){?>
                                    <td class="sl">
                                    <label class='label label-success' > 审批通过</label>
                                    </td>
                                <?php }else{?>
                                    <td class="sl">
                                    <label class='label label-default' > 未知</label>
                                    </td>
                                <?php }?>

                                <td class="sl opr-box" >
                                    <div class="czuo-box" style='width:fit-content'>
                                        <!--审核通过才有下载、sn列表、添加数量-->
                                        <?php if($val['check_status'] == 3):?>
                                            <a href="<?=Url::toRoute(['batch/export_csv','batch_id'=>$val['id']])?>" class="set-btn">下载</a>
                                            <span class="xian"></span>
                                            <a href="<?=Url::toRoute(['batch/get_batch_detail','batch_id'=>$val['id'], 'mid' => $mid])?>" class="set-btn">SN</a>
                                            <span class="xian"></span>
                                            <a data-id="<?=$val['id']?>" class="set-btn add-num">添加</a>
                                            <span class="xian"></span>
                                        <?php endif;?>
                                        <!--审核-->
                                        <?php if($val['check_status'] == 1):?>
                                            <a class="sh check" data-id="<?=$val['id']?>">审批</a>
                                        <?php endif;?>


                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <?= LinkPager::widget([
                    'pagination'    =>  $pages,
                    'nextPageLabel' =>  '»',
                    'prevPageLabel' =>  '«',
                    'options'   =>  ['class' => 'pagination pagination-sm no-margin pull-right'],
                    'hideOnSinglePage' => false,
                    'maxButtonCount' => 10
                ]);?>
            </div>

        </section>

        <!-- 审核 -->
        <div class="del-box sh-box" >
            <div class="dialog">
                <span class="font_family icon-close fa fa-close"></span>
                <img src="../../manufacture/web/images/warning-large.png" alt="">
                <h4 class="dia-title">是否审核通过?</h4>
                <div class="operate-del">
                    <div class="cursor btn btn-default" onclick="check_status(2)">不通过</div>
                    <div class="cursor btn btn-primary" onclick="check_status(3)">通过</div>
                </div>
            </div>
        </div>

        <!-- 添加入库时弹框 -->
        <div class="del-box add-putStorage">
            <div class="dialog">
                <span class="font_family icon-close cursor fa fa-close cursor"></span>
                <h4 class="dia-title">请输入想要添加的数量</h4>
                <div class="sl-input">
                    <input type="text" name="" id="count_num" class="input-shuru input-sl form-control my-colorpicker1 colorpicker-element upc_code" placeholder="输入添加数量">
                    <span class="ts">*数量不能为空！</span>
                </div>
                <div class="operate-del">
                    <button class="btn btn-primary  m-r-5" onclick='add_batch_sn()'>确认</button>
                    <button class="btn btn-default cancel">取消</button>
                    <!-- <div class="cursor cancel">取消</div>
                    <div class="cursor confirm">确认</div> -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>

<!-- REQUIRED JS SCRIPTS -->
<script>
    /*
    返回上一级
     */
    $('.back-fh').click(function(){
        window.location.href = "<?=  Url::toRoute(['manufacture/list']) ?>";
    });

    var glo_v = {
        'batch_id':''//批次号id
    }
    /**
     * 添加数量
     */
        // 点击添加数量 弹出数量框
    var num_y=
    $('.add-num').click(function(){
        glo_v.batch_id = $(this).attr('data-id');//批次号id
        $('.add-putStorage').show();
        num_y=$(this).parent().parent().parent().find('td.sl-num').text();
    });

    function add_batch_sn()
    {
        if(glo_v.batch_id == ''){
            $('.add-putStorage').find('.ts').html('参数有误');
            $('.add-putStorage').find('.ts').show();
            // fail('参数有误');
            return ;
        }

        var count = $('#count_num').val();//数量
        var reg_count=/^[0-9]\d*$/;//验证为正整数
        if(!count){
            // fail('数量不能为空');
            $('.add-putStorage').find('.ts').html('数量不能为空');
            $('.add-putStorage').find('.ts').show();
            return ;
        }
        if(!reg_count.test(count)){
            $('.add-putStorage').find('.ts').html('请输入正整数');
            $('.add-putStorage').find('.ts').show();
            return ;
        }
        // var num_z=num_y+count;
        // if(num_z>100000){
        //     $('.add-putStorage').find('.ts').html('数量超过最大值');
        //     $('.add-putStorage').find('.ts').show();
        //     return;
        // }

        var _csrf = '<?= Yii::$app->request->csrfToken ?>';
        $.ajax({
            url:'<?=Url::toRoute("batch/add_batch_sn")?>',
            type:'post',
            dataType:'json',
            data:{'batch_id': glo_v.batch_id,'count':count,'_csrf':_csrf},
            success:function (data) {
                if(data.code != 0){
                    // fail(data.message,'<?=Url::toRoute("batch/batch_list")?>');
                    // <?=Url::toRoute("batch/batch_info_list")?>
                    $('.add-putStorage').hide();
                    fail(data.message);
                    setTimeout(function(){fun1();},2000);
                    function fun1(){
                        window.location.reload();
                    }
                }else{
                    // 添加成功
                    $('.add-putStorage').hide();
                    succ(data.message);
                    setTimeout(function(){fun1();},2000);
                    function fun1(){
                        window.location.reload();
                    }

                }
            }
        })
    }
    /**
     * 审批
     */
    $('.check').click(function(){
        $('.sh-box').show();
        glo_v.batch_id = $(this).attr('data-id');
    });

    //二次点击控制
    let click_status = false;
    //确认提交审核
    function check_status(val){
        var _csrf = '<?= Yii::$app->request->csrfToken ?>';
        //二次点击直接返回
        if(click_status){return false;}
        click_status = true;

        $.ajax({
            url:'<?=Url::toRoute("sn/check_pass")?>',
            type:'post',
            dataType:'json',
            data:{'check_status':val,'id':glo_v.batch_id,'_csrf':_csrf},
            success:function (data) {
                $('.sh-box').hide();
                if(data.code != 0){
                    fail(data.message,'<?=Url::toRoute(["batch/batch_info_list",'mid'=>$mid])?>');
                }else{
                    succ(data.message,'<?=Url::toRoute(["batch/batch_info_list",'mid'=>$mid])?>');
                }
            },
            complete:function () {
                click_status = false;
            }
        })
    }




    /**
     * 全选
     */
    var isCheckAll = false;
    function swapCheck() {
        if (isCheckAll) {
            $("input[name='item']").each(function() {
                this.checked = false;
            });
            isCheckAll = false;
        } else {
            $("input[name='item']").each(function() {
                this.checked = true;
            });
            isCheckAll = true;
        }
    }
    /**
     * 审批end
     */

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>


