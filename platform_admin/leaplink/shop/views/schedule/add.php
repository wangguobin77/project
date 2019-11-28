<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" type="text/css" href="css/v2/partner/reg.css">
<!-- 日期控件 -->
<link type="text/css" rel="stylesheet" href="css/public/jeDate-test.css">
<link type="text/css" rel="stylesheet" href="css/public/jedate.css">
  <style type="text/css">.jeinput{margin-left:0!important;}.info{background:#fff;padding:24px;}input[readonly]{background:#fff!important;}.select2-container .select2-selection--single .select2-selection__rendered {color: #555;}.select2-container--default .select2-selection--multiple{border-radius:4px;}</style>

 <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav" style="height:70px;">
         <a style="line-height: 26px;margin:0;float:right;" href="JavaScript:history.go(-1)" class="btn btn-primary  mid-button">
            返回
        </a>
    </div>
    <!-- Main content -->


    <section class="content container-fluid" style="padding:30px;">
        <div class='info col-md-12'>
            <form id="sub-form">
                <input  type="hidden" value="<?=$bid?>" name="bid">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;">
                        <div class="row col-md-12">
                            <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>推送标题</label>
                                    <input type="text" class="form-control push-title" name="title" autocomplete="off" placeholder="请输入推送标题">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>有效日期:</label>
                                    <input type="text" class="jeinput jeinput1 form-control push-date" id="testblue" placeholder="有效日期" timeattr='YYYY-MM-DD hh:mm:ss' name="date" autocomplete="off" readonly>
                                    <span class="ts-des tishi">*有效日期不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>推送日期:</label>
                                    <select class="form-control  select2 selsctday push-day" multiple name='day' autocomplete="off">
                                        <option value='1'>周一</option>
                                        <option value='2'>周二</option>
                                        <option value='3'>周三</option>
                                        <option value='4'>周四</option>
                                        <option value='5'>周五</option>
                                        <option value='6'>周六</option>
                                        <option value='0'>周日</option>
                                    </select>
                                      <span class="ts-des tishi tishi-day">*推送日期不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-9">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>是否重复:</label>
                                    <div class="box-code form-control" style="border:0;">
                                        <select class="form-control select2 selsct_rep" name="interval_type">
                                            <option selected="selected" value="0">单次</option>
                                            <option value="1">重复</option>
                                        </select>

                                    </div>
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>


                        <div class="rep_box" style="display:none;">
                            <div class="row col-md-12">
                                <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">

                                        <label class="col-md-4 title"><i>*</i>推送时间:</label>
                                        <input type="text" class="jeinput jeinput1 form-control reptime" id="pushtime" placeholder="推送时间"  timeattr='hh:mm:ss' name='time' autocomplete="off" readonly >
                                        <span class="ts-des tishi">*推送时间不能为空</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-4 title"><i>*</i>推送间隔:</label>
                                        <input  type="text" class="form-control name delaytime" autocomplete="off"  placeholder="推送间隔(1-600s)" name="delaytime">
                                        <span class="ts-des tishi">*推送间隔不能为空</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="once_box">
                            <div class="row col-md-12">
                                <div class="col-md-9">
                                    <div class="form-group col-md-12 input-xx">
                                        <label class="col-md-4 title"><i>*</i>推送时间:</label>
                                        <input type="text" class="jeinput form-control oncejeinput oncetime"  placeholder="推送时间"  name='once_time' autocomplete="off" readonly>
                                        <span class="ts-des tishi">*推送时间不能为空</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 提交操作区域 -->
                        <div class="row col-md-12" style="padding-left:200px !important;margin-top:40px;">
                       <!--      <div class="form-group col-md-3 input-xx" style="margin-right:16px;"">
                                <button type="button" class="btn btn-block btn-back" onclick="back()">返回</button>
                            </div> -->
                            <div class="form-group col-md-3 input-xx">
                                <button type="button" class="btn btn-block btn-primary submit-btn">提交</button>
                            </div>
                        </div>
                    </div>
                </div>
             </form>
        </div>
    </section>


</div>
<?php include_once(NAV_DIR."/footer.php");?>
<!-- ./wrapper -->
<script type="text/javascript">

    $(document).ready(function(){
        $('.select2').select2()
    });
    $(".selsct_rep").change(function(event) {
        if($(".selsct_rep").find("option:selected").text()=='单次'){
            $('.once_box').show()
            $('.rep_box').hide()

        }else{
            $('.once_box').hide()
            $('.rep_box').show()
        }
    });
    // $(".jeinput1").each(function(){
    //     var mat = $(this).attr("timeattr");
    //     jeDate(this,{
    //         format: mat,
    //          range:"/",
    //     multiPane:false,
    //     minDate: jeDate.nowDate({DD:"0"}),
    //     donefun:function(obj) {
    //         console.log(obj)
    //     },
    //     theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
    //     });
    // });
    jeDate('.push-date',{
        format: 'YYYY-MM-DD hh:mm:ss',
        isinitVal:true,
        range:"/",
        multiPane:false,
        minDate: jeDate.nowDate({DD:"0"}),
        donefun:function(obj) {
        },
        theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
    })
    jeDate(".reptime",{
        isinitVal:true,
        format:"hh:mm:ss",
        multiPane:false,
        range:"/",
        theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
        zIndex:3000
    })

    jeDate(".oncejeinput",{
        format:"hh:mm:ss",
        theme:{bgcolor:"#3367FF",pnColor:"#3367FF"},
        zIndex:3000
    })


    $('.submit-btn').unbind('click').click(function(){
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
        var push_date=$('.push-date').val(),
            push_day=$('.push-day').val(),
            reptime=$('.reptime').val(),
            oncetime=$('.oncetime').val(),
            dalay=$('.delaytime').val();
        if(push_date==''){
            $('.push-date').next().show()
            return
        }else{
            $('.push-date').next().hide()
        }
        if(push_day.length=='0'){
            $('.tishi-day').show()
            return
        }else{
            $('.tishi-day').hide()
        }

        var tt=$(".selsct_rep").find("option:selected").text();
        if(tt=='单次'){
            if(oncetime==''){
                $('.oncetime').next().show()
                return
            }else{
                 $('.oncetime').next().hide()
            }

        }else{
            if(reptime==''){
                $('.reptime').next().html('*推送时间不能为空')
                $('.reptime').next().show()
                return
            }else{
                 $('.reptime').next().hide()
            }
            if(dalay==''){
                $('.delaytime').next().html('*推送间隔不能为空')
                $('.delaytime').next().show()
                return
            }else if(dalay>0 && dalay>600){
                $('.delaytime').next().html('*推送间隔格式不正确')
                $('.delaytime').next().show()
                return
            }else if(!fun.isNum(dalay)){
                $('.delaytime').next().html('*推送间隔格式不正确')
                $('.delaytime').next().show()
                return
            }else{
                $('.delaytime').next().hide()
            }
        };

        var data =$('#sub-form').serializeObject();
        $.ajax({
            url:'<?=Url::toRoute("schedule/add")?>',
            type:'post',
            dataType:'json',
            data:data,
            success: function(data) {
                if(data.code==0){
                    succ('提交成功','<?=Url::toRoute(['schedule/list','bid'=>$bid])?>');
                }else{
                    fail('提交失败')
                }

            }
        });
    })

</script>
