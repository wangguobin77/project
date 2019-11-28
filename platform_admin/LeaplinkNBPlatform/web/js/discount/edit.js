/*
* @Author: Marte
* @Date:   2019-08-12 17:20:41
* @Last Modified by:   Marte
* @Last Modified time: 2019-08-13 09:56:45
*/

'use strict';
//初始化select
$(document).ready(function(){
    $('.select2').select2()
})
// 点击重置清空input
function clear_all(){
    $('.name').val('')
    $('#product_img').attr('src','');
}

// 选择优惠券模板
$('.coupon-radio').each(function(index){
       $(this).click(function(){
            $(this).parent().addClass('item-img-active').siblings().removeClass('item-img-active')
       })
})

//验证必填项
function reg(){
    var name=$('#name').val(),
        value=$('#value').val(),
        price=$('#price').val();
    if(name==''){
        fail('请输入商品名');
        return
    }
    if(!fun.isMc(name)){
        fail('商品名格式不合法');
        return
    }

    if(value==''){
        fail('请输入价值');
        return
    }
    if(!fun.isNum(value)){
        fail('价值格式不合法');
        return
    }



}