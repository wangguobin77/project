<?php
use yii\helpers\Url;
use app\models\shop\ARResource;
?>
<?php include_once(NAV_DIR."/header.php");?>

<link type="text/css" rel="stylesheet" href="layui/css/layui.css">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="min-height: 560px;">
            <!-- Main content -->
            <section class="content container-fluid">

            <!-- 导航下标注 -->
                <!--<div class="right-box p-b-20 row">
                    <button type="button" class="btn  btn-default">
                        返回
                    </button>
                </div>-->
                <form id="sub-form">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                    <div class="row col-md-12">
                        <!--            <form id="mymessage-form">-->
                        <div class="col-md-6">
                            <div class="form-group col-md-12 input-xx">
                                <label class="col-md-3 title">商户名称:</label>
                                <input type="text" class="form-control my-colorpicker1 colorpicker-element name" autocomplete="off" placeholder="请输入商户名称" name="name" value="<?=$shopInfo->name?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group col-md-12 input-xx">
                                <label class="col-md-3 title">商户类别:</label>
                                <select class="form-control select2" style="width: 100%;" id='class' name="category">
                                    <?php
                                    foreach ($category as $item):
                                        ?>
                                        <option value="<?=$item['category_id']?>" <?php if($shopInfo->shop_category_id == $item['category_id']){echo ' selected="selected" ';} ?>><?= $item['name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group col-md-12 input-xx select-input">
                                <label class='col-md-3 title'>所在地区:</label>
                                <select name="code_p"  class="form-control select2 select2-hidden-accessible col-md-3" id="province" onchange="change_p(this)"  tabindex="-1" aria-hidden="true">
                                    <option value="" >请选择</option>
                                </select>
                                <select name="code_c" class="form-control select2 select2-hidden-accessible col-md-3" id="city" onchange="change_c(this)" tabindex="-1" aria-hidden="true">
                                    <option value="" >请选择</option>

                                </select>
                                <select name="code_a"  class="form-control select2 select2-hidden-accessible col-md-3" id="area" tabindex="-1" aria-hidden="true">
                                    <option value="" >请选择</option>

                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group col-md-12 input-xx">
                                <label class="col-md-3 title">详细地址:</label>
                                <input type="text" class="form-control detailaddress" autocomplete="off" placeholder="详细地址" name="address" value="<?=$shopInfo->address?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class=" form-group col-md-12 input-xx" style="width:100%;display:flex;">
                                <label class="col-md-3 title">营业时间:</label>
                                <div class="jeinpbox form-control" >
                                    <input type="text" class="jeinput time" id="testblue" placeholder="开始时间至结束时间" style="border:0;" name='time' readonly value="<?=$shopInfo->open_time?> - <?=$shopInfo->close_time?>">
                                    <input type="hidden"  class="validity" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 店铺图片上传 -->
                    <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                        <div class="row content-wrap">
                            <div class="col-md-6 row-xx">
                                <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">店铺LOGO(选填):</h4>
                                <div class="col-md-8 tpimg-b">
                                    <?php
                                    $logo=0;
                                    foreach ($shopInfo->resourceRelations as $item){
                                        if($item->resource->position_type == ARResource::POSITION_TYPE1){
                                            $logo++;
                                            ?>
                                            <div class="tpimg-box dianpu-img">
                                                <div class="bg-icon">
                                                    <i style="display:block;">
                                                        <span class="close-icon"></span>
                                                        <img style="width:100px;height:100px" src="<?=$item->resource->remote_uri?>" align="top">
                                                    </i>
                                                    <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                    <input type="hidden" name="logo" class="imgV">
                                                    <input type="hidden" name="logo_id" value="<?=$item->id?>">
                                                    <img src="images/addicon_big.png" class='add-img' alt="">
                                                </div>
                                            </div>
                                            <?php
                                        }}
                                    for($i = $logo; $i < 1; $i++){
                                        ?>
                                        <div class="tpimg-box dianpu-img">
                                            <div class="bg-icon">
                                                <i style="display:none;">
                                                    <span class="close-icon"></span>
                                                    <img style="width:100px;height:100px" src="" align="top">
                                                </i>
                                                <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                <input type="hidden" name="logo" class="imgV">
                                                <input type="hidden" name="logo_id">
                                                <img src="images/addicon_big.png" class='add-img' alt="">
                                            </div>
                                        </div>
                                    <?php }?>
                              <!--       <p class="img-des col-md-9">
                                        上传的文件大小不能超过2M
                                    </p> -->
                                    <!-- 上传图片提示信息 -->
                                    <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                        *请上传满足要求的图片
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                        <div class="row content-wrap">
                            <div class="col-md-6 row-xx">
                                <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">店铺插图(选填):</h4>
                                <div class="col-md-8 tpimg-b">
                                    <?php
                                    $plate=0;
                                    foreach ($shopInfo->resourceRelations as $item){
                                        if($item->resource->position_type == ARResource::POSITION_TYPE2){
                                            $plate++;
                                            ?>
                                            <div class="tpimg-box dianpu-img">
                                                <div class="bg-icon">
                                                    <i style="display:block;">
                                                        <span class="close-icon"></span>
                                                        <img style="width:100px;height:100px" src="<?=$item->resource->remote_uri?>" align="top">
                                                    </i>
                                                    <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                    <input type="hidden" name="plate" class="imgV">
                                                    <input type="hidden" name="plate_id" value="<?=$item->id?>">
                                                    <img src="images/addicon_big.png" class='add-img' alt="">
                                                </div>
                                            </div>
                                            <?php
                                        }}
                                        for($i = $plate; $i < 4; $i++){
                                        ?>
                                        <div class="tpimg-box dianpu-img">
                                            <div class="bg-icon">
                                                <i style="display:none;">
                                                    <span class="close-icon"></span>
                                                    <img style="width:100px;height:100px" src="" align="top">
                                                </i>
                                                <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                <input type="hidden" name="plate" class="imgV">
                                                <input type="hidden" name="plate_id">
                                                <img src="images/addicon_big.png" class='add-img' alt="">
                                            </div>
                                        </div>
                                    <?php }?>
                                    <!-- 上传图片提示信息 -->
                                    <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                        *请上传满足要求的图片
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- 营业执照 -->
                    <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                        <div class="row content-wrap">
                            <div class="col-md-6 row-xx">
                                <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">营业执照:</h4>
                                <div class="col-md-8 tpimg-b">
                                    <?php
                                    $license=0;
                                    foreach ($shopInfo->resourceRelations as $item){
                                        if($item->resource->position_type == ARResource::POSITION_TYPE3){
                                            $license++;
                                            ?>
                                            <div class="tpimg-box dianpu-img">
                                                <div class="bg-icon">
                                                    <i style="display:block;">
                                                        <span class="close-icon"></span>
                                                        <img style="width:100px;height:100px" src="<?=$item->resource->remote_uri?>" align="top">
                                                    </i>
                                                    <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                    <input type="hidden" name="license" class="imgV">
                                                    <input type="hidden" name="license_id" value="<?=$item->id?>">
                                                    <img src="images/addicon_big.png" class='add-img' alt="">
                                                </div>
                                            </div>
                                            <?php
                                        }}
                                    for($i = $license; $i < 1; $i++){
                                        ?>
                                        <div class="tpimg-box dianpu-img">
                                            <div class="bg-icon">
                                                <i style="display:none;">
                                                    <span class="close-icon"></span>
                                                    <img style="width:100px;height:100px" src="" align="top">
                                                </i>
                                                <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                <input type="hidden" name="license" class="imgV">
                                                <input type="hidden" name="license_id">
                                                <img src="images/addicon_big.png" class='add-img' alt="">
                                            </div>
                                        </div>
                                    <?php }?>
                              <!--       <p class="img-des col-md-9">
                                        上传的文件大小不能超过2M
                                    </p> -->
                                    <!-- 上传图片提示信息 -->
                                    <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                        *请上传满足要求的图片
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 经营许可证 -->
                    <div class="row col-md-10 col-md-offset-1 " style="padding-left:24px;padding-right:24px; margin-bottom: 20px">
                        <div class="row content-wrap">
                            <div class="col-md-6 row-xx">
                                <h4 class="col-md-3 T1RRTittle" style="line-height:88px;">经营许可证:</h4>
                                <div class="col-md-8 tpimg-b">
                                    <?php
                                    $certificate=0;
                                    foreach ($shopInfo->resourceRelations as $item){
                                        if($item->resource->position_type == ARResource::POSITION_TYPE4){
                                            $certificate++;
                                            ?>
                                            <div class="tpimg-box dianpu-img">
                                                <div class="bg-icon">
                                                    <i style="display:block;">
                                                        <span class="close-icon"></span>
                                                        <img style="width:100px;height:100px" src="<?=$item->resource->remote_uri?>" align="top">
                                                    </i>
                                                    <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                    <input type="hidden" name="certificate" class="imgV">
                                                    <input type="hidden" name="certificate_id" value="<?=$item->id?>">
                                                    <img src="images/addicon_big.png" class='add-img' alt="">
                                                </div>
                                            </div>
                                            <?php
                                        }}
                                    for($i = $certificate; $i < 1; $i++){
                                        ?>
                                        <div class="tpimg-box dianpu-img">
                                            <div class="bg-icon">
                                                <i style="display:none;">
                                                    <span class="close-icon"></span>
                                                    <img style="width:100px;height:100px" src="" align="top">
                                                </i>
                                                <input type="button" accept="image/png, image/jpg" class="imgDom" value="+">
                                                <input type="hidden" name="certificate" class="imgV">
                                                <input type="hidden" name="certificate_id">
                                                <img src="images/addicon_big.png" class='add-img' alt="">
                                            </div>
                                        </div>
                                    <?php }?>
                              <!--       <p class="img-des col-md-9">
                                        上传的文件大小不能超过2M
                                    </p> -->
                                    <!-- 上传图片提示信息 -->
                                    <p class="img-des col-md-9 img-ts" style="color:red;font-size:10px;display:none;white-space:nowrap;">
                                        *请上传满足要求的图片
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <div class="row col-md-12">
                    <div class="row col-md-12">
                        <!-- 提交 按钮 -->
                        <div class="col-md-6 col-md-offset-1">
                            <div class="form-group col-md-6 input-xx">
                                <button type="button" class="btn btn-block btn-primary submit-btn" onclick="put()">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>
<script   src="js/metmerchants/set.js"></script>
<!-- 图片上传组件 -->
<script src="layui/layui.js" charset="utf-8"></script>
<script src="js/public/uploader.js"></script>

</body>
<script>
    var UPLOAD_URL = 'http://test.cloud.leaplink.cn/api/fs/upload/upload?dir=shop'; //上传地址
    var STATIC_REMOTE_DOMAIN = 'http://test.cloud.leaplink.cn';  //静态资源域名地址

    $(document).ready(function(){
        $('.select2').select2();
        /**
         * address初始化
         */
        var pr_str = '';//省选择
        var default_p = <?=json_encode($shopInfo['code_p'])?>;//显示省
        var default_c = <?=json_encode($shopInfo['code_c'])?>;//显示市
        var default_a = <?=json_encode($shopInfo['code_a'])?>;//显示区
        pr_str = '<option value="'+default_p.area_id+'">'+default_p.area_name+'</option>';
        for(var v in area_info){
            pr_str += '<option value="'+area_info[v].area_id+'">'+area_info[v].area_name+'</option>';
        }
        $('#province').html(pr_str);

        $('#city').html('<option value="'+default_c.area_id+'">'+default_c.area_name+'</option>');
        $('#area').html('<option value="'+default_a.area_id+'">'+default_a.area_name+'</option>');
    })
    /**
     * 信息提交
     */
    var str='';
    const put = () =>{
        reg();
        if(reg_flag==false) return false
        var data =$('#sub-form').serializeObject();
        $.ajax({
            url:'<?=Url::toRoute('shop/set-merchants')?>',
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(data.code==0){
                    succ('修改成功')
                }else{
                    fail('修改失败')
                }
            }
        });

    };

</script>


