/*
* @Author: Marte
* @Date:   2019-08-14 10:06:23
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-21 14:29:01
*/

'use strict';
function step(opt){
    opt.find('.step-num').addClass('step-num-active')
    opt.find('.step-des').find('span').addClass('step-des-active')
    opt.find('.step-des').find('span.xt').addClass('xt-zctive')
}
$(".yyzz_selsct").change(function(event) {
     if($(".yyzz_selsct").find("option:selected").text()=='自定义'){
        $('#test03').removeAttr('disabled');
        $('#test03').attr('placeholder','请选择有效期的截止日期')
         $('#test03').css('opacity',1)
    }else{
         $('#test03').attr('disabled',true);
         $('#test03').attr('placeholder','');
          $('#test03').val('');
          $('#test03').css('opacity',0)
    }
});

// 商户信息设置信息的步骤显示
var step_sec=$('.step-sec'),
    step_rd=$('.step-rd');
;(function() {
    let mobileChecker = $('#mobile').TChecker({
        required: {
            rule: true,
            error: '*' + "手机号不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^(\+\d{2,3}\-)?\d{11}$/,
            error: '*' + "手机格式不正确"
        }
    });
    let codeChecker = $('#code').TChecker({
        required: {
            rule: true,
            error: '*' + "验证码不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\d{6}$/,
            error: '*' + "验证码格式不正确"
        }
    });

    let passwordChecker = $('#password').TChecker({
        required: {
            rule: true,
            error: '*' + "密码不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^.{6,20}$/,
            error: '*' + "密码格式不正确"
        }
    });
    let emailChecker = $('#email').TChecker({
        required: {
            rule: true,
            error: '*' + "邮箱不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,8})$/,
            error: '*' + "邮箱格式不正确"
        }
    });
    let business_licenseChecker = $('#business_license').TChecker({
        required: {
            rule: true,
            error: '*' + "营业执照全称不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "营业执照全称格式不正确"
        }
    });
    let credit_codeChecker = $('#credit_code').TChecker({
        required: {
            rule: true,
            error: '*' + "社会信用代码不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "社会信用代码格式不正确"
        }
    });
    let license_addressChecker = $('#license_address').TChecker({
        required: {
            rule: true,
            error: '*' + "营业执照地址不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "营业执照地址格式不正确"
        }
    });



    let legal_nameChecker = $('#frname').TChecker({
        required: {
            rule: true,
            error: '*' + "法人姓名不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "法人姓名格式不正确"
        }
    });

    // 提交确认
    $('.submit-btn').unbind('click').click(function() {

        let correct = mobileChecker.check();
        if (!correct) {return false;}
        correct = codeChecker.check();
        if (!correct) {return false;}
        correct = passwordChecker.check();
        if (!correct) {return false;}
        let pwd=$('#password').val();
        let rpwd=$('#rpassword').val();
        if(!fun.repeatpwd(pwd,rpwd)){
            fail('两次密码输入不一致')
            return
        }
        correct = emailChecker.check();
        if (!correct) {return false;}
        let idnum=$('#idnum').val();
        if(idnum==''){
            fail('证件号码不能为空')
            return
        }
        if($(".idnum_select").find("option:selected").text()=='身份证'){
            if(!fun.isIdentity_card(idnum)){
                fail('证件号码不合法')
                return
            }
        }
        let  sczp=$('#sczp').attr('src');
        if(sczp==''){
            fail('请上传手持身份证截图')
             return
        }
        correct = business_licenseChecker.check();
        if (!correct) {return false;}

        correct = credit_codeChecker.check();
        if (!correct) {return false;}
        correct = license_addressChecker.check();
        if (!correct) {return false;}
        let yyzz_date=$('#test03').val();//营业执照有效期
        if($(".yyzz_selsct").find("option:selected").text()=='自定义'){
            if($('#test03').val()==''){
                fail('请选择营业执照截至日')
                return
            }
        }
        let  license_img=$('#license_img').attr('src');//营业执照

        if(license_img==''){ fail('请上传营业执照');  return}
        correct = legal_nameChecker.check();
        if (!correct) {return false;}
        let legal_img1=$('#img1').attr('src');//法人证件照片
        if(legal_img1==''){
            fail('请上传法人证件正面照片');return
        }

        if($('#img2').attr('src')==''){
             fail('请上传法人证件反面照片');return
        }
        $('.info-form').hide();
        $('.checkbox').show();
        step(step_sec);

        var data =JSON.stringify($('.info-form').serializeObject());
        console.log(data)

    });

})();