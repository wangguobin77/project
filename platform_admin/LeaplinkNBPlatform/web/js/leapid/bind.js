/*
* @Author: Marte
* @Date:   2019-11-26 15:23:55
* @Last Modified by:   Marte
* @Last Modified time: 2019-11-28 10:27:02
*/

'use strict';
function hideconfirm(){
    $('.delete').hide()
}
function getRow(obj){
    var i = 0;
    while(obj.tagName.toLowerCase() != "tr"){
    obj = obj.parentNode;
    if(obj.tagName.toLowerCase() == "table")return null;
    }
    return obj;
}
function clear(){
   $(".select2").val(['0']).trigger('change')
   $('.start_id').val('')
   $('.end_id').val('')

}