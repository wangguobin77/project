<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

        <div style="display:flex;justify-content:flex-start">
            <div class="col-md-6">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-3 title'>唯一标识:</label>
                    <input  type="text" class="form-control sn sl" autocomplete="off"  placeholder="请输入唯一标识" name="sn"  >
                </div>
            </div>


            <div class="col-md-2" style='float: left;text-align:left'>
            启用   <input type="checkbox" class="minimal" id="check_status">

                <button style='margin-left: 10px' class="btn btn-primary sn-confirm">新增SN
                </button>
            </div>


            <div class="col-md-1" style='float: right;text-align:right;flex-grow:1'>

                <button style='margin-left: 10px;min-width:96px;' class="btn btn-default sn-back">返回
                </button>
            </div>


        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">唯一标识</th>
                    <th width="320px"  class="sl">创建时间			</th>

                    <th width="320px"  class="sl">状态		</th>

                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data_list) && !empty($data_list)): ?>
                    <?php foreach ($data_list as $k=>$item):?>
                        <tr>
                            <td class="sl"><?=$item['sn']?></td>
                            <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                            <td class="sl status">
                                <div class="czuo-box"  style="align-items: center;justify-content: flex-end;">

                                    <?php if($item['status']==0):?>
                                        <span class="label label-danger">禁用</span>
                                    <?php elseif($item['status']==1):?>
                                        <span class="label label-success">启用</span>
                                    <?php endif;?>

                                </div>
                            </td>
                            <td class="sl opr-box" >
                                <div class="czuo-box"  style="align-items: center;justify-content: flex-start;">
                                    <?php if($item['status']==1):?>
                                        <!-- 禁用按钮 -->
                                        <a  onclick="set_status('<?=$item['gn_id']?>',0)" class='set-btn'>禁用</a>
                                    <?php elseif($item['status']==0):?>
                                        <!-- 启用按钮 -->
                                        <a  onclick="set_status('<?=$item['gn_id']?>',1)" class='set-btn'>启用</a>

                                    <?php endif;?>
                                    <span class="xian"></span>

                                    <a sn_id="<?=$item['gn_id']?>" class='btn-del'>删除</a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>


                <input type="hidden" name="group_id" value="<?=$group_id?>">
               <!-- <input type="hidden" name="type" value="<?/*=$type*/?>">-->
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'maxButtonCount' => 10, //显示分页数量
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],

            ]);?>
        </div>


        <!-- 删除提示框 -->
        <div class="del-box delete">
            <div class="dialog">
                <span class="font_family icon-close fa fa-close"></span>
                <img src="/static/images//warning-large.png" alt="">
                <h6>是否确认删除?</h6>
                <div class="operate-del">
                    <div class="cursor cancel btn btn-default"> 取消</div>
                    <div class="cursor confirm  btn btn-primary">确认</div>
                </div>

            </div>
        </div>

    </section>
</div>


    <!-- 删除提示框 -->
    <div class="del-box delete">
        <div class="dialog">
            <span class="font_family icon-close cursor"></span>
                <img src="/static/images//warning-large.png" alt="">
                <h6 class="del-title">是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel"> 取消</div>
                <div class="cursor confirm">确认</div>

            </div>

        </div>
    </div>

<?php include_once(NAV_DIR."/footer.php");?>

<script src="/static/js/public/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    //
    $('.sn-back').click(function(){
        history.go(-1);
    })


    function set_status(sn_id,status){

        if(sn_id =='' || typeof(sn_id) == "undefined"){
            fail('设置项有问题，请检查。');return
        }

        var group_id=$('input[name="group_id"]').val();

        $.ajax({
            url:'<?=Url::toRoute('ota-group/group_sn_status')?>',
            type:'post',
            dataType:'json',
            data:{'sn_id':sn_id,'group_id':group_id,'status':status,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code == 0){
                    succ(data.message);
                    setTimeout(refresh(),3000);
                }
                else{
                    fail(data.message);
                }
            }
        })

    }

    $("#checkAll").bind("click", function () {

        if($(this).attr("checked") == 'checked'){
            $("input[name='ck']:checkbox").attr("checked", true);
        } else {
            $("input[name = 'ck']:checkbox").attr("checked", false);
        }
    });

    // 点击添加入库单 弹出添加出库单填写框
    $('.add-sn').click(function(){
        $('.add-putStorage').show();

    })

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('ota-group/group_list')?>';
        var group_name = $("input[name='group_name']").val();
        var status = $('#status option:selected') .val();//选中的值
        url += '&status=' + status + '&group_name=' + group_name;
        location.href = url;
    });


    // 点击删除
    $('.btn-del').click(function(){
        $('.delete').show();
    })
    // 添加入库取消按键
    $('.add-putStorage').find('.cancel').unbind('click').click(function(){
        $('.input-sl').val('');
        $('.add-putStorage').find('.ts').text('').hide();
        $(this).parent().parent().parent().hide();
    })
    // 添加入库关闭 按键
    $('.add-putStorage').find('.icon-close').unbind('click').click(function(){
        $('.input-sl').text('');
        $(this).parent().parent().parent().hide();
    })

    // 弹框取消按键
    $('.delete').find('.cancel').click(function(){
        $(this).parent().parent().parent().hide();
    })
    // 弹框关闭 按键
    $('.del-box').find('.icon-close').click(function(){
        $(this).parent().parent().parent().hide();
    })

    $('.drop-boxa').click(function(){
        $(this).find('.drop-select').toggle();
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

    /*
    添加sn操作
     */
    $('.sn-confirm').click(function(){
        var sn=$('.sn').val();
        var group_id=$('input[name="group_id"]').val();

        if(sn == '' || sn == undefined || sn == null){
            fail('唯一标识不能为空');
            return false;
        }
        var  status = 0;
        if($('.minimal').is(':checked')) {
            status = 1;
        }


        var name_length = getStrLeng(sn);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        var url = "<?=Url::toRoute('sn_add')?>";

        $.ajax({
            url:'<?=Url::toRoute('ota-group/sn_add')?>',
            type:'post',
            dataType:'json',
            data:{'sn':sn,'group_id':group_id,'status':status,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);
                }
                succ(data.message,function(){
                    refresh()
                });

            }
        })
    })


    $('.btn-del').click(function(){
        var sn_id = $(this).attr('sn_id');
        var group_id=$('input[name="group_id"]').val();

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('ota-group/group_sn_del')?>',
                type:'post',
                dataType:'json',
                data:{'sn_id':sn_id,'group_id':group_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        setTimeout(refresh(),1600);
                    }
                    else{
                        fail(data.message);return
                    }
                }
            })

        })
    });


     // 点击确认时判断出库单号是否正确

    // 删除产品
    function del(sn_id){

        // 删除单号
//        $('.confirm').click(function () {
//        var user_id = $("#user_id").val();
        var msg="确定要删除吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('ota-group/group_sn_del')?>',
                type:'post',
                dataType:'json',
                data:{'sn_id':sn_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message,function(){
                            refresh()
                        });

                    }
                    else{
                        fail(data.message);return
                    }
                }
            })
        }
        //})
    }

/*
点击禁用按钮
 */
$('.forbidden').click(function(){
    tips_warning('确定要禁用吗？');
})

/**
 * 点击启用按钮
 */
$('.enabled').click(function(){
    tips_warning('确定要启用吗？');
})
</script>
