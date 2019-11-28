
//// UTF8字符集实际长度计算
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
// 注册登录框  密码 手机 邮箱的验证
var fun = {
    isMobile: function(value) {
        // var reg = /^1[3|4|5|7|8]\d{9}$/;
        var reg=/^(\+\d{2,3}\-)?\d{11}$/;

        if (!reg.test(value)) {
            $(this).next().show();
            return false;
        }return true;

    },
    yzm: function(value) {
        if (value == "") {
            $(this).next().show();
            return false;
        }return true;

    },
    pwd: function(value) {
        var rege = /^.{6,20}$/;
        if (!rege.test(value)) {
            $(this).next().show();
            return false;
        }return true;
        if (value === '') {
            $(this).next().show();
            return false;
        }return true;

    },
    repeatpwd: function(firstvaluea, lastvaluea) {
        if (firstvaluea !== lastvaluea) {
            $(this).next().show();
            return false;
        }return true;

    },
    isMail: function(value) {
        // var reg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/g;
        // var reg=/^[a-zA-Z0-9_-]+([\.a-zA-Z0-9_-]{6,128})+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]{2,6})$/;
        var reg=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,8})$/;
        if (!reg.test(value)) {
            $(this).next().show();
            return false;
        }return true;
        if (value == '') {
            $(this).next().show();
            return false;
        }return true;
    },

    // 名称验证1 -128 位
    isMc:function(value){
        var reg = /^.{1,128}$/;
        if(!reg.test($.trim(value))){
            $(this).next().show();
            return false;
        }return true;
    },
    // 备注 长度不大于2048
    isDes:function(value){
        var reg = /^.{0,2048}$/;
        if(!reg.test(value)){
            $(this).next().show();
            return false;
        }return true;
    },
    // key code 校验
    isCode:function(value){
        // 以 0x或0X 开始 后面加两位有数字字母组成不区分大小写
        var reg=/^([0]{1})+([xX]{1})+([0-9a-zA-Z]{2})$/;
        if(!reg.test(value)){
            $(this).next().show();
            return false;
        }return true;
    },
    // 电话号码的校验 区号 3-4位- 后面7-8位数字  如：029-8888888
    isPhone:function(value){
        reg= /^(\d{3,4}\-)?[1-9]\d{6,7}$/;
        if(!reg.test(value)){
            $(this).next().show();
            return false;
        }return true;
    },
    //登录名的 4-128
    isLoginName:function(value){
        var reg = /^.{4,128}$/;
        if(!reg.test(value)){
            $(this).next().show();
            return false;
        }
        return true;
    },
    // 输入正整数
    isNum:function(value){
        var reg=/^[1-9]\d*$/;
        if(!reg.test(value)){
             $(this).next().show();
            return false;
        }
        return true;
    },
    //验证身份证
    isIdentity_card:function(value){
        var  reg =/^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/;
        if(!reg.test(value)){
            return false;
        }
        return true;
    }
}

//为文本框写的简易校验器
//var nameChecker = $("#txtName").TChecker({
//    required: { rule: true, error: "请输入您的名字" },
//    format: { rule: /^[a-z]+$/, error: "您的名字格式不正确" }
//});

$.fn.TChecker = function (opts) {
    var $this = this;
    var $validator = $this.nextAll(".tishi");
    var status = 0;

    if (opts.ele) $validator = $(opts.ele);

    var doError = function (error, msg) {
        if (error) {
            $validator.html(msg);
            $validator.show();
            // $this.focus();
            return true;
        }
        else {
            $validator.html("");
            $validator.hide();
            return false;
        }return true;
    }

    $this.blur(function () {
        status = 1;
        var value = $this.val();

        if (opts.required) {
            if (doError(opts.required.rule && value == "", opts.required.error)) return false;
        }
        if (opts.format) {
            if (doError(!opts.format.rule.test(value), opts.format.error)) return false;
        }
        if (typeof opts.custom == "function") {
            var result = opts.custom(value);
            if (doError(!result.rule, result.error)) return false;
        }
    });

    return {
        correct: $validator.html() === "",
        check: function (once) {
            once = (once != false);
            if (status === 0 || !once) {
                $this.blur();
            }
            var correct = $validator.html() === "";
            if (!correct) $this.focus();
            return correct;
        }
    };
};

function imgUpload(obj){
    // debugger;
    console.log(obj);
    var fileData = obj.files[0];
    var size = fileData.size;
    var type=fileData.type;

    var reader = new FileReader();

    reader.onload = function (e) {

        var data = e.target.result;


        //加载图片获取图片真实宽度和高度
        var image = new Image();

        image.onload=function(){
            var width = image.width;
            var height = image.height;
            // if(width!=1080 && height!=400){
            if(width<1080 && height<400){
                alert('规格错误，请上传1080*400的图片');
                $('input[name="picurl"]').val(null);
                return false;
            }

            $(obj).prev("i").html("<img style='display:block;width:108px;height:108px;' src='"+data+"'>");

        };
        image.src= data;
    };
    //读取文件的base64数据
   reader.readAsDataURL(fileData);
 }


function fileChange(target) {
     var fileSize = 0;
     if (isIE && !target.files) {
       var filePath = target.value;
       var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
       var file = fileSystem.GetFile (filePath);
       fileSize = file.Size;
     } else {
      fileSize = target.files[0].size;
      }
      var size = fileSize / 1024;
      if(size>2000){
       alert("附件不能大于2M");
       target.value="";
       return
      }
      var name=target.value;
      var fileName = name.substring(name.lastIndexOf(".")+1).toLowerCase();
      if(fileName !="xls" && fileName !="xlsx"){
          alert("请选择execl格式文件上传！");
          target.value="";
          return
      }
    }

function filefujianChange(target) {
       var fileSize = 0;
       if (isIE && !target.files) {
         var filePath = target.value;
         var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
         var file = fileSystem.GetFile (filePath);
         fileSize = file.Size;
       } else {
        fileSize = target.files[0].size;
        }
        var size = fileSize / 1024;
        if(size>2000){
         alert("附件不能大于2M");
         target.value="";
         return
        }
        var name=target.value;
        var fileName = name.substring(name.lastIndexOf(".")+1).toLowerCase();
        if(fileName !="jpg" && fileName !="jpeg" && fileName !="pdf" && fileName !="png" && fileName !="dwg" && fileName !="gif" ){
          alert("请选择图片格式文件上传(jpg,png,gif,dwg,pdf,gif等)！");
            target.value="";
            return
        }
      }
function refresh(){
    window.location.href="";
}

// 列表行互换顺序
function tr_order(){
    if($(this).parent().parent().next())
    $(this).parent().parent().next().after($(this).parent().parent());

}
/*
获取路由参数信息
 */
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    console.log(window.location.href)
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

/**
 * COOKIE set  get   del 设置 获取 删除cookie
 */

function setCookie(name,value)
{
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");

    if(arr=document.cookie.match(reg))

        return unescape(arr[2]);
    else
        return null;
}


//删除cookies
function delCookie(name)
{
    var exp = new Date();
    exp.setTime(exp.getTime() - 1);
    var cval=getCookie(name);
    if(cval!=null)
        document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

/*promise 封装   请求XMLKhttprequesT
 * [getJSON description]
 * @param  {[type]} url [description]
 * @return {[type]}     [description]
 */
const getJSON = function(url,type) {
    const promise = new Promise(function(resolve, reject){
        const handler = function() {
          if (this.readyState !== 4) {
            return;
          }
          if (this.status === 0) {
            resolve(this.response);
          } else {
            reject(new Error(this.statusText));
          }
        };
        const client = new XMLHttpRequest();
        client.open(type, url);
        client.onreadystatechange = handler;
        client.responseType = "json";
        client.setRequestHeader("Accept", "application/json");
        client.send();

    });

    return promise;
};


 /**
     * 自动将form表单封装成json对象 {a:'b'} 类型未obj
     */
    $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [ o[this.name] ];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    var msg=(msg)=>{
        $('.tsinfo').html(msg);
        $('.tsinfo').show();
    }

//    图片上传，将base64的图片转成二进制对象，塞进formdata上传
function upload(basestr, type, $li) {
    var text = window.atob(basestr.split(",")[1]);
    var buffer = new Uint8Array(text.length);
    var pecent = 0,
        loop = null;
    for (var i = 0; i < text.length; i++) {
        buffer[i] = text.charCodeAt(i);
    }
    var blob = getBlob([buffer], type);
    var xhr = new XMLHttpRequest();
    var formdata = getFormData();
    formdata.append('imagefile', blob);
    // 上传接口
    xhr.open('post', '/upload/upload');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var jsonData = JSON.parse(xhr.responseText);


            var text = jsonData.data.path ? "<?= Yii::t('app','UPLOAD_SUCCESS')?>" : "<?= Yii::t('app','UPLOAD_DEFEATED')?>";
            console.log(text + '：' + jsonData.data.name);
            clearInterval(loop);
            //当收到该消息时上传完毕
            $li.find(".progress span").animate({
                'width': "100%"
            }, pecent < 95 ? 200 : 0, function() {
                $(this).html(text);
            });
            if (!jsonData.data.path) return;
            $("#logo").attr('value',jsonData.data.name);
        }
    };
     xhr.send(formdata);
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
 * 获取json数组中 value值相同的
 */

let arr= [];
let rep = [];
arr.forEach((item,index)=>{
    if(arr.indexOf(item)!=index){ // 匹配数组元素第一个item位置和当前循环的index
        let obj = {};
        obj.key = (arr.indexOf(item) + 1) + '和' + (index + 1); // 用'|'分隔两个重复项的下标
        obj.value = item;
        rep.push(obj);
    }
});
/**
 * params
 * @param  {[type]} arr [description]
 * @return {[type]}     [description]
 */
const searchrepeat=(arr)=>{
    let rep = [];
    arr.forEach((item,index)=>{
        if(arr.indexOf(item)!=index){ // 匹配数组元素第一个item位置和当前循环的index
            let obj = {};
            obj.key = (arr.indexOf(item) + 1) + '和' + (index + 1); // 用'|'分隔两个重复项的下标
            obj.value = item;
            rep.push(obj);
        }
    });
}


/**
 * 操作localstorage
 */
function handleLocalStorage(method, key, value) {
  switch (method) {
    case 'get' : {
      let temp = window.localStorage.getItem(key);
      if (temp) {
        return temp
      } else {
        return false
      }
    }
    case 'set' : {
      window.localStorage.setItem(key, value);
      break
    }
    case 'remove': {
      window.localStorage.removeItem(key);
      break
    }
    default : {
      return false
    }
  }
}
// 调用方法设置localstorage  handleLocalStorage('set', 'userName', 'Tom');


//去除数组中重复对象
function unique(arr){
    var unique = {};
    arr.forEach(function(a){ unique[ JSON.stringify(a) ] = 1 });
    arr= Object.keys(unique).map(function(u){return JSON.parse(u) });
    return arr
    console.log(arr)
}

let newArr=[];//去除空对象
for(let j in arr){
    for(let prop in arr[j]){
          if(prop!=''||arr[j][prop]!=''){
              newArr.push(arr[j]);
        }
    }
};

/**
 * es6中对某个属性值去重
 */
 // var arr2 = [
 //        {
 //            from:'张三',
 //            to: '河南'
 //        },
 //        {
 //            from:'王二',
 //            to: '阿里'
 //        },
 //        {
 //            from:'王二',
 //            to: '杭州'
 //        },
 //        {
 //            from:'王二',
 //            to: '山东'
 //        },
 //    ]
    function unique(arr1) {
        const res = new Map();
        return arr1.filter((a) => !res.has(a.from) && res.set(a.from, 1))
    }

/*
promise 请求封装
 */
    function ajax(url,type,param,async,header) {
        return new Promise(function(resolve, reject) {
            var req = new XMLHttpRequest();
            req.onload = function() {
                if(req.status == 200 || req.status == 304) {
                    resolve(JSON.parse(req.response));
                } else {
                    reject(Error(req.statusText));
                }
            };
            req.onerror = function() {
                reject(Error("Network Error"));
            };
            type == null || type.toUpperCase() == 'GET'?type='get':type='post';
            param = formatParams(param);
            param == null || param == ''?url:url=url+'?'+param;
            async == null || async == true?async=true:async=false;
            //设置表单提交时的内容类型，未完
            //xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.open(type,url,async);
            req.send();
        });
        function formatParams(data) {
            var _fpArr = [];
            for (var _fpName in data) {
          _fpArr.push(_fpName + "=" + data[_fpName]);
            }
            return _fpArr.join("&");
        };
    };
   //  ajax('http://192.168.90.30:3030/mock/98/api/code/codes','post',{},true).then(function(response) {
   //      console.log('请求成功~');
   //      console.log(JSON.stringify(response));
   //  }, function(error) {
   //      console.error("Failed!", error);
   //  });

   //  /**
   //   * es6
   //   */
   //   // promise  构造函数 通过new  一个实例
   // new Promise((resolve, reject) => {
   //    resolve(1);
   //    console.log(2);
   //  }).then(r => {
   //    console.log(r);
   //  });


/* 封装ajax函数
 * @param {string}opt.type http连接的方式，包括POST和GET两种方式
 * @param {string}opt.url 发送请求的url
 * @param {boolean}opt.async 是否为异步请求，true为异步的，false为同步的
 * @param {object}opt.data 发送的参数，格式为对象类型
 * @param {function}opt.success ajax发送并接收成功调用的回调函数
 */
    function ajax(opt) {
        opt = opt || {};
        opt.method = opt.method.toUpperCase() || 'POST';
        opt.url = opt.url || '';
        opt.async = opt.async || true;
        opt.data = opt.data || null;
        opt.success = opt.success || function () {};
        var xmlHttp = null;
        if (XMLHttpRequest) {
            xmlHttp = new XMLHttpRequest();
        }
        else {
            xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
        }var params = [];
        for (var key in opt.data){
            params.push(key + '=' + opt.data[key]);
        }
        var postData = params.join('&');
        if (opt.method.toUpperCase() === 'POST') {
            xmlHttp.open(opt.method, opt.url, opt.async);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=utf-8');
            xmlHttp.send(postData);
        }
        else if (opt.method.toUpperCase() === 'GET') {
            xmlHttp.open(opt.method, opt.url + '?' + postData, opt.async);
            xmlHttp.send(null);
        }
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                opt.success(xmlHttp.responseText);
            }
        };
    }
//使用例子
    // ajax({
    //     method: 'POST',
    //     url: 'test.php',
    //     data: {
    //         name1: 'value1',
    //         name2: 'value2'
    //     },
    //     success: function (response) {
    //        console.log(response);
    //     }
    // });