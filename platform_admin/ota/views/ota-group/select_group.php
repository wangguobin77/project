<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>

<?php include_once(NAV_DIR."/header.php");?>


<div class="content-wrapper">

    <!-- Main content -->
    <section class="content container-fluid">

        <div class="right-box" style="justify-content: flex-end;">

            <button type="button" class="btn  btn-default"  onclick=javascript:location.href="<?=Url::toRoute(['/ota-package/package_list','ver_id'=>$pack_data['to_ver_id']])?>" >
                返回
            </button>
        </div>
        <div class="">
            <table class="table">
                <thead>
                <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                    <th width="160px"  class="sl">灰度组名称</th>
                    <th width="320px"  class="sl">简单描述				</th>
                    <th width="320px"  class="sl">创建时间		</th>
                    <th width="320px"  class="sl">状态			</th>
                    <th width="160px"  class="sl">SN数量			 </th>
                    <th width="300px"  class="sl" >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(isset($data)):?>
                    <?php foreach ($data as $k=>$item):?>
                        <tr>
                            <td class="sl"><?=$item['group_name']?></td>
                            <td class="sl"><?=$item['description']?></td>
                            <td class="sl"><?php if($item['created_ts'] >0) echo date('Y-m-d H:i:s',$item['created_ts'])?></td>
                            <td class="sl">
                                <?php if(in_array($item['group_id'],$bindGroup)):?>
                                    <span class="label label-success">已关联</span>
                                <?php else:?>
                                    <span class="label label-default">未关联</span>
                                <?php endif;?>
                            </td>
                           <!-- <td class="sl"><a href="<?/*=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id']])*/?>"><?/*=$item['sn_sum']*/?></a></td>-->
                            <td class="sl"><a href="<?=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id']])?>"><?=isset($sum_info[$item['group_id']])?$sum_info[$item['group_id']]:0?></a></td>
                            <td class="sl opr-box" >
                                <div class="czuo-box"  style="width:fit-content">

                                    <?php if(in_array($item['group_id'],$bindGroup)):?>
                                        <a  style="font-size: 12px;" class='cansole-relevance' onclick="set_relation('<?=$item['group_id']?>','<?=$pack_id?>',0)">取消</a>
                                    <?php else:?>
                                        <a style="font-size: 12px;" class='relevance' onclick="set_relation('<?=$item['group_id']?>','<?=$pack_id?>',1)">关联</a>
                                    <?php endif;?>
                                    <span class="xian" ></span>
                                    <a href="<?=url::toRoute(['/ota-group/sn_list','group_id'=>$item['group_id'],'type'=>'1'])?>" >SN列表</a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach;?>
                <?php endif;?>

                </tbody>
            </table>
        </div>
        <!-- 页码 开始 -->
        <nav class="footer" aria-label="..." style="padding-right:24px;" >
            <?= LinkPager::widget([
                'pagination'    =>  $pages,
                'maxButtonCount' => 10, //显示分页数量
                'nextPageLabel' =>  '下一页',
                'prevPageLabel' =>  '上一页',
                'options'   =>  ['class' => 'pages pagination'],

            ]);?>
        </nav>
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
<!-- 删除提示框 -->
<div class="del-box delete">
    <div class="dialog">
        <span class="font_family icon-close cursor"></span>
        <img src="/static/images//warning-large.png" alt="">
        <h6 class="del-title">是否确认删除?</h6>
        <div class="operate-del">
            <div class="cursor cancel"> 取消</div>
            <div class="cursor confirm">确认</div>

        </div>

    </div>
</div>
<script type="text/javascript">
    /**
     * 导航下搜索下拉框
     */
    $('.drop-boxa').click(function(){
        $(this).find('.drop-select').toggle();
    })
    // 点击除了下拉框之外的区域  下拉框消失
    $(document).click(function(e){
        var _con = $('.drop-boxa');   // 设置目标区域
        if(!_con.is(e.target) && _con.has(e.target).length === 0){
            $('.drop-select').css('display','none');
        }
    })

    /**
     * 导航下拉end
     */

    /**
     * 点击取消
     */
    // $('.cansole-relevance').click(function(){
    //     tips_warning('确认取消关联吗？')
    //
    // })
    // $('.relevance').click(function(){
    //     tips_warning('确认要关联吗？')
    //
    // })

    function set_relation(group_id,pack_id,status){

        var msg = '确定要：';

        if(status ==0){
            msg +="取消关联吗？";
        }
        if(status ==1){
            msg +="关联灰度组吗？";
        }

        if(group_id =='' || typeof(group_id) == "undefined"){
            fail('设置项有问题，请检查。');return
        }
        if(pack_id =='' || typeof(pack_id) == "undefined"){
            fail('差分别有问题，请检查。');return
        }

        if(confirm(msg)){
            $.ajax({
                url:'<?=Url::toRoute('ota-group/gray_selected')?>',
                type:'post',
                dataType:'json',
                data:{'group_id':group_id,'pack_id':pack_id,'_csrf-backend':'<?=Yii::$app->request->csrfToken?>'},
                success: function(data) {
                    console.log(data);
                    if(data.code == 0){
                        succ(data.message,function(){
                            refresh()
                        });

                    }
                    else{
                        fail(data.message);
                    }
                }
            })
        }

    }

</script>


