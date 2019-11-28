layui.use('upload', function(){
    var $ = layui.jquery;
    var upload = layui.upload;
    //执行实例
    upload.render({
        elem: '.imgDom' //绑定元素
        ,url: UPLOAD_URL
        ,done: function(res){
            //上传完毕回调
            var l = STATIC_REMOTE_DOMAIN+res.retData.url
            //获取当前触发上传的元素，一般用于 elem 绑定 class 的情况，注意：此乃 layui 2.1.0 新增
            var item = this.item;
            var t = $(item)
            item.prev().show();
            item.nextAll('.imgV').val(l);
            item.prev().find('img').attr('src', l);
        }
        ,error: function(){
            //请求异常回调
        }
    });

});



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
        $(e).next('input').val(url);
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