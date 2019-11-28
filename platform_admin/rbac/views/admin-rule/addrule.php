<?php
use yii\helpers\Url;
?>

<?php include_once(NAV_DIR."/header.php");?>




<link rel="stylesheet" href="/static/css/public/table.css">
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default btn-back-call">
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <form id="rule-from">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input name="pid" type="hidden" value="<?=$id?>">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>菜单名称:</label>
                        <input  type="text" name="title" class="form-control my-colorpicker1 colorpicker-element cdmc" autocomplete="off"  placeholder="请输入菜单名称" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>路径地址:</label>
                        <input  type="text" name="route" class="form-control my-colorpicker1 colorpicker-element lydz" autocomplete="off"  placeholder="请输入路由地址" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>图标:</label>
                        <input  type="text" name="icon" class="form-control my-colorpicker1 colorpicker-element tb" autocomplete="off"  placeholder="请输入图标"  >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>排序:</label>
                        <input  type="text" name="order" class="form-control my-colorpicker1 colorpicker-element px" autocomplete="off"  placeholder="请输入序号,排序从大到小排列"  >
                    </div>
                </div>
                <!-- <div class="col-md-6" style='height:54px'>

                 </div> -->

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>菜单类型:</label>
                        <div class='radio-inline'>
                            <input  type="radio" name="type" id="male" name="type" value="1"  class="minimal" checked>
                            <label for="male" class='m-l-5 m-r-5'>
                                菜单+权限
                            </label>
                            <input  type="radio" name="type" name="type" value="2" id="manu"  class="minimal" >
                            <label for="manu" class='m-l-5 m-r-5'>
                                菜单
                            </label>
                            <input  type="radio" name="type" name="type" id="ds" value="3" class="minimal" >
                            <label for="ds" class='m-l-5 m-r-5'>
                                权限
                            </label>
                        </div>

                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>是否显示:</label>
                        <div class='radio-inline'>
                            <input  type="radio" id="xs" name="is_show" checked value="1"class="minimal" >
                            <label for="xs" class='m-l-5 m-r-5'>
                                显示
                            </label>
                            <input  type="radio"  id="bxs" name="is_show" value="0"  class="minimal" >
                            <label for="bxs" class='m-l-5 m-r-5'>
                                不显示
                            </label>

                        </div>

                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <div class='radio-inline'>
                            <input  type="radio" id="qy" name="status" checked value="1" class="minimal" >
                            <label for="qy" class='m-l-5 m-r-5'>
                                启用
                            </label>
                            <input  type="radio" id="jy" name="status" value="0"  class="minimal" >
                            <label for="jy" class='m-l-5 m-r-5'>
                                不启用
                            </label>

                        </div>

                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>显示场景:</label>
                        <div class='radio-inline'>
                            <input type="radio" id="all" name="is_on_show" value="0" class="minimal" checked>
                            <label for="all" class='m-l-5 m-r-5'>
                                都显示
                            </label>
                            <input  type="radio" id="pcs" name="is_on_show" value="1"  class="minimal" >
                            <label for="pcs" class='m-l-5 m-r-5'>
                                pc端显示

                            </label>

                            <input  type="radio" id="sjd" name="is_on_show" value="2" class="minimal" >
                            <label for="sjd" class='m-l-5 m-r-5'>
                                手机端显示

                            </label>

                        </div>

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>允许添加子级:</label>
                        <div class='radio-inline'>
                            <input  type="radio" id="pt" name="is_have_part" checked value="1" class="minimal" >
                            <label for="pt" class='m-l-5 m-r-5'>
                                允许
                            </label>
                            <input  type="radio" id="ck" name="is_have_part" value="0"  class="minimal" >
                            <label for="ck" class='m-l-5 m-r-5'>
                                不允许
                            </label>

                        </div>

                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title-center' style='width:14%!important'>备注(选填):</label>
                        <textarea style='height:200px' type="text"  name="condition" class="des form-control my-colorpicker1 colorpicker-element beizhu" autocomplete="off"  placeholder="请输入备注信息" ></textarea>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'></label>
                        <button type="button" class='btn btn-block btn-primary submit-btn'>提交</button>
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

<script>
    var glo = {
        'is_true':true
    };

    $('input[type="radio"].minimal').iCheck({
        radioClass : 'iradio_minimal-blue'
    });

    //提交用户信息表单
    $('.submit-btn').click(function(){
        if(!glo.is_true){
            return false;
        }
        // 菜单名称
        if($('.cdmc').val()==''){
            fail('菜单名称不能为空');
            return false;
        }
        if(!fun.isMc($('.cdmc').val())){
            fail('菜单名称输入字符不满足要求');
            return false;
        }
        // 路由地址
        if($('.lydz').val()==''){
            fail('路由地址不能为空');
            return false;
        }
        if(!fun.isMc($('.lydz').val())){
            fail('路径地址输入字符不满足要求');
            return false;
        }
        //图标
        if(!fun.isMc($('.tb').val())){
            fail('图标不能为空');
            return false;
        }

        //排序  输入正整数
        if($('.px').val()==''){
            fail('排序不能为空');
            return false;
        }
        if(!fun.isNum($('.px').val())){
            fail('排序输入字符不满足要求');
            return false;
        }

          // 备注
        if(!fun.isDes($('.des').val()) && $('.des').val()==!''){
            fail('输入长度超出限制');
            return false;
        }


        glo.is_true = false;
        var data = $('#rule-from').serialize();

        $.ajax({
            url:'<?=Url::toRoute('admin-rule/create')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                glo.is_true = true;

                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-rule/index')?>');
                }else{
                    fail(data.message);
                }

            }
        })
    });
</script>

