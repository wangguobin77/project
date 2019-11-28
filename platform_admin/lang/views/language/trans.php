<?php
use yii\helpers\Url;
?>
<link rel="stylesheet" href="/static/css/lang/trans.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="row col-md-12" id='add_form'>
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
                <div class="col-md-6" style='padding-left:0 !important'>
                    <div style='padding-left:0 !important' class="form-group col-md-12 input-xx">
                        <label style='padding-left:0 !important' class='col-md-3 title'>添加Key:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element" autocomplete="off" name="key_value"  placeholder="请输入Key值" >
                        <button   id="add-key" type="button"
                                  class="btn btn-block btn-primary btn-width add-btn trans_add_key_submit_btn" style='z-index:1000; height: 34px; width: 50px; margin-left: 20px'>
                            新增
                        </button>
                    </div>
                </div>
            </form>
            <div class="right-box p-b-20">
                <button type="button" class="btn go_back_list btn-default btn-back-call">返回</button>
            </div>
        </div>

        <div class="">
            <table class="table trans-table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="550px"  class="sl">Key</th>
                    <th width="550px"  class="sl">简体中文<?= '('.$count['all'].'/'.$count['cn'].')'?></th>
                    <th width="550px"  class="sl">Enlish<?= '('.$count['all'].'/'.$count['en'].')'?></th>
                    <?php if($third):?>
                        <th width="550px" class="sl">
                            <?php foreach($language as $val): if($val['id']==$third):?>
                                <?=$val['lang'].'('.$count['all'].'/'.$count['th'].')'?>
                            <?php endif;endforeach;?>
                        </th>
                    <?php endif;?>
                </tr>

                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                <tr data-id="<?=$item['id']?>">
                    <td class="sl">
                        <div class="czuo-box">
                            <input type="text" data-id="<?=$item['id']?>" value="<?=$item['lang_key'];?>" class='sl' name='key'/>
                            <div>
                                <a href="javascript:;" onclick="if(confirm('确认提交吗？')) key_delete(this);else return false;">删除</a>
                                <span class='xian'></span>
                                <a href="javascript:;" onclick="key_edit(this)">保存</a>
                            </div>
                        </div>
                    </td>
                    <td class="sl">
                        <div class="czuo-box">
                            <input type="text" value="<?=$item['cn_value'];?>" data-lang-id="<?=$item['cn_lang_id'];?>" class='sl' />
                            <div>
                                <a href="javascript:;"  onclick="change_value(this)">保存</a>
                            </div>
                        </div>
                    </td>
                    <td class="sl">
                        <div class="czuo-box">
                            <input type="text" value="<?=$item['en_value'];?>" data-lang-id="<?=$item['en_lang_id'];?>" class='sl'/>
                            <div>
                                <a href="javascript:;" onclick="change_value(this)">保存</a>
                            </div>
                        </div>
                    </td>
                    <?php if($third):?>
                        <td class="sl">
                            <div class="czuo-box">
                                <input type="text" value="<?=$item['th_value'];?>" data-lang-id="<?=$item['th_lang_id'];?>" class='sl'/>
                                <div>
                                    <a href="javascript:;" onclick="change_value(this)">保存</a>
                                </div>
                            </div>
                        </td>
                    <?php endif;?>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<!-- /.content-wrapper end-->

<!-- REQUIRED JS SCRIPTS -->
<script src="/static/js/public/jquery.min.js"></script>
<script src="/static/js/public/bootstrap.min.js"></script>
<script src="/static/js/public/adminlte.min.js"></script>
<script src='/static/js/public/ts.js'></script>
<script type="text/javascript">
    file_id = '<?=Yii::$app->request->get('file')?>';
    //添加key值
    $('.trans_add_key_submit_btn').unbind('click').click(function () {
        var data = {};
        data.key = $.trim($("input[name='key_value']").val());
        if (!data.key) {
            fail('key不能为空');
            return false;
        }
        data.file_id = file_id;
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
        $.ajax({
            url:'<?=Url::toRoute('/key/add')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                 console.log(data);    return false;
                if(data.code==0){
                    window.location.href=window.location.href;
                } else {
                    fail(data.message);
                }
            }
        })
    });

    //返回列表
    $('.go_back_list').unbind('click').click(function(){
        location.href = '<?=url::toRoute('/file/list')?>';
    });

    //修改value
    function change_value(obj) {
        var that = $(obj).parent().prev(), data = {};
        data.key_id = $(obj).parent().parent().parent().parent().attr('data-id');
        data.lang_id = $(that).attr('data-lang-id');
        data.value = $(that).val();
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
//        console.log(data); return false;
        $.ajax({
            url:'<?= url::toRoute('/value/add_edit')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                 console.log(data); return false;
                 if (data.code == 0) {
                     succ(data.message);
                 } else {
                     fail(data.message);
                 }
            }
        })
    }

    //删除key
    function key_delete(obj){
        var that = $(obj).parent().parent().parent().parent(), data = {};
        data.id = $(that).attr('data-id');
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
        $.ajax({
            url:'<?=url::toRoute('/key/delete')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                // console.log(data);    return false;
                if(data.code==0){
                    $(that).remove();
                    succ(data.message);
                } else {
                    fail(data.message);
                }
            }
        })
    }
    //修改key
    function key_edit(obj){
        var that = $(obj).parent().prev(), data = {};
        data.key = $.trim($(that).val());
        if (data.key.length < 1 || data.key.length > 255) {
            fail('key必须是1-255长的字符');
            return false;
        }
        data.id = $(that).attr('data-id');
        data._csrf = '<?=Yii::$app->request->csrfToken?>';
//        console.log(data); return false;
        $.ajax({
            url:'<?= url::toRoute('/key/edit')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
//                 console.log(data);    return false;
                if (data.code == 0) {
                    succ(data.message);
                } else {
                    fail(data.message);
                }
            }
        })
    };

</script>


