 // ;(function() {


    // 提交确认
    $('.submit-btn').click(function() {
        let titleChecker = $('.push-title').TChecker({
            required: {
                rule: true,
                error: '*' + "推送标题不能为空"
            },
            format: {
                rule:/\S/,
                rule:/^\S{2,128}$/,
                error: '*' + "推送标题格式不正确"
            }
        });

        let correct = titleChecker.check();
        if (!correct) {return false;}
        var push_date=$('.push-date').val(),//y有效期
            push_day=$('.push-day').val(),
            reptime=$('.reptime').val(),
            oncetime=$('.oncetime').val(),
            dalay=$('.delaytime').val(),
            date1=new Date(),
            year=date1.getFullYear(),
            mouth=date1 .getMonth()+1, //获取当前月份(0-11,0代表1月)
            date=date1 .getDate(),
            full=year+'-'+mouth+'-'+date,
            oncetimeval=full+' '+oncetime,
            full=year+'-'+mouth+'-'+date;

        if(push_date==''){
            $('.push-date').next().show()
            return
        }else{
            $('.push-date').next().hide()
        }
        console.log(push_day.length)
        if(push_day.length=='0'){
            $('.tishi-day').show()
            return
        }else{
            $('.tishi-day').hide()
        }

        var tt=$(".selsct_rep").find("option:selected").text();
        // 有效期参数
        var starttime=push_date.split("/")[0],
            endtime=push_date.split("/")[1],
            oncetimedate = new Date(oncetimeval),
            starttimedate = new Date(starttime),
            endtimedate = new Date(endtime),
            oncetimestmp= oncetimedate.valueOf(),
            starttimestmp=starttimedate.valueOf(),
            endtimestmp=endtimedate.valueOf();
            console.log( oncetimestmp +'```'+starttimestmp +'```'+endtimestmp)
        if(tt=='单次'){
            if(oncetime==''){
                $('.oncetime').next().html('*推送时间不能为空')
                $('.oncetime').next().show()
                return
            }else{
                 $('.oncetime').next().hide()
            }
            /**
             * 判断推送时间段是否处在有效期内
             */
            if(oncetimestmp<starttimestmp || oncetimestmp>endtimestmp){
                $('.oncetime').next().html('*推送时间不在有效期内')
                $('.oncetime').next().show()
                return
            }
        }else{
            debugger
            if(reptime==''){
                $('.reptime').next().html('*推送时间不能为空')
                $('.reptime').next().show()
                return
            }else{
                 $('.reptime').next().hide()
            }
            if(dalay==''){
                $('.delaytime').next().show()
                return
            }else if(dalay>0 && dalay>600){
                 $('.delaytime').next().html('*推送间隔格式不正确')
                $('.delaytime').next().show()
                return
            }else if(!fun.isNum(dalay)){
                $('.delaytime').next().html('*推送间隔格式不正确')
                $('.delaytime').next().show()
            }else{
                $('.delaytime').next().hide()
            }
        };

    });

// })();