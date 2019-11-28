/*
* @Author: Marte
* @Date:   2018-07-23 10:00:19
* @Last Modified by:   Marte
* @Last Modified time: 2018-09-17 11:41:00
*/

'use strict';
// 添加机构部门
$('.opr-box').find('a.glyphicon-plus').click(function(){
    $(this).next().toggle().parent().parent().parent().siblings().find('ul.add-drop').hide();

})

//计算row高度
var height=$('table').height();
console.log(height);
$('table').parent('.row').css("height",height+80);

// 删除框操作
$('.del-box').find('.cancel').click(function(){
    $('.del-box').hide();
})

$('.del-box').find('.icon-close').click(function(){
    $('.del-box').hide();
})