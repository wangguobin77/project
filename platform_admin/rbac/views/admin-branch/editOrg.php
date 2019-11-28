<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/12
 * Time: 下午5:35
 */
use yii\helpers\Url;
use app\models\AdminInstitutionsType;
use app\models\AdminBranch;

$AdminInstitutionsTypeInfo = (new AdminInstitutionsType)->disAdminInstitutionsTypeName();//组织类型

$AdminBranchInfo = (new AdminBranch)->getOrgAndBranchAllListName();//部门名称信息

?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/select2.min.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<link rel="stylesheet" href="/static/css/public/admin.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default">
                返回
            </button>
            <!-- <button type="button" class="btn  btn-primary">
                 新增
             </button>-->
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input name="pid" type="hidden" value="<?=$model['pid']?>">
                <input name="id" type="hidden" value="<?=$model['id']?>">
                <input name="type" type="hidden" value="1">
                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>机构名称:</label>
                        <input type="text" id="org-s" value="<?=$model['title']?>" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="title" placeholder="请输入机构名称" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>机构类型:</label>
                        <select name="i_b_type_id"   class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <?php foreach ($AdminInstitutionsTypeInfo as $k=>$v):?>
                                <?php if($k == $model['i_b_type_id']){?>
                                    <option seclected value="<?=$k?>"><?=$v?></option>
                                <?php }else{?>
                                    <option value="<?=$k?>"><?=$v?></option>
                                <?php }?>
                            <?php endforeach;?>

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx select-input">
                        <label class='col-md-3 title'>所在地区:</label>
                        <?php $a = explode('-',$model['region']);?>
                        <select name="position_id"  class="form-control  select3 select2-hidden-accessible col-md-3" id="province" onchange="change_p(this)"  style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value=""><?=$a[0]?></option>

                        </select>
                        <select name="position_id"  class="form-control select4 select2-hidden-accessible col-md-3" id="city" onchange="change_c(this)" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value=""><?=$a[1]?></option>

                        </select>
                        <select name="position_id"  class="form-control select5 select2-hidden-accessible col-md-3" id="area" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value=""><?=$a[2]?></option>

                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>联系地址:</label>
                        <input type="text" value="<?=$model['c_address']?>" class="form-control my-colorpicker1 colorpicker-element bmmc lxdz" autocomplete="off" name="c_address" placeholder="请输入联系地址" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>联系人:</label>
                        <input type="text" value="<?=$model['contact_name']?>" class="form-control my-colorpicker1 colorpicker-element bmmc lxr" autocomplete="off" name="contact_name" placeholder="请输入联系人" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>联系方式:</label>
                        <input type="text" value="<?=$model['mobile']?>" class="form-control my-colorpicker1 colorpicker-element bmmc lxfs" autocomplete="off" name="mobile" placeholder="请输入联系方式" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>邮箱(选填):</label>
                        <input type="text" value="<?=$model['c_email']?>" class="form-control my-colorpicker1 colorpicker-element bmmc lxyx" autocomplete="off" name="c_email" placeholder="请输入邮箱" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12  input-xx">
                        <label class='col-md-3 title'>上级机构/部门:</label>
                        <span class='form-control'><?=$AdminBranchInfo[$model['pid']];?></span>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title'>备注(选填):</label>
                        <textarea type="text" name="condition" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off" name="real_name" placeholder="请输入备注信息" ><?=$model['condition']?></textarea>
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
        </div>
        </form>
</div>
</section>
</div>
<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>
<!-- Select2 -->
<script   src="/static/js/public/select2.full.min.js"></script>

<script   src="/static/js/public/iCheck/icheck.min.js"></script>
<script   src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/area.js"></script>
<script   src="/static/js/public/iCheck/icheck.min.js"></script>

<script>

    $(document).ready(function(){


        $('.select2').select2()
        $('.select3').select2()
        $('.select4').select2()
        $('.select5').select2()
        //iCheck for checkbox and radio inputs
        /*  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
         checkboxClass: 'icheckbox_minimal-blue',
         radioClass   : 'iradio_minimal-blue'
         })*/
        $('input[type="radio"].minimal').iCheck({
            radioClass : 'iradio_minimal-blue'
        });

        $('.btn-default').click(function(){
            back();
        })


        /*地址*/
        /**
         * address初始化
         */
        /*地址*/
        /**
         * address初始化
         */
        var pr_str = '';//省


        var select_p = '<?=$a[0]?>';
        var select_c = '<?=$a[1]?>';
        var select_a = '<?=$a[2]?>';

        pr_str = '<option>'+select_p+'</option>';
        for(var v in area_info){
            pr_str += '<option value="'+area_info[v].area_id+'">'+area_info[v].area_name+'</option>';
        }
        $('#province').html(pr_str);

        $('#city').html('<option value="">'+select_c+'</option>');
        $('#area').html('<option value="">'+select_a+'</option>');

    });


    var glo = {
        'parent_id':0,//全局父类id
        'is_true':true
    }

    /**
     * 悬着省会促发下一级联动
     */
    function change_p(obj){

        $('#area').html('<option value="">请选择</option>');
        var p_id = $(obj).val();

        glo.parent_id = p_id;

        var area_city_info = area_info[p_id].children;//获取子集信息

        var pr_str = '';//省

        pr_str = '<option value="">请选择</option>';
        for(var v in area_city_info){
            pr_str += '<option value="'+area_city_info[v].area_id+'">'+area_city_info[v].area_name+'</option>';
        }

        $('#city').html(pr_str);

    }

    /**
     * 选择区促发下级联动
     */
    function change_c(obj){

        var c_id = $(obj).val();
        var area_city_info = area_info[glo.parent_id].children[c_id].children;//获取子集信息
        var pr_str = '';//省
        pr_str = '<option value="">请选择</option>';
        for(var v in area_city_info){
            pr_str += '<option value="'+area_city_info[v].area_id+'">'+area_city_info[v].area_name+'</option>';
        }
        $('#area').html(pr_str);
    }


    //提交用户信息表单
    $('.submit-btn').click(function(){
        var data = $('#admin-user-form').serialize();

        var province_text=$("#province").find("option:selected").text();  //省

        var city_text=$("#city").find("option:selected").text();  //市

        var area_text=$("#area").find("option:selected").text();  //区

        if(!glo.is_true){
            return false;
        }
        // 机构名称
        if(!fun.isMc($('#org-s').val())){

            fail('The name of the organization cannot be empty');//机构名称不能为空
            return false;
        }
        // 地区选择
        if(province_text == '请选择'){
            // alert('请选择省份');

            fail('请选择省份');
            return false;
        }

        if(city_text == '请选择'){
            // alert('请选择市');

            fail('请选择市');
            return false;
        }

        if(area_text == '请选择'){
            // alert('请选择区');

            fail('请选择区');
            return false;
        }
        // 联系地址
        if(!fun.isMc($('.lxdz').val())){

            fail('联系地址不能为空');
            return false;
        }

        // 联系人
        if(!fun.isMc($('.lxr').val())){

            fail('联系人不能为空');
            return false;
        }

        // 联系方式
        if(!fun.isMobile($('.lxfs').val())){

            fail('联系方式格式不正确');
            return false;
        }

        if($('.lxfs').val()==''){

            fail('联系方式不能为空');
            return false;
        }
        // 邮箱 选填 项
        if(!fun.isMail($('.lxyx').val()) && $('.lxyx').val()==!''){
            fail('邮箱格式不正确');
            return false;
        }

        // 备注
        if(!fun.isDes($('.des').val()) && $('.des').val()==!''){
            fail('备注长度超出限制');
            return false;
        }

        glo.is_true = false;

        data += '&region='+province_text+'-'+city_text+'-'+area_text;
        $.ajax({
            url:'<?=Url::toRoute('admin-branch/update')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {

                glo.is_true = true;

                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-branch/index')?>');
                }else{
                    fail(data.message)
                }


            }
        })
    });


</script>