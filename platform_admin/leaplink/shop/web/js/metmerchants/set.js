/*
* @Author: Marte
* @Date:   2019-07-19 16:28:07
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-24 16:32:51
*/

'use strict';
var date=$('.validity').val();
jeDate("#testblue",{
    theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
    format: "hh:mm",
    multiPane:false,
    range:"-",
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
        var t = e.getAttribute("data-width"),
            a = e.getAttribute("data-height"),
            r = [],
            o = $(".imgs");
        r.push(o);
        var result = e.files[0];
        var s = result.size;
        if (s > 1024*1024*2){
            $(e).parent().parent().next('.img-ts').show();
            return false;
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
