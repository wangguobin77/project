/*
* @Author: Marte
* @Date:   2019-09-10 09:32:33
* @Last Modified by:   Marte
* @Last Modified time: 2019-10-21 11:01:37
*/

'use strict';
// 确认创建信息提交
;(function() {
    let codenumChecker = $('#code_num').TChecker({
        required: {
            rule: true,
            error: '*' + "核销码不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "核销码格式不正确"
        }
    });

    let nameChecker = $('#name').TChecker({
        required: {
            rule: true,
            error: '*' + "品名不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "品名格式不正确"
        }
    });

    let oripriceChecker = $('#ori_price').TChecker({
        required: {
            rule: true,
            error: '*' + "原价不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^[1-9]\d*$/,
            error: '*' + "原价格式不正确"
        }
    });
     let priceChecker = $('#price').TChecker({
        required: {
            rule: true,
            error: '*' + "原价不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^[1-9]\d*$/,
            error: '*' + "原价格式不正确"
        }
    });

    // 提交确认
    $('.submit-btn').click(function() {
        let correct = codenumChecker.check();
        if (!correct) {return false;}

        correct = nameChecker.check();
        if (!correct) {return false;}

        correct = oripriceChecker.check();
        if (!correct) {return false;}

        correct = priceChecker.check();
        if (!correct) {return false;}
        let shop_img=$('#shop_img').attr('src');
        if(shop_img==''){
            fail('请上传商品图片')
            return false;
        }

        var data =JSON.stringify($('.info-form').serializeObject());
        console.log(data)

        tips_warning('提交确认','确定将商品提交审核吗？');
        // 点击确认提示框中的确认按键
        $('.confirm').click(function(){
            $('.delete').hide();
            $('.info-form').hide();
            $('.checkbox').show();
            // 第二部信息审核高亮
            sec_stepactive()
            // 第三步登记成功高亮
            // third_stepactive()

        })

    });

})();
