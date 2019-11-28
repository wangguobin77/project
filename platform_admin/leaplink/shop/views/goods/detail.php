<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" href="css/coupon/add.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->

    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default btn-back-call" onclick='javascript:history.back();'>
                返回
            </button>
        </div>
        <form id="sub-form">
            <div class='row'>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>名称:</label>
                        <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入名称" name="title" value="<?=isset($data['title'])?$data['title']:''?>">
                        <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                    </div>
                </div>

                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>价格:</label>
                        <input  type="number" class="form-control price" autocomplete="off"  placeholder="请输入价格" name='price' value="<?=isset($data['price'])?$data['price']:''?>">
                    </div>
                </div>
                <div class="col-md-6 col-xs-12  col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>价值:</label>
                        <input  type="number" class="form-control val" autocomplete="off"  placeholder="请输入价值" name='worth' value="<?=isset($data['worth'])?$data['worth']:''?>">
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class=" form-group col-md-12 input-xx" style="width:100%;display:flex;">
                        <label class="col-md-3 title">有效期:</label>
                        <div class="jeinpbox form-control" style="margin-right:0">
                            <input type="text" class="jeinput date" name="date"  id="testblue" placeholder="起始日期 至 结束日期" readonly
                                   value="<?=isset($data['start_at'])?($data['start_at'].'-'.$data['end_at']):''?>">
                            <input type="hidden" class="validity" />
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xs-12 col-sm-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">不可用日期(选填):</label>
                        <div class='radio-inline form-control data-box'>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周一" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周一
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周二" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周二
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周三" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周三
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周四" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周四
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周五" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周五
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周六" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周六
                                </label>
                            </div>
                            <div class="col-md-1">
                                <input type="checkbox" name="disabled"  value="周日" >
                                <label class='m-l-5 m-r-5 check_box_label'>
                                    周日
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">适用范围(选填):</label>
                        <textarea style="height:200px" type="text" name="scope" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息"><?=isset($data['scope'])?$data['scope']:''?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">使用规则(选填):</label>
                        <textarea style="height:200px" type="text" name="info" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息"><?=isset($data['info'])?$data['info']:''?></textarea>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/coupon/list.js"></script>

</body>