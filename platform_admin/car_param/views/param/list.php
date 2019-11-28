<?php
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/department.css">
<link rel="stylesheet" href="/static/css/public/treegrid.css">
<link rel="stylesheet" href="/static/css/public/admin-branch.css">


<style>
    tbody tr td.opr-box a{
        font-size: 14px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content container-fluid">
        <div class="container-fluid" style="display:flex;flex-grow:1;flex-direction: column;padding:0;width:100%;">
            <div class="right-box p-b-20">
                <button type="button" class="btn  btn-primary param_add_btn">
                    <?=Yii::t('app', 'ADD_FIRST_LEVEL_PARAM')?>
                </button>
            </div>
            <div class="row">
                <table class="tree1">
                    <thead>
                    <tr>
                        <th width="34%" class="sl"><?=Yii::t('app', 'PARAM_CNNAME')?></th>
                        <th width="34%" class="sl"><?=Yii::t('app', 'PARAM_NAME')?></th>
                        <th width="12%" class="sl"><?=Yii::t('app', 'PARAM_VALUE')?></th>
                        <th width="20%" class="sl"><?=Yii::t('app', 'OPERATION')?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $v):?>
                        <tr class="treegrid-<?=$v['id']?> <?php if($v['parent_id'] != 0) echo 'treegrid-parent-'.$v['parent_id']?>">
                            <td>
                                <div class="text-box sl" contenteditable="false" style="font-size:12px;"><?=$v['cnname']?></div>
                            </td>
                            <td><?=$v['category_name']?></td>
                            <td class="sl"><?=$v['category_value']?></td>
                            <td class="sl opr-box" >
                                <div class="czuo-box" style='width:fit-content'>
                                    <a href="<?=url::toRoute(['edit','id'=>$v['id']])?>"><?=Yii::t('app', 'EDIT')?></a>
                                    <span class='xian'></span>
                                    <a href="javascript:;" class='set-btn del-btn' data-id="<?=$v['id']?>"><?=Yii::t('app', 'DELETE')?></a>
                                    <?php if($v['level'] < 3):?>
                                    <span class='xian'></span>
                                    <a href="<?=url::toRoute(['add','pid'=>$v['id']])?>"><?=Yii::t('app', 'ADD')?></a>
                                    <?php endif;?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/jquery.treegrid.js"></script>
<script src="/static/js/public/department.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.tree1').treegrid({
            initialState: 'expanded',
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
        if ($('#node-2').treegrid('isCollapsed')) {//节点时折叠的吗
            $('#node-2').treegrid('isCollapsed');
        } else {
            $('#node-2').treegrid('isCollapsed');

        }
    };
    //新增
    $('.param_add_btn').unbind('click').click(function () {
        location.href = '<?=url::toRoute('add')?>';
    });
    //删除
    $('.del-btn').unbind('click').click(function () {
        var id = $(this).attr('data-id');
        $('.delete').show();
        // 删除提示框
        $('.delete').find('.cancel').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.icon-close').unbind('click').click(function(){
            $('.delete').hide();
        });
        $('.delete').find('.confirm').unbind('click').click(function(){
            $('.delete').hide();
            $.ajax({
                url:'<?=Url::toRoute('delete')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    console.log(data);
                    if( data.code == 0 ) {
                        succ(data.message,'<?=url::toRoute('list')?>');
                    } else {
                        fail(data.message);
                    }
                }
            });
        });
    });
</script>