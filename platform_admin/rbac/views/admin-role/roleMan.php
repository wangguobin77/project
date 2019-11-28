<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/17
 * Time: 下午3:17
 */

use yii\helpers\Url;

?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet"
      href="/static/css/public/treegrid.css">
<link rel="stylesheet"
      href="/static/css/rbac/admin-role.css">
<link rel="stylesheet"
      href="/static/css/public/table.css">
<link rel="stylesheet"
      href="/static/css/public/dialog.css">
<link rel="stylesheet" href="/static/css/public/add-dialog.css">
<!-- 右下方主体内容 -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="container-fluid"
             style="display:flex;flex-grow:1;flex-direction: column;padding:0;width:100%;">
            <div class="right-box p-b-20 row">
                <button type="button" id="add-role-info" data-id="<?=$pid?>" class="btn  btn-default">
                    添加
                </button>

            </div>
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
                                    <th width="60%"
                                        class="sl">角色名称</th>
                                    <th width="40%"
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
    <!-- 列表完 -->
</div>

<!-- 添加 编辑角色组名称 -->
<div class="del-box add-dialog">
    <div class="dialog">
        <span class=" icon-close cursor fa fa-close"></span>
        <h4 class="dia-title">输入名称</h4>
        <div class="sl-input">
            <input type="text" name="role-name" id="role-name" class="input-shuru input-sl form-control" placeholder="输入名称">
            <!--                 <span class="ts">*名称不能为空！</span> -->
        </div>
        <div class="operate-del">
            <button class="btn btn-primary  m-r-5" onclick="addData()">确认</button>
            <button class="btn btn-default cancel">取消</button>
        </div>
    </div>
</div>
<!--update-->
<div class="del-box up-add-dialog">
    <div class="dialog">
        <span class=" icon-close cursor fa fa-close"></span>
        <h4 class="dia-title">修改输入名称</h4>
        <div class="sl-input">
            <input type="text" name="role-name" id="up-role-name" class="input-shuru input-sl form-control" placeholder="输入名称">
            <!--                 <span class="ts">*名称不能为空！</span> -->
        </div>
        <div class="operate-del">
            <button class="btn btn-primary  m-r-5" onclick="editData()">确认</button>
            <button class="btn btn-default cancel">取消</button>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<?php include_once(NAV_DIR."/footer.php");?>

<!-- dialog js -->

<script src="/static/js/public/jquery.treegrid.js"></script>
<script type="text/javascript">
    /*
    设置cookie
     */
    function SetCookie(Name, Value){
        var str=Name+"="+Value;
        document.cookie =str;
    }
    var glo = {
        id:0,
        is_true:true
    }
    $(document).ready(function () {
        //console.log('<?=$i?>');
        //console.log('<?=$tt?>');
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

    //添加角色分组
    $('#add-role-info').click(function(){

        glo.id = $(this).attr('data-id');//父类id
        $('.add-dialog').show();

    });

    /*
    添加角色名称时保存某个 唯一 表示 定位到修改当前的位置变量 pos_id
     */

    var pos_id='';//用来判断其是否含有父级元素
    function add(obj){
        glo.id = $(obj).attr('data-id');
        /*
        pos_id 赋值
         */
        SetCookie('glo_id',glo.id);
        pos_id=$(this).parent().parent().attr('id');

        $('#role-name').val('');//晴空

        $('.add-dialog').show();
    }

    function addData()
    {
        if(!glo.is_true){
            return false;
        }
        if(!glo.id) return false;
        var role_value = $('#role-name').val();
        if(!role_value){fail('名称不能为空');return false;}
        glo.is_true = false;
        $.ajax({
            url:'<?=Url::toRoute('admin-role/create')?>',
            type:'post',
            dataType:'json',
            data:{'pid':glo.id,'name':role_value,'_csrf':"<?= Yii::$app->request->csrfToken ?>"},
            success:function (data) {
                glo.is_true = true;

                if (data.code == 0 ){
                    $('.add-dialog').hide()
                    succ('添加成功','<?=Url::toRoute('admin-role/index')?>');

                }else{
                    fail('添加失败，请重新添加！');
                }
            }
        })
    }

    /*
     b
     */
    function edit(obj){
        glo.id = $(obj).attr('data-id');
        SetCookie('glo_id',glo.id);
        $('#up-role-name').val($(obj).attr('data-name'));
        $('.up-add-dialog').show();
    }

    function editData()
    {
        if(!glo.id) return false;
        var children_v = $('#up-role-name').val();

        if(!glo.is_true){
            return false;
        }
        glo.is_true = false;
        $.ajax({
            url:'<?=Url::toRoute('admin-role/update')?>',
            type:'post',
            dataType:'json',
            data:{'id':glo.id,'name':children_v,'_csrf':"<?= Yii::$app->request->csrfToken ?>"},
            success:function (data) {
                glo.is_true = true;
                if (data.code == 0 ){
                    $('.up-add-dialog').hide();
                    succ('修改成功！','<?=Url::toRoute('admin-role/index')?>')
                }else{
                    fail('修改失败！');
                }
            }
        })
    }

    function del(obj){
        glo.id = $(obj).attr('data-id');
        $('.delete').show();
    }

    //确认删除角色
    $('.confirm').click(function(){
        $('.delete').hide();
        var role_id = glo.id;//要删除的角色id
        if(!role_id){
            return false;
        }

        if(!glo.is_true){
            return false;
        }
        glo.is_true = false;

        $.ajax({
            url:'<?=Url::toRoute('admin-role/delete')?>',
            type:'post',
            dataType:'json',
            data:{'role_id':role_id,'_csrf':"<?= Yii::$app->request->csrfToken ?>"},
            success:function (data) {
                //console.log(data);
                glo.is_true = true;

                if (data.code == 0 ){
                    succ(data.message,'<?=Url::toRoute('admin-role/index')?>');
                }else{
                    fail(data.message);
                }
            }
        })

    });
</script>
