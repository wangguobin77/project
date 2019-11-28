/*
* @Author: Marte
* @Date:   2019-07-19 16:28:07
* @Last Modified by:   Marte
* @Last Modified time: 2019-07-29 18:03:40
*/

'use strict';
var date=$('.validity').val();
jeDate("#testblue",{
    theme:{bgcolor:"#367fa9",pnColor:"#367fa9"},
    format: "hh:mm",
    multiPane:false,
    range:" 至 ",
    donefun:function(obj) {
        date=obj.val;
    },
});
// $(document).ready(function(){
//         $('.select2').select2()
//         /*地址*/
//         /**
//          * address初始化
//          */
//         var pr_str = '';//省

//         pr_str = '<option value="">请选择</option>';
//         for(var v in area_info){
//             pr_str += '<option value="'+area_info[v].area_id+'">'+area_info[v].area_name+'</option>';
//         }
//         $('#province').html(pr_str);

//         $('#city').html('<option value="">请选择</option>');

//         $('#area').html('<option value="">请选择</option>');

//     });


    /**
     * 选择省会促发下一级联动
     */

    var glo = {
        'parent_id':0,//全局父类id
        'is_true':true
    }
    function change_p(obj){

        $('#area').html('<option value="">请选择</option>');
        var p_id = $(obj).val();

        glo.parent_id = p_id;
        console.log(p_id)

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
        if($("#city").find("option:selected").text()!=='请选择'){
            var area_city_info = area_info[glo.parent_id].children[c_id].children;//获取子集信息
            var pr_str = '';//省
            pr_str = '<option value="">请选择</option>';
            for(var v in area_city_info){
                pr_str += '<option value="'+area_city_info[v].area_id+'">'+area_city_info[v].area_name+'</option>';
            }
            $('#area').html(pr_str);
        }else{
             $('#area').html('<option value="">请选择</option>');
        }
    }

    //上传图片
    function imgUpload_sm(e) {
        console.log($(e).val())
        var t = e.getAttribute("data-width"),
            a = e.getAttribute("data-height"),
            r = [],
            o = $(".imgs");
        r.push(o);
        var result = e.files[0];
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
    var reg_flag=false;
    function reg(){
        var name=$('.name').val(),
            classfiy=$("#class").find("option:selected").text(),
            province_text=$("#province").find("option:selected").text(),  //省
            city_text=$("#city").find("option:selected").text(),  //市
            area_text=$("#area").find("option:selected").text(), //区
            detailaddress=$('.detailaddress').val(),
            time=$('.time').val(),
            license=$('.license').attr('src'),
            certificate=$('.certificate').attr('src');

        if(name==''){
            fail('请输入商户名称');
            return
        }
        if(!fun.isMc(name)){
             fail('商户名称不合法');
            return
        }
        if(classfiy=='请选择'){
            fail('请输入商户类别');
            return
        }
         if(province_text == '请选择'){
            fail('请选择省份');
            return false;
        }

        if(city_text == '请选择'){
            fail('请选择市');
            return false;
        }

        if(area_text == '请选择'){
            fail('请选择区');
            return false;
        }
        if(detailaddress==''){
            fail('请填写详细地址');
            return false;
        }
        if(!fun.isMc(detailaddress)){
            fail('详细地址不合法');
            return false;
        }
        if(time==''){
            fail('请选择营业时间')
            return false;
        }
        if(license==''){
            fail('请上传营业执照')
            return false;
        }
        if(certificate==''){
            fail('请上传经营许可证')
            return false;
        }
        reg_flag=true;




    }