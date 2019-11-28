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

        </div>
        <!-- 搜索end -->
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">序号</th>
                    <th width="200px"  class="sl">Key</th>
                    <th width="160px"  class="sl">Code	</th>
                    <th width="160px"  class="sl">	Parent	</th>
                    <th width="200px"  class="sl">Type
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                    <tr>
                    <td class="sl"><?=$item['id']?></td>
                    <td class="sl"><?=$item['key']?>K</td>
                    <td class="sl"><?=$item['code']?></td>
                    <td class="sl"><?=$item['parent']?></td>
                    <td class="sl"><?=$item['type']?></td>
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
    $('.make_config_file').unbind('click').click(function(){
        var _csrf = '<?=Yii::$app->request->csrfToken?>';
        var url_ = '<?=url::toRoute('/keymap_json/keycode_json')?>';
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
</script>


