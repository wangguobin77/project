

<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<link rel="stylesheet"
          href="/static/css/public/department-add.css">
<?php include_once(NAV_DIR."/header.php");?>

    <div class="content-wrapper"
     style="min-height: 302.984px;">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 頂部導航條 -->

        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><?php include_once(NAV_DIR."/bottom-menu.php");?></li>
                <span>
                   <button type="button" class="btn  btn-default" style="margin-top: -7px;"  onclick=javascript:location.href="<?=Url::toRoute(['package_list','ver_id'=>$pack_data['to_ver_id']])?>" >
                        返回
                    </button>
                </span>
            </ol>
        </div>
       <!-- 导航end -->
        <!-- 内容区域-->
        <div class="row col-md-12">
            <div id="mymessage-form">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title">结束版本:</label>
                       <!-- <input type="text"
                               value="<?/*=$pack_data['to_ver_name']*/?>"
                               class="form-control my-colorpicker1 colorpicker-element bmdz"
                               autocomplete="off"
                               disabled >-->
                        <input type="text"
                               value="<?=$ver_list[$pack_data['to_ver_id']]?$ver_list[$pack_data['to_ver_id']]:'未知'?>"
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
                            <?php if(isset($pack_data['is_full']) && $pack_data['is_full'] >0):?>
                            <option value="">请选择任意版本</option>
                            <?php else:?>
                                <option value="">请选择</option>
                            <?php endif;?>
                            <?php foreach ($version_list as $k=>$item):?>
                                <option value="<?=$item['ver_id']?>" <?php if( $item['ver_id']==$pack_data['from_ver_id']){ echo 'selected';} ?> from_ver_id="<?=$item['ver_id']?>"  ><?=$item['ver_name']?></option>
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
                            <option value="0" <?php if($pack_data['auto_download'] == '0'){ echo 'selected';}?>>否</option>
                            <option value="1" <?php if($pack_data['auto_download'] == '1'){ echo 'selected';}?>>仅WIFI</option>
                            <option value="2" <?php if($pack_data['auto_download'] == '2'){ echo 'selected';}?>>任意网络</option>
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
                            <option value="0" <?php if($pack_data['force_update'] == '0'){ echo 'selected';}?>>否</option>
                            <option value="1" <?php if($pack_data['force_update'] == '1'){ echo 'selected';}?>>是</option>
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
                            <option value="0" <?php if($pack_data['fullupdate'] == '0'){ echo 'selected';}?>>不允许</option>
                            <option value="1" <?php if($pack_data['fullupdate'] == '1'){ echo 'selected';}?>>允许</option>
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
                            <option value="1" <?php if($pack_data['alt_style'] == '1'){ echo 'selected';}?> >通知栏提示</option>
                            <option value="2" <?php if($pack_data['alt_style'] == '2'){ echo 'selected';}?> >弹框提示</option>
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
                            <option value="zh" <?php if($pack_data['lang'] == 'zh'){ echo 'selected';}?> >中文</option>
                            <option value="en" <?php if($pack_data['lang'] == 'en'){ echo 'selected';}?> >英文</option>
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
                                  placeholder="请输入备注信息"><?=$pack_data['description']?></textarea>
                    </div>
                </div>

                <input type="hidden" name="to_ver_id" value="<?=$to_ver_data['created_ts']?>">
                <input type="hidden" name="ver_id" value="<?=$to_ver_data['ver_id']?>">
                <input type="hidden" name="pack_id" value="<?=$pack_data['sp_pack_id']?>">
                <input type="hidden" name="is_full" value="<?=$to_ver_data['is_full']?>">

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



<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script>

    $('.select2').select2()


    // 点击确认提交时弹框
    // 点击确认提交时弹框
    $('.submit-btn').click(function(){

        /*var status = $("#status").val();*/
        var description = $("#description").val();

        // var sp_pack_name = $("input[/**/name='sp_pack_name']").val();
        var to_ver_id = $("input[name='to_ver_id']").val();
        var ver_id = $("input[name='ver_id']").val();
        var sp_pack_id = $("input[name='sp_pack_id']").val();

        var from_real_ver_id =$("#from_ver_id").find("option:selected").attr('from_ver_id');
        var to_ver_name = $("input[name='to_ver_name']").val();
        var from_ver_id = $("#from_ver_id").val();
        // var to_ver_id = $("#to_ver_id").val();

        var auto_download = $('#auto_download option:selected') .val();
        var force_update = $('#force_update option:selected') .val();
        var alt_style = $('#alt_style option:selected') .val();
        var fullupdate = $('#fullupdate option:selected') .val();
        var lang = $('#lang option:selected') .val();
        var pack_id = $("input[name='pack_id']").val();
        var is_full = $("input[name='is_full']").val();


       /* var name_length = getStrLeng(sp_pack_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }*/


        /*if(sp_pack_name ==''){
            fail('请填写差分包名称');return
        }*/
        if(from_ver_id ==''){
            fail('请选择开始版本');return
        }
        if(is_full == ''){
            if(from_ver_id >=to_ver_id){
                fail('开始版本必须低于当前版本，请重新选择。');return
            }
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
        /*if(status ==''){
            alert('选择版本状态');return
        }*/

        if(is_full != ''){
            var url = "<?=Url::toRoute('ota-package/package_full_submit')?>";
        }else{
            var url = "<?=Url::toRoute('ota-package/package_submit')?>";
        }

        $.ajax({
            // 验证当前单号是否存在
            url:url,
            type:'post',
            dataType:'json',
            data:{
                'from_ver_id':from_real_ver_id,'to_ver_id':ver_id,'lang':lang,
                'alt_style':alt_style,'fullupdate':fullupdate,/*'gray_group':jsonStr,*/'sp_pack_id':sp_pack_id,'pack_id':pack_id,
                'auto_download':auto_download,'force_update':force_update,/*'status':status,*/'description':description,
                '_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message);
                    var url = '<?=url::toRoute('ota-package/package_list')?>';

                    url += '&ver_id='+ver_id ;
                    location.href = url;
                    // setTimeout(refresh(),1600);
                }
            }
        })

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

    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
    })

</script>





