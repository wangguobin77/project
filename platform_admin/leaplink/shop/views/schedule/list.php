<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
<?php include_once(NAV_DIR."/header.php");?>
<style type="text/css" media="screen">
    body{overflow-x:hidden;}.warn{color:red!important;}.open{color:#68C743!important;}
</style>
<link rel="stylesheet" href="css/v2/advertising/advertising.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
       <!-- 导航下标注 -->
    <div class="right-box p-b-20 row head-nav">
        <div>
            <section class='row'>
                <span class='yj'>广告</span><span class='yj'>/</span><span class='yj'>广告管理</span>
            </section>
            <h5 class="zhubt">广告管理</h5>
        </div>
    </div>

    <!-- Main content -->
    <section class="content container-fluid">
        <div class='info ' style="background:#fff;padding:24px;flex-grow: 1;">
            <div class="">
                <div class="operate-btn" style="margin:20px 0">
                     <a style="line-height: 26px;"  href="<?=Url::toRoute(['schedule/add','bid'=>$bid])?>" class="btn btn-primary save_adver mid-button">
                        创建计划
                    </a>
                    <a style="line-height: 26px;margin:0;float:right;" href="<?=Url::toRoute('broadcasts/list')?>" class="btn btn-primary  mid-button">
                        返回
                    </a>
                </div>
                 <table class="table ">
                    <thead>
                        <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                            <th width="320px"  class="sl">名称 </th>
                            <th width="320px"  class="sl">有效期</th>
                            <th width="480px"  class="sl">推送日期</th>
                            <th width="320px"  class="sl">推送时间</th>
                            <th width="160px"  class="sl">推送间隔</th>
                            <th width="160px"  class="sl">状态</th>
                            <th width="300px"  class="sl" >操作</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php if($data):?>
                     <?php foreach ($data as $k=>$v):?>
                     <tr>
                         <td class="sl"><?=$v['title']?></td>

                         <td class="sl"><?=date('Y-m-d H:i:s',$v['start_ts']);?>~<?=date('Y-m-d H:i:s',$v['end_ts']);?></td>
                         <?php
                         $week_str = '';
                         if($v['week']) {
                             $week_arr = explode(',', $v['week']);

                             foreach ($week_arr as $kk => $vv) {
                                 $week_str .= Yii::$app->params['week_arr'][$vv] . ',';
                             }
                         }


                         ?>
                         <td class="sl"><?=$week_str?></td>

                         <?php if($v['interval_type'] == 1):?>
                             <td class="sl"><?=$v['send_start_ts'];?>~<?=$v['send_end_ts'];?> </td>
                         <?php else: ?>
                             <td class="sl"><?=$v['send_start_ts'];?></td>
                         <?php endif;?>
                         <td class="sl"><?=$v['interval_ts'];?>s</td>
                         <td class="sl status-box"><span class="<?=$v['type'] == 1?'shengxiao':'caogao'?>"></span><?=$v['type'] == 1?'已开启':'已关闭'?></td>
                         <td class="sl opr-box" >
                             <div class="czuo-box" style='width:fit-content'>
                                 <a  href="<?=Url::toRoute(['schedule/edit','sid'=>$v['id']])?>" class='set-btn edit-btn'>编辑</a>
                                 <div class='xian'></div>
                                 <?php if($v['type'] == 1):?>
                                     <button  class='set-btn sub-btn warn' data-sid="<?=$v['id']?>" data-type="<?=$v['type']?>">关闭</button>
                                 <?php else:?>
                                     <button  class='set-btn sub-btn open' data-sid="<?=$v['id']?>" data-type="<?=$v['type']?>">开启</button>
                                 <?php endif;?>
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
                <ul class="pagination pagination-sm no-margin pull-right">
                    <?= LinkPager::widget([
                        'pagination'    =>  $pages,
                        'nextPageLabel' =>  '下一页',
                        'prevPageLabel' =>  '上一页',
                        'options'   =>  ['class' => 'pagination-sm no-margin pull-right pagination'],
                        'hideOnSinglePage' => false,
                        'maxButtonCount' => 10
                    ]);?>
                </ul>
            </div>
            <!-- 页码end -->
        </div>
    </section>
</div>

<?php include_once(NAV_DIR."/footer.php");?>
<!-- jQuery 3 -->
<script >
    // 开启计划
    $('.sub-btn').click(function(){
        var sid = $(this).attr('data-sid');
        var type = $(this).attr('data-type');
        $.ajax({
            url:'<?=Url::toRoute("schedule/operation")?>',
            type:'post',
            dataType:'json',
            data:{'sid':sid,'type':type,'_csrf':'<?=Yii::$app->request->csrfToken?>'},
            success: function(data) {
                if(data.code==0){
                    succ('操作成功');
                    window.location.href=''
                }else{
                    fail('操作失败')
                }

            }
        });
    })
</script>