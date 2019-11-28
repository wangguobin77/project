
    $('.items').click(()=>{

        $(this).find('h4').addClass('items-active');

        $(this).siblings().find('h4').removeClass('items-active');

    })

    // 动画初始化

    var wow = new WOW({

        boxClass: 'wow',

        animateClass: 'animated',

        offset:200,

        mobile: false,

        live: true

    });

    wow.init();

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

    /**
     **中文字符计算length
     */
    var getStrLeng=(str)=>{
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

    /**
     * 维修选项的判断
     */
    var flag = false ;//标记判断是否选中一个
    var selectOne= ()=> {
        var names = document.getElementsByName("weixiu");
        for(var i=0;i<names.length;i++){
            if(names[i].checked){
                flag = true ;
             }
         }
    }

    /**
     * 去除所有空格
     */
    function checkspace(str) {
        if (str.indexOf(" ") >=0) //alert("输入有空格！");
        str= str.replace(/\s/g, ""); //强制删除所有空格
    }


    /**
     * 表单信息提交
     */
     $('.submit-btn').click(()=>{
        selectOne();
        var reg_mobile=/^[1][3,4,5,7,8][0-9]{9}$/,
            reg_email= /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,8}$/,

            phone= $('.phone').text().replace(/\s/g, ""),
            // console.log(phone);
            company=$('.company').text().replace(/\s/g, ""),
            account= $('.account').text().replace(/\s/g, ""),
            address= $('.address').text().replace(/\s/g, ""),
            logistics= $('.logistics').text().replace(/\s/g, ""),
            trackingnumber= $('.trackingnumber').text().replace(/\s/g, ""),
            productmodel= $('.productmodel').text().replace(/\s/g, ""),
            sn= $('.sn').text().replace(/\s/g, ""),
            partlist= $('.partlist').text().replace(/\s/g, ""),
            shop= $('.shop').text().replace(/\s/g, ""),
            purchasedate= $('.purchasedate').text().replace(/\s/g, ""),
            ordernumber= $('.ordernumber').text().replace(/\s/g, ""),
            accidentdate= $('.accidentdate').text().replace(/\s/g, ""),
            des= $('.des').text().replace(/\s/g, ""),
            email= $('.email').text().replace(/\s/g, "");

        //名称

        if(getStrLeng(company)==0 || company==''){
            msg('姓名或名称不能为空！');
            $('.company').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(company)>128){
            msg('字符长度超出限制');
            $('.company').parent().addClass('warn');
            return false;
        }else{
            $('.tsinfo').hide();
            $('.company').parent().removeClass('warn');
        }
        // 账号
        if(getStrLeng(account)==0){
            msg('账号不能为空！');
            $('.account').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(account)>128){
            msg('字符长度超出限制');
            $('.account').parent().addClass('warn');
            return false;
        }else{
            $('.tsinfo').hide();
            $('.account').parent().removeClass('warn');
        }
        // 收件地址
        if(getStrLeng(address)==0){
            msg('收件地址不能为空！');
            $('.address').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(address)>128){
            msg('字符长度超出限制');
            $('.address').parent().addClass('warn');
            return false;
        }else{
            $('.tsinfo').hide();
            $('.address').parent().removeClass('warn');
        }

        // 电话
        if(!reg_mobile.test(phone)){
            msg('请填写正确的手机号信息');
              $('.phone').parent().addClass('warn');
            return false;
        }else{
            $('.phone').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        //邮箱
        if(!reg_email.test(email)){
            msg('请填写正确的邮箱信息');
            $('.email').parent().addClass('warn');
            return false;
        }else{
            $('.email').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        //寄回物流服务商
        if(getStrLeng(logistics)==0){
            msg('寄回物流服务商不能为空！');
            $('.logistics').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(logistics)>128){
            msg('字符长度超出限制');
            $('.logistics').parent().addClass('warn');
            return false;
        }else{
            $('.logistics').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        // 寄回物流单号
        if(getStrLeng(trackingnumber)==0){
            msg('寄回物流单号不能为空！');
            $('.trackingnumber').parent().addClass('warn');
            return false;
        }
       if(getStrLeng(trackingnumber)>128){
            msg('字符长度超出限制');
            $('.trackingnumber').parent().addClass('warn');
            return false;
        }else{
            $('.trackingnumber').parent().removeClass('warn');
            $('.tsinfo').hide();
        }

        // 产品型号 productmodel
        if(getStrLeng(productmodel)==0){
            msg('产品型号不能为空！');
            $('.productmodel').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(productmodel)>128){
            msg('字符长度超出限制');
            $('.productmodel').parent().addClass('warn');
            return false;
        }else{
            $('.productmodel').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        // 产品序列号
       if(getStrLeng(sn)==0){
            msg('产品序列号不能为空！');
            $('.sn').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(sn)>128){
            msg('字符长度超出限制');
            $('.sn').parent().addClass('warn');
            return false;
        }else{
            $('.sn').parent().removeClass('warn');
            $('.tsinfo').hide();
        }


        // 寄回部件清单
        if(getStrLeng(partlist)==0){
            msg('寄回部件清单不能为空！');
            $('.partlist').parent().addClass('warn');
            return false;
        }
        if(getStrLeng(partlist)>1024){
            msg('字符长度超出限制');
            $('.partlist').parent().addClass('warn');
            return false;
        }else{
            $('.partlist').parent().removeClass('warn');
            $('.tsinfo').hide();
        }

        // 购买渠道
        if(getStrLeng(shop)>128){
            msg('字符长度超出限制');
            $('.shop').parent().addClass('warn');
            return false;
        }else{
            $('.shop').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        // 购买日期
        if(getStrLeng(purchasedate)>128){
            msg('字符长度超出限制');
            $('.purchasedate').parent().addClass('warn');
            return false;
        }else{
            $('.purchasedate').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        // 订单号
        if(getStrLeng(ordernumber)>128){
            msg('字符长度超出限制');
            $('.ordernumber').parent().addClass('warn');
            return false;
        }else{
            $('.ordernumber').parent().removeClass('warn');
            $('.tsinfo').hide();
        }

        //服务信息为必选项
        if(!flag){
             msg('请选择服务信息');
             return false;
        }
        // 事故日期
        if(getStrLeng(accidentdate)>128){
            msg('字符长度超出限制');
            $('.accidentdate').parent().addClass('warn');
            return false;
        }else{
            $('.accidentdate').parent().removeClass('warn');
            $('.tsinfo').hide();
        }
        // 问题描述
        if(getStrLeng(des)>1024){
            msg('字符长度超出限制');
            $('.des').parent().addClass('warn');
            return false;
        }else{
            $('.accidentdate').parent().removeClass('warn');
            $('.tsinfo').hide();
        }


        // 隐藏域提交数据值
        var inputs=$('.hidden');
        $.each(inputs, function(index, val) {
            var value=$(this).prev('td').find('p').text().replace(/\s/g, "");
            if(value){
                $(this).val(value);
            }else{
                $(this).val('');
            }
        });
        var data =JSON.stringify($('#form_btn').serializeObject());
        var  baseurl='http://cloud.senseplay.cn/';
        $.ajax({
            url:baseurl+'api/service/apply',
            type:'post',
            dataType:'json',
            data:data,
             beforeSend: function(){

            },
            success:function (res) {

                if(res.data.code==0){
                    // success
                    $('.success-info').show().delay(3000).hide(0);
                    // 情况所有把输入框数据
                    setTimeout(function(){
                        window.location.href=''
                    }, 3000)

                }else{
                    // fail
                    msg('提交失败，请重新提交！');
                }

            },
            complete: function () {
            }
        })
     })

 // $(".tab-anniu").on("click",function () {
 //    alert(1111);
 //        $('.wrapper-view').stop();
 //        $(this).siblings(".tab-anniu").removeAttr("id");

 //        if($(this).attr("id")=="open"){
 //            $(this).removeAttr("id").next().hide().siblings(".wrapper-view").hide();
 //        }else{
 //            $(this).attr("id","open").next().show().siblings(".wrapper-view").hide();
 //        }
 //        if($(this).attr('id')!='open'){
 //            $(this).next('.wrapper-view').hide();
 //        }
 //    });