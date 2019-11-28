<?php
use yii\helpers\Url;
use app\models\AdminBranchType;
use app\models\AdminInstitutionsType;
/*
$GLOBALS['AdminBranchTypeInfo'] = (new AdminBranchType)->disAdminBranchTypeName();//部门

$GLOBALS['AdminInstitutionsTypeInfo'] = (new AdminInstitutionsType)->disAdminInstitutionsTypeName();//组织*/

?>
<style type="text/css">
    tbody tr td.opr-box {
        line-height: normal;
    }
    tbody tr td.opr-box a{
        line-height:normal;
    }
</style>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
      href="/static/css/public/admin-branch.css">
    <link rel="stylesheet"
          href="/static/css/public/treegrid.css">
    <link rel="stylesheet"
          href="/static/css/public/department.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content container-fluid">
        <div class="container-fluid"
             style="display:flex;flex-grow:1;flex-direction: column;padding:0;width:100%;">
            <!-- <div class="bottom-menu"
                 style="margin-top:0;">
                <h4>组织/部门管理</h4>
            </div> -->
            <!-- 表格 -->
            <div id="example"
                 style="flex-grow:1;padding-left:24px;padding-right:24px;">
                <div class="row">
                    <!-- 左侧菜单 sm>=768px-->
                    <div class="col-md-12  col-sm-12 col-xs-12 "
                         style="border-right:0;padding:0;">
                        <div class="row">
                            <table class="tree1">
                                <thead>
                                <tr>
                                    <th width="34%"
                                        class="sl">组织/部门名称</th>
                                    <th width="15%"
                                        class="sl">组织/部门类型</th>
                                    <th width="20%"
                                        class="sl">地址</th>
                                    <th width="16%"
                                        class="sl">操作</th>
                                </tr>
                                </thead>
                                <tbody>
                               <?=$model?>
                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>
            </div>


        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once(NAV_DIR."/footer.php");?>

<script src="/static/js/public/jquery.treegrid.js"></script>
<script src="/static/js/rbac/department.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.tree1').treegrid({
            // initialState: 'collapsed',
            onChange: function () {
                // alert("Changed: "+$(this).attr("id"));
            },
            onCollapse: function () {
                // alert("Collapsed: "+$(this).attr("id"));
            },
            onExpand: function () {
                // alert("Expanded "+$(this).attr("id"));
            }
        });
        $('#node-1').on("change", function () {
            // alert("Event from " + $(this).attr("id"));
        });
    });

    window.onload = function () {
        $('.add-drop').hover(function(){

$(this).show()

},function(){

    $(this).hide()

});
        if ($('#node-2').treegrid('isCollapsed')) {//节点时折叠的吗
            $('#node-2').treegrid('isCollapsed');


        } else {
            $('#node-2').treegrid('isCollapsed');

        }
    }
    // 删除
    function Del(id) {
        $('.del-box').show();
        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();

            $.ajax({
                url:'<?=Url::toRoute(["admin-branch/del"])?>',
                type:'get',
                dataType:'json',
                data:{'id':id},
                success: function(data) {
                    if( data.code == 0 ) {
                        succ(data.message,'<?=Url::toRoute('admin-branch/index')?>');
                    }else{
                        fail(data.message);
                    }
                }
            });
            return;
        });

    }
</script>
