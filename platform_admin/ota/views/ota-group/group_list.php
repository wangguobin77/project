<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>


<div class="content-wrapper">
    <!-- Main content -->

    <section class="content container-fluid">

        <div style="display:flex;justify-content:flex-start">
            <div class="col-md-4">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-5 title'>灰度组名:</label>
                    <input  type="text" class="form-control " autocomplete="off"  placeholder="请输入灰度组"name="group_name" >
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-5 title'>描述(选填):</label>
                    <input  type="text" class="form-control " autocomplete="off"  placeholder="请输入描述"name="group_desc" >
                </div>

            </div>

            <div class="col-md-2" style='float: left;text-align:left'>
                <button class="btn btn-primary save_group">
                    增加灰度组
                </button>
            </div>


        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">灰度组名称	</th>
                    <th width="320px"  class="sl">简单描述				</th>
                    <th width="320px"  class="sl">创建时间		</th>
                    <th width="160px"  class="sl">SN数量			 </th>
                    <th width="160px"  class="sl">灰度组状态			 </th>
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data)):?>
                    <?php foreach ($data as $k=>$item):?>
                        <tr>
                            <td class="sl"><?=$item['group_name']?></td>
                            <td class="sl"><?=$item['description']?></td>
                            <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                           <!-- <td class="sl"><a href="<?/*=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id']])*/?>"><?/*=$item['sn_sum']*/?></a></td>-->
                            <td class="sl"><a href="<?=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id']])?>"><?=isset($sum_info[$item['group_id']])?$sum_info[$item['group_id']]:0?></a></td>
                            <!-- <td class="sl opr-box" > -->
                            <td class="sl">
                                <?php if($item['status']=='-1'){
                                    echo '<span class="label label-warning">删除</span>';
                                }elseif($item['status']=='0'){
                                    echo '<span class="label label-danger">禁用</span>';
                                }elseif($item['status']=='1'){

                                    echo '<span class="label label-success">启用</span>';
                                }
                                ?>
                            </td>
                            <td class="sl opr-box" >
                                <div class="czuo-box"  style='width:fit-content'>
                                    <?php /*if($item['status'] !='-1'):*/?>
                                    <!-- <a href="<?=url::toRoute(['group_edit','group_id'=>$item['group_id']])?>" class=" ">编辑</a> -->
                                    <a  class="btn-del" style="font-size: 12px;" group_id = "<?=$item['group_id']?>">删除</a>
                                    <span class="xian"></span>
                                    <?php if($item['status']==1):?>
                                        <a  style="font-size: 12px;" onclick="set_status('<?=$item['group_id']?>',0)">禁用</a>
                                    <?php elseif($item['status']==0):?>
                                        <a style="font-size: 12px;" onclick="set_status('<?=$item['group_id']?>',1)">启用</a>
                                    <?php endif;?>
                                    <span class="xian" ></span>
                                    <a href="<?=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id'],'type'=>2])?>" >SN列表</a>
                                    <?php /*endif;*/?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>

                </tbody>
            </table>
            <input type="hidden" name="user_id" value="<?=$user_id?>">

        </div>
        <!-- 页码 开始 -->
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>
        <!-- 页码end -->


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
                <h6>是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel"> 取消</div>
                <div class="cursor confirm">确认</div>

            </div>

        </div>
    </div>


<?php include_once(NAV_DIR."/footer.php");?>

<script src="../../ota/web/js/public/ts.js"></script>
<script type="text/javascript">

    // 增加灰度组
    $('.save_group').click(function(){

        var description = $("input[name='group_desc']").val();

        var group_name = $("input[name='group_name']").val();
        var user_id = $("input[name='user_id']").val();

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

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-group/group_submit')?>',
            type:'post',
            dataType:'json',
            data:{'group_name':group_name,'user_id':user_id,'description':description,/*'sn':sn,*/'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
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



    $("#checkAll").bind("click", function () {

        if($(this).attr("checked") == 'checked'){
            $("input[name='ck']:checkbox").attr("checked", true);
        } else {
            $("input[name = 'ck']:checkbox").attr("checked", false);
        }
    });
    function select_status(){
        var url = '<?=url::toRoute('ota-group/group_list')?>';
        var status = $('#status option:selected') .val();//选中的值
        var ver_id = $("input[name='ver_id']").val();
        url += '&status=' + status  ;
        location.href = url;
    }

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('ota-group/group_list')?>';
        var group_name = $("input[name='group_name']").val();

        var name_length = getStrLeng(group_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }
        var status = $('#status option:selected') .val();//选中的值
        url += '&status=' + status + '&group_name=' + group_name;
        location.href = url;
    });
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
        // css(
        // 'display', 'block').parent('').siblings('').children('ul').css('display','none');
    })

    // 点击添加入库单 弹出添加出库单填写框
    $('.add-btn').click(function(){

        window.location.href="<?=url::toRoute('ota-group/group_add')?>";

    })


    $('.btn-del').click(function(){
        var user_id = $("#user_id").val();
        var group_id = $(this).attr('group_id');

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('ota-group/group_del')?>',
                type:'post',
                dataType:'json',
                data:{'group_id':group_id,'user_id':user_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        setTimeout(refresh(),1600);
                    }
                    else{
                        fail(data.message);
                    }
                }
            })

        })
    });

    // 删除产品
    function del(group_id){
        // 删除单号
//        $('.confirm').click(function () {
        var user_id = $("#user_id").val();
        var msg="确定要删除当前灰度组吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('ota-group/group_del')?>',
                type:'post',
                dataType:'json',
                data:{'group_id':group_id,'user_id':user_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        setTimeout(refresh(),1600);
                    }
                    else{
                        fail(data.message);
                    }
                }
            })
        }
    }

    function set_status(group_id,status){

        if(group_id =='' || typeof(group_id) == "undefined"){
            fail('设置项有问题，请检查。');return
        }


        $.ajax({
            url:'<?=Url::toRoute('ota-group/group_status')?>',
            type:'post',
            dataType:'json',
            data:{'group_id':group_id,'status':status,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code == 0){
                    succ(data.message,function(){
                        refresh()
                    });

                }
                else{
                    fail(data.message);
                }
            }
        })

    }
    /**
     * 点击操作中禁用按钮
     */
    $('.forbidden').click(function(){
        tips_warning('确认要禁用吗 ？');
    })
</script>
