/*
* @Author: Marte
* @Date:   2018-07-16 16:27:31
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-31 10:17:23
*/

'use strict';


// 删除框体
$('.icon-operation_delate').click(function(){
    $('.del-box').show();
})

$('.icon-close').click(function(){
    $('.del-box').hide();
})

$('.cancel').click(function(){
    $('.del-box').hide();
})

// 弹框取消按键
$('.del-box').find('.icon-close').click(function(){
    $(this).parent().parent().hide();
})