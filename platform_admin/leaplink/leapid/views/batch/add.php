<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-10-30
 * Time: 14:40
 */
use yii\helpers\Url;
use app\models\batch\ARLeapLink;
?>

<?php include_once(NAV_DIR."/header.php");?>
<!-- 日期控件 -->
<link type="text/css" rel="stylesheet" href="/static/css/public/jeDate-test.css">
<link type="text/css" rel="stylesheet" href="/static/css/public/jedate.css">
<script type="text/javascript" src="/static/js/public/jedate.js"></script>
<style>
    input[type=date]::-webkit-inner-spin-button { visibility: hidden; }
    input[readonly] {
        background: #fff!important;
    }
    input[type="date"]::-webkit-clear-button{
        display:none;
    }.jeinpbox{margin-right:0;}
    .right-box a{
        min-width: 96px;
        text-align: center;
        margin-bottom: 15px;
        float: right;
        outline: none;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 560px;">
    <!-- Main content -->
    <section class="content container-fluid" style="padding-top:25px;">
        <div class="right-box p-b-20 row">
            <a type="button" class="btn  btn-default btn-back-call" href="<?= Url::toRoute(["batch/list"])?>">
                返回
            </a>
        </div>
        <form id="user_form">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
            <!-- 内容区域-->
            <div class="row col-md-12">

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>芯片型号:</label>
                        <input type="text" name="chip_type" class="form-control " autocomplete="off"  placeholder="请输入芯片型号" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>固件升级秘钥:</label>
                        <input type="text" name="key_update" class="form-control " autocomplete="off"  placeholder="请输入固件升级秘钥" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class=" form-group col-md-12 input-xx" style="width:100%;display:flex;">
                        <label class="col-md-3 title">批次日期:</label>
                        <div class="jeinpbox form-control" style="padding:0">
                            <input type="text" name="batch_date" class="jeinput time form-control"  placeholder="请输入批次日期" style="border:0;height:32px;"  readonly timeattr='YYYY-MM-DD'>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>批次流水:</label>
                        <input name="batch_no" type="text" class="form-control" autocomplete="off"  placeholder="请输入批次流水" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>生产数量:</label>
                        <input  name="batch_count" type="text" class="form-control" autocomplete="off"
                                placeholder="请输入生产数量" >
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-3 title">备注(选填):</label>
                        <textarea style="height:200px" type="text" name="info" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息"></textarea>
                    </div>
                </div>

                <div class="row col-md-12">
                    <div class="row col-md-12">
                        <div class="col-md-6 col-md-offset-1">
                            <div class="form-group col-md-6 input-xx">
                                <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script>

    $(".jeinput").each(function(){
        var mat = $(this).attr("timeattr");
        jeDate(this,{
            format: mat,
            minDate: jeDate.nowDate({DD:0}), //0代表今天，-1代表昨天，-2代表前天，以此类推
            donefun:function(obj) {
                console.log(obj)
            },
            theme:{bgcolor:"#3C8DBC",pnColor:"#3C8DBC"},
        });
    });
    $('.select2').select2();
    $('.submit-btn').unbind('click').click(function(){
        var reg=/^\S{2,128}$/,
            reg_space=/\S/;
        var data =$('#user_form').serializeObject();
        $.ajax({
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(data.code==0){
                    succ('提交成功','<?=Url::toRoute(['batch/list'])?>');
                }else{
                    fail(data.message)
                }
            }
        });
    })

</script>


