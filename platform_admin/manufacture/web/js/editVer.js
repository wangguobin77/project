/*
* @Author: Marte
* @Date:   2018-08-27 17:26:45
* @Last Modified by:   Marte
* @Last Modified time: 2018-11-22 17:49:14
*/

'use strict';

// 点击命令列表
$('.ml-item').click(function(){
    $(this).addClass('li-active').siblings('.ml-item').removeClass('li-active');
    $(this).find('a').addClass('li-a-active').siblings('.ml-item').find('a').removeClass('li-a-active');
    $('.youce-add-box').hide();
    $('.youce-detail-box').show();

})
// 点击添加命令
// $('.add-commond').click(function(){
//     $(this).addClass('add-ml-active').siblings().removeClass('li-active');

//     $('.youce-add-box').show();
//     $('.youce-detail-box').hide();
// })
