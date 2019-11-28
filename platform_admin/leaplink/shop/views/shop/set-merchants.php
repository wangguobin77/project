<?php
use yii\helpers\Url;
use app\models\shop\ARResource;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" type="text/css" href="layui/css/layui.css">
 <link rel="stylesheet" type="text/css" href="css/v2/partner/reg.css">
<link rel="stylesheet" type="text/css" href="css/v2/merchant/set.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 560px;">
        <!-- 导航下标注 -->
        <div class="right-box p-b-20 row head-nav">
            <section class='row'>
                <span class='yj'>设置</span><span class='yj'>/</span><span class='yj'>商户设置</span>
            </section>
            <h5 class="zhubt">商户设置</h5>
        </div>
         <!-- Main content -->
        <section class="content container-fluid" style="background: #F0F2F5;padding:24px;">
            <div class='info col-md-12' style="background:#fff;min-width:880px;padding-top:44px;">
                <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;">
                    <ul class='step'>
                        <li class='step-box'  style="width:302px;">
                            <span class='step-num step-num-active'>1</span>
                            <a class='step-des'>
                                <span class='step-des-active'>进行中</span>
                                <span class='xt xt-zctive'></span>
                            </a>
                        </li>
                        <li class='step-box' style="width:302px;">
                            <span class='step-num'>2</span>
                            <a class='step-des'>
                                <span>信息审核</span>
                                <span class='xt'></span>
                            </a>
                        </li>
                        <li class='step-box'>
                            <span class='step-num'>3</span>
                            <a class='step-des'>
                                <span>变更成功</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <form class="info-form"  id="sub-form">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                        <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-3 title"><i>*</i>商户名称:</label>
                                        <input id="name" type="text" class="form-control name" autocomplete="off" placeholder="请输入商户名称" name="name" value="<?=$shopInfo->name?>">
                                        <span class="ts-des">* 商户名称不能为空</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-3 title"><i>*</i>商户类型:</label>
                                        <select class="form-control select2 class" style="width:100%;height:40px;border-radius:4px;" id='class' name="category">
                                            <?php
                                            foreach ($category as $item):
                                                ?>
                                                <option value="<?=$item['category_id']?>" <?php if($shopInfo->shop_category_id == $item['category_id']){echo ' selected="selected" ';} ?>><?= $item['name']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="ts-des">* 商户类型不能为空</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-3 title"><i>*</i>省市区:</label>
                                        <select name="code_p" class="form-control  select2 select2-hidden-accessible col-md-3" id="province" onchange="change_p(this)"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option value="" >请选择</option>

                                        </select>
                                        <select name="code_c"  class="form-control select2 select2-hidden-accessible col-md-3" id="city" onchange="change_c(this)" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option value="" >请选择</option>

                                        </select>
                                        <select name="code_a"  class="form-control select2 select2-hidden-accessible col-md-3" id="area" style="width: 100%;" tabindex="-1" aria-hidden="true">
                                            <option value="" >请选择</option>

                                        </select>
                                        <span class="ts-des">* 地址不能为空</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-3 title"><i>*</i>详细地址:</label>
                                        <input  type="text" class="form-control detailaddress " autocomplete="off" placeholder="请输入详细地址" value="<?=$shopInfo->address?>" name="address" >
                                        <span class="ts-des">* 请输入详细地址</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-3 title"><i>*</i>营业时间:</label>
                                        <input type="text" class="jeinput form-control time" id="testblue" placeholder="营业时间" name='time' autocomplete="off" style="height:36px;"  readonly value="<?=$shopInfo->open_time?> - <?=$shopInfo->close_time?>">
                                        <input type="hidden"  class="validity" />
                                    </div>
                                    <span class="ts-des">* 请输入营业时间</span>
                                </div>
                            </div>
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx tp-view">
                                        <label class="col-md-3 title">店铺LOGO/门头照:</label>
                                        <?php
                                            $logo=0;
                                            foreach ($shopInfo->resourceRelations as $item){
                                                if($item->resource->position_type == ARResource::POSITION_TYPE1){
                                                    $logo++;
                                                    ?>
                                                <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                                    <div class="bg-icon">
                                                        <i style="display:none;" class='close_box'>
                                                            <span class="close-icon"></span>
                                                            <img id="sczp" style="width:88px;height:88px" src="<?=$item->resource->remote_uri?>"  align="top">
                                                        </i>
                                                        <input type="file"  class="imgDom">
                                                        <input type="hidden" name="logo" class="imgV">
                                                        <input type="hidden" name="logo_id" value="<?=$item->id?>">
                                                        <img src="images/v2/scicon.png" class='add-img' alt="">
                                                    </div>
                                                </div>
                                        <?php
                                            }}
                                            for($i = $logo; $i < 1; $i++){
                                            ?>
                                            <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                                <div class="bg-icon">
                                                    <i style="display:none;" class='close_box'>
                                                        <span class="close-icon"></span>
                                                        <img id="sczp" style="width:88px;height:88px"  align="top">
                                                    </i>
                                                    <input type="file"  class="imgDom">
                                                    <input type="hidden" name="logo" class="imgV">
                                                    <input type="hidden" name="logo_id" >
                                                    <img src="images/v2/scicon.png" class='add-img' alt="">
                                                </div>
                                            </div>
                                         <?php }?>

                                        <div class='des'></div>
                                    </div>
                                    <span class="ts-des" >* LOGO格式不正确</span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;">
                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx tp-view">
                                        <label class="col-md-3 title">店铺插图:</label>
                                        <div class="form-control tpimg-box dianpu-img" style="height:88px;">
                                            <?php
                                                $plate=0;
                                                foreach ($shopInfo->resourceRelations as $item){
                                                    if($item->resource->position_type == ARResource::POSITION_TYPE2){
                                                        $plate++;
                                                        ?>
                                                    <div class="bg-icon">
                                                        <i style="display:none;" class='close_box'>
                                                            <span class="close-icon"></span>
                                                            <img id="img1" src="<?=$item->resource->remote_uri?>"  align="top">
                                                        </i>
                                                        <input type="file" class="imgDom">
                                                        <input type="hidden" name="plate" class="imgV">
                                                        <input type="hidden" name="plate_id" value="<?=$item->id?>">
                                                        <img src="images/v2/scicon.png" class='add-img' alt="">
                                                    </div>
                                            <?php
                                                }}
                                            for($i = $plate; $i < 4; $i++){
                                                ?>
                                                 <div class="bg-icon">
                                                    <i style="display:none;" class='close_box'>
                                                        <span class="close-icon"></span>
                                                        <img id="img1"  align="top">
                                                    </i>
                                                    <input type="file" class="imgDom">
                                                    <input type="hidden" name="plate" class="imgV">
                                                    <input type="hidden" name="plate_id" >
                                                    <img src="images/v2/scicon.png" class='add-img' alt="">
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <span class="ts-des">* 店铺插图格式不正确</span>
                                </div>
                            </div>

                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx tp-view">
                                        <label class="col-md-3 title"><i>*</i>营业执照:</label>
                                        <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                            <?php
                                                $license=0;
                                                foreach ($shopInfo->resourceRelations as $item){
                                                    if($item->resource->position_type == ARResource::POSITION_TYPE3){
                                                        $license++;
                                                        ?>
                                                        <div class="bg-icon">
                                                            <i style="display:none;" class='close_box'>
                                                                <span class="close-icon"></span>
                                                                <img id="license_img" style="width:88px;height:88px" src=""  src="<?=$item->resource->remote_uri?>" align="top">
                                                            </i>
                                                            <input type="file" accept="image/png, image/jpg" class="imgDom" >
                                                            <input type="hidden" name="license" class="imgV">
                                                            <input type="hidden" name="license_id" value="<?=$item->id?>">
                                                            <img src="images/v2/scicon.png" class='add-img' alt="">
                                                        </div>

                                                <?php
                                                    }}
                                                for($i = $license; $i < 1; $i++){
                                                    ?>
                                                    <div class="bg-icon">
                                                        <i style="display:none;" class='close_box'>
                                                            <span class="close-icon"></span>
                                                            <img id="license_img" style="width:88px;height:88px" src="" align="top">
                                                        </i>
                                                        <input type="file" accept="image/png, image/jpg" class="imgDom" >
                                                        <input type="hidden" name="license" class="imgV">
                                                        <input type="hidden" name="license_id">
                                                        <img src="images/v2/scicon.png" class='add-img' alt="">
                                                    </div>
                                            <?php }?>
                                        </div>


                                        <div class='des'>
                                           <!--  <span>1. 三证合一证件无需上传；</span>
                                            <span>2. 组织机构代码证必须在有效期范围内；</span>
                                            <span>3. 格式要求：原件照片、扫描件或复印件加盖企</span>
                                            <span>业公章后的扫描件；</span> -->
                                        </div>
                                    </div>
                                    <span class="ts-des">* 请上传营业执照</span>
                                </div>
                            </div>

                            <div class="row col-md-12">
                               <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx tp-view">
                                        <label class="col-md-3 title"><i>*</i>经营许可证:</label>
                                        <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                            <?php
                                                $certificate=0;
                                                foreach ($shopInfo->resourceRelations as $item){
                                                    if($item->resource->position_type == ARResource::POSITION_TYPE4){
                                                        $certificate++;
                                                        ?>
                                                    <div class="bg-icon">
                                                        <i style="display:none;" class='close_box'>
                                                            <span class="close-icon"></span>
                                                            <img id="certificate" style="width:88px;height:88px" src="<?=$item->resource->remote_uri?>" >
                                                        </i>
                                                        <input type="button" accept="image/png, image/jpg" class="imgDom" >
                                                        <input type="hidden" name="certificate" class="imgV">
                                                        <input type="hidden" name="certificate_id" value="<?=$item->id?>">
                                                        <img src="images/v2/scicon.png" class='add-img' alt="">
                                                    </div>
                                                <?php
                                            }}
                                            for($i = $certificate; $i < 1; $i++){
                                                ?>
                                                <div class="bg-icon">
                                                    <i style="display:none;" class='close_box'>
                                                        <span class="close-icon"></span>
                                                        <img id="certificate" style="width:88px;height:88px" src="">
                                                    </i>
                                                    <input type="hidden" name="certificate" class="imgV">
                                                    <input type="hidden" name="certificate_id">
                                                    <img src="images/v2/scicon.png" class='add-img' alt="">
                                                </div>

                                            <?php }?>
                                        </div>
                                        <div class='des'></div>
                                    </div>
                                    <span class="ts-des">* 请上传经营许可证</span>
                                </div>
                            </div>


                             <!-- 提交操作区域 -->
                            <div class="row col-md-12" style="padding-left:200px !important;">
                                <div class="form-group col-md-3 input-xx">
                                    <button type="button" class="btn btn-block btn-primary submit-btn" onclick="put()">确定</button>
                                </div>
                            </div>
                         </div>
                    </div>
                </form>
                <!-- 点击提交以后出现已提交资质审核 -->
                <div class='checkbox' style="display:none;">
                    <div class="flex sec-step">
                        <img src="images/v2/okstep.png" alt="">
                        <h4 class='sec-title'>已提交审核</h4>
                        <span class="sec-des">信息已提交审核，请耐心等待</span>
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
         var name=$('.name').val(),
            classfiy=$("#class").find("option:selected").text(),
            province_text=$("#province").find("option:selected").text(),  //省
            city_text=$("#city").find("option:selected").text(),  //市
            area_text=$("#area").find("option:selected").text(), //区
            detailaddress=$('.detailaddress').val(),
            time=$('.time').val(),
            license=$('.license').attr('src'),
            certificate=$('.certificate').attr('src');

        if(name==''){
            fail('请输入商户名称');
            return
        }
        if(!fun.isMc(name)){
             fail('商户名称不合法');
            return
        }
        if(classfiy=='请选择'){
            fail('请输入商户类别');
            return
        }
         if(province_text == '请选择'){
            fail('请选择省份');
            return false;
        }

        if(city_text == '请选择'){
            fail('请选择市');
            return false;
        }

        if(area_text == '请选择'){
            fail('请选择区');
            return false;
        }
        if(detailaddress==''){
            fail('请填写详细地址');
            return false;
        }
        if(!fun.isMc(detailaddress)){
            fail('详细地址不合法');
            return false;
        }
        if(time==''){
            fail('请选择营业时间')
            return false;
        }
        if(license==''){
            fail('请上传营业执照')
            return false;
        }
        if(certificate==''){
            fail('请上传经营许可证')
            return false;
        }
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


