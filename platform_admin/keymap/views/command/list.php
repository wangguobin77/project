<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/select2.min.css">

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 搜索 -->
        <div class="right-box p-b-20 row">
            <button type="button" class="btn btn-primary make_config_file">
                生成配置文件
            </button>
            <button type="button" class="btn  btn-primary command_add_btn">
                新增
            </button>

        </div>
        <!-- 搜索end -->
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">Key</th>
                    <th width="200px"  class="sl">Code</th>
                    <th width="160px"  class="sl">Version</th>
                   <!--  <th width="160px"  class="sl">Analog Num</th>
                    <th width="200px"  class="sl">Normal num</th>
                    <th width="200px"  class="sl">Operate</th> -->
                    <th width="160px"  class="sl">模拟量</th>
                    <th width="200px"  class="sl">偏移量</th>
                    <th width="200px"  class="sl">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                    <tr>
                        <td class="sl"><?=$item['key']?></td>
                        <td class="sl"><?=$item['code']?></td>
                        <td class="sl"><?=$item['version']?></td>
                        <td class="sl"><?=$item['analog_params']?></td>
                        <td class="sl"><?=$item['normal_params']?></td>
                        <td class="sl opr-box" >
                            <div class="czuo-box" style='width:fit-content'>
                                <a href="javascript:;" class='set-btn del-btn' onclick="del(<?=$item['id']?>)">删除</a>
                                <span class='xian'></span>
                                <a href="<?=url::toRoute(['edit','id'=>$item['id']])?>">编辑</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script>
    $('.select2').select2();
    //生成配置文件
    //生成配置文件
    $('.make_config_file').unbind('click').click(function(){
        var _csrf = '<?=Yii::$app->request->csrfToken?>';
        var url_ = '<?=Url::toRoute('/keymap_json/command_all_json')?>';
        $.ajax({
            url:url_,
            type:'post',
            dataType:'json',
            data:{'_csrf':_csrf},
            success:function (data) {
                // console.log(data);
                if(data.code != 0){
                    fail(data.message);
                }else{
                    succ(data.message);
                }
            }
        })
    });

    //删除command
    function del(id){
        $('.del-box').show();
        //取消
        $('.cancel').unbind('click').click(function(){
            $('.del-box').hide();
            return false;
        });
        $('.icon-close').unbind('click').click(function(){
            $('.del-box').hide();
            return false;
        });
        $('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
        });
        //删除
        $('.confirm').unbind('click').click(function(){
            var _csrf = '<?= Yii::$app->request->csrfToken ?>';
            $.ajax({
                url:'<?=Url::toRoute('delete')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf':_csrf},
                success:function (data) {
//                 console.log(data); return false;
                    if(data.code != 0){
                        fail(data.message);
                    }else{
                        $('.del-box').hide();
                        succ(data.message,'<?=url::toRoute('list')?>');
                    }
                }
            })
        })
    }

    //添加command
    $('.command_add_btn').unbind('click').click(function(){
        location.href = '<?=url::toRoute('add')?>';
    })
</script>


