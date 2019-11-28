/*
* @Author: Marte
* @Date:   2019-10-18 15:06:48
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-18 15:11:55
*/

'use strict';
(function() {
    let passwordChecker = $('#pwd').TChecker({
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
    $('.submit-btn').click(function(){
        var pwd=$('#pwd').val(),
            rpwd=$('#rpassword').val(),
            classify=$(".class").find("option:selected").text();
        let correct = passwordChecker.check();
        if (!correct) {return false;}
        if(!fun.repeatpwd(pwd,rpwd)){
              $('.rpassword').next().show()
            return
        }else if(fun.repeatpwd(pwd,rpwd)){
            $('.rpassword').next().hide()
        }
        if(classify=='请选择'){
            fail('请输入商户类别');
            return
        }
        data =JSON.stringify($('.info-form').serializeObject());
        console.log(data)
    })
})();