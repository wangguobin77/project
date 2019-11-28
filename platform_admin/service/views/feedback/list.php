<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<style type="text/css" media="screen">
      table{
            table-layout:fixed;
        }
        table td{
            word-wrap:break-word;
            overflow:hidden;
        }
</style>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
       <div class="right-box p-b-20">
            <a id="daochu">
                <button type="button" class="btn  btn-primary">
                   导出表格
                </button>
            </a>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th class=''>姓名</th>
                    <th class=''>电话</th>
                    <th class=''>邮箱</th>
                    <th class=''>物流公司</th>
                    <th class=''>物流单号</th>
                    <th class=''>产品型号</th>
                    <th class=''>产品sn</th>
                    <th class=''>购买日期</th>
                    <th class=''>申请时间</th>
                    <th class=''>沟通状态</th>
                    <th class=''>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item):?>
                <tr data-id="<?=$item['id']?>">
                    <td class=''><?=$item['company']?></td>
                    <td class=''><?=$item['phone']?></td>
                    <td class=''><?=$item['email']?></td>
                    <td class=''><?=$item['logistics']?></td>
                    <td class=''><?=$item['trackingnumber']?></td>
                    <td class=''><?=$item['productmodel']?></td>
                    <td class=''><?=$item['sn']?></td>
                    <td class=''><?=$item['purchasedate']?></td>
                    <td class=''><?=date('Y-m-d H:i:s', $item['created_at'])?></td>
                    <td class=''>
                        <select name="contact_status"  class="form-control select2" <?php if($item['contact_status'] != 0) echo 'disabled';?> style="width: 80%;" tabindex="-1" aria-hidden="true">
                            <option value="" <?php if($item['contact_status'] == 0) echo 'selected';?>>待沟通</option>
                            <option value="1" <?php if($item['contact_status'] == 1) echo 'selected';?> >完成</option>
                            <option value="2" <?php if($item['contact_status'] == 2) echo 'selected';?> >废弃</option>
                        </select>
                    </td>
                    <td class=''>
                    <!--     <span class='xian'></span> -->
                        <a href="<?=url::toRoute(['feedback/view','id'=>$item['id']])?>" class='set-btn' target="_blank">查看</a>
                    </td>
                </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>

    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script type="text/javascript">
    //Initialize Select2 Elements
    $('.select2').select2();

    $('select[name="contact_status"]').on('change', function () {
        let self = $(this), tr = self.closest('tr');
        let id = tr.data('id'), contact_status = self.val();
        self.prop("disabled", true);

        $.ajax({
            url:"<?=url::toRoute(['feedback/change_status'])?>",
            method:'post',
            dataType:'json',
            data:{contact_status:contact_status, id:id},
            success:function (data) {
                if(data.code != 0){
                    fail(data.message);
                   // window.location.reload();
                }
            }
        });
    })



    // table 导出表格
        var html =document.getElementsByTagName("table")[0].outerHTML;
        var blob = new Blob([html], { type: "application/vnd.ms-excel" });
        var a = document.getElementById("daochu");
        a.href = URL.createObjectURL(blob);
        // 设置文件名
        a.download = "售后服务统计.xls";
</script>



