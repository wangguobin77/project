<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <div style="display:flex;justify-content:flex-end">
            <div class="col-md-3">
                <div class="form-group col-md-12 input-xx">
                    <label class='col-md-4 title'></label>
                    <select name="position_id" id="type" onchange="select_type()" class="form-control select2 select2-hidden-accessible col-md-9" style="width: 100%;" tabindex="-1" aria-hidden="true">
                        <option value="" >请选择</option>
                        <?php foreach ($types as $k=>$item):?>
                            <option value="<?=$k?>" <?php if($k==$pro_type) {echo 'selected';} ?> ><?=$item?></option>
                        <?php endforeach;?>
                    </select>

                </div>
            </div>

            <div class="col-md-2" style='float: right;text-align:right'>
                <button class="btn btn-primary add-btn">增加产品</button>
            </div>
        </div>

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                   <!-- <th width="320px"  class="sl">ID</th>-->
                    <th width="320px"  class="sl">产品名称		</th>
                    <th width="320px"  class="sl">产品CODE	</th>
                    <th width="320px"  class="sl">产品类型	</th>
                    <th width="160px"  class="sl">创建时间	 </th>
                    <th width="160px"  class="sl">更新时间	 </th>
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data)):?>
                    <?php foreach ($data as $k=>$item):?>
                        <tr>
                           <!-- <td class="sl"><?/*=$item['pro_id']*/?></td>-->
                            <td class="sl"><?=$item['pro_name']?></td>
                            <td class="sl"><?=$item['pro_code']?></td>
                            <td class="sl">
                              <!--  --><?/*=$item['type_name']*/?>
                                <?=Yii::$app->params['product_type'][$item['type']]?>
                            </td>
                            <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                            <td class="sl"><?=($item['updated_ts'])?date('Y-m-d H:i:s',$item['updated_ts']):0?></td>
                            <!--<td class="sl"><?/*=$item['staff_name']*/?> </td>-->

                            <td class="sl opr-box" >
                                <div class="czuo-box"  style="align-items: center;justify-content: flex-start;">
                                    <a href="<?=url::toRoute(['product_edit','pro_id'=>$item['pro_id']])?>" class="font_family">
                                        <span style="font-size: 13px;">编辑</span>
                                    </a>
                                    <span class="xian"></span>
                                    <a class="font_family btn-del " pro_id="<?=$item['pro_id']?>">
                                        <span style="font-size: 13px;">删除</span>
                                    </a>
                                    <span class="xian"></span>
                                    <a class="font_family " href="<?=url::toRoute(['ota-version/version_list'])?>&pro_id=<?=$item['pro_id']?>" ><span style="font-size: 13px;">版本列表</span></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>


                </tbody>
            </table>
        </div>

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
        <input type="hidden" name="user_id" value="<?=$user_id?>">

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




    <!-- 删除提示框 -->
    <div class="del-box delete">
        <div class="dialog">
            <span class="font_family icon-close cursor"></span>
                <img src="/static/images//warning-large.png" alt="">
                <h6>是否确认删除?</h6>
            <div class="operate-del">
                <div class="cursor cancel"> 取消</div>
                <div class="cursor confirm">确认</div>

            </div>

        </div>
    </div>



<?php include_once(NAV_DIR."/footer.php");?>
<script src="/static/js/public/select2.full.min.js"></script>
<script type="text/javascript">
    $('.select2').select2()
    // 删除
    $('.btn-del').click(function(){
        $('.delete').show();
    })
    function select_type(){
        var url = '<?=url::toRoute('ota-product/product_list')?>';
        var type = $('#type option:selected') .val();//选中的值
        url += '&pro_type=' + type ;
        location.href = url;
    }

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('ota-product/product_list')?>';
        var pro_name = $("input[name='product_name']").val();

        var name_length = getStrLeng(pro_name);
        if( name_length > 128 ) {
            fail('字符过长，请重新输入！');return
        }
        var type = $('#type option:selected') .val();//选中的值
        url += '&pro_type=' + type + '&pro_name=' + pro_name;
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

    // 点击添加
    $('.add-btn').click(function(){
        var url = "<?=url::toRoute('ota-product/add-product')?>";
        window.location.href=url;
    })

    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var pro_id = $(this).attr('pro_id');

        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('ota-product/product_del')?>',
                type:'post',
                dataType:'json',
                data:{'pro_id':pro_id,'user_id':99,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message,'<?=Url::toRoute('ota-product/product_list')?>');
                        // setTimeout(refresh(),3000);
                    }else{
                        fail(data.message);
                    }

                }
            })

        })
    });


    /*成功or失败or删除显示*/
    var sess_v = '<?=get_ses_data("data")?>';
    if(sess_v){
        succ(sess_v);
    }


</script>
