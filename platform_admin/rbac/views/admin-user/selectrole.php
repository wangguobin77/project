<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\AdminRole;
use app\helps\Tree;

$AdminRole = Tree::makeTree((new AdminRole)->getRoleAll());//全部角色

?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" type="text/css" href="/static/css/public/iCheck/all.css">
<link rel="stylesheet" href="/static/css/public/admin.css">

<link rel="stylesheet" href="/static/css/public/table.css">

<link rel="stylesheet" href="/static/css/rbac/set-role.css">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content container-fluid">
            <div class="right-box p-b-20 row">
                <button type="button" class="btn btn-default btn-back-call">
                    返回
                </button>
            </div>
            <form id="role-from">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <input name="id" type="hidden" value="<?=$uid?>">
                <!-- 选择角色-->
                <div class="row col-md-12 role-item">
                    <?php if($AdminRole):?>
                        <form id="role-from">
                            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                            <input name="id" type="hidden" id="user_id" value="<?=$uid?>">
                            <?php foreach ($AdminRole as $key=>$val):?>
                            <div class="row col-md-12">
                                <div class="col-md-6 role-title">
                                    <?=$val['name']?>
                                </div>
                            </div>
                                <?php if(isset($val['children'])):?>
                                    <?php foreach ($val['children'] as $v):?>
                                    <div class="col-md-3">
                                        <div class="form-group col-md-12">
                                            <label class="col-md-12 sl role-name">
                                                <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                                    <input type="checkbox" class="minimal" name="items[]"  value="<?=$v['id']?>" <?=in_array(intval($v['id']),$myRoleList) == true?'checked':''?> style="position: absolute; opacity: 0;">
                                                </div>
                                                <h5 class='items sl'><?=$v['name']?></h5>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach;?>
                                <?php endif;?>
                            <?php endforeach;?>
                        </form>
                    <?php else:?>
                        <p>暂无角色，请管理员添加角色</p>
                    <?php endif;?>
                </div>
            </form>

            <!-- 提交按钮 -->
           <!-- <div class="row col-md-12">
                <div class="col-md-6">
                    <div class="form-group col-md-6 input-xx">
                        <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                    </div>
                </div>
            </div>-->
    </div>
    </section>
</div>
<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>
<script   src="/static/js/public/iCheck/icheck.min.js"></script>
<script type="text/javascript">
   /* var glo = {
        'is_true':true
    };

    //提交用户信息表单
    $('.submit-btn').unbind('click').click(function(){
        if(!glo.is_true){
            return false;
        }

        var data = $('#role-from').serialize();

        $.ajax({
            url:'<?=Url::toRoute('admin-user/set-role')?>',
            type:'post',
            dataType:'json',
            data:data,
            success:function (data) {
                glo.is_true = true;
                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-user/index')?>');
                }else{
                    fail(data.message);
                }
            }
        })
    });*/
   //全局变量参数
   var glo = {
       is_true:true,
       role_id:null//需要修改的id
   }

   $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
       checkboxClass: 'icheckbox_minimal-blue',
   });

   $('input[type="checkbox"].minimal').on('ifClicked',function () {
       var _this = this

       var id = $('#user_id').val();

       var role_id = $(_this).val();
       var type_id = 0;

      if($(_this).is(':checked')){//选中为2 与正常相反
          type_id = 2;

      } else{
          type_id = 1;

      }

       if(!id) return false;

       if(!role_id) return false;

       glo.is_true = false;
       $.ajax({
           url: '<?=Url::toRoute('admin-user/set-role')?>',
           type: 'post',
           dataType: 'json',
           data: {'role_id': role_id,'id': id,'type':type_id,'_csrf':"<?= Yii::$app->request->csrfToken ?>"},
           success: function (data) {
               glo.is_true = true;
             /*  if (data.code == 0 ){
                   succ(data.message,'');
               }else{
                   fail(data.message);
               }*/
           }
       })

   });


</script>

