/*
* @Author: Marte
* @Date:   2019-08-14 10:06:23
* @Last Modified by:   Marte
* @Last Modified time: 2019-08-15 10:35:20
*/

'use strict';
$(".yyzz_selsct").change(function(event) {
     if($(".yyzz_selsct").find("option:selected").text()=='自定义'){
        $('#test03').removeAttr('disabled');
        $('#test03').attr('placeholder','请选择有效期的截止日期')
    }else{
         $('#test03').attr('disabled',true);
         $('#test03').attr('placeholder','');
    }
});
var reg_flag=false;
const reg=()=>{
    var mobile=$('#mobile').val(),
        code=$('#code').val(),
        pwd=$('#password').val(),
        rpwd=$('#rpassword').val(),
        email=$('#email').val(),
        idnum=$('#idnum').val(),//申请人证件号码
        sczp=$('#sczp').attr('src'),//申请人手持身份证截图
        business_license=$('#business_license').val(),//营业执照全称
        credit_code=$('#credit_code').val(),//社会信用代码、
        license_address=$('#license_address').val(),//营业执照地址
        yyzz_date=$('#test03').val(),//营业执照有效期
        license_img=$('#license_img').attr('src'),//营业执照
        legal_name=$('#frname').val(),//法人姓名
        legal_img1=$('#img1').attr('src');//法人证件照片

    if(mobile==''){
        fail('手机号码不能为空');
        return
    }
    if(!fun.isMobile(mobile)){
        fail('手机号码格式不正确');
        return
    }
    if(code==''){
        fail('验证码不能为空');
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
    if(!fun.repeatpwd(pwd,rpwd)){
        fail('两次密码输入不一致')
        return
    }
    if(email==''){
        fail('邮箱不能为空');return}
    if(!fun.isMail(email)){
        fail('邮箱格式不正确')
        return
    }
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
     if(sczp==''){
        fail('请上传手持身份证截图')
         return
    }
    if(business_license==''){
        fail('营业执照全称不能为空')
         return
    }
    if(credit_code==''){
        fail('社会信用代码不能为空')
        return
    }
    if(license_address==''){
        fail('营业执照地址不能为空')
        return
    }

    if($(".yyzz_selsct").find("option:selected").text()=='自定义'){
        if($('#test03').val()==''){
            fail('请选择营业执照截至日')
            return
        }

    }
    if(license_img==''){ fail('请上传营业执照');  return}

    if(legal_name=='') {fail('请输入法人代表姓名不能为空');  return}
    if(!fun.isMc(legal_name)) {fail('法人代表姓名不合法');return}
    if(legal_img1==''){
        fail('请上传法人证件正面照片');return
    }

    if($('#img2').attr('src')==''){
         fail('请上传法人证件反面照片');return
    }
    reg_flag=true;
}