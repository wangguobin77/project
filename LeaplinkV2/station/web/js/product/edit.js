/*
* @Author: Marte
* @Date:   2019-08-12 14:45:28
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-21 17:26:47
*/

/**
 * 重置
 */
const resetall=()=>{
    $('.close_box').hide();
    $('input.form-control').val('');
    $('.bg-icon').find('input').val('');
    $('#shop_img').attr('src','');
    $('input[name="plate"]').val('');

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

/**
 * 各项信息验证
 */
var reg_fun_flag=false;
function reg_fun(){
    let codenumChecker = $('#code_num').TChecker({
        required: {
            rule: true,
            error: '*' + "核销码不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "核销码格式不正确"
        }
    });

    let nameChecker = $('#name').TChecker({
        required: {
            rule: true,
            error: '*' + "品名不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "品名格式不正确"
        }
    });

    let oripriceChecker = $('#ori_price').TChecker({
        required: {
            rule: true,
            error: '*' + "原价不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^[1-9]\d*$/,
            error: '*' + "原价格式不正确"
        }
    });
    let priceChecker = $('#price').TChecker({
        required: {
            rule: true,
            error: '*' + "原价不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^[1-9]\d*$/,
            error: '*' + "原价格式不正确"
        }
    });

    // 提交确认
    let correct = codenumChecker.check();
    if (!correct) {return false;}

    correct = nameChecker.check();
    if (!correct) {return false;}

    correct = oripriceChecker.check();
    if (!correct) {return false;}

    correct = priceChecker.check();
    if (!correct) {return false;}
    let shop_img=$('#shop_img').attr('src');
    if(shop_img==''){
        fail('请上传商品图片')
        return false;
    }

    var data =JSON.stringify($('.info-form').serializeObject());
    console.log(data)
    reg_fun_flag=true
}
