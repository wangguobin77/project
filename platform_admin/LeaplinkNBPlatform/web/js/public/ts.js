// // 刷新
// function refresh(){
//      window.location.reload();
// }
// // 成功提示
// function succ(val){
//     $('.succ').find('p').html(val);
//     $('.succ').show();
//     $('.succ').delay(1600).hide(0);

// }

// // fail提示
// function fail(val){
//     $('.fail-ts').find('p').html(val);
//     $('.fail-ts').show();
//     $('.fail-ts').delay(1600).hide(0);

// }

// 刷新
function refresh(){
     window.location.reload();
}
// 成功提示
function succ(val, url){
    $('.succ').find('p').html(val);
    $('.succ').show();
    $('.succ').delay(2000).hide(0);
    if(Object.prototype.toString.call(url) =='[object String]'&& url ){
        setTimeout(function(){
            location.href=url
        },2000);
    }
    else if(Object.prototype.toString.call(url) =='[object Function]'&& url ){
        setTimeout(function(){
            url()
        },2000);
    }
}

// fail提示
function fail(val, url){
    $('.fail-ts').find('p').html(val);
    $('.fail').show();
    $('.fail-ts').show();
    $('.fail').delay(2000).hide(0);
    if( url ){
        setTimeout(function(){
            location.href=url
        },2000);
    }
}

// 提示框函数
function tips_warning(val){
    $('h6').html(val);
    $('.delete').show();
}

 // 弹框取消按键
$('.delete').find('.cancel').click(function(){
    $('.delete').hide();
})
// 弹框关闭 按键
$('.delete').find('.icon-close').click(function(){
    $('.delete').hide();
})

function back(){
    window.history.go(-1);
}
//中文字体长度计算
function getStrLeng(str){
    var realLength = 0;
    var len = str.length;
    var charCode = -1;
    for(var i = 0; i < len; i++){
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) {
            realLength += 1;
        }else{
            // 如果是中文则长度加3
            realLength += 3;
        }
    }
    return realLength;
}

/*
1600ms后返回至url
 */
function back_url(url){
    setTimeout(function(){
        window.location.href=url;
    },1600)
}

$('.back-btn').click(function(){
     window.history.go(-1);
})


/**
 * 阻止事件冒泡函数
 */
function stopPropagation(e) {
     e = e || window.event;
     if(e.stopPropagation) { //W3C阻止冒泡方法
         e.stopPropagation();
     } else {
         e.cancelBubble = true; //IE阻止冒泡方法
     }
 }