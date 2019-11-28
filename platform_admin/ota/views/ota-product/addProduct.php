<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/department-add.css">
<!-- 内容区 -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">

    <!-- 导航下标注 -->
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default"  onclick=javascript:location.href="<?=Url::toRoute('ota-product/product_list')?>" >
                返回
            </button>
        </div>
        <div class="row col-md-12">
            <!--            <form id="mymessage-form">-->
            <div class="col-md-6">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>产品名称:</label>
                    <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" autocomplete="off"  placeholder="请输入产品名称" name='product_name'>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>产品类型:</label>
                    <select id="type"    class="form-control  col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <!-- <option value="">请选择...</option> -->
                        <?php foreach ($product_types as $k=>$item):?>
                            <option value="<?=$k?>" ><?=$item?></option>
                        <?php endforeach;?>

                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'>产品CODE:</label>
                    <input  type="text" class="form-control " autocomplete="off"  placeholder="请输入产品CODE" name="product_code" >
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-3 title-center' style='width:14%!important'>产品描述(选填):</label>
                    <textarea id='desc' style='height:200px' type="text"   class="form-control beizhu" autocomplete="off"  placeholder="请输入产品描述" ></textarea>
                </div>
            </div>



            <div class="col-md-6">
                <div class="form-group col-md-12 input-xx save-box">
                    <label class='col-md-4 title'></label>
                    <button class='btn btn-block btn-primary btn-sub'>提交</button>
                </div>
            </div>
            <input type="hidden" name="user_id" value="<?=$user_id?>">
            <!--            </form>-->
        </div>
</div>

<?php include_once(NAV_DIR."/footer.php");?>
<script>
// 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

        var type = $("#type").val();
        var desc = $("#desc").val();
        var product_name = $("input[name='product_name']").val();
        var product_code = $("input[name='product_code']").val();
        var user_id = $("input[name='user_id']").val();
        var user_name = $("input[name='user_name']").val();
        if(product_name ==''){
            fail('请填写产品名称！');return
            //$("input[name='product_name']").next().show();return
        }
        if(product_code ==''){
            fail('请填写产品CODE！');return
            //$("input[name='product_code']").next().show();return
        }
        if(type ==''){
            fail('请填写产品类型！');return
        }

        var name_length = getStrLeng(product_name);
        var code_length = getStrLeng(product_code);
        var desc_length = getDescStrLeng(desc);

        if( name_length > 128 || code_length > 128 || desc_length >2048) {
            fail('字符过长，请重新输入！');return
        }

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-product/product_submit')?>',
            type:'post',
            dataType:'json',
            data:{'product_name':product_name,'product_code':product_code,'user_id':user_id,'user_name':user_name,'desc':desc,'type':type,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                console.log(data);
                if(data.code > 0){
                    fail(data.message);return;
                }else {
                    succ(data.message,"<?=Url::toRoute('ota-product/product_list')?>");
                    // 保存成功

                }
            }
        })
    })

function getDescStrLeng(str){
    var realLength = 0;
    var len = str.length;
    var charCode = -1;
    for(var i = 0; i < len; i++){
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 2048) {
            realLength += 1;
        }else{
            // 如果是中文则长度加3
            realLength += 3;
        }
    }
    return realLength;
}


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

// 确认提交框操作end




</script>





