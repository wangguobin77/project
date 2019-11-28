<?php
use yii\helpers\Url;
use app\models\AdminPosition;

$positionList = (new AdminPosition())->getPositionListInfo();//获取所有职位

?>
<?php include_once(NAV_DIR."/header.php");?>
<!-- 提示样式 -->

<link rel="stylesheet" type="text/css" href="/static/css/public/kendo/kendo.common.min.css">
<link rel="stylesheet" type="text/css" href="/static/css/public/kendo/kendo.default.min.css">
<link rel="stylesheet" type="text/css" href="/static/css/public/kendo/kendo.default.mobile.css">

<!-- <link rel="stylesheet" href="../../rbac/web/css/kendo.common-material.min.css" /> -->
<link rel="stylesheet" href="/static/css/public/kendo/kendo.material.min.css" />
<link rel="stylesheet" href="/static/css/public/kendo/kendo.material.mobile.min.css" />
<style>
   .k-multiselect-wrap{
        border-color:#d2d6de !important
    }
    .k-input.k-readonly{
            padding-top: 7px;
        }
        .k-multiselect-wrap{
            height: 100%;
        }
        .k-state-hover > .k-clear-value{
            display: block;
            position: absolute;
            right: 0;
            top: 10px
        }
        .k-clear-value{
            display: none;
            position: absolute;
            right: 0;
            top: 10px
        }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default back-btn">
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>员工姓名:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element ygxm" autocomplete="off" name="real_name" placeholder="请输入员工姓名" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>员工工号:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element yggh" name="work_number" value="<?=$work_number_id?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>手机号:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element sjhm" autocomplete="off" name="mobile" placeholder="请输入手机号，同时用于账号登录">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>员工状态:</label>
                        <select class="form-control select2 select2-hidden-accessible col-md-9" name="status" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option selected="selected" value="1">在职</option>
                            <option value="0">离职</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>生日(选填):</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc laydate-icon" style="height:34px;border:1px solid #d2d6de;padding-left:12px;" name="bathday" placeholder="选择生日" value="" onclick="laydate({istime: false, format: 'YYYY-MM-DD',max:laydate.now()})">

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>性别(选填):</label>
                        <select name="sex"  class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value=1>男</option>
                            <option value=2>女</option>
                        </select>
                    </div>
                </div>

                <!-- 所驻部门 -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>所属部门:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element sjhm" autocomplete="off" style="width: 100%;height:auto" id="dropdowntree"  name="position">
                        <div id="mm">

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>职位(选填):</label>
                        <select name="position_id" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >请选择</option>
                            <?php foreach ($positionList as $v):?>
                                <option value="<?=$v['id']?>" ><?=$v['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>备注(选填):</label>
                        <textarea type="text" name="des" class="des form-control my-colorpicker1 colorpicker-element ygxm" autocomplete="off" placeholder="请输入备注信息" ></textarea>
                    </div>
                </div>
        </div>
        <div class="row col-md-12">
            <!-- 提交 按钮 -->
            <div class="col-md-6 col-md-offset-1">
                <div class="form-group col-md-6 input-xx">
                    <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                </div>
            </div>
        </div>
        </form>
</div>
</section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>

<!-- Select2 -->
<script   src="/static/js/public/select2.full.min.js"></script>

<script src="/static/js/public/laydate.js"></script>
<script src="/static/js/public/kendo/kendo.all.min.js"></script>
<script type="text/javascript">

    /*
     所属部门选择框
     */
    $('.select2').select2();

    /*
     end
     */
    var glo = {
        'is_true':true,
        'orgInfo':null//全局部门组织数据
    };

    $(document).ready(function(){

        glo.orgInfo = <?=$select_val?>;

        $("#dropdowntree").kendoDropDownTree({
            placeholder: "请选择",
            checkboxes: true,
            checkAll: false,
            autoClose: true,
            dataSource: glo.orgInfo[0]['items'],
            checkboxes: {
                checkChildren: true
            },
            change: function(e) {
                var value = this.value();
                var input_str = '';
                if(value.length > 0){

                    for(var i=0; i<value.length;i++){
                        input_str += '<input type="hidden" name="branch[]" value="'+value[i]+'"/>';
                    }
                }
                $('#mm').html('');
                $('#mm').html(input_str);
                //console.log(value[1]);
            }

        });
    });


    //提交用户信息表单
    $('.submit-btn').click(function(){

        if(!glo.is_true){
            return false;
        }
        //员工姓名
        if(!fun.isMc($('.ygxm').val())){
            fail('员工姓名不能为空');
            return false;
        }
        //员工公号
        if(!fun.isNum($('.yggh').val())){
            fail('员工工号不能为空');
            return false;
        }
        //手机号码 验证
        if($('.sjhm').val()==''){
            fail('手机号不能为空');
            return false;
        }
        if(!fun.isMobile($('.sjhm').val())){
            fail('手机格式不正确');
            return false;
        }
        //所属部门
        if($('#mm').children().length==0){
            fail('请选择所属部门');
            return false;
        }
        // 备注
        if(!fun.isDes($('.des').val()) && $('.des').val()==!''){
            fail('备注长度超出限制');
            return false;
        }

        glo.is_true = false;
        var data = $('#admin-user-form').serialize();
        //console.log(data);
        $.ajax({
            url:'<?=Url::toRoute('admin-user/create')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                glo.is_true = true;

                if (data.code == 0 ){
                    // window.location.href='<?=Url::toRoute('admin-user/index')?>';
                    succ(data.message,'<?=Url::toRoute('admin-user/index')?>');
                    // window.location.href='<?=Url::toRoute('admin-user/index')?>';
                }else{
                    fail(data.message)
                }
            }
        })
    });
</script>
