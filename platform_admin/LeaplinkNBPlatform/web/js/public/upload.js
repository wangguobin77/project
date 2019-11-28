
/* @Author: Marte
* @Date:   2019-07-23 09:47:37
* @Last Modified by:   Marte
* @Last Modified time: 2019-09-10 10:47:57
*/

'use strict';

    function uploadImage(obj){
        var fileData = obj.files[0];
        var reader = new FileReader();

        reader.onload = function (e) {

            var data = e.target.result;

            //加载图片获取图片真实宽度和高度
            var image = new Image();

            image.onload=function(){
                // if(image.width != w || image.height != h){
                //    return false;
                // }
                var xhr;
                var fd;
                xhr = new XMLHttpRequest();
                fd = new FormData();

                fd.append("file", fileData);
                //设置二进制文边界件头
                xhr.open("POST", "./upload/images", true);
                xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
                xhr.send(fd);
                xhr.onreadystatechange = function(){
                   if(xhr.readyState == 4)
                   {
                       if(xhr.status == 200)
                       {
                           console.log(xhr.responseText);
                           var result = JSON.parse(xhr.responseText);
                           if(result.code == 0)
                           {
                           //上传成功时
                               $(obj).prev("i").show();
                               $(obj).prev("i").html("<span class='close_icon'></span>"+"<img src='"+data+"'>");
                               // $(obj).next().val(result.data);
                           }else{

                               alert(result.data);
                           }
                       }else{
                           // alert('failed!')

                       }
                   }
                }
            };
            image.src= data;
        };
        //读取文件的base64数据
        reader.readAsDataURL(fileData);

    }


    /**
     * 上传文件 分片上传合并
     */

    const BYTES_PER_CHUNK = 512*1024; // 每个文件切片大小定为.5MB .
    var slices,totalSlices,start=0,end=BYTES_PER_CHUNK,index = 0,stop = 0;

    // $(obj).click(function(){
    //     var file=$("#file");
    //     if($.trim(file.val())==''){
    //         alert("请选择文件");
    //         return false;
    //     }
    //     sendRequest()
    // });

    //发送请求
    function sendRequest() {
        var blob = document.getElementById('file').files[0];//获取文件信息
        // 计算文件切片总数
        slices = Math.ceil(blob.size / BYTES_PER_CHUNK);
        totalSlices= slices;
        if(stop==1){
            alert("停止上传");
            return false
        }
        if(start < blob.size) {
            if(end > blob.size) end = blob.size;
            uploadFile(blob, index, start, end);
            start = end;
            end = start + BYTES_PER_CHUNK;
            index++;
        }
    };


    //上传文件
    function uploadFile(blob, index, start, end) {
        var xhr,fd,chunk;
        xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if(xhr.readyState == 4) {
                if(xhr.responseText) alert(xhr.responseText);

                if(slices>1) slices--;

                var percent=100*index/slices+'%';//上传百分比计算
                if(percent>100){
                    percent=100;
                }else if(percent==0&&slices==1){
                    percent=100;
                }
                // 如果所有文件切片都成功发送，发送文件合并请求。
                if(percent == 100) {
                    mergeFile(blob);
                    start=0;
                    //alert('文件上传完毕');
                }else{
                    if(stop!=1) sendRequest();
                }
            }
        };

        chunk =blob.slice(start,end);//切割文件
        //构造form数据
        fd = new FormData();
        fd.append("file", chunk);
        fd.append("name", blob.name);
        fd.append("index", index);
        xhr.open("POST", "/upload/fileupload", true);//true 是否异步上传
        //设置二进制文边界件头
        xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
        xhr.send(fd);
    };

    function mergeFile(blob) {
        var xhr,fd;
        xhr = new XMLHttpRequest();
        fd = new FormData();
        fd.append("name", blob.name);
        fd.append("index", totalSlices);
        xhr.open("POST", "/upload/meradd", true);
        xhr.setRequestHeader("X_Requested_With", location.href.split("/")[3].replace(/[^a-z]+/g, '$'));
        xhr.send(fd);

        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4)
            {
                if(xhr.status == 200)
                {
                    var result = JSON.parse(xhr.responseText);
                    if(result.code == 0)
                    {
                        alert(result.message);
                        // result.data
                    }else{
                        alert(result.data);
                    }
                }else{
                    alert('failed!');
                }
            }
        }
    };

    /**
     * 上传文件end
     **/




//上传图片
function imgUpload_sm(e) {
    var t = e.getAttribute("data-width"),
        a = e.getAttribute("data-height"),
        r = [],
        o = $(".imgs");
    r.push(o);
    var result = e.files[0];
    console.log(result);
    var s = result.size;
    if (s > 1024*1024*2){
        $('.img-des-zc').hide();
        $('.img-ts').show();
        return false;
    }else{
        $('.img-ts').hide();
    }
    var n = new FileReader;
    n.onload = function(o) {


        var l = o.target.result;
        let blob = new Blob([result]);//存储二进制数据
        console.log(blob)
        let url = URL.createObjectURL(blob);//生成本地图片地址用于图片预览
        console.log(url)  //转化成blob类型
        var s = new Image;
        $(e).prev().show();
        $(e).next('input').val(l);
        $(e).prev().find('img').attr('src', url);
        //                 $(e).next().next().remove();
        //                 $(e).prev().show();
        // s.onload = function() {
        //     $.ajax({
        //         url:'<?=Url::toRoute('logo')?>',
        //         type:'post',
        //         dataType:'json',
        //         data:{'file':l,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
        //         success: function(data) {
        //             console.log(data);
        //             if( data.code == 0 ) {
        //                 $(e).next().val(data.message);
        //                 $(e).prev().find('img').attr('src', data.message);
        //                 $(e).next().next().remove();
        //                 $(e).prev().show();
        //             } else {
        //                 fail(data.message);
        //             }
        //         }
        //     });

        // };
        s.src = l
    };
    n.readAsDataURL(result)
}