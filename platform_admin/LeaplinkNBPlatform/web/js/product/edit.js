/*
* @Author: Marte
* @Date:   2019-08-12 14:45:28
* @Last Modified by:   Marte
* @Last Modified time: 2019-08-12 15:46:38
*/
//上传图片
function imgUpload_sm(e) {
    var t = e.getAttribute("data-width"),
        a = e.getAttribute("data-height"),
        r = [],
        o = $(".imgs");
    r.push(o);
    var result = e.files[0];
    var s = result.size;
    if (s > 1024*1024*2){
        $('.img-des-zc').hide();
        $('.img-ts').show();
        return false;
    }else{
        $('.img-ts').hide();
    }
    var n = new FileReader;
    n.onload = function(o) {


        var l = o.target.result;
        let blob = new Blob([result]);//存储二进制数据
        console.log(blob)
        let url = URL.createObjectURL(blob);//生成本地图片地址用于图片预览
        console.log(url)  //转化成blob类型
        var s = new Image;
        $(e).prev().show();
        $(e).next('input').val(l);
        $(e).prev().find('img').attr('src', url);
        //                 $(e).next().next().remove();
        //                 $(e).prev().show();
        // s.onload = function() {
        //     $.ajax({
        //         url:'<?=Url::toRoute('logo')?>',
        //         type:'post',
        //         dataType:'json',
        //         data:{'file':l,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
        //         success: function(data) {
        //             console.log(data);
        //             if( data.code == 0 ) {
        //                 $(e).next().val(data.message);
        //                 $(e).prev().find('img').attr('src', data.message);
        //                 $(e).next().next().remove();
        //                 $(e).prev().show();
        //             } else {
        //                 fail(data.message);
        //             }
        //         }
        //     });

        // };
        s.src = l
    };
    n.readAsDataURL(result)
}
function reg(){
    var code=$('#verification_code').val(),
        name=$('#name').val(),
        original_price=$('#original_price').val(),
        sell_price=$('#sell_price').val(),
        product_img=$('.product_img').attr('src');

    if(code==''){
        fail('请输入核销码');
        return
    }
    if(!fun.isMc(code)){
        fail('核销码格式不合法');
        return
    }

    if(name==''){
        fail('请输入商品名');
        return
    }
    if(!fun.isMc(name)){
        fail('商品名格式不合法');
        return
    }

    if(original_price==''){
        fail('请输入原价');
        return
    }
    if(!fun.isNum(original_price)){
        fail('原价输入格式不合法');
        return
    }

    if(sell_price==''){
        fail('请输入售');
        return
    }
    if(!fun.isNum(sell_price)){
        fail('售价输入格式不合法');
        return
    }
    if(product_img==''){
        fail('请上传商品图片')
        return false;
    }

}
// 点击重置清空input 商品图片、
function clear_all(){
    $('.name').val('')
    $('#product_img').attr('src','');
}

// 点击close-icon
$('.close_box').delegate('.close-icon','click',function(e){
    e.preventDefault();
    $(this).parent().hide();
})

// 点击查看预览大图
$('.close_box').delegate('img.img-pic','click',function(e){
    e.preventDefault();
    var data_src=$(this).attr('src');
    $('.del-box-look').show();
    $('.del-box-look').find('img').attr('src',data_src);

})
// 点击预览关闭按钮
$('.close-btn').click(function(){
    $(this).parent().hide();
})