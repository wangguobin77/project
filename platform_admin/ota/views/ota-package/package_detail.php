<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

    <!-- kendo资源 -->
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
                        <button style="margin-top: -7px;" type="button" class="btn  btn-default"  onclick=javascript:location.href="<?=Url::toRoute(['package_list','ver_id'=>$pack_data['to_ver_id']])?>" >
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
                                   value="<?=$ver_list[$pack_data['to_ver_id']]?>"
                                   class="form-control my-colorpicker1 colorpicker-element bmdz"
                                   autocomplete="off"
                                   disabled >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-4 title">开始版本 :</label>
                            <select id="from_ver_id" name=""
                                    class="form-control  select2 select2-hidden-accessible  col-md-9"
                                    style="width: 100%;"
                                    tabindex="-1"
                                    aria-hidden="true" disabled>
                                <option value="">请选择</option>

                                <?php foreach ($version_list as $k=>$item):?>
                                    <option value="<?=$item['ver_id']?>" <?php if( $item['ver_id']==$pack_data['from_ver_id']){ echo 'selected';} ?> from_ver_id="<?=$item['ver_id']?>"  ><?=$item['ver_name']?></option>
                                <?php endforeach;?>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-4 title">自动下载 :</label>
                            <select  name="" id="auto_download" class="form-control select2 select2-hidden-accessible   col-md-9"
                                     style="width: 100%;"
                                     tabindex="-1"
                                     aria-hidden="true" disabled>
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
                                     disabled>
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
                                    class="form-control select2 select2-hidden-accessible  col-md-9"
                                    style="width: 100%;"
                                    tabindex="-1"
                                    aria-hidden="true" disabled>
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
                                    aria-hidden="true" disabled>
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
                                    aria-hidden="true" disabled>
                                <option value="">请选择</option>
                                <option value="zh" <?php if($pack_data['lang'] == 'zh'){ echo 'selected';}?> >中文</option>
                                <option value="en" <?php if($pack_data['lang'] == 'en'){ echo 'selected';}?> >英文</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-4 title"></label>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group col-md-12 input-xx">
                            <label class="col-md-3 title"
                                   style="width:14%!important;">升级描述: </label>
                            <textarea id="description" style="height:200px"
                                      type="text"
                                      name="description"
                                      class="des form-control my-colorpicker1 colorpicker-element beizhu"
                                      autocomplete="off"
                                      placeholder="请输入备注信息" disabled><?=$pack_data['description']?></textarea>
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
</script>