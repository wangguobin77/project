<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" type="text/css" href="/static/css/manufacture/sninfolist.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <form style="display:flex;justify-content:flex-end" data-action="<?=Url::toRoute(['batch/get_batch_detail','batch_id'=>$params['batch_id']])?>" method="POST">
            <input type="hidden" value="<?= $params['mid'] ?>" id="mid">
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>状态:</label>
                    <select name="check_status"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value="" >请选择</option>
                        <option value="0" <?= $params['check_status'] !== '' && $params['check_status'] == 0?'selected="selected"':'' ?> >全部</option>
                        <option value="1" <?= $params['check_status'] == 1?'selected="selected"':'' ?> >校验中</option>
                        <option value="2" <?= $params['check_status'] == 2?'selected="selected"':'' ?> >可使用</option>
                        <option value="3" <?= $params['check_status'] == 3?'selected="selected"':'' ?> >已废弃</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <input name='sn' type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" value="<?= $params['sn'] ?>"  placeholder="搜索SN号" >
                </div>
            </div>

            <div class="col-md-3" style='float: right;text-align:right'>
                <button class="btn btn-success" id="search">搜索</button>
                <span class="btn btn-warning fileinput-button" id="filePicker">批量上传</span>
                <button class="btn btn-default" id="backToList">返回列表</button>
            </div>
        </form>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">流水号</th>
                    <th width="160px"  class="sl">sn号</th>
                    <th width="160px"  class="sl">生产批次号</th>
                    <th width="160px"  class="sl">创建时间</th>
                    <th width="100px"  class="sl">状态</th>
                    <th width="150px"  class="sl" >首次绑定时间</th>
                    <th width="150px"  class="sl" >绑定用户uuid</th>
                </tr>
                </thead>
                <tbody>
                <?php if($data):?>
                    <?php foreach ($data as $key=>$val):?>
                        <tr>
                            <td class="sl">
                                <?=$val['sn_id']?>
                            </td>
                            <td class="sl"><?=$val['sn']?></td>
                            <td class="sl"><?=$val['bid']?></td>
                            <td class="sl"><?=date('Y-m-d H:i:s',$val['created_ts'])?></td>
                            <?php if($val['check_status'] == 1):?>
                                <td class="sl zz-box"><span class="dqr">校验中</span></td>
                            <?php elseif($val['check_status'] == 2):?>
                                <td class="sl zz-box"><span class="zaizhi rk">可使用</span></td>
                            <?php else:?>
                                <td class="sl zz-box"><span class="yzf">已废弃</span></td>
                            <?php endif;?>

                            <td class="sl pt log-detail"><?=$val['bind_time']?"<span data-sn=\"".$val['sn']."\">".date('Y-m-d H:i:s',$val['bind_time'])."</span>":"-"?></td>
                            <td class="sl pt u-check"><?= $val['uuid'] ? "<span>" . $val['uuid'] . "</span>" : "-" ?></td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>
                </tbody>
            </table>
        </div>
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '»',
                'prevPageLabel' =>  '«',
                'options'   =>  ['class' => 'pagination pagination-sm no-margin pull-right'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>

    </section>


</div>
<!-- /.content-wrapper end-->

<!-- sn 绑定解绑信息日志 -->
  <div class="box box-table" id="log-module">
        <div class="box-header">
            <h3 class="box-title" style='width:90%;'><font>STVEK0021910100001</font>&nbsp;绑定解绑记录</h3>
            <span class="font_family icon-close cursor" style="float:right;width:15px;height:15px;"></span>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>用户uuid</th>
                        <th>操作</th>
                        <th>时间</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
          </table>
        </div>
    </div>


<?php include_once(NAV_DIR."/footer.php");?>

<!-- REQUIRED JS SCRIPTS -->
<<script src="/static/js/public/select2.full.min.js"></script>
<script type="text/javascript" src="/static/js/public/webuploader.min.js"></script>
<script type="text/javascript" src="/static/js/manufacture/sn_info_list.js"></script>
<script>
    let SN_BIND_LOG_URL = '<?=Url::toRoute(['sn/sn_bind_log'])?>';

    $('.select2').select2();

    //阻止button自动提交事件
    $("button").on("click", function(e){
        e.preventDefault();
    });

    $("#search").on("click", function () {
        let my_form = $(this).closest('form');
        let action = my_form.data('action'),
            mid = $('#mid').val(),
            check_status = my_form.find('select[name="check_status"]').val(),
            sn = my_form.find('input[name="sn"]').val();
        my_form.attr('action', action+ '&check_status=' + check_status + '&sn=' + sn + '&mid=' + mid).submit();
    });

    //返回列表
    $("#backToList").click(function () {
        window.location.href = "<?=Url::toRoute(['batch/batch_info_list','mid'=>$params['mid']])?>";
    });

    (function () {
        // 初始化Web Uploader
        let uploader = WebUploader.create({

            // 选完文件后，是否自动上传。
            auto: true,

            // swf文件路径
            swf: '../../../js/Uploader.swf',

            // 文件接收服务端。
            server: "<?=Url::toRoute(['sn/multiple_save'])?>",

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {
                id: '#filePicker',
                multiple: false, //禁用多个文件上传
            },
            accept: { //接受文件类型
                title: 'csv',
                extensions: 'csv',
                mimeTypes: 'text/*'
            },
            fileNumLimit: 1, //数量限制
            fileSingleSizeLimit: 10 * 1024 * 1024

        });

        // 当有文件被添加进队列的时候
        uploader.on('fileQueued', function (file) {
            //判断当前上传文件格式
            if(uploader.getFiles()[uploader.getFiles().length - 1].ext.toLowerCase() != "csv"){
                fail('上传文件格式不正确');
                uploader.reset();
            }
        });

        uploader.on('uploadSuccess', function (file, response) {
            if(response.code == 0){
                succ(response.message, window.location.href);
            }else{
                fail(response.message, window.location.href);
            }

        });

        /**
         * 验证文件格式以及文件大小
         */
        uploader.on("error", function (type) {

            if (type == "F_EXCEED_SIZE") {
                fail('文件大小不能超过10M');
                uploader.reset();
            }
            else {
                fail('上传出错！请检查后重新上传！');
                uploader.reset();
            }
        });
    })();



</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->


