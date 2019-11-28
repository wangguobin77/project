<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<style type="text/css">
    table{
        table-layout:fixed;
    }
    tbody tr td{
        word-break:break-word;
    }
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

        <div style="display:flex;justify-content:flex-end">
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <!-- <label class='col-md-3 title'>唯一标识:</label> -->
                    <input  type="text" class="form-control " autocomplete="off" value="<?=$sn?>" name="sn" placeholder="输入设备SN" >
                </div>
            </div>
            <div class="col-md-1">
                <button style='margin-left: 10px' id="search-product" class="btn btn-primary">搜索
                </button>
            </div>
            <div class="col-md-1">
                <button style='margin-left: 10px' id="add_resource" class="btn btn-primary">添加 resource
                </button>
            </div>
        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="320px"  class="sl">  编号	</th>
                    <th width="160px"  class="sl">	SN（前8位）	</th>
                    <th width="320px"  class="sl">  背景大图</th>
                    <th width="320px"  class="sl">	ICON小图	</th>
                    <th width="320px"  class="sl">	终端视频	</th>
                    <th width="320px"  class="sl">	描述	</th>
                    <th width="320px"  class="sl">	创建时间</th>
                    <th width="320px"  class="sl">	更新时间</th>

                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $k=>$item):?>
                    <tr>
                        <td class=""><?=$item['resource_id']?></td>
                        <td class=""><?=$item['sn']?></td>
                        <td class="sl">
                            <img  border="0" class="pimg" src="<?=$item['resource_back_img']?>" alt="picture" width="80" height="60">
                        </td>
                        <td class="sl ">
                            <img  border="0" class="pimg"  src="<?=$item['resource_icon_img']?>" alt="picture" width="80" height="60">
                        </td>
                        <td class="sl ">
                            <video src="<?=$item['resource_rotate_video']?>"  width="80" height="60"></video>
                        </td>
                        <td class="sl"><?=$item['desc']?></td>

                        <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                        <td class="sl"><?php if($item['updated_ts'] >0) echo date('Y-m-d H:i:s',$item['updated_ts'])?></td>
                        <td class="sl opr-box" >
                            <div class="czuo-box"  style="align-items: center;    display: block;">
                                <a href="<?=url::toRoute(['edit','sn'=>$item['sn']])?>" class="font_family icon-operation_edit ">编辑</a>
                                <span class="xian"></span>
                                <a class="font_family icon-operation_delate btn-del" resource_id="<?=$item['sn']?>">删除</a>

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

<div id="outerdiv" style="position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:2;width:100%;height:100%;display:none;">
</div>
    <div id="innerdiv" style="position:absolute;">
        <img id="bigimg" style="border:5px solid #fff;" src="" />
    </div>


<?php include_once(NAV_DIR."/footer.php");?>




<script type="text/javascript">

    $(function(){
        $(".pimg").click(function(){
            var _this = $(this);//将当前的pimg元素作为_this传入函数
            imgShow("#outerdiv", "#innerdiv", "#bigimg", _this);
        });
    });

    function imgShow(outerdiv, innerdiv, bigimg, _this) {
        var src = _this.attr("src");//获取当前点击的pimg元素中的src属性
        $(bigimg).attr("src", src);//设置#bigimg元素的src属性

        /*获取当前点击图片的真实大小，并显示弹出层及大图*/
        $("<img/>").attr("src", src).load(function () {
            var windowW = $(window).width();//获取当前窗口宽度
            var windowH = $(window).height();//获取当前窗口高度
            var realWidth = this.width;//获取图片真实宽度
            var realHeight = this.height;//获取图片真实高度
            var imgWidth, imgHeight;
            var scale = 0.6;//缩放尺寸，当图片真实宽度和高度大于窗口宽度和高度时进行缩放

            if (realHeight > windowH * scale) {//判断图片高度
                imgHeight = windowH * scale;//如大于窗口高度，图片高度进行缩放
                imgWidth = imgHeight / realHeight * realWidth;//等比例缩放宽度
                if (imgWidth > windowW * scale) {//如宽度扔大于窗口宽度
                    imgWidth = windowW * scale;//再对宽度进行缩放
                }
            } else if (realWidth > windowW * scale) {//如图片高度合适，判断图片宽度
                imgWidth = windowW * scale;//如大于窗口宽度，图片宽度进行缩放
                imgHeight = imgWidth / realWidth * realHeight;//等比例缩放高度
            } else {//如果图片真实高度和宽度都符合要求，高宽不变
                imgWidth = realWidth;
                imgHeight = realHeight;
            }
            $(bigimg).css("width", imgWidth);//以最终的宽度对图片缩放

            var w = (windowW - imgWidth) / 2;//计算图片与窗口左边距
            var h = (windowH - imgHeight) / 2;//计算图片与窗口上边距
            $(innerdiv).css({"top": h, "left": w});//设置#innerdiv的top和left属性
            $(outerdiv).fadeIn("fast");//淡入显示#outerdiv及.pimg
        });

        $(outerdiv).click(function () {//再次点击淡出消失弹出层
            $(this).fadeOut("fast");
        });

    }




    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('resource/list')?>';
        var sn = $("input[name='sn']").val();

        var name_length = getStrLeng(sn);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }
        var type = $('#type option:selected') .val();//选中的值
        url += '&sn=' + sn;
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
    $('#add_resource').click(function(){
        window.location.href="<?=url::toRoute('resource/add_resource')?>";
    })

    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var resource_id = $(this).attr('resource_id');

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('resource/delete')?>',
                type:'post',
                dataType:'json',
                data:{'resource_id':resource_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
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
