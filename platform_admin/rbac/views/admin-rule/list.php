<?php
use yii\helpers\Url;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
      href="/static/css/public/admin-branch.css">
<link rel="stylesheet"
      href="/static/css/public/treegrid.css">
<link rel="stylesheet"
      href="/static/css/public/department.css">
<style type="text/css">
    table{
        table-layout:fixed;
    }
    table tr td:nth-child(2){
        white-space:normal;
        word-break:break-all;
    }
</style>
<div class="content-wrapper">
       <!-- Main content -->
      <section class="content container-fluid">
        <div class="container-fluid"
                     style="display:flex;flex-grow:1;flex-direction: column;padding:0;width:100%;">
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
                                    <th width="22.5%" class="sl">名称</th>
                                    <th width="18%" class="sl">路径</th>
                                    <th width="8.5%" class="sl">类型</th>
                                    <th width="5.5%" class="sl">排序</th>
                                    <th width="8.5%" class="sl">是否显示</th>
                                    <th width="8.5%" class="sl">显示场景</th>
                                    <th width="8.5%" class="sl">允许添加子集</th>
                                    <th width="5%" class="sl">状态</th>
                                    <th width="16%" class="sl">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?=$listInfo?>

                            </tbody>

                        </table>

                                </div>
                            </div>
                        </div>
                    </div>


         </div>

       </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/jquery.treegrid.js"></script>
<script>
    //全局变量参数
    var glo = {
        id:'',
        is_true:true,
    }
    $(document).ready(function () {
        $('.tree1').treegrid();

    });


    function sendDelRule(obj){
        glo.id = $(obj).attr('data-id');
        $('.delete').show();
    }

    //确认删除角色
    $('.confirm').click(function(){
        $('.delete').hide();
        var rule_id = glo.id;//要删除的角色id
        if(!rule_id){
            return false;
        }

        if(!glo.is_true){
            return false;
        }
        glo.is_true = false;

        $.ajax({
            url: '<?=Url::toRoute('admin-rule/del')?>',
            type: 'post',
            dataType: 'json',
            data: {'id': glo.id, '_csrf': "<?= Yii::$app->request->csrfToken ?>"},
            success: function (data) {
                glo.is_true = true;
                if (data.code == 0) {
                    succ(data.message, '<?=Url::toRoute('admin-rule/index')?>');
                } else {
                    fail(data.message);
                }
            }
        })

    });
</script>

