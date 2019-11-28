/*
* @Author: Marte
* @Date:   2019-09-05 10:59:55
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-18 17:22:49
*/

'use strict';
$('.close-icon').click(function(){
    $('.reg-con').hide()
})
// 两种方式切换
$('.mm-login').click(function(){
    $('.sub-yzm').hide();
    $('.sub-mm').show();
})

$('.yzm-login').click(function(){
    $('.sub-yzm').show();
    $('.sub-mm').hide();
})
/**
 * 点击注册
 */
const reg=()=>{
    $('.reg-con').show()
}
var showpass=false;

const changepass=()=>{
    showpass=!showpass
    if(showpass){
        $('#password').attr('type','text')
    }else{
         $('#password').attr('type','password')
    }
}