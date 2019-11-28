<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/18
 * Time: 上午10:56
 */
use yii\helpers\Url;

$AdminRole = new app\models\AdminRole;
use yii\bootstrap\ActiveForm;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
      href="/static/css/public/table.css">
<link rel="stylesheet"
      href="/static/css/public/dialog.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <div class="right-box p-b-20 row" style="padding:15px;">
        <button type="button" class="btn  btn-default">
            返回
        </button>

    </div>
   <!-- <s0ection class="content container-fluid">-->
        <div class="row">

            <div class="col-sm-10 col-md-offset-1">
                <h2 style="font-size:20px;">分配权限</h2>
                <div id="treeview-checkable" class=""></div>
            </div>
        </div>
   <!-- </section>-->
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once(NAV_DIR."/footer.php");?>
<!-- AdminLTE App -->

<!-- 增加的js -->
<script src="/static/js/public/bootstrap-treeview.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    $('.btn-default').click(function(){
        back();
    })
    //全局变量参数
    var glo = {
        is_true:true,
        role_id:null//需要修改的id
    }

    $(function() {

        glo.role_id = '<?=$role_id?>';
        var defaultData = <?php echo json_encode($ruleAll)?>;
        //console.log(defaultData);
        //权限
        var rule = <?php echo json_encode($rule_list);?>;

        var $checkableTree = $('#treeview-checkable').treeview({
            data: defaultData,
            showIcon: false,
            showCheckbox: true,
            onNodeChecked: function (event, node) {
                sendRuleId(glo.role_id,1,node.id);
                rule.push(node.id);
                glo.is_true = true;
            },
            onNodeUnchecked: function (event, node) {

                sendRuleId(glo.role_id,2,node.id);
                delete  rule[$.inArray(node.id, rule)]
                glo.is_true = true;
            }
        });
    });

    function sendRuleId(role_id,type,rule_id)
    {
        if (!glo.is_true) {
            return false;
        }
        glo.is_true = false;
        $.ajax({
            url: '<?=Url::toRoute('admin-role/add-rule-role')?>',
            type: 'post',
            dataType: 'json',
            data: {'role_id': glo.role_id, 'type': type, 'rule_id': rule_id,'_csrf':"<?= Yii::$app->request->csrfToken ?>"},
            success: function (data) {
                glo.is_true = true;
            }
        })
    }

</script>
