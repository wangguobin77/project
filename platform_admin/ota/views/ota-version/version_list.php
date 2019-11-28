<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/public/iCheck/all.css">

    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- 导航条 -->
        <!-- <div class="right-box p-b-20 row" style='line-height:40px;font-size:16px;'>

        </div> -->

        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><?php include_once(NAV_DIR."/bottom-menu.php");?></li>
            </ol>


        </div>

        <div  class='col-md-12 row'>
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-5 title'>新增版本:</label>
                    <input  type="text" class="form-control ver-num" autocomplete="off"  placeholder="请输入版本" name="ver-num" >
                </div>
            </div>

           <!--  <div class="col-md-2" style='float: left;text-align:left'>
                是否整包   <input type="checkbox" class="is_full" id="is_full">
                <button class="btn btn-primary ver-confirm">新增版本</button>
            </div> -->

            <div class="col-md-4" style='float: left;text-align:left'>
                <div class="col-md-12">
                    是否整包
                    <input type="checkbox" class="is_full minimal" id="is_full">

                    是否整体更新
                    <input type="checkbox" class="is_up_holt minimal" value=1 id="is_up_holt">
                    <button style='margin-left: 10px' class="btn btn-primary ver-confirm">新增版本
                    </button>
                </div>

            </div>

            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <div class="col-md-6">
                        <select name="pro_id" id="pro_id"  class="form-control select2 select2-show-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                            <!--<option value="">请选择...</option>-->
                            <?php foreach ($pro_names as $k=>$item):?>
                                <!--<option value="<?/*=$k*/?>" <?php /*if($k==$pro_id) {echo 'selected';} */?> ><?/*=$item['pro_name']*/?></option>-->
                                <option value="<?=$item['pro_id']?>" <?php if($item['pro_id']==$pro_id) {echo 'selected';} ?> ><?=$item['pro_name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" id="search-product" style="min-width: 96px;">确认</button>
                    </div>
                </div>
            </div>

           <!--  <div class="col-md-2">
                <button class="btn btn-primary" id="search-product" style="min-width: 96px;">确认</button>
            </div> -->
            <div class="col-md-2" style='float: right;text-align:right'>
                <button class="btn btn-default back"  style="min-width: 96px;">返回</button>
            </div>
        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">序号</th>
                    <th width="320px"  class="sl">版本号			</th>
                    <th width="100px"  class="sl">是否整包</th>
                    <th width="320px"  class="sl">创建时间		</th>
                  <!--  <th width="320px"  class="sl">已发		</th>
                    <th width="160px"  class="sl">灰度		 </th>
                    <th width="160px"  class="sl">未提交		 </th>-->
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data)):?>
                    <?php $number=count($data);$i=0;?>
                    <?php foreach ($data as $k=>$item):?>
                        <tr>
                          <!--  <td class="sl"><?/*=$k+1*/?></td>-->
                            <td class="sl"><?=intval($number-$i);?></td>
                            <td class="sl"><?=$item['ver_name']?></td>
                            <td class="sl">
                                <?php if(isset($item['is_full']) && $item['is_full'] >0):?>
                                    <span style="color: #00e765">是</span>
                                <?php else:?>
                                    否
                                <?php endif;?>
                            </td>
                            <td class="sl"><?php if(isset($item['created_ts']) && $item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                          <!--  <td class="sl">
                                <?php /*if(isset($item['published']) && $item['published'] >0):*/?>
                                    <?/*=$item['published']*/?>
                                <?php /*else:*/?>
                                    0
                                <?php /*endif;*/?>
                            </td>

                            <td class="sl"><?php /*if(isset($item['gray']) && $item['gray'] >0):*/?>
                                    <?/*=$item['gray']*/?>
                                <?php /*else:*/?>
                                    0
                                <?php /*endif;*/?></td>

                            <td class="sl"><?php /*if(isset($item['unpublished']) && $item['unpublished'] >0):*/?>
                                    <?/*=$item['unpublished']*/?>
                                <?php /*else:*/?>
                                    0
                                <?php /*endif;*/?></td>-->
                            <!-- 新原型增加  new-->
                            <td class="sl opr-box" >
                                <div class="czuo-box"  style="align-items: center;">

                                    <a class="btn-del" ver_id="<?=$item['ver_id']?>">删除</a>
                                    <span class="xian"></span>

                                    <?php if(isset($item['is_full']) && $item['is_full'] >0):?>
                                        <a  href="<?=url::toRoute(['/ota-package/package_full_add','ver_id'=>$item['ver_id'],'pro_id'=>$pro_id])?>" >整包新增</a>
                                        <span class="xian"></span>
                                        <a href="<?=url::toRoute(['/ota-package/package_list','ver_id'=>$item['ver_id'],'pro_id'=>$pro_id])?>"  >整包列表</a>
                                    <?php else:?>
                                        <a  href="<?=url::toRoute(['/ota-package/package_add','ver_id'=>$item['ver_id'],'pro_id'=>$pro_id])?>" >差分包新增</a>
                                        <span class="xian"></span>
                                        <a href="<?=url::toRoute(['/ota-package/package_list','ver_id'=>$item['ver_id'],'pro_id'=>$pro_id])?>"  >差分包列表</a>
                                    <?php endif;?>

                                </div>
                            </td>
                        </tr>
                        <?php $i++;?>
                    <?php endforeach;?>
                <?php endif;?>


                <input type="hidden" name="user_id" value="<?=$user_id?>">
                <input type="hidden" name="pro_id" value="<?=$pro_id?>">

                </tbody>
            </table>
        </div>
        <!--<div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                <li><a>«</a></li>
                <li><a>1</a></li>
                <li><a>2</a></li>
                <li><a>3</a></li>
                <li><a>»</a></li>
            </ul>
        </div>-->

        <!-- 页码 开始 -->
        <div class="box-footer clearfix">
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                'hideOnSinglePage' => false,
                'maxButtonCount' => 10
            ]);?>
        </div>
        <!-- 页码end -->


        <!-- 删除提示框 -->
        <div class="del-box delete">
            <div class="dialog">
                <span class="font_family icon-close fa fa-close"></span>
                <img src="/static/images//warning-large.png" alt="">
                <h6>是否确认删除?</h6>
                <div class="operate-del">
                    <div class="cursor cancel btn btn-default"> 取消</div>
                    <div class="cursor confirm  btn btn-primary">确认</div>
                </div>

            </div>
        </div>

    </section>
</div>



<?php include_once(NAV_DIR."/footer.php");?>
<!-- 选择框 -->
<script src="/static/js/public/select2.full.min.js"></script>
<script src="/static/js/public/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    $('input[type="checkbox"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
    });
    $('.back').click(function(){
        window.location.href = "<?=url::toRoute('ota-product/product_list')?>";
    })
    /*
    增加添加版本验证
     */
        $('.ver-confirm').click(function(){
            var ver_name = $('.ver-num').val();
            var reg=/^([vV]{1})+([0-9]{1})+([.]{1})+([0-9]{1})+([.]{1})+([0-9]{1})$/;//版本的强校验
            var pro_id = $("input[name='pro_id']").val();
            if(ver_name == '' || ver_name == undefined || ver_name == null){
                fail('版本号不能为空');
                return false;
            }

            var is_full = 0;
            var is_up_holt = 0;
            if($('.is_full').is(':checked')) {
                is_full = 1;
            }

            if($('#is_up_holt').is(':checked')) {
                is_up_holt = 1;
            }
            // if(!reg.test(ver_name)){
            //     fail('版本号格式不正确');return false;
            // }
            // ajax请求

            $.ajax({
                // 验证当前单号是否存在
                url:'<?=Url::toRoute('ota-version/version_submit')?>',
                type:'post',
                dataType:'json',
                data:{'ver_name':ver_name,'pro_id':pro_id,'is_up_holt':is_up_holt,'is_full':is_full,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code > 0){
                        fail(data.message);return
                    }else {
                        // 保存成功
                        succ(data.message);
                        setTimeout(refresh(),1600);
                        //window.location.href='<?//=Url::toRoute('ota-version/version_detail')?>//&ver_id='+data.data;
                    }
                }
            })


        })
    /**
     * 版本增加end
     */

    $('#submit_button').click(function(){
        var type = $('#type').val();
        var pro_id = $('#pro_id').val();


        // ajax请求

        $.ajax({
            // 验证当前单号是否存在
            url:'<?=Url::toRoute('ota-version/version_list_sub')?>',
            type:'post',
            dataType:'json',
            data:{'type':type,'pro_id':pro_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code > 0){
                    fail(data.message);return
                }else {
                    // 保存成功
                    succ(data.message,function(){
                        refresh()
                    });
                    //window.location.href='<?//=Url::toRoute('ota-version/version_detail')?>//&ver_id='+data.data;
                }
            }
        })


    })


    function select_status(){
        var url = '<?=url::toRoute('ota-version/version_list')?>';
        var status = $('#status option:selected') .val();//选中的值
        var pro_id = $("input[name='pro_id']").val();
        url += '&status=' + status + '&pro_id='+pro_id ;
        location.href = url;
    }

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('ota-version/version_list')?>';
        // var type = $('#type').val();
        var pro_id = $('#pro_id').val();
        url += '&pro_id=' + pro_id;
        location.href = url;
    });
    function getStrLeng(str){
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

    // 点击删除
    $('.btn-del').click(function(){
        $('.delete').show();
    })
    // 弹框取消按键
    $('.delete').find('.cancel').click(function(){
        $(this).parent().parent().parent().hide();
    })
    // 弹框关闭 按键
    $('.del-box').find('.icon-close').click(function(){
        $(this).parent().parent().parent().hide();
    })

    $('.drop-boxa').click(function(){
        $(this).find('.drop-select').toggle();
    })

    // 点击添加入库单 弹出添加出库单填写框
    $('.add-btn').click(function(){

        var url="<?=url::toRoute('ota-version/version_add')?>";

        var pro_id = $("input[name='pro_id']").val();
        url += '&pro_id='+pro_id ;
        location.href = url;

    })


    /*
    删除产品
     */
    function del(ver_id){
//        $('.confirm').click(function () {
        var user_id = $("#user_id").val();
        var msg="确定要删除吗？";
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('ota-version/version_del')?>',
                type:'post',
                dataType:'json',
                data:{'ver_id':ver_id,'user_id':user_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        setTimeout(refresh(),1600);
                    }
                    else{
                        fail(data.message);
                    }
                }
            })
        }
        //})
    }

    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var ver_id = $(this).attr('ver_id');
        var user_id = $("#user_id").val();
        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('ota-version/version_del')?>',
                type:'post',
                dataType:'json',
                data:{'ver_id':ver_id,'user_id':user_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message);
                        setTimeout(refresh(),1600);
                    }
                    else{
                        fail(data.message);
                    }
                }
            })

        })
    });

</script>
