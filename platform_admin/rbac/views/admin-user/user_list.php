<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use app\models\AdminRole;
use app\models\AdminBranch;

use app\models\AdminPosition;

$positionList = (new AdminPosition())->getPositionIdInfo();//获取所有职位

$AdminBranch = (new AdminBranch());

?>
<?php include_once(NAV_DIR."/header.php");?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content container-fluid">
            <div class="right-box p-b-20">
                <a href="<?=Url::toRoute('admin-user/create')?>">
                <button type="button" class="btn  btn-primary">
                    新增
                </button>
                </a>
            </div>
            <div class="">
                <table class="table">
                     <thead>
                        <tr style=" background-color: rgba(0, 0, 0, 0.09); border: solid 1px rgba(0, 0, 0, 0.15);">
                            <th width="160px"  class="sl">员工工号</th>
                            <th width="320px"  class="sl">员工姓名</th>
                            <th width="480px"  class="sl">手机号</th>
                            <th width="160px"  class="sl">员工状态 </th>
                            <th width="270px"  class="sl">职位 </th>
                            <th width="272px"  class="sl">操作</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($model as $v):?>
                            <tr>
                                <td class="sl"><?=$v['work_number']?></td>
                                <td class="sl"><?=$v['real_name']?></td>
                                <td class="sl"><?=$v['mobile']?></td>
                                <?php if($v['status'] == 1):?>
                                    <td class="sl zz-box"><span class="zaizhi rk">在职</span></td>
                                <?php else:?>
                                    <td class="sl zz-box"><span class="btn-danger">离职</span></td>
                                <?php endif;?>
                                <td class="sl">
                                    <?php
                                        if($v['position_id'] === 0) {
                                            echo '系统超级用户';
                                        } else if($v['position_id']) {
                                            echo $v['position_name'];
                                        } else {
                                            echo '暂无职位';
                                        }
                                    ?>
                                </td>
                                <td class="sl opr-box" >
                                    <div class="czuo-box">
                                        <a href="<?=Url::toRoute(['admin-user/update', 'id' => $v['id']])?>">编辑信息</a>
                                        <span class='xian'></span>
                                        <a href="<?=Url::toRoute(['admin-user/set-role', 'id' => $v['id']])?>" class='set-btn'>角色设置</a>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                     </tbody>
                </table>
            </div>
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

        </section>
      </div>
      <!-- /.content-wrapper end-->

<?php include_once(NAV_DIR."/footer.php");?>

