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
function tips_warning(title,des){
    $('h6').html(title);
    $('.warrning-des').html(des);
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
 * 提示信息
 */
function tips(val){
    $('.ts-des').html(val);
    $('.ts-des').show();
}


function send_code(){
    var T=60;
    $(this).attr('disabled', 'true');
    var t=setInterval(function(){
        T--;
        $(this).innerHTML=T;
        if(T===0){
            $('#code').removeAttr('disabled', 'true');
            clearInterval(t);
            $('#code').html('重新发送');
        }else {
            $('#code').html('重新发送(' + T + ')s');
        }
    },1000)
}

// 点击close-icon
$('.close_box').delegate('.close-icon','click',function(e){
    e.preventDefault();
    $(this).parent().hide();
    $(this).next().attr('src','');

    $(this).parent().next('input[type="file"]').val('');
    $(this).parent().next('input[type="hidden"]').val('');
    $('input[name="plate"]').val('');
})

/**
 * 时序步骤流程显示
 * @return {[type]} [description]
 */
function sec_stepactive(){
    $('.step-sec').find('.step-num').addClass('step-num-active')
    $('.step-des').find('span:nth-child(1)').addClass('step-des-active')
    $('.step-des').find('span:nth-child(2)').addClass('xt-zctive')

}
function third_stepactive(){
    $('.step-third').find('.step-num').addClass('step-num-active')
    $('.step-third').find('span.des').find('span').addClass('step-des-active')

}

/**
 * 提交时序成功显示
 */
function checksuccess(title,des){
    $('.checkbox').show()
    $('.checkbox').find('.sec-title').html(title)
    $('.checkbox').find('.sec-des').html(des)
}




/**
 * 获取相同的元素下标
 * @return {[type]} [description]
 **/
function searchKeys(arr){
    // var arr = ['11', '11', '111', '4', '5', '6', '6', '7','7', '8', '1', '1', '1'];
    var str = "";
    var strary = [];
    for (var i = 0; i < arr.length; i++) {
        var hasRead = false;
        for ( var k = 0; k < strary.length; k++) {
            if (strary[k] == arr[i]){
                hasRead = true;
            }
        }
        if(!hasRead){
            var _index = i, haveSame = false;
            for (var j = i + 1; j < arr.length; j++) {
                if(j == parseInt(i) + parseInt(1)){
                    _index++;
                }
                if (arr[i] ==arr[j]) {
                    _index += "," + (parseInt(j)+1);
                    haveSame = true;
                }
            }
            if (haveSame) {
                strary.push(arr[i]);
                str += "数组第"+_index+"个相同，相同值为"+arr[i]+ "!!!\n";
            }
        }
    }
    alert(str);
}

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

/**
 * 获取json数组中 value值相同的
 */

// let arr= [];
// let rep = [];
// arr.forEach((item,index)=>{
//     if(arr.indexOf(item)!=index){ // 匹配数组元素第一个item位置和当前循环的index
//         let obj = {};
//         obj.key = (arr.indexOf(item) + 1) + '和' + (index + 1); // 用'|'分隔两个重复项的下标
//         obj.value = item;
//         rep.push(obj);
//     }
// });
/**
 * params
 * @param  {[type]} arr [description]
 * @return {[type]}     [description]
 */
// const searchrepeat=(arr)=>{
//     let rep = [];
//     arr.forEach((item,index)=>{
//         if(arr.indexOf(item)!=index){ // 匹配数组元素第一个item位置和当前循环的index
//             let obj = {};
//             obj.key = (arr.indexOf(item) + 1) + '和' + (index + 1); // 用'|'分隔两个重复项的下标
//             obj.value = item;
//             rep.push(obj);
//         }
//     });
// }

/**
 * 数组对象属性去重
 */

