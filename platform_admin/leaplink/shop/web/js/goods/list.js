/*
* @Author: Marte
* @Date:   2019-07-18 16:47:27
* @Last Modified by:   Marte
* @Last Modified time: 2019-07-18 16:49:49
*/

'use strict';
function del(id){
    $('.del-box').show().find('.confirm').data('id', id);
}

$('.confirm').unbind().click(function () {
    var id = $(this).data('id');
    $.ajax({
        url:DEL_URL,
        type:'post',
        dataType:'json',
        data:{id:id, _csrf:_csrf},
        success: function(data) {
            if(data.code==0){
                succ('删除成功')
                $('.del-box').hide()
                $('#tr_' + id).find('.check_status').text('已删除').end().find('.del_remove').remove()
            }else if(data.code == 101008){
                fail('已生效状态,不允许删除')
            }else{
                fail('删除失败')
            }

        }
    });
});

function check_pass(id) {
    $.ajax({
        url: CHECK_URL,
        type: 'post',
        dataType: 'json',
        data: {id: id, _csrf: _csrf},
        success: function (data) {
            if (data.code == 0) {
                succ('提交成功')
                $('#tr_' + id).find('.check_status').text('审核中').end().find('.check_pass_remove').remove()
            } else if (data.code == 101008) {
                fail('不是可以提交的状态')
            } else {
                fail('删除失败')
            }

        }
    });
}