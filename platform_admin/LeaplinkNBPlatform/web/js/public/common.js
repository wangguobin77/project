
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
 * 获取随机值
 */
function getRandomIndex (min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min)
}
/**
 * 打乱一个数组
 */
function shuffle (arr) {
  const _arr = arr.slice()
  for (let i = 0; i < _arr.length; i++) {
    const j = getRandomIndex(0, i)
    const t = _arr[i]
    _arr[i] = _arr[j]
    _arr[j] = t
  }
  return _arr
}
