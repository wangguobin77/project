<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
          href="/static/css/public/department-add.css">
<!-- 内容区 -->
<div class="content-wrapper"
     style="min-height: 302.984px;">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 頂部導航條 -->

        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><?php include_once(NAV_DIR."/bottom-menu.php");?></li>
                <span>
                    <button type="button" class="btn  btn-default" style="margin-top: -7px;" onclick=javascript:location.href="<?=Url::toRoute('ota-version/version_list')?>&pro_id=<?=$version_data['pro_id']?>" >
                        返回
                    </button>
                </span>

            </ol>
        </div>
        <!-- 導航條end -->

        <!-- 内容区域-->
        <div class="row col-md-12">
            <div id="mymessage-form">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">结束版本:</label>
                        <input type="text"
                               value="<?=$version_data['ver_name']?>"
                               class="form-control my-colorpicker1 colorpicker-element bmdz"
                               autocomplete="off"
                               disabled >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">开始版本 :</label>
                        <select id="from_ver_id" name=""
                                class="form-control select2 select2-show-accessible col-md-9"
                                style="width: 100%;"
                                tabindex="-1"
                                aria-hidden="true">
                            <option value="">请选择</option>
                            <?php foreach ($version_list as $k=>$item):?>
                                <option value="<?=$item['ver_id']?>" from_ver_id ="<?=$item['ver_id']?>" ><?=$item['ver_name']?></option>
                            <?php endforeach;?>
                        </select>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">自动下载 :</label>
                        <select  name="" id="auto_download"
                                 class="form-control select2 select2-hidden-accessible col-md-9"
                                 style="width: 100%;"
                                 tabindex="-1"
                                 aria-hidden="true">
                            <option value="">请选择</option>
                            <option value="0" selected>否</option>
                            <option value="1">仅WIFI</option>
                            <option value="2">任意网络</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">强制升级 :</label>
                        <select name="" id="force_update"
                                class="form-control select2 select2-hidden-accessible col-md-9"
                                style="width: 100%;"
                                tabindex="-1"
                                aria-hidden="true">
                            <option value="">请选择</option>
                            <option value="0" selected>否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">整包升级 :</label>
                        <select name="" id="fullupdate"
                                class="form-control select2 select2-hidden-accessible col-md-9"
                                style="width: 100%;"
                                tabindex="-1"
                                aria-hidden="true">
                            <option value="">请选择</option>
                            <option value="0" selected>不允许</option>
                            <option value="1">允许</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">提示类型 :</label>
                        <select name="" id="alt_style"
                                class="form-control select2 select2-hidden-accessible col-md-9"
                                style="width: 100%;"
                                tabindex="-1"
                                aria-hidden="true">
                            <option value="">请选择</option>
                            <option value="1" selected>通知栏提示</option>
                            <option value="2">弹框提示</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">语言 :</label>
                        <select name="" id="lang"
                                class="form-control select2 select2-hidden-accessible col-md-9"
                                style="width: 100%;"
                                tabindex="-1"
                                aria-hidden="true">
                            <option value="">请选择</option>
                            <option value="zh" >中文</option>
                            <option value="en" selected>英文</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title-center"
                               style="width:14%!important">升级描述(选填): </label>
                        <textarea id="description" style="height:200px"
                                  type="text"
                                  name="description"
                                  class="des form-control my-colorpicker1 colorpicker-element beizhu"
                                  autocomplete="off"
                                  placeholder="请输入备注信息"></textarea>
                    </div>
                </div>

                <input type="hidden" name="to_ver_id" value="<?=$version_data['created_ts']?>">
                <input type="hidden" name="ver_id" value="<?=$ver_id?>">

                <input type="hidden" name="sp_pack_id" value="0">

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title"></label>
                        <button class="btn btn-block btn-primary submit-btn">提交</button>
                    </div>
                </div>



            </div>
        </div>

    </section>
</div>

<!-- </div> -->

<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script>

    $('.select2').select2()
/*
    点击确认提交时弹框
 */
    $('.submit-btn').click(function(){

        //var status = $("#status").val();
        var description = $("#description").val();

        // var sp_pack_name = $("input[name='sp_pack_name']").val();
        var to_ver_id = $("input[name='to_ver_id']").val();
        var ver_id = $("input[name='ver_id']").val();

        var from_real_ver_id =$("#from_ver_id").find("option:selected").attr('from_ver_id');

        // var from_ver_name =$("#to_ver_id option:selected");
        //console.log(from_ver_name);
        var to_ver_name = $("input[name='to_ver_name']").val();
        var from_ver_id = $("#from_ver_id").val();


        var auto_download = $('#auto_download option:selected') .val();
        var force_update = $('#force_update option:selected') .val();
        var alt_style = $('#alt_style option:selected') .val();
        var fullupdate = $('#fullupdate option:selected') .val();
        var lang = $('#lang option:selected') .val();

        // var name_length = getStrLeng(sp_pack_name);
        // if( name_length > 128 ) {
        //     fail('字符过长，请重新输入！');return
        // }


        // if(sp_pack_name ==''){
        //     fail('请填写差分包名称');return
        // }
        if(from_ver_id ==''){
            fail('请选择开始版本');return
        }
        if(from_ver_id == to_ver_id){//对比两个本版 两个值如果一样说明开始版本高于或者等于结束版本
            fail('开始版本必须低于当前版本，请重新选择。');return
        }
        if(auto_download =='' || typeof(auto_download) == "undefined"){
            fail('请选择自动下载');return
        }
        if(force_update =='' || typeof(force_update) == "undefined"){
            fail('请选择强制升级');return
        }
        if(fullupdate =='' || typeof(fullupdate) == "undefined"){
            fail('请选择允许整包升级');return
        }

        if(alt_style =='' || typeof(alt_style) == "undefined"){
            fail('请选择提示类型');return
        }

        if(lang =='' || typeof(lang) == "undefined"){
            fail('请选择语言');return
        }

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-package/package_submit')?>',
            type:'post',
            dataType:'json',
            data:{
                /*'sp_pack_name':sp_pack_name,*/'from_ver_id':from_real_ver_id,'to_ver_id':ver_id,'lang':lang,
                'alt_style':alt_style,'fullupdate':fullupdate,'sp_pack_id':0,
                'auto_download':auto_download,'force_update':force_update,/*'status':status,*/'description':description,
                '_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message,function(){
                        refresh()
                    });
                }
            }
        })

    })
    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
    })

// 确认提交框操作end

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


</script>





