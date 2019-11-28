

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
            <button type="button" class="btn  btn-default" onclick="goback()" >
                返回
            </button>

        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
            <div id="admin-user-form">
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>来源 :</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" value="<?=$data['client_name']?>" autocomplete="off" name="client_name" placeholder="请输入" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>来源ID:</label>
                        <input type="text" class="form-control my-colorpicker1 colorpicker-element bmmc" disabled value="<?=$data['client_id']?>" autocomplete="off" name="client_id" placeholder="请输入" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>状态:</label>
                        <select id="status" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <option value="" >请选择</option>
                            <option value="0" <?php if($data['status'] ==0){echo 'selected'; } ?>>禁用</option>
                            <option value="1" <?php if($data['status'] ==1){echo 'selected'; } ?> >启用</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="pro_id" value="<?=$data['id']?>">
                <div class="col-md-6"
                     style='height: 54px;'>
                </div>
                <div class="col-md-6 save-box">
                    <div class="form-group col-md-12 input-xx">
                        <label class="col-md-4 title"></label>
                        <button class="btn btn-block btn-primary submit-btn">确定修改</button>
                    </div>
                </div>


            </div>
        </div>
    </section>
</div>
<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script>
    function goback(){
        history.go(-1);//返回或者history.back();
    }
    $('.select2').select2()
    // 点击确认提交时弹框
    $('.save-box').find('.submit-btn').click(function(){

        var status = $("#status").val();
        var client_name = $("input[name='client_name']").val();
        var client_id = $("input[name='client_id']").val();
        var pro_id = $("input[name='pro_id']").val();
        if(client_name ==''){
            // alert('请填写产品名称！');return
            $("input[name='client_name']").next().show();return
        }else{
            $("input[name='client_name']").next().hide();
        }
        if(client_id ==''){
            // alert('请填写产品CODE！');return
            $("input[name='client_id']").next().show();return

        }else{
            $("input[name='client_id']").next().hide();
        }
        var name_length = getStrLeng(client_name);
        var code_length = getStrLeng(client_id);
        if( name_length > 128 || code_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        var msg ='您将要修改的信息如下，请确认';

        if(client_name != '<?=$data['client_name']?>'){
            msg += '\n来源：'+client_name+'\n';

        }

        if(status ==''){
            fail('请选择状态！');return
            //$("input[name='product_code']").next().show();return
        }
        var status_name = '';
        if(status ==0){
            status_name +='禁用';
        }else if(status ==1){
            status_name +='启用';
        }
        if(client_id != '<?=$data['client_id']?>'){
            msg += '\n来源ID：'+client_id+'\n';

        }
        if(status != '<?=$data['status']?>' ){
            msg += '\n状态：'+status_name+'\n';
        }

        msg +="确定要修改吗？";
        if(confirm(msg)){

            $.ajax({
                // 验证当前单号是否存在
                url:'<?=Url::toRoute('club_client/client_submit')?>',
                type:'post',
                dataType:'json',
                data:{'client_name':client_name,'client_id':client_id,'status':status,'pro_id':pro_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);
                    }else {
                        // 保存成功
                        succ(data.message,function(){
                        refresh()
                       });
                        //window.location.href='<?//=Url::toRoute('club_client/client_detail')?>//&client_id='+data.data;
                    }
                }
            })
        }

    })
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


</script>





