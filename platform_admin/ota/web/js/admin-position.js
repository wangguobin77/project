'use strict';
// 出现添加position区域
  $('#add_form').toggle()
  $('.add-save').toggle()
  $('.add-cancel').toggle()
   
$('.add-btn').click(function(event) {
       $(this).toggle()
       $('#add_form').toggle()
       $('.add-save').toggle()
       $('.add-cancel').toggle()
    // $('.add_position').css({
    //     opacity: '1',

    // });

});
$('.add-save').click(function(){
     $(this).toggle()
     $('#add_form').toggle()
     $('.add-cancel').toggle()
     $('.add-btn').toggle()
     

})
$('.add-cancel').click(function(){
     $(this).toggle()
     $('#add_form').toggle()
     $('.add-save').toggle()
     $('.add-btn').toggle()

})

// // 添加职位
// $('.bc-pos').click(function(){
//     var value = $(this).parent().find('div').html();
//     if(value == ''){
//         $('.add_position').find('.error-ts').show();
//         $('.div-textarea-add').css('border','1px solid red');
//         return false;
//     }
// })

// //编辑职位
// $('.edit-box').click(function(){
//     $(this).parent().siblings('td').find('div.div-textarea').attr('contenteditable','true');
//     $(this).parent().siblings('td').find('div.div-textarea').addClass('div-textarea-active');
//     $(this).parent().parent().addClass('edit-active');
//     $(this).parent().parent().find('.xian-hide').css('display','none')
//     // 相邻元素不可以操作 禁用相邻元素的可编辑属性
//     $(this).parent().parent().siblings().find('div.div-textarea-edit').removeClass('div-textarea-active');
//     $(this).parent().parent().siblings().removeClass('edit-active');
//     $(this).parent().parent().siblings().find('div.div-textarea-edit').attr('contenteditable','false');
//     // 编辑删除按钮消失  出现保存取消
//     $(this).css('display',"none");
//     $(this).next("div").css('display','none');
//     $(this).next("div").next('span').css('display','none')
//     $(this).parent().find('span.btn-opr').css('display','block');
//     $(this).parent().parent().siblings().find('span.btn-opr').css('display','none');
 
//     $(this).parent().parent().find('.xian-hide').css('display','inline-flex')
//     $(this).parent().parent().siblings().find('span.edit-pancel').next('div').css('display','block');
//     $(this).parent().parent().siblings().find('.xian-hide').css('display','none');
   
//      $(this).parent().parent().siblings().find('span.edit-pancel').css('display','block');
// })
