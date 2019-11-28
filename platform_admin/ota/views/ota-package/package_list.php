<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>


<?php include_once(NAV_DIR."/header.php");?>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
            <!-- 导航条 -->
        <div class="right-box p-b-20 row">
            <ol class="breadcrumb">
                <li><?php include_once(NAV_DIR."/bottom-menu.php");?></li>
                <span>
                    <button type="button" class="btn  btn-default"  style="margin-top: -7px;" onclick=javascript:location.href="<?=Url::toRoute('ota-version/version_list')?>&pro_id=<?=$ver_data['pro_id']?>" >
                        返回
                    </button>
                </span>
            </ol>
        </div>
        <!-- 導航條end -->

        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">序号</th>
                    <th width="320px"  class="sl">起始版本号				</th>
                    <th width="320px"  class="sl">创建时间		</th>
                    <th width="160px"  class="sl">发布时间			 </th>
                    <th width="160px"  class="sl">版本状态			 </th>
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data)):?>
                    <?php foreach ($data as $k=>$item):?>
                        <tr>
                            <td class="sl"><?=$k+1?></td>
                            <td class="sl">
                                <?php if(isset($item['is_full']) && $item['is_full'] > 0):?>
                                    之前所有版本
                                <?php else:?>
                                   <!-- --><?/*=$item["from_ver_name"]*/?>
                                    <?=$ver_list[$item['from_ver_id']]?>
                                <?php endif;?>

                            </td>


                            <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                            <td class="sl"><?=intval($item['updated_ts'])?date('Y-m-d H:i:s',$item['updated_ts']):0?></td>
                            <td  >
                                <?php if($item['status']=='-1'){
                                    echo '<span class="label label-danger">禁用</span>';
                                }elseif($item['status']=='0'){
                                    echo '<span class="label label-default">未发布</span>';
                                }elseif($item['status']=='1'){
                                    echo '<span class="label label-warning">灰度测试中</span>';
                                }elseif($item['status']=='2'){
                                    echo '<span class="label label-success">已测试</span>';
                                }elseif($item['status']=='3'){
                                 echo '<span class="label label-primary">已发布</span>';
                                }
                                ?>
                            </td>
                            <td class="sl opr-box" >
                                <div class="czuo-box"  style="width:fit-content">
                                    <?php if($item['status']=='3'):?>
                                        <a href="<?=url::toRoute(['package_detail','pack_id'=>$item['sp_pack_id'],'ver_id'=>$ver_id])?>">查看</a>
                                        <span class="xian"></span>
                                    <?php else:?>
                                       <!-- <a href="<?/*=url::toRoute(['package_edit','pack_id'=>$item['sp_pack_id'],'ver_id'=>$ver_id])*/?>" >编辑</a>-->
                                    <?php endif;?>
                                   <!-- <span class="xian"></span>-->
                                    <?php if($item['status']=='-1'):?>
                                        <a href="#"  onclick="return modify_status('<?=$item['sp_pack_id']?>','0')">启用</a>
                                    <?php else:?>
                                        <a href="#" onclick="return modify_status('<?=$item['sp_pack_id']?>',-1)">禁用</a>
                                    <?php endif;?>
                                     <span class="xian"></span>

                                    <?php if($item['status']=='0'):?>
                                        <a href="<?=url::toRoute(['ota-group/select_group','pack_id'=>$item['sp_pack_id']])?>"  >提交测试</a>
                                    <?php elseif($item['status']=='1'):?>
                                        <a href="<?=url::toRoute(['ota-group/select_group','pack_id'=>$item['sp_pack_id']])?>" >灰度组</a>
                                        <span class="xian"></span>
                                        <a href="#" onclick="return modify_status('<?=$item['sp_pack_id']?>',2)" >通过测试</a>
                                        <span class="xian"></span>
                                    <?php elseif($item['status']=='2'):?>
                                        <a href="#" onclick="return modify_status('<?=$item['sp_pack_id']?>',3)" >对外发布</a>
                                        <span class="xian"></span>
                                    <?php endif;?>
                                    <!--删除差分包-->
                                    <?php if($item['status']==0):?><!--已发布-->
                                    <span class="xian"></span>
                                    <a class="btn-del" pack_id="<?=$item['sp_pack_id']?>" >删除</a>
                                    <span class="xian"></span>
                                <?php /*else:*/?><!--
                                    <span class="xian"></span>
                                    <a  onclick="del('<?/*=$item['pack_id']*/?>')" style="font-size: 12px;color: #1890ff">删除</a>-->
                                <?php endif;?>

                                    <?php  if($item['status'] != '-1'):?>
                                         <!-- <span class="xian"></span> -->
                                        <!--<a href="<?/*=url::toRoute(['sync-data','sp_pack_id'=>$item['sp_pack_id'],'ver_id'=>$ver_id])*/?>" >同步缓存数据</a>-->
                                        <a href="#" onclick="syncData(<?=$item['sp_pack_id']?>,<?=$ver_id?>)" >同步缓存数据</a>
                                    <?php endif;?>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>

                </tbody>

                <input type="hidden" name="ver_id" value="<?=$ver_id?>">
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
<script src="/static/js/public/select2.full.min.js"></script>
<script type="text/javascript">
    $('.select2').select2()
    $("#checkAll").bind("click", function () {

        if($(this).attr("checked") == 'checked'){
            $("input[name='ck']:checkbox").attr("checked", true);
        } else {
            $("input[name = 'ck']:checkbox").attr("checked", false);
        }
    });

    function select_status(){
        var url = '<?=url::toRoute('ota-package/package_list')?>';
        var status = $('#status option:selected') .val();//选中的值
        var ver_id = $("input[name='ver_id']").val();
        url += '&status=' + status + '&ver_id='+ver_id ;
        location.href = url;
    }

    //搜索
    $('#search-product').click(function(){
        var url = '<?=url::toRoute('ota-package/package_list')?>';
        var version_data_json = $("input[name='version_data_json']").val();
        var status = $('#status option:selected') .val();//选中的值
        url += '&status=' + status + '&data_json=' + version_data_json;
        location.href = url;
    });

    function modify_status(pack_id,status) {

        var msg = '确定要：';
        if(status =='-1'){
            msg +="禁用吗？";
        }
        if(status =='0'){
            msg +="启用吗？";
        }
        if(status =='1'){
            msg +="提交测试吗？";
        }
        if(status =='2'){
            msg +="通过测试吗？";
        }
        if(status =='3'){
            msg +="发布吗？";
        }
        $('.delete').find('del-title').html(msg);
        var ver_id = $("input[name='ver_id']").val();
        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('ota-package/set_version_status')?>',
                type:'post',
                dataType:'json',
                data:{'pack_id':pack_id,'status':status,'ver_id':ver_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code == 0){
                        succ(data.message,function(){
                        refresh()
                    });
                    }
                    else{
                        fail(data.message);return
                    }
                }
            })
        }
    }

    var glo_val = {
        'istrue':true
    }
    /**
     * 同步数据
     * @param sp_pack_id
     * @param ver_id
     * @returns {boolean}
     */
    function syncData(sp_pack_id,ver_id)
    {
        if(glo_val.istrue == false) return false;

        glo_val.istrue = false;

        $.ajax({
            url:'<?=Url::toRoute('ota-package/sync-data')?>',
            type:'post',
            dataType:'json',
            data:{'sp_pack_id':sp_pack_id,'ver_id':ver_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                glo_val.istrue = true;
                if(data.code == 0){
                    succ(data.message);
                }
                else{
                    fail(data.message);return
                }
            }
        })

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
        var ver_id = $("input[name='ver_id']").val();

        window.location.href="<?=url::toRoute('ota-package/package_add')?>&ver_id="+ver_id;

    })


    $('.btn-del').click(function(){
        //var user_id = $("#user_id").val();
        var pack_id = $(this).attr('pack_id');
        var user_id = $("#user_id").val();
        $('.del-box').show();

        $('.del-box').find('.confirm').unbind('click').click(function(){
            $('.del-box').hide();
            $.ajax({
                url:'<?=Url::toRoute('ota-package/package_del')?>',
                type:'post',
                dataType:'json',
                data:{'pack_id':pack_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    if(data.code==0){
                        succ(data.message,function(){
                        refresh()
                    });
                    }
                    else{
                        fail(data.message);
                    }
                }
            })

        })
    });

</script>
