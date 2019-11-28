/*
* @Author: Marte
* @Date:   2019-11-12 10:48:15
* @Last Modified by:   Marte
* @Last Modified time: 2019-11-12 11:16:55
*/

'use strict';


$('.submit-btn').click(function(){
    var start_id=$('.start_id').val(),
        end_id=$('.end_id').val(),
        rule=/^[1-9]\d*$/,
        prevent=true;
    if(!prevent){
        return false
    }
    if(!rule.test(start_id)){
        fail('请输入开始的leapid,不能含有空格')
        $('.start_id').focus()
        return
    }
    if(!rule.test(end_id)){
        fail('请输入结束的leapid,不能含有空格')
        $('.end_id').focus()
        return
    }
    if(start_id>=end_id){
        fail('开始的leapid不能大于结束的leapid')
        return
    }


})