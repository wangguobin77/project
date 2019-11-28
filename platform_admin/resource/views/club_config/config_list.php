<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="../../club/web/css/add-dialog.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid" >
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default" onclick=location.href="<?=Url::toRoute(('club_client/client_list'))?>">
                返回
            </button>
            <button  type="button" class="btn add-btn add-sn  btn-primary btn-add">
                添加类型
            </button>

        </div>



        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">编号	</th>
                    <th width="320px"  class="sl">	来源ID				</th>
                    <th width="320px"  class="sl">	来源类型			</th>

                    <th width="320px"  class="sl">	创建时间					</th>


                    <th width="300px"  class="sl" >操作
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $k=>$item):?>
                    <tr>
                        <td class="sl">
                            <?=$item['id']?>
                        </td>
                        <td class="sl"><?=$item['client_id']?></td>
                        <td class="sl"><?=$item['type']?></td>

                        <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>

                        <td class="sl opr-box" >
                            <div class="czuo-box"  style="align-items: center;">
                                <a id="<?=$item['id']?>" class="btn-del" >删除</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>



                </tbody>
            </table>
            <input type="hidden" name="client_id" value="<?=$client_id?>">
        </div>
        <nav class="footer" aria-label="..." style="padding-right:24px;" >
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pages pagination'],

            ]);?>
        </nav>


        <!-- 添加提示框 -->
        <div class="del-box add-dialogs">
            <div class="dialog">
                <span class=" icon-close cursor fa fa-close dialog-close"></span>
                <h4 class="dia-title">类型新增</h4>
                <div class="sl-input">
                    <input type="text" name="input-sl" id="role-name" class="input-shuru input-sl form-control" placeholder="for example:image">
                    <!--                 <span class="ts">*名称不能为空！</span> -->
                </div>
                <div class="operate-del">
                    <button class="btn btn-primary confirm m-r-5" >确认</button>
                    <button class="btn btn-default cancel">取消</button>
                </div>
            </div>
        </div>

    </section>
</div>



    <!-- 删除提示框 -->
    <div class="del-box delete">
        <div class="dialog">
            <span class="font_family icon-close cursor"></span>
                <img src="../../club/web/images/warning-large.png" alt="">
                <h6>是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel"> 取消</div>
                <div class="cursor confirm">确认</div>

            </div>

        </div>
    </div>


<?php include_once(NAV_DIR."/footer.php");?>

<script src="../../club/web/bower_components/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript">
    $('.select2').select2()
    $("#checkAll").bind("click", function () {

        if($(this).attr("checked") == 'checked'){
            $("input[name='ck']:checkbox").attr("checked", true);
        } else {
            $("input[name = 'ck']:checkbox").attr("checked", false);
        }
    });

    // 点击添加入库单 弹出添加出库单填写框
    // $('.add-sn').click(function(){
    //     $('.add-putStorage').show();
    //
    // })

    //添加type 显示
    $('.btn-add').click(function(){
        $('.add-dialogs').show();
    })
    //添加type 关闭
    $('.cancel').click(function(){
        $('.add-dialogs').hide();
    })
    $('.dialog-close').click(function(){
        $('.add-dialogs').hide();
    })

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('club_config/config_list')?>';
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
        window.location.href="";
    })
    // 弹框关闭 按键
    $('.del-box').find('.icon-close').click(function(){
        $(this).parent().parent().parent().hide();
        window.location.href="";
    })

    $('.drop-boxa').click(function(){
        $(this).find('.drop-select').toggle();
        // css(
        // 'display', 'block').parent('').siblings('').children('ul').css('display','none');
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




    $('.add-dialogs').find('.confirm').click(function(){
        var type=$('.input-sl').val();
        var client_id=$('input[name="client_id"]').val();

        if(type == ""){
            fail('请输入TYPE');return
        }

        var name_length = getStrLeng(type);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');
            return
        }

        //var url = "<?//=Url::toRoute('type_submit')?>//";

        $.ajax({
            url:'<?=Url::toRoute('club_config/type_submit')?>',
            type:'post',
            dataType:'json',
            data:{'type':type,'client_id':client_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
//                    console.log(data);
                if(data.code > 0){
                    fail(data.message);return
                }
                succ(data.message,function(){
                        refresh()
                });
            }
        })




    })


     // 点击确认时判断出库单号是否正确



    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var id = $(this).attr('id');

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('club_config/type_del')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        window.location.reload();
                    }
                    else{
                        fail(data.message);return
                    }
                }
            })

        })
    });
    // 删除产品
    function del(id){

        // 删除单号
//        $('.confirm').click(function () {
//        var user_id = $("#user_id").val();
        var msg="确定要删除吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('club_config/type_del')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        window.location.reload();
                    }
                    else{
                        fail(data.message);return
                    }
                }
            })
        }
        //})
    }

</script>
