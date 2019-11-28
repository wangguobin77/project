<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

use common\helpers\UtilsHelper;
?>

<?php include_once(NAV_DIR."/header.php");?>

<link rel="stylesheet" type="text/css" href="css/v2/product/info.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 560px;">
    <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <section class='row'>
            <span class='yj'>商品</span><span class='yj'>/</span><span class='yj'>商品管理</span><span class='yj'>/</span><span class='yj'>添加</span>
        </section>
        <h5 class="zhubt">添加</h5>
    </div>

    <!-- Main content -->
    <section class="content container-fluid" style="background: #F0F2F5;padding:24px;">
        <div class='info col-md-12' style="background:#fff;min-width:880px;padding-top:44px;">
            <div class='col-md-12 info-wrap' style="min-width:790px;width:790px;margin:0 auto;">
                <ul class='step'>
                    <li class='step-box step-fir'  style="width:223px;">
                        <span class='step-num step-num-active'>1</span>
                        <a class='step-des'>
                            <span class='step-des-active'>进行中</span>
                            <span class='xt xt-zctive'></span>
                        </a>
                    </li>
                    <li class='step-box step-sec' style="width:240px;">
                        <span class='step-num '>2</span>
                        <a class='step-des'>
                            <span>信息审核</span>
                            <span class='xt'></span>
                        </a>
                    </li>
                    <li class='step-box step-third'>
                        <span class='step-num'>3</span>
                        <a class='step-des'>
                            <span>登记成功</span>
                        </a>
                    </li>
                </ul>
            </div>

           <form class="info-form" id="sub-form" >
                <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                    <div class='col-md-12 info-wrap' style="min-width:850px;width:850px;margin:0 auto;margin-top:10px;">
                        <div class="row col-md-12">
                           <div class="col-md-7 info-input">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>状态:</label>
                                   <input  type="text" class="form-control status" disabled placeholder="状态" name="status" value="<?=isset($data['id'])?'编辑':'新建'?>">
                                    <input type="hidden" name="id" value="<?=isset($data['id'])?$data['id']:''?>">
                                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->csrfToken?>">
                                    <span class="ts-des tishi"></span>
                                </div>

                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-7 info-input">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>核销码:</label>
                                    <input  type="text" class="form-control name" autocomplete="off"  placeholder="请输入用于商品核销的代码" name='upc' value="<?= isset($data['upc'])?$data['upc']:''?>" id='code_num'>
                                    <span class="ts-des tishi">* 核销码不能为空</span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                           <div class="col-md-7">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>品名:</label>
                                    <input id="name" type="text" class="form-control name" autocomplete="off"  placeholder="请输入商品品名" name='name' value="<?= isset($data['name'])?$data['name']:''?>">
                                    <span class="ts-des tishi">* 品名不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-7">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>原价:</label>
                                    <input  type="text" class="form-control val" id="ori_price" autocomplete="off"  placeholder="请输入商品原价" name='worth' value="<?= isset($data['worth'])?UtilsHelper::fen2yuan($data['worth']):''?>">
                                    <span class="ts-des tishi">* 原价不能为空</span>
                                </div>
                            </div>
                        </div>
                        <div class="row col-md-12">
                           <div class="col-md-7">
                                <div class="form-group col-md-12 input-xx">
                                    <label class="col-md-3 title"><i>*</i>售价:</label>
                                     <input id="price"  type="text" class="form-control price" autocomplete="off"  placeholder="请输入商品售价" name='price' value="<?= isset($data['price'])?UtilsHelper::fen2yuan($data['price']):''?>">
                                    <span class="ts-des tishi">* 售价不能为空</span>
                                </div>
                            </div>
                        </div>

                        <div class="row col-md-12">
                           <div class="col-md-7">
                                <div class="form-group col-md-12 input-xx tp-view">
                                    <label class="col-md-3 title"><i>*</i>商品图片:</label>
                                    <div class="form-control tpimg-box dianpu-img" style="height:88px;width:88px;">
                                        <div class="bg-icon">
                                            <i class='close_box'>
                                                <span class="close-icon"></span>
                                                <!-- <img id="shop_img" style="width:88px;height:88px" src="" align="top"> -->
                                            </i>
                                            <input type="file" name="picurl" accept="image/png, image/jpg" onchange="imgUpload_sm(this)" data-width="800px" data-height="800px" >
                                            <input type="hidden" name="plate">
                                            <!-- <img src="images/v2/scicon.png" class='add-img' alt=""> -->
                                            <img class="img-thumbnail" id='shop_img' src="images/coupon_bg.jpg">
                                        </div>
                                    </div>
                                    <div class='des'></div>
                                </div>
                                <span class="ts-des tishi" >* 请上传商品图片</span>
                            </div>
                        </div>
                        <!-- 提交操作区域 -->
                        <div class="row col-md-12" style="padding-left:200px !important;margin-top: 30px;">
                            <button type="button" class="btn  btn-primary submit-btn mid-button btn-submit ">提交</button>
                            <button type="button" class="btn rep-btn min-button back" onclick="resetall()">重置</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- 点击提交以后出现已提交资质审核 -->
            <div class='checkbox'>
                <div class='sec-step flex'>
                    <img src="images/v2/okstep.png" alt="">
                    <h4 class='sec-title'>已提交审核</h4>
                    <span class="sec-des">信息已提交审核，请耐心等待</span>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
<?php include_once(NAV_DIR."/footer.php");?>

<script src="js/goods/add.js"></script>
<script type="text/javascript">
    var POST_URL = "<?= Url::toRoute(['goods/edit'])?>";
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
    //                 location.href = "<?=Url::toRoute(['goods/list'])?>"
    //             }else{
    //                 fail('提交失败')
    //             }
    //         }
    //     });

    // };
    /**
     * 重置
     */
    const resetall=()=>{
        $('.close_box').hide();
        $('input.form-control').val('');
        $('.bg-icon').find('input').val('');
        $('#shop_img').attr('src','');
        $('input[name="plate"]').val('');

    }
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
                error: '*' + "售价不能为空"
            },
            format: {
                rule:/\S/,
                rule:/^[1-9]\d*$/,
                error: '*' + "售价格式不正确"
            }
        });

        // 提交确认
        $('.btn-submit').click(function() {
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
            var data =$('#sub-form').serializeObject();
            $.ajax({
                url:POST_URL,
                type:'post',
                dataType:'json',
                data:data,
                success: function(data) {
                    if(data.code==0){
                         succ('提交成功','<?=Url::toRoute(['goods/list'])?>');
                    }else{
                        fail('提交失败')
                    }
                }
            });



        });

    })();
</script>
</body>