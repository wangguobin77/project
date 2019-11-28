/*
* @Author: Marte
* @Date:   2019-08-12 17:20:41
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-25 10:11:14
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
    var names = document.getElementsByName("coupon");
    for(var i=0;i<names.length;i++){
            names[i].checked=false;
     }
}

// 选择优惠券模板
$('.coupon-radio').each(function(index){
       $(this).click(function(){
            $(this).parent().addClass('item-img-active').siblings().removeClass('item-img-active')
       })
})

function reg(){
    let nameChecker = $('.name').TChecker({
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

    let oripriceChecker = $('#value').TChecker({
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
    let correct = nameChecker.check();
    if (!correct) {return false;}

    correct = oripriceChecker.check();
    if (!correct) {return false;}

    correct = priceChecker.check();
    if (!correct) {return false;}
    var names = document.getElementsByName("coupon"),
        select_flag=false;
    for(var i=0;i<names.length;i++){
        if(names[i].checked){
            select_flag = true ;
         }
     }
     if(select_flag==false){
        fail('请选择优惠券背景！')
        return
     }


}



