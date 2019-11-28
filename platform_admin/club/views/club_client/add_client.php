<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>


<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <?php include_once(NAV_DIR."/bottom-menu.php");?>
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default" onclick=location.href="<?=Url::toRoute(('club_client/client_list'))?>">
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <div id="admin-user-form">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>来源 :</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc"  autocomplete="off" name="client_name" placeholder="请输入" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>来源ID:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc"  autocomplete="off" name="client_id" placeholder="请输入" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <select id="status" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >请选择</option>
                            <option value="0" >禁用</option>
                            <option value="1" selected >启用</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6"
                     style='height: 54px;'>
                </div>
                <div class="col-md-6 save-box">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title"></label>
                        <button class="btn btn-block btn-primary submit-btn">提交</button>
                    </div>
                </div>


            </div>
        </div>
    </section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>

<script src="/static/js/public/select2.full.min.js"></script>
<script>

    $('.select2').select2()
// 点击确认提交时弹框
    $('.submit-btn').click(function(){
        var client_name = $("input[name='client_name']").val();
        var client_id = $("input[name='client_id']").val();
        var status = $("#status").val();
        /*var user_id = $("input[name='user_id']").val();
        var user_name = $("input[name='user_name']").val();*/
        if(client_name ==''){
            fail('请输入来源！');return
            //$("input[name='product_name']").next().show();return
        }
        if(client_id ==''){
            fail('请输入来源ID！');return
            //$("input[name='product_code']").next().show();return
        }

        if(status ==''){
            fail('请选择状态！');return
            //$("input[name='product_code']").next().show();return
        }

        var name_length = getStrLeng(client_name);
        var code_length = getStrLeng(client_id);

        if( name_length > 128 || code_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('club_client/client_submit')?>',
            type:'post',
            dataType:'json',
            data:{'client_name':client_name,'client_id':client_id,'status':status,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    // alert(data.message);return
                    fail(data.message);return
                }else {
                    console.log(data);
                    // 保存成功
                    // alert(data.message);
                    // window.location.href='<?=Url::toRoute('client_detail')?>?pro_id='+data.data;
                    //var src='<?//=Url::toRoute('club_client/client_detail')?>//&client_id='+data.data;
                    succ(data.message,function(){
                        refresh()
                    });
           
                }
            }
        })
    })

    function getStrLeng(str){
        var realLength = 0;
        var len = str.length;
        var charCode = -1;
        for(var i = 0; i < len; i++){
            charCode = str.charCodeAt(i);
            if (charCode >= 0 && charCode <= 128) {
                realLength += 1;
            }else{
                // 如果是中文则长度加3
                realLength += 3;
            }
        }
        return realLength;
    }
    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.confirm').click(function(){
        window.location.href="auditDetail.html";
    })
// 确认提交框操作end




</script>





