/*
* @Author: Marte
* @Date:   2019-07-17 15:34:28
* @Last Modified by:   Marte
* @Last Modified time: 2019-08-16 17:20:52
*/

'use strict';
jeDate("#testblue",{
    format: "hh:mm",
    multiPane:false,
    range:" - "
});

$(document).ready(function(){
    $('.select2').select2()
        $('.select2').select2()
        /**
         * address初始化
         */
        var pr_str = '';//省

        pr_str = '<option value="">请选择</option>';
        for(var v in area_info){
            console.log(area_info[v].children) //去除key值对应的value值
            pr_str += '<option value="'+area_info[v].area_id+'">'+area_info[v].area_name+'</option>';
        }
        $('#province').html(pr_str);

        $('#city').html('<option value="">请选择</option>');

        $('#area').html('<option value="">请选择</option>');

    });


    /**
     * 选择省会促发下一级联动
     */
    function change_p(obj){

        $('#area').html('<option value="">请选择</option>');

        var p_id = $(obj).val();

        glo.parent_id = p_id;
        if($("#province").find("option:selected").text()!=='请选择'){
             var area_city_info = area_info[p_id].children;//获取子集信息

            var pr_str = '';//省

            pr_str = '<option value="">请选择</option>';
            for(var v in area_city_info){
                pr_str += '<option value="'+area_city_info[v].area_id+'">'+area_city_info[v].area_name+'</option>';
            }

            $('#city').html(pr_str);

        }else{
            $('#city').html('<option value="">请选择</option>');
        }



    }

    /**
     * 选择区促发下级联动
     */
    function change_c(obj){

        var c_id = $(obj).val();
        if($("#province").find("option:selected").text()!=='请选择'){
            var area_city_info = area_info[glo.parent_id].children[c_id].children;//获取子集信息
            var pr_str = '';//省
            pr_str = '<option value="">请选择</option>';
            for(var v in area_city_info){
                pr_str += '<option value="'+area_city_info[v].area_id+'">'+area_city_info[v].area_name+'</option>';
            }
            $('#area').html(pr_str);
        }else{
            $('#city').html('<option value="">请选择</option>');
        }

    }

    /**
     * 将base64转换为文件对象
     * （即用文件上传输入框上传文件得到的对象）
     * @param {String} base64 base64字符串
     */
    function convertBase64UrlToBlob(base64){
        var base64Arr = base64.split(',');
        if(base64Arr.length > 1){
            //如果是图片base64，去掉头信息
            base64 = base64Arr[1];
        }
        // 将base64解码
        var bytes = atob(base64);
        console.log(bytes);
        var bytesCode = new ArrayBuffer(bytes.length);
        // 将base64转换为ascii码
        for (var i = 0; i < bytes.length; i++) {
            bytesCode[i] = bytes.charCodeAt(i);
        }
        // 转换为类型化数组
        var byteArray = new Uint8Array(bytesCode);
        // 生成Blob对象（文件对象）
        return new Blob( [byteArray] , {type : 'image/png'});
    }

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
            return
        }else{
            $('.img-ts').hide();
        }
        var n = new FileReader;
        n.onload = function(o) {
            var l = o.target.result;
            var s = new Image;
            $(e).prev().show();
            $(e).next('input').val(l);
            $(e).prev().find('img').attr('src', l);
            console.log(convertBase64UrlToBlob(l));
            // $(e).next().next().remove();
            // $(e).prev().show();
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
    var data='',
        reg_flag=false;
    function reg(){
        var account=$('#mobile').val(),
            code=$('#codenum').val(),
            pwd=$('.pwd').val(),
            name=$('.name').val(),
            classify=$(".class").find("option:selected").text(),//商户类别
            province=$("#province").find("option:selected").text(),
            city=$("#city").find("option:selected").text(),
            area=$("#area").find("option:selected").text(),
            detailaddress=$('.detail_address').val(),
            time=$('#testblue').val(),
            license=$('.license').attr('src'),
            certificate=$('.certificate').attr('src');

            if(account==''){
                fail('手机号码不能为空')
                return
            }
            if(!fun.isMobile(account)){
                fail('手机号码格式不正确')
                return
            }
            if(code==''){
                fail('验证码不能为空')
                return
            }
            if(code.indexOf(" ") != -1){
                fail('验证码格式不正确')
                return
            }
            if(code.length!==6){
                fail('验证码格式不正确')
                return
            }
            if(pwd==''){
                fail('密码不能为空')
                return
            }
            if(!fun.pwd(pwd) || pwd.indexOf(" ") != -1){
                fail('密码格式不正确')
                return
            }

            if(name==''){
                fail('名称不能为空')
                return
            }
            if(!fun.isMc(name) ){
                fail('密码格式不正确')
                return
            }

            if(classify=='请选择'){
                fail('请输入商户类别');
                return
            }
             if(province== '请选择'){
                fail('请选择省份');
                return
            }

            if(city == '请选择'){
                fail('请选择市');
                return
            }

            if(area == '请选择'){
                fail('请选择区');
                return
            }
            if(detailaddress==''){
                fail('请填写详细地址');
                return
            }
            if(!fun.isMc(detailaddress)){
                fail('详细地址不合法');
                return
            }
            if(time==''){
                fail('请选择营业时间')
                return
            }
            if(license==''){
                fail('请上传营业执照')
                return
            }
            if(certificate==''){
                fail('请上传经营许可证')
                return
            }
            data =JSON.stringify($('#sub-form').serializeObject());
            reg_flag=true;

    }