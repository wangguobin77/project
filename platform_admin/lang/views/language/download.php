<?php
use yii\helpers\Url;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20">
            <button type="button" class="btn go_back_list btn-default btn-back-call">返回</button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="50%"  class="sl">语言</th>
                    <th width="50%"  class="sl">操作 </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                <tr>
                    <td class="sl"><?=$item['lang']?></td>
                    <td class="sl"><a href="<?=Url::toRoute(['down','file'=>$item['file_id'],'lang'=>$item['id']])?>">下载文件</a></td>
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
<script>
    //返回列表
    $('.go_back_list').unbind('click').click(function () {
        location.href = '<?=url::toRoute('/file/list')?>';
    });
</script>


