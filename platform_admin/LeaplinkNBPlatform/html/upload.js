/*
* @Author: Marte
* @Date:   2017-09-21 14:54:03
* @Last Modified by:   Marte
* @Last Modified time: 2019-08-07 10:08:54
*/

'use strict';
//支持多图片上传
//var img_js = 0;//图片计数器
/*图片处理*/
var filechooser = document.getElementById("choose");
//    用于压缩图片的canvas
var canvas = document.createElement("canvas");
var ctx = canvas.getContext('2d');
//    瓦片canvas
var tCanvas = document.createElement("canvas");
var tctx = tCanvas.getContext("2d");
var maxsize = 500 * 1024;

filechooser.onchange = function() {
    if (!this.files.length) return;
    var files = Array.prototype.slice.call(this.files);
   /* if (files.length > 9) {*/ //上传头像属于单图片 所以只能是1 后期会为配置参数
    if (files.length > 1) {
        alert("最多同时只可上传1张图片");
        return;
    }
    files.forEach(function(file, i) {
        if (!/\/(?:jpeg|png|gif)/i.test(file.type)) return;
        var reader = new FileReader();
        var li = document.createElement("li");
//          获取图片大小
        var size = file.size / 1024 > 1024 ? (~~(10 * file.size / 1024 / 1024)) / 10 + "MB" : ~~(file.size / 1024) + "KB";
        li.innerHTML = '<div class="progress"><span></span></div><div class="size">' + size + '</div>';

        $(".img-list").html("");//先置为空
        $(".img-list").append($(li));
        reader.onload = function() {
            var result = this.result;
            var img = new Image();
            img.src = result;
            $(li).css("background-image", "url(" + result + ")");
            //如果图片大小小于100kb，则直接上传
            if (result.length <= maxsize) {
                img = null;
                upload(result, file.type, $(li));
                return;
            }
//      图片加载完毕之后进行压缩，然后上传
            if (img.complete) {
                callback();
            } else {
                img.onload = callback;
            }
            function callback() {
                var data = compress(img);
                upload(data, file.type, $(li));
                img = null;
            }
        };
        reader.readAsDataURL(file);
    })
};


// 使用canvas对大图片进行压缩
function compress(img) {
    var initSize = img.src.length;
    var width = img.width;
    var height = img.height;
    //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
    var ratio;
    if ((ratio = width * height / 4000000) > 1) {
        ratio = Math.sqrt(ratio);
        width /= ratio;
        height /= ratio;
    } else {
        ratio = 1;
    }
    canvas.width = width;
    canvas.height = height;
//        铺底色
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    //如果图片像素大于100万则使用瓦片绘制
    var count;
    if ((count = width * height / 1000000) > 1) {
        count = ~~(Math.sqrt(count) + 1); //计算要分成多少块瓦片
//            计算每块瓦片的宽和高
        var nw = ~~(width / count);
        var nh = ~~(height / count);
        tCanvas.width = nw;
        tCanvas.height = nh;
        for (var i = 0; i < count; i++) {
            for (var j = 0; j < count; j++) {
                tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
                ctx.drawImage(tCanvas, i * nw, j * nh, nw, nh);
            }
        }
    } else {
        ctx.drawImage(img, 0, 0, width, height);
    }

    //进行最小压缩
    var ndata = canvas.toDataURL('image/jpeg', 0.1);
    console.log('压缩前：' + initSize);
    console.log('压缩后：' + ndata.length);
    console.log('压缩率：' + ~~(100 * (initSize - ndata.length) / initSize) + "%");
    tCanvas.width = tCanvas.height = canvas.width = canvas.height = 0;
    return ndata;
}

/**
 * 获取blob对象的兼容性写法
 * @param buffer
 * @param format
 * @returns {*}
 */
function getBlob(buffer, format) {
    try {
        return new Blob(buffer, {type: format});
    } catch (e) {
        var bb = new (window.BlobBuilder || window.WebKitBlobBuilder || window.MSBlobBuilder);
        buffer.forEach(function(buf) {
            bb.append(buf);
        });
        return bb.getBlob(format);
    }
}
/**
 * 获取formdata
 */
function getFormData() {
    var isNeedShim = ~navigator.userAgent.indexOf('Android')
        && ~navigator.vendor.indexOf('Google')
        && !~navigator.userAgent.indexOf('Chrome')
        && navigator.userAgent.match(/AppleWebKit\/(\d+)/).pop() <= 534;
    return isNeedShim ? new FormDataShim() : new FormData()
}
/**
 * formdata 补丁, 给不支持formdata上传blob的android机打补丁
 * @constructor
 */
function FormDataShim() {
    console.warn('using formdata shim');
    var o = this,
        parts = [],
        boundary = Array(21).join('-') + (+new Date() * (1e16 * Math.random())).toString(36),
        oldSend = XMLHttpRequest.prototype.send;
    this.append = function(name, value, filename) {
        parts.push('--' + boundary + '\r\nContent-Disposition: form-data; name="' + name + '"');
        if (value instanceof Blob) {
            parts.push('; filename="' + (filename || 'blob') + '"\r\nContent-Type: ' + value.type + '\r\n\r\n');
            parts.push(value);
        }
        else {
            parts.push('\r\n\r\n' + value);
        }
        parts.push('\r\n');
    };



    // Override XHR send()
    XMLHttpRequest.prototype.send = function(val) {
        var fr,
            data,
            oXHR = this;
        if (val === o) {
            // Append the final boundary string
            parts.push('--' + boundary + '--\r\n');
            // Create the blob
            data = getBlob(parts);
            // Set up and read the blob into an array to be sent
            fr = new FileReader();
            fr.onload = function() {
                oldSend.call(oXHR, fr.result);
            };
            fr.onerror = function(err) {
                throw err;
            };
            fr.readAsArrayBuffer(data);
            // Set the multipart content type and boudary
            this.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + boundary);
            XMLHttpRequest.prototype.send = oldSend;
        }
        else {
            oldSend.call(this, val);
        }
    };
}