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
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

        <div class="row col-md-12" id='add_form'>
            <form id="admin-user-form">
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>"/>
                <div class="col-md-6" style='padding-left:0 !important'>
                    <div style='padding-left:0 !important' class="form-group col-md-12 input-xx">
                        <label style='padding-left:0 !important' class='col-md-3 title'>职位名称:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" autocomplete="off" name="real_name" id="zw" placeholder="请输入职位名称" >
                   <button   id="btn-submit" type="button"
                    class="btn btn-block btn-primary btn-width add-btn" style='z-index:1000;height: 34px; margin-left:20px'>
                        新增
                  </button>
                    </div>
                </div>
            </form>
        </div>



        <!-- <div class="right-box p-b-20"> -->
            <!-- <button style='z-index:1000'   id="btn-submit" type="button"
                    class="btn btn-block btn-primary btn-width add-btn" style='z-index:1000; height: 34px; width: 50px; margin-left: 20px'>
                新增
            </button> -->
           <!-- <button style='z-index:1000'  type="button"
                    class="btn btn-block btn-default btn-width m-l-10">
                返回
            </button>-->
        <!-- </div> -->
        <div class="container-fluid"
             >

            <!-- 下面的列表  -->
            <div class="">
                <table class="table">
                    <thead>
                    <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                        <th width="20%"
                            class="sl">职位编号</th>
                        <th width="40%"
                            class="sl">职位名称</th>
                        <th width="40%"
                            class="sl">操作</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php if($model){ foreach($model as $k=>$v){?>
                        <tr class="add_position">
                            <td class="sl"><?=$v['id']?></td>
                            <td class="sl zhiwei-name">
                                <div class="div-textarea div-textarea-edit" contenteditable="false" ><?=$v['name']?></div>
                            </td>
                            <td  class='caozuo-box auto'>
                                <a href="<?=Url::toRoute(['admin-position/update','id'=>$v['id']])?>"><span class="sl icon-operation_edit font_family edit-pancel cursor edit-box btn-up" data-id="<?=$v['id']?>">编辑</span></a>
                                <div class='xian'></div>
                                <span class="sl icon-operation_delate edit-pancel cursor btn-sc" data-id="<?=$v['id']?>">删除</span>
                            </td>
                        </tr>

                    <?php    }
                    }
                    ?>

                    </tbody>
                </table>
            </div>

            <!-- 搜索时没有此信息时显示 -->
            <div class="none-info"
                 style="display:none;">
                <div class="row"
                     style="display:flex;flex-wrap: wrap;justify-content: flex-start;padding:0 24px;">
                    <div class="none-info"
                         style="margin:0 auto;">
                        <img src="/static/images/errorview-empty.png"
                             alt="">
                        <h5 class="non-message">没有找到相关信息~</h5>
                    </div>
                </div>
            </div>
            <!-- 无此消息提示 end -->

            <!-- 页码 开始 -->

            <!-- 页码end -->

        </div>


    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once(NAV_DIR."/footer.php");?>

<script>

    var glo = {
        'on_off':true,
    };


    //添加职位
    $('#btn-submit').click(function(){
        if(!glo.on_off){
            return false;
        }
        var value = $('#zw').val();

        if(value == ''){
            fail('缺少参数！');
            return false;
        }
        /*if(!fun.isMc(value)){
            fail('参数不正确！');
            return false;
        }*/

        //提交职位信息
        glo.on_off = false;
        $.ajax({
            url:'<?=Url::toRoute('admin-position/create')?>',
            type:'post',
            dataType:'json',
            data:{'name':value,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
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


    //删除职位
    $('.btn-sc').click(function(){
        $('.del-box').show();
        var id = $(this).attr('data-id');
        $('.del-box').find('.confirm').click(function(){
            $('.del-box').hide();
            if(!glo.on_off){
                return false;
            }
            glo.on_off = false;
            $.ajax({
                url:'<?=Url::toRoute('admin-position/delete')?>',
                type:'post',
                dataType:'json',
                data:{'id':id,'_csrf':'<?= Yii::$app->request->csrfToken?>'},
                success:function (data) {
                    glo.on_off = true;
                    if (data.code == 0 ){
                        succ(data.message,'<?=Url::toRoute('admin-position/index')?>');
                    }else{
                        fail(data.message);
                    }


                }
            })

        })
    })
</script>
