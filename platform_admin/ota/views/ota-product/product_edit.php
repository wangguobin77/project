
<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/department-add.css">
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div class="right-box p-b-20 row">
            <button type="button" class="btn  btn-default"  onclick=javascript:location.href="<?=Url::toRoute('ota-product/product_list')?>" >
                返回
            </button>
        </div>
        <!-- 内容区域-->
        <div class="row col-md-12">
<!--            <form id="mymessage-form">-->
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>产品名称:</label>
                        <input  type="text" class="form-control my-colorpicker1 colorpicker-element bmdz" value="<?=$data['pro_name']?>" autocomplete="off"  placeholder="请输入产品名称" name='product_name'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>产品类型:</label>
                        <select id="type"    class="form-control  col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <!-- <option value="">请选择...</option> -->
                            <?php foreach ($product_types as $k=>$item):?>
                                <option value="<?=$k?>" <?php if($k==$data['type']){ echo 'selected';} ?> type_name = "<?=$item?>"><?=$item?></option>
                            <?php endforeach;?>

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-4 title'>产品CODE:</label>
                        <input  type="text" class="form-control " autocomplete="off" value="<?=$data['pro_code']?>" placeholder="请输入产品CODE" name="product_code" >
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group col-md-12 input-xx">
                        <label class='col-md-3 title-center' style='width:14%!important'>产品描述(选填):</label>
                        <textarea id='desc' style='height:200px' type="text"   class="form-control beizhu" autocomplete="off"  placeholder="请输入产品描述" ><?=$data['description']?></textarea>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group col-md-12 input-xx save-box">
                        <label class='col-md-4 title'></label>
                        <button class='btn btn-block btn-primary btn-sub'>提交</button>
                    </div>
                </div>
                <input type="hidden" name="user_id" value="<?=$user_id?>">
                <input type="hidden" name="pro_id" value="<?=$data['pro_id']?>">
<!--            </form>-->
        </div>

    </section>
</div>

<!-- 内容区 -->


<?php include_once(NAV_DIR."/footer.php");?>
<script>

    // 点击确认提交时弹框
    $('.save-box').find('.btn-sub').click(function(){

        var type = $("#type").val();
        var type_name =$("#type").find("option:selected").attr('type_name');

        var desc = $("#desc").val();
        var product_name = $("input[name='product_name']").val();
        var product_code = $("input[name='product_code']").val();
        var user_id = $("input[name='user_id']").val();
        var user_name = $("input[name='user_name']").val();
        var pro_id = $("input[name='pro_id']").val();
        if(product_name ==''){
            fail('请填写产品名称！');return
        }
        if(product_code ==''){
            fail('请填写产品CODE！');return

        }
        var name_length = getStrLeng(product_name);
        var code_length = getStrLeng(product_code);
        if( name_length > 128 || code_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }

        if(type ==''){
            fail('请填写产品类型！');return
        }

        var msg ='您将要修改的信息如下，请确认';

        if(product_name != '<?=$data['pro_name']?>'){
            msg += '\n产品名称：'+product_name+'\n';

        }
        if(product_code != '<?=$data['pro_code']?>'){
            msg += '\n产品CODE：'+product_code+'\n';

        }
        if(type != '<?=$data['type']?>'){
            msg += '\n产品类型：'+type_name+'\n';
        }
        if(Base64.encode(desc) != '<?=base64_encode($data['description'])?>'){
            msg += '\n产品描述：'+desc+'\n';
        }
        msg +="确定要修改吗？";
        // if(confirm(msg)){
            $.ajax({
                // 验证当前单号是否存在
                url:'<?=Url::toRoute('ota-product/product_edit_submit')?>',
                type:'post',
                dataType:'json',
                data:{'product_name':product_name,'product_code':product_code,'user_id':user_id,'user_name':user_name,'desc':desc,'type':type,'pro_id':pro_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);
                    }else {
                        // 保存成功
                        succ(data.message,"<?=Url::toRoute('ota-product/product_list')?>");



                    }
                }
            })
        // }

    })
    $('.del-box').find('.cancel').click(function(){
        $('.del-box').hide();
    })
    $('.del-box').find('.icon-close').click(function(){
        $('.del-box').hide();
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

    var Base64 = {
// private property
        _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

// public method for encoding
        encode : function (input) {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = Base64._utf8_encode(input);

            while (i < input.length) {

                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                    Base64._keyStr.charAt(enc1) + Base64._keyStr.charAt(enc2) +
                    Base64._keyStr.charAt(enc3) + Base64._keyStr.charAt(enc4);

            }

            return output;
        },

// public method for decoding
        decode : function (input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {

                enc1 = Base64._keyStr.indexOf(input.charAt(i++));
                enc2 = Base64._keyStr.indexOf(input.charAt(i++));
                enc3 = Base64._keyStr.indexOf(input.charAt(i++));
                enc4 = Base64._keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

            }

            output = Base64._utf8_decode(output);

            return output;

        },

// private method for UTF-8 encoding
        _utf8_encode : function (string) {
            string = string.replace(/\r\n/g,"\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                }
                else if((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
                else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            }

            return utftext;
        },

// private method for UTF-8 decoding
        _utf8_decode : function (utftext) {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while ( i < utftext.length ) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                }
                else if((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i+1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                }
                else {
                    c2 = utftext.charCodeAt(i+1);
                    c3 = utftext.charCodeAt(i+2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }
            return string;
        }
    }
</script>





