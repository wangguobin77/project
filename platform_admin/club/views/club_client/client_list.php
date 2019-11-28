<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

        <div style="display:flex;justify-content:flex-end">
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <!-- <label class='col-md-3 title'>唯一标识:</label> -->
                    <input  type="text" class="form-control " autocomplete="off" value="<?=$client_name?>" name="client_name" placeholder="搜索产品名称" >
                </div>
            </div>
            <div class="col-md-1">
                <button style='margin-left: 10px' id="search-product" class="btn btn-primary">搜索
                </button>
            </div>
        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">  编号	</th>
                    <th width="320px"  class="sl">	来源	</th>
                    <th width="320px"  class="sl">	来源ID</th>
                    <th width="320px"  class="sl">  状态</th>
                    <th width="320px"  class="sl">	创建时间	</th>
                    <th width="320px"  class="sl">	更新时间</th>

                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $k=>$item):?>
                    <tr>
                        <td class="sl"><?=$item['id']?></td>
                        <td class="sl"><?=$item['client_name']?></td>
                        <td class="sl"><?=$item['client_id']?></td>
                        <td class="sl">
                            <?php if($item['status']==1){ echo '<span class="label label-success">启用</span>';}else{ echo '<span class="label label-danger">禁用</span>'; }?>
                        </td>
                        <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                        <td class="sl"><?php if($item['updated_ts'] >0) echo date('Y-m-d H:i:s',$item['updated_ts'])?></td>
                        <td class="sl opr-box" >
                            <div class="czuo-box"  style="align-items: center;">
                                <a href="<?=url::toRoute(['client_edit','client_id'=>$item['client_id']])?>" class="font_family icon-operation_edit ">编辑</a>
                                <span class="xian"></span>
                                <a class="font_family icon-operation_delate btn-del" client_id="<?=$item['client_id']?>">删除</a>
                                <span class="xian" ></span>
                                <a href="<?=url::toRoute(['/club_config/config_list','client_id'=>$item['client_id']])?>">来源类型管理</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>



                </tbody>
            </table>
        </div>
        <nav class="footer" aria-label="..." style="padding-right:24px;" >
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pages pagination'],

            ]);?>
        </nav>


        <!-- 删除提示框 -->
        <div class="del-box delete">
            <div class="dialog">
                <span class="font_family icon-close fa fa-close"></span>
                <img src="../../club/web/images/warning-large.png" alt="">
                <h6>是否确认删除?</h6>
                <div class="operate-del">
                    <div class="cursor cancel btn btn-default"> 取消</div>
                    <div class="cursor confirm  btn btn-primary">确认</div>
                </div>

            </div>
        </div>

    </section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>




<script type="text/javascript">

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('club_client/client_list')?>';
        var client_name = $("input[name='client_name']").val();

        var name_length = getStrLeng(client_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }
        var type = $('#type option:selected') .val();//选中的值
        url += '&client_name=' + client_name;
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
    })

    // 点击添加入库单 弹出添加出库单填写框
    $('.add-btn').click(function(){
        window.location.href="<?=url::toRoute('club_client/add_client')?>";
    })

    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var client_id = $(this).attr('client_id');

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('club_client/client_del')?>',
                type:'post',
                dataType:'json',
                data:{'client_id':client_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message,function(){
                            window.location.reload();
                        });
                    }
                    else{
                        fail(data.message);
                    }
                }
            })

        })
    });


    // 删除产品
    function del(client_id){
        // //删除框出现
        var user_id = $("#user_id").val();
        var msg="确定要删除吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('club_client/client_del')?>',
                type:'post',
                dataType:'json',
                data:{'client_id':client_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message,function(){
                            window.location.reload();
                        });
                     
                    }
                    else{
                        fail(data.message);
                    }
                }
            })
        }
    }

</script>
