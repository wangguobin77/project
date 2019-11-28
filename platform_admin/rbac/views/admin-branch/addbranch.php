<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/12
 * Time: 下午4:07
 */

use yii\helpers\Url;
use app\models\AdminBranchType;
use app\models\AdminBranch;

$AdminBranchTypeInfo = (new AdminBranchType)->disAdminBranchTypeName();//部门
$AdminBranchInfo = (new AdminBranch)->getOrgAndBranchAllListName();//部门名称信息

$type_id_name = [
    1=>'female',//普通
    2=>'male'//仓库
];

$str = '
            <input type="radio" id="female" class="minimal" name="i_b_type_id" checked value=1 />
            <label class="m-l-5 m-r-5" style="font-size:14px">普通</label>



            <input type="radio" id="male" class="minimal" name="i_b_type_id" value=2 />
            <label class="m-l-5" style="font-size:14px">仓库</label>
        ';
?>
<?php include_once(NAV_DIR."/header.php");?>


<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/select2.min.css">
<!-- Content Wrapper. Contains page content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default">
                返回
            </button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?=Yii::$app->request->csrfToken ?>">
                <input name="pid" type="hidden" value="<?=$id?>">

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>部门名称:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="title" placeholder="请输入部门名称" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>部门类型:</label>
                        <div class='radio-inline'>
                            <?=$str;?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>部门地址:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off" name="c_address" placeholder="请输入部门地址">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12  input-xx">
                        <label class='col-md-3 title' > 上级机构/部门:</label>
                        <span class='form-control'><?=$AdminBranchInfo[$id];?></span>

                    </div>

                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>备注(选填):</label>
                        <textarea type="text" name="condition" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" placeholder="请输入备注信息" ></textarea>
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


<script   src="/static/js/public/iCheck/icheck.min.js"></script>
<script>
    /*返回上一级

     */
    $('.btn-default').click(function(){
        back();
    })

    $('input[type="radio"].minimal').iCheck({
        radioClass : 'iradio_minimal-blue'
    });

    var glo = {
        'is_true':true
    };

    $('.bread-menu').click(function(){
        event.stopPropagation();
        $('.res-992-m-menubox').toggle();

    });
    $(document).click(function(e){
        var _con = $('.res-992-m-menubox');   // 设置目标区域
        if(!_con.is(e.target) && _con.has(e.target).length === 0){ // Mark 1
            $('.res-992-m-menubox').css('display','none');
        }

    })

    //提交用户信息表单
    $('.submit-btn').click(function(){
        if(!glo.is_true){
            return false;
        }
        // 部门名称：
        if(!fun.isMc($('.bmmc').val())){
            fail('请输入正确的部门名称');
            return false;
        }else{
            $('.bmmc').next().hide();
        }
        // 部门地址
        if(!fun.isMc($('.bmdz').val())){
            fail('请输入正确的部门地址');
            return false;
        }else{
            $('.bmdz').next().hide();
        }
        //备注
        if(!fun.isDes($('.des').val()) && $('.des').val()==!''){
            fail('备注长度超出限制');
            return false;
        }
        glo.is_true = false;
        var data = $('#admin-user-form').serialize();
        $.ajax({
            url:'<?=Url::toRoute('admin-branch/addbranch')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                glo.is_true = true;
                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-branch/index')?>');
                }else{
                    fail(data.message);
                }

            }
        })
    });
</script>
