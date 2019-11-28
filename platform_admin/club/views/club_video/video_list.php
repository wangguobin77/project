<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

    <!-- kendo资源 -->
    <link href="/css/public/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/public/fonts.css">
    <link rel="stylesheet" type="text/css" href="/css/public/all.css">
    <link rel="stylesheet" type="text/css" href="/css/public/reset.css">
    <link rel="stylesheet" type="text/css" href="/myfonts/iconfont.css">

    <link rel="stylesheet" type="text/css" href="/css/roleManagement.css">
    <link rel="stylesheet" type="text/css" href="/css/dialog.css">
    <link rel="stylesheet" type="text/css" href="/css/public/nav.css">
    <link rel="stylesheet" type="text/css" href="/css/ota/chainList.css">
    <link rel="stylesheet" type="text/css" href="/css/ts.css">


    <div class="container-fluid" style="display:flex;flex-grow:1;flex-direction: column;padding:0;">
        <!-- 导航下标注 -->
        <div class="bottom-menu" style="margin-top:0;">
            <!-- <h4>视频管理</h4> -->
            <?php include_once(NAV_DIR."/bottom-menu.php");?>
            <div class="right-box">
                <!-- 状态 -->
                <div class=" col-lg-2  sousuo-rq">
                    <h6 class=" biaoti">状态：</h6>

                    <select name="" class="drop-boxa  cursor " id="status" style="outline:none;flex-grow:0">
                        <option value="">请选择...</option>
                        <option value="0" <?php if($status =='0'){ echo "selected"; }?> >上传失败</option>
                        <option value="1" <?php if($status =='1'){ echo "selected"; }?> >上传成功</option>
                        <option value="-1" <?php if($status =='-1'){ echo "selected"; }?> >视频删除</option>
                    </select>
                </div>
                <!--<div class="search-box">
                    <span class=" glyphicon glyphicon-search"></span>
                    <input type="text" name="client_name" placeholder="搜索产品名称" style="border:0;outline:none;" class="sl" value="<?/*=$client_name*/?>">
                </div>-->
                <div class="btn-ss cursor" id="search-product">搜索</div>
                <!--<div class="add-btn cursor">
                    添加产品
                </div>-->
            </div>
        </div>

        <!-- 下面的列表  -->
        <div class="table-responsive" style="padding:0 24px;">
            <table class="table">
                 <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="160px"  class="col-md-1 sl">ID</th>
                        <th width="320px"  class="col-md-2 sl">账号ID</th>
                        <th width="320px"  class="col-md-2 sl">视频链接</th>
                        <th width="320px"  class="col-md-2 sl">状态</th>
                        <th width="320px"  class="col-md-2 sl">是否推荐</th>
                        <th width="160px"  class="col-md-1 sl">创建时间</th>
                        <th width="160px"  class="col-md-1 sl">更新时间</th>
                        <th width="160px"  class="col-md-2 sl" >操作</th>
                    </tr>
                 </thead>

                 <tbody>
                 <?php foreach ($data as $k=>$item):?>
                    <tr>
                        <td class="sl"><?=$item['id']?></td>
                        <td class="sl"><?=$item['client_id']?></td>
                        <td class="sl"><?=$item['video_uri']?></td>
                        <td class="sl">
                            <?php if($item['status']==1){
                                echo '<span style="color: #00A080">上传成功</span>';
                            }else if($item['status']==0){ echo '<span style="color: #953b39">上传失败</span>';
                            }
                            else{ echo '<span style="color: #953b39">用户/管理员删除</span>';
                            }?>
                        </td>
                        <td class="sl">
                            <?php if($item['is_recommended']==1){
                                echo '<span style="color: #00A080">是</span>';
                            }else if($item['is_recommended']==0){ echo '<span style="color: #953b39">否</span>';
                            }?></td>
                        <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                        <td class="sl"><?php if($item['updated_ts'] >0) echo date('Y-m-d H:i:s',$item['updated_ts'])?></td>

                         <td class="sl opr-box" >
                            <div class="czuo-box"  style="align-items: center;justify-content: flex-end;">
                                <?php if($item['status'] !='-1'):?>
                                    <a href="<?=url::toRoute(['video_edit','id'=>$item['id']])?>" class="font_family icon-operation_edit "></a>
                                    <span class="xian"></span>
                                    <a class="font_family icon-operation_delate " onclick="del('<?=$item['id']?>')"></a>
                                <?php endif;?>
                            </div>
                        </td>
                    </tr>
                 <?php endforeach;?>
                 </tbody>
            </table>
            <!--<input type="hidden" name="user_id" value="<?/*=$user_id*/?>">-->
        </div>

        <!-- 页码 开始 -->
        <nav clas="footer" aria-label="..." style="padding-right:24px;" >
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pages pagination'],

            ]);?>
        </nav>
        <!-- 页码end -->

        <!-- 搜索时没有此信息时显示 -->
        <div class="none-info" style="display:<?php if($data) echo 'none';else echo 'block';?>;">
            <div class="row" style="display:flex;flex-wrap: wrap;justify-content: flex-start;padding:0 24px;">
                <div class="none-info" style="margin:0 auto;">
                    <img src="/images/errorview-empty.png" alt="" >
                    <h5 class="non-message">没有找到相关信息~</h5>
                </div>
            </div>
        </div>
        <!-- 无此消息提示 end -->

    </div>

    <!-- 删除提示框 -->
    <div class="del-box delete">
        <div class="dialog">
            <span class="font_family icon-close cursor"></span>
                <img src="/images/warning-large.png" alt="">
                <h6>是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel"> 取消</div>
                <div class="cursor confirm">确认</div>

            </div>

        </div>
    </div>

    <!-- 添加入库时弹框 -->
<!--     <div class="del-box add-putStorage">
        <div class="dialog">
            <span class="font_family icon-close cursor"></span>
            <h4 class="dia-title">请输入出库单号</h4>
            <div class="sl-input">
                <input type="text" name="" class="input-shuru input-sl" placeholder="输入出库单号">
                <span class="ts">*出库单号不存在</span>
            </div>
            <div class="operate-del">
                <div class="cursor cancel"> 跳过</div>
                <div class="cursor confirm">确认</div>
            </div>
        </div>
    </div> -->
<script src="/js/public/ts.js"></script>
<script type="text/javascript">

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('club_video/video_list')?>';


        var status = $('#status option:selected') .val();//选中的值
        url += '&status=' + status;
        location.href = url;
    });


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


    // 删除视频
    function del(video_id){

        var msg="确定要删除吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('club_video/video_del')?>',
                type:'post',
                dataType:'json',
                data:{'video_id':video_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
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
