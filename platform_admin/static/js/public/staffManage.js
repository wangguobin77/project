/*
* @Author: Marte
* @Date:   2018-07-02 14:28:55
* @Last Modified by:   Marte
* @Last Modified time: 2018-07-03 17:21:59
*/

'use strict';

// 列表下拉从菜单

    $('.drop-down-box').click(function(){
        $(this).find('.drop-select').css(
        'display', 'block').parent('').siblings('').children('ul').css('display','none');

    })



