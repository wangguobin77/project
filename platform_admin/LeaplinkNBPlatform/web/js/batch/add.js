/*
* @Author: Marte
* @Date:   2019-10-30 16:26:15
* @Last Modified by:   Marte
* @Last Modified time: 2019-11-12 10:47:22
*/

'use strict';
$('.submit-btn').unbind('click').click(function(){
    var reg=/^\S{2,128}$/,
           reg_space=/\S/;
    let batchidChecker = $('.batchid').TChecker({
        required: {
            rule: true,
            error: '*' + "批次id不能为空"
        },

        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "批次id格式不正确"
        }
    });
    let chip_typeChecker = $('.chip_type').TChecker({
        required: {
            rule: true,
            error: '*' + "芯片型号不能为空"

        },
        format: {
            rule:/\S/,
            rule:/^([0]{1})+([0-3]{1})+([0]{1})+([0-3]{1})$/,
            error: '*' + "芯片型号格式不正确"
        }
    });
    let batchdateChecker = $('.batchdate').TChecker({
        required: {
            rule: true,
            error: '*' + "批次日期不能为空"
        },
        format: {
            rule:/\S/,
            error: '*' + "批次日期不能为空"
        }
    });
    let batchnoChecker = $('.batchno').TChecker({
        required: {
            rule: true,
            error: '*' + "批次流水不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "批次流水格式不正确"
        }
    });

    let batchnumChecker = $('.batchnum').TChecker({
        required: {
            rule: true,
            error: '*' + "生产数量不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^[1-9]\d*$/,
            error: '*' + "生产数量不正确"
        }
    });
    let checktimeChecker = $('.checktime').TChecker({
        required: {
            rule: true,
            error: '*' + "审批时间不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "审批时间格式不正确"
        }
    });

    let creattimeChecker = $('.creattime').TChecker({
        required: {
            rule: true,
            error: '*' + "创建时间不能为空"
        },
        format: {
            rule:/\S/,
            rule:/^\S{2,128}$/,
            error: '*' + "创建时间格式不正确"
        }
    });
    let correct = batchidChecker.check();
    if (!correct) {return false;}
     correct = chip_typeChecker.check();
    if (!correct) {return false;}
     correct = batchdateChecker.check();
    if (!correct) {return false;}
    correct = batchnoChecker.check();
    if (!correct) {return false;}
     correct = batchnumChecker.check();
    if (!correct) {return false;}
      correct = checktimeChecker.check();
    if (!correct) {return false;}
     correct = creattimeChecker.check();
    if (!correct) {return false;}
})