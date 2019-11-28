<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-10-30
 * Time: 14:40
 */
use yii\helpers\Url;
use app\models\batch\ARLeapid;
?>
<link rel="stylesheet" href="/static/css/public/bootstrap-table-expandable.css">
<!-- 日期控件 -->
<link type="text/css" rel="stylesheet" href="/static/css/public/jeDate-test.css">
<link type="text/css" rel="stylesheet" href="/static/css/public/jedate.css">
<script type="text/javascript" src="/static/js/public/jedate.js"></script>
<?php include_once(NAV_DIR."/header.php");?>
    <style>
        input[type=date]::-webkit-inner-spin-button { visibility: hidden; }
        input[readonly] {
            background: #fff!important;
        }
        input[type="date"]::-webkit-clear-button{
           display:none;
        }.jeinpbox{margin-right:0;}.form-group{margin-bottom:20px;}.tishi{position: absolute;top:68%;color:red;left:25%;font-size: 8px;}
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
                <a type="button" class="btn  btn-default btn-back-call" onclick='window.history.go(-1)'>
                    返回
                </a>

            </div>
            <form id="user_form">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                 <!-- 内容区域-->
                 <div class="row col-md-12">

                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>开始leapid:</label>
                            <input type="text" class="form-control start_id" name="start_id" autocomplete="off"   placeholder="请输入开始leapid" >
                            <span class="ts-des tishi"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>结束leapid:</label>
                            <input type="text" class="form-control end_id" name="end_id" autocomplete="off"  placeholder="请输入结束leapid">
                            <span class="ts-des tishi" ></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12 input-xx">
                            <label class='col-md-3 title'>leapid用途:</label>
                            <select name="using" class="form-control select2 select2-hidden-accessible col-md-9 " style="width: 100%;" tabindex="-1" aria-hidden="true">
                                <option>未设置</option>
                                <?php foreach (ARLeapid::USING_LABELS as $k => $use): ?>
                                    <option value="<?=$k?>"><?=$use?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="ts-des tishi"></span>
                        </div>
                    </div>
                </div>

                <div class='row'>
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group col-md-12 input-xx">-->
<!--                            <label class="col-md-3 title">备注(选填):</label>-->
<!--                            <textarea style="height:200px" type="text"  class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息"></textarea>-->
<!--                        </div>-->
<!--                    </div>-->
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
    $(function(){
        $('.select2').select2({});
        //$('.submit-btn').css('color','red')
    })
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
                    succ('设置成功','<?=Url::toRoute(['leapid/list', 'batch_id' => $params['batch_id']])?>');
                }else{
                    fail(data.message)
                }
            }
        });
    })

</script>


