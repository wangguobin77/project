<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="/static/css/manufacture/sn.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content container-fluid">
            <div class="right-box p-b-20">
                <button  type="button" class="btn btn-default" id="backToList">
                    返回列表
                </button>
            </div>
            <div class="row col-md-12">
                <form id="manufacture_form">
                    <div class="col-md-12">
                        <div class="form-group col-md-6 input-xx">
                            <label class='col-md-4 title'>公司名称:</label>
                            <div class='form-control' style='border:none'>
                            <?=$manufacture_info['name']?>（<?=$manufacture_info['name_en']?>）
                            </div>
                            <!-- <input style='visibility:hidden'  type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off"  placeholder="请输入中文名称" > -->
                        </div>
                        <div class="form-group col-md-6 input-xx">
                            <label class='col-md-4 title'>公司名缩写:</label>
                            <input value="<?=$manufacture_info['short']?>" <?=isset($manufacture_info['short'])?"disabled":""?> style='width:50%;' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" minlength="2" id="m_short" placeholder="请输入两个字符" >
                            <div class="col-md-6">
                                <button type="button" data-mid="<?=$manufacture_info['id']?>" data-short-id="<?=$manufacture_info['m_short_id']?>" title="<?=Yii::t('app',' MANUFACTURE_SAVE')?>" class="btn btn-primary m-l-5 m-r-5 company_save_btn <?=isset($manufacture_info['short'])?'none':''?>">保存</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                   
                    </div>
                </form>
            </div>
            <div class="">

                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="160px"  class="sl">终端名</th>
                        <th width="140px"  class="sl">英文名</th>
                        <th width="140px"  class="sl">型号</th>
                        <th width="560px"  class="sl">缩写<?=Yii::t('app','4CHARACTER')?></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php if(count($device_info_list) > 0){ ?>

                            <?php foreach ($device_info_list as $key=>$val){ ?>
                                <tr>
                                    <td class="sl"><?=$val['name']?></td>
                                    <td class="sl"><?=$val['name_en']?></td>
                                    <td class="sl zz-box"><?=$val['type']?></td>
                                    <td class="sl opr-box" >
                                        <div class="row col-md-12 sn-td-box">
                                            <input type="text" value="<?=$val['device_type_short']?>" <?=isset($val['device_type_short'])?"disabled":""?> class="form-control my-colorpicker1 colorpicker-element bmmc col-md-6 sn-td-input" autocomplete="off" placeholder="请输入四个字符" >
                                            <button data-did="<?=$val['id']?>" data-short-id="<?=$val['device_type_short_id']?>" class="btn X-Small btn-xs  btn-primary m-l-5 m-r-5 td_save_btn <?=isset($val['device_type_short'])?'none':''?>">
                                                保存
                                            </button>
                                        </div>

                                    </td>
                                </tr>
                            <?php }?>

                        <?php }?>
                    </tbody>
                </table>


                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="160px"  class="sl">遥控器名</th>
                        <th width="140px"  class="sl">英文名</th>
                        <th width="140px"  class="sl">型号</th>
                        <th width="560px"  class="sl">缩写<?=Yii::t('app','4CHARACTER')?></th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php if(count($remote_info_list) > 0){ ?>

                        <?php foreach ($remote_info_list as $key=>$val){ ?>
                            <tr>
                                <td class="sl"><?=$val['name']?></td>
                                <td class="sl"><?=$val['name_en']?></td>
                                <td class="sl"><?=$val['type']?></td>
                                <td class="sl opr-box" >
                                    <div class="row col-md-12 sn-td-box">
                                        <input type="text" value="<?=$val['short']?>" <?=isset($val['short'])?"disabled":""?> class="form-control my-colorpicker1 colorpicker-element bmmc col-md-6 sn-td-input" autocomplete="off" name="sn_terminal_name" placeholder="请输入四个字符" >
                                        <button data-rid="<?=$val['id']?>" data-short-id="<?=$val['remote_short_id']?>" title="<?=Yii::t('app',' MANUFACTURE_SAVE')?>" class="btn X-Small btn-xs  btn-primary m-l-5 m-r-5 td_terminal_save_btn <?=isset($val['short'])?'none':''?>">
                                            保存
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        <?php }?>

                    <?php }?>
                    </tbody>
                </table>
            </div>


        </section>
    </div>
    <!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>

<!-- REQUIRED JS SCRIPTS -->
<script>
    /*
    返回上一级
     */
    $('#backToList').click(function(){
        window.location.href = "<?=  Url::toRoute(['manufacture/list']) ?>";
    });

    //公司缩写保存
    $('.company_save_btn').click(function(){
        let m_short_id = $(this).data('short-id'),
            short = $('#m_short').val(),
            url,
            _csrf = '<?= Yii::$app->request->csrfToken ?>',
            data;

        if( short.length != 2) {
            fail("<?= Yii::t('app','LENGTH_ILLEAGL')?>");
            return;
        }
        if(!m_short_id){
            let m_id = $(this).data('mid');
            url = '<?=Url::toRoute('sn/manufacture_short_add')?>';
            data = {'mid':m_id,'short':short,'_csrf':_csrf};
        }else{
            url = '<?=Url::toRoute('sn/manufacture_short_up')?>';
            data = {'id':m_short_id,'short':short,'_csrf':_csrf};
        }

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: data,
            success:function (data) {
                if(data.code==0){
                    succ(data.message, window.location.href);
                }else{
                    fail(data.message);
                }

            }
        });
    });
    // 遥控 缩写
    $('.td_save_btn').click(function(){
        let device_type_short_id = $(this).data('short-id'),
            _csrf = '<?= Yii::$app->request->csrfToken ?>',
            short = $(this).parent().children('input').val(),
            url,
            data;

        if( short.length !=4) {
            fail("<?= Yii::t('app','LENGTH_ILLEAGL')?>");
            return;
        }
        if(!device_type_short_id){
            let d_id = $(this).data('did');
            url = '<?=Url::toRoute('sn/device_type_short_add')?>';
            data = {'device_type_id':d_id,'short':short,'_csrf':_csrf};
        }else{
            url = '<?=Url::toRoute('sn/device_type_short_up')?>';
            data = {'id':device_type_short_id,'short':short,'_csrf':_csrf};
        }

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: data,
            success:function (data) {
                if(data.code==0){
                    succ(data.message, window.location.href);
                }else{
                    fail(data.message);
                }

            }
        });
    });
    // 终端 缩写
    $('.td_terminal_save_btn').click(function(){
        let remote_short_id = $(this).data('short-id'),
            _csrf = '<?= Yii::$app->request->csrfToken ?>',
            short = $(this).parent().children('input').val(),
            url,
            data;
        if( short.length !=4) {
            fail("<?= Yii::t('app','LENGTH_ILLEAGL')?>");
            return;
        }
        if(!remote_short_id){
            let r_id = $(this).data('rid');
            url = '<?=Url::toRoute('sn/remote_type_short_add')?>';
            data = {'remote_type_id':r_id,'short':short,'_csrf':_csrf};
        }else{
            url = '<?=Url::toRoute('sn/remote_type_short_up')?>';
            data = {'id':remote_short_id,'short':short,'_csrf':_csrf};
        }

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: data,
            success:function (data) {
                if(data.code==0){
                    succ(data.message, window.location.href);
                }else{
                    fail(data.message);
                }

            }
        });
    })

</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>


