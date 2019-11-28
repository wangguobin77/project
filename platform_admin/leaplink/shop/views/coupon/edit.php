<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" type="text/css" href="css/v2/discount/list.css">
<style type="text/css">.select-input>span.select2{
   width:34% !important;
}.form-group label{min-width:92px;}.cursor{cursor:pointer;}input[type='number']::-webkit-outer-spin-button,input[type='number']::-webkit-inner-spin-button{
    -webkit-appearance: none !important;
    margin: 0;
}
 input[type="number"]{-moz-appearance:textfield;}</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 560px;">
    <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <section class='row'>
            <span class='yj'>优惠券</span><span class='yj'>/</span><span class='yj'>优惠券管理</span>
        </section>
        <h5 class="zhubt">优惠券管理</h5>
    </div>
    <!--商品信息-->
    <section class="content container-fluid" style="background: #F0F2F5;padding:24px;">
        <div class='info col-md-12' style="background:#fff;min-width:880px;padding-top:44px;">
<!--             <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;">
                    <ul class='step'>
                        <li class='step-box'>
                            <span class='step-num step-num-active'>1</span>
                            <a class='step-des'>
                                <span class='step-des-active'>进行中    ——————————</span>
                                <span class='step-des-active'>这里是描述的步骤</span>
                            </a>
                        </li>
                        <li class='step-box'>
                            <span class='step-num'>2</span>
                            <a class='step-des'>
                                <span>提交信息审核   ————————</span>
                                <span>这里是描述的步骤</span>
                            </a>
                        </li>
                        <li class='step-box'>
                            <span class='step-num'>3</span>
                            <a class='step-des'>
                                <span>登记成功            </span>
                                <span>这里是描述的步骤</span>
                            </a>
                        </li>
                    </ul>
            </div> -->

            <form class="info-form">
                <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-group col-md-12 input-xx">
                                    <label class="col-md-3 title">状态:</label>
                                    <input  type="text" class="form-control status" disabled placeholder="状态" name="status" value="<?=isset($data['id'])?'编辑':'新建'?>">
                                    <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>
                       <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>优惠券名称:</label>
                                    <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入优惠券的名称" name='title' value="<?= isset($data['title'])?$data['title']:''?>">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>价值:</label>
                                    <input  type="number" class="form-control val" autocomplete="off"  placeholder="请输入优惠券的价值" name='worth' value="<?= isset($data['worth'])?$data['worth']:''?>">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>价格:</label>
                                    <input  type="number" class="form-control price" autocomplete="off"  placeholder="请输入优惠券的价格" name='price' value="<?= isset($data['price'])?$data['price']:''?>">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title">默认投放数量:</label>
                                    <input  type="number" class="form-control number" autocomplete="off"  placeholder="请输入优惠券的默认投放数量" name='number' value="<?= isset($data['number'])?$data['number']:''?>">
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>
          <!--               <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title">默认有效时间:</label>
                                    <div class="form-control" style="display:flex;justify-content:space-around;">
                                         <input type="num" id='default_num' class=" my-colorpicker1 colorpicker-element" autocomplete="off" placeholder="请输入默认有效时间" style="flex-grow:1;height:100%;border:0;">
                                        <span style="width:28px;">分钟</span>
                                    </div>
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div> -->

                       <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title">适用范围:</label>
                                    <textarea name='scope' style="height:160px" type="text" class="form-control beizhu" autocomplete="off" placeholder="请输入适用范围"><?= isset($data['scope'])?$data['scope']:''?></textarea>
                                    <span class="ts-des tishi"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <div class="col-md-6">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title">使用规则:</label>
                                    <textarea name="info"   style="height:160px" type="text" class="form-control beizhu" autocomplete="off" placeholder="请输入使用规则"><?= isset($data['info'])?$data['info']:''?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group col-md-12 input-xx">
                                <label class="col-md-3 title-center" style="width:12%!important"><i>*</i>优惠券背景:</label>
                                <div style="height:250px;border:0;padding:0;"  class="form-control coupon-boxs clearfix">
                                    <div class="coupon-box">
                                        <div class="item-img coupon-bg">
                                            <img class="img-thumbnail"  src="images/v2/coupon1.png" alt="">
                                            <!-- <input type="radio" name="coupon"   class="cursor coupon-radio"> -->
                                        </div>
                                        <div class="item-img coupon-bg">
                                            <img  class="img-thumbnail" src="images/v2/coupon2.png" alt="">
                                            <!-- <input type="radio"  name="coupon" class="cursor coupon-radio"> -->
                                        </div>
                                        <div class="item-img coupon-bg">
                                            <img  class="img-thumbnail" src="images/v2/coupon3.png" alt="">
                                            <!-- <input type="radio"  name="coupon"  class="cursor coupon-radio"> -->
                                        </div>
                                        <div class="item-img coupon-bg">
                                            <img  class="img-thumbnail" src="images/v2/coupon4.png" alt="">
                                            <!-- <input type="radio" name="coupon"   class="cursor coupon-radio"> -->
                                        </div>
                                    </div>
                                </div>
                                <span class="ts-des tishi"></span>
                            </div>
                        </div>
                         <!-- 提交操作区域 -->
                        <div class="row col-md-12" style="padding-left: 12%!important;">
                            <div class="form-group col-md-2 input-xx">
                                <button type="button" class="btn btn-block btn-primary submit-btn" >保存</button>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<!-- /.content-wrapper -->

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/coupon/add.js"></script>
<script type="text/javascript">
    // var POST_URL = "<?= Url::toRoute(['coupon/edit'])?>";
    // const put = () =>{
    //     var data =$('#sub-form').serializeObject();
    //     $.ajax({
    //         url:POST_URL,
    //         type:'post',
    //         dataType:'json',
    //         data:data,
    //         success: function(data) {
    //             if(data.code==0){
    //                 succ('提交成功')
    //                 location.href = "<?=Url::toRoute(['coupon/list'])?>"
    //             }else{
    //                 fail(data.msg)
    //             }
    //         }
    //     });

    // };

    // $('.coupon-radio').each(function(index){
    //    $(this).click(function(){
    //         $(this).parent().addClass('item-img-active').siblings().removeClass('item-img-active')
    //    })
    // });

    $('.coupon-bg').each(function(index){
       $(this).click(function(){
            $(this).addClass('item-img-active').siblings().removeClass('item-img-active')
       })
    });
    ;(function() {
         let nameChecker = $('.name').TChecker({
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
        let oripriceChecker = $('.val').TChecker({
            required: {
                rule: true,
                error: '*' + "价值不能为空"
            },
            format: {
                rule:/\S/,
                rule:/^[1-9]\d*$/,
                error: '*' + "价值格式不正确"
            }
        });
        let priceChecker = $('.price').TChecker({
            required: {
                rule: true,
                error: '*' + "价格不能为空"
            },
            format: {
                rule:/\S/,
                rule:/^[1-9]\d*$/,
                error: '*' + "价格格式不正确"
            }
        });



        var  POST_URL = "<?= Url::toRoute(['coupon/edit'])?>";
        $('.submit-btn').click(function(){
            let correct = nameChecker.check();
            if (!correct) {return false;}

            correct = oripriceChecker.check();
            if (!correct) {return false;}

            correct = priceChecker.check();
            if (!correct) {return false;}
            // var names = document.getElementsByName("coupon"),
            //     select_flag=false;
            // for(var i=0;i<names.length;i++){
            //     if(names[i].checked){
            //         select_flag = true ;
            //      }
            //  }
            //  if(select_flag==false){
            //     fail('请选择优惠券背景！')
            //     return
            //  }
           var data =JSON.stringify($('.info-form').serializeObject());
           console.log(data)
            $.ajax({
                url:POST_URL,
                type:'post',
                dataType:'json',
                data:data,
                success: function(data) {
                    if(data.code==0){
                        succ('提交成功','<?=Url::toRoute(['coupon/list'])?>');
                    }else{
                        fail(data.msg)
                    }
                }
            });
        })


    })();
</script>
</body>