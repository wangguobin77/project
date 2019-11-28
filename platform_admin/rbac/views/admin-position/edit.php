<?php
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/12
 * Time: 下午2:18
 */
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
      href="/static/css/rbac/admin-position.css">
<!-- Content Wrapper. Contains page content -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Main content -->
    <section class="content container-fluid">

        <div class="row col-md-12" id='edit_form'>
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
                <input type="hidden" name="id" id="admin-position-id" value="<?=$data['id']?>">
                <!-- <div class="col-md-6 col-sm-12 col-xs-12"> -->
                <div class="right-box p-b-20">

                    <button    type="button"
                               class="btn  btn-default btn-width m-l-10 position_back">
                        返回
                    </button>
                </div>
                <div class="col-md-12">
                    <div  class="form-group col-md-6 input-xx">
                        <label  class='col-md-3 title'>职位名称:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="real_name" id="zw" value="<?=$data['name']?>">
                    </div>
                    <button style='margin-left:20px'  type="button"
                             class="btn  btn-primary btn-width btn-submit">
                        保存
                    </button>
                </div>

            </form>
        </div>






</div>
<div class="container-fluid"
     style="display:flex;flex-grow:1;flex-direction: column;padding:0;">

</div>


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- /.content-wrapper -->

<?php include_once(NAV_DIR."/footer.php");?>

<script>
    $('.position_back').click(function(){

        window.location.href= '<?=Url::toRoute('admin-position/index')?>'
    })

    var glo = {
        'on_off':true,
    };

    //修改职位
    $('.btn-submit').click(function(){
        var id = $('#admin-position-id').val();
        if(!glo.on_off){
            return false;
        }
        var value = $('#zw').val();

        if(value == ''){
            fail('缺少参数！');
            return false;
        }


        //提交职位信息
        glo.on_off = false;
        $.ajax({
            url:'<?=Url::toRoute('admin-position/update')?>',
            type:'post',
            dataType:'json',
            data:{'name':value,'id':id,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
            success:function (data) {
                glo.on_off = true;
                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-position/index')?>');
                }else{
                    fail(data.message);
                }

            }
        })

    });

</script>
