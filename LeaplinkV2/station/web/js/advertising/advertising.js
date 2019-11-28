/*
* @Author: Marte
* @Date:   2019-07-18 11:45:56
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-25 09:33:19
*/

'use strict';
function opencoupon(){
    $('.coupon-dialog').show();
}
function cancelcoupon(){
    $('.coupon-dialog').hide();
}

$(document).ready(function(){
    $('.select2').select2()
});
const reset_search=()=>{
    // console.log($(".yyzz_selsct option:checked").text())
    $('.name').val('')
    $('#testblue').val('')
    $(".yyzz_selsct").select2('val','0')
}
$(".jeinput").each(function(){
    var mat = $(this).attr("timeattr");
    jeDate(this,{
        format: mat,
         range:"至",
    multiPane:false,
    donefun:function(obj) {
        console.log(obj)
    },
    theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
    });
});