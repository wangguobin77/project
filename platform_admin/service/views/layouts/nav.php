<?php
use app\controllers\base\BaseCore;

$rbac_data = BaseCore::$rule_list;

$rbac_own_arr = BaseCore::$menu_key;

$rbac_role_id_list = json_decode(BaseCore::$role_list,true);

$_menuCurrentId = BaseCore::$_menuCurrentId;
/**
 * 导航
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/11
 * Time: 下午5:58
 */

?>
<!-- 左侧菜单 -->
<ul class="sidebar-menu" data-widget="tree">
<?/*=$menu*/?>
    <?php foreach ($rbac_data as $k=>$v):?>
        <?php  if (isset($v['type']) && $v['type'] != 3 && $v['status'] == 1 && $v['pid'] == 0 && $v['is_show'] != 0): ?>
            <?php if(!$v['route']):continue;?>
            <?php endif;?>
            <?php if($v['route'] == 'admin'):$v['route']='rbac'?>
            <?php endif;?>

            <?php $projectName = '/'.$v['route'].'/web/index.php?r=';?>

            <?php if(in_array($v['id'],$rbac_own_arr) || in_array(BaseCore::ADMIN_ID,$rbac_role_id_list)):?>
                 <?php $active = in_array($v['id'],$_menuCurrentId)?'active':'';?>

                        <li class="treeview <?=$active?>">
                            <a href="#">
                            <i class="fa fa-asterisk"></i> <span><?=$v['title']?></span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                            </a>

                             <?php if(isset($v['children']) && $v['status'] == 1 && $v['is_show'] != 0):?>
                                    <ul class="treeview-menu">
                                         <?php foreach ($v['children'] as $kks=>$vvs):if (isset($vvs['is_show']) && $vvs['is_show'] != 0 && $vvs['status'] == 1 && $vvs['type'] != 3):?>

                                        <?php if(in_array($vvs['id'],$rbac_own_arr) || in_array(BaseCore::ADMIN_ID,$rbac_role_id_list)):?><!--2019-04-->
                                             <?php $active = in_array($vvs['id'],$_menuCurrentId)?'active':'';?>

                                                    <li class="treeview <?=$active?>">
                                                        <a href="javascript:;"><i class="fa fa-circle-o"></i><?=$vvs['title']?>
                                                            <span class="pull-right-container">
                                                            <i class="fa fa-angle-left pull-right"></i>
                                                            </span>
                                                        </a>

                                                        <?php if(isset($vvs['children']) && count($vvs['children']) > 0):?>
                                                            <ul class="treeview-menu">
                                                                <?php foreach ($vvs['children'] as $key=>$val):?>
                                                                <?php if(in_array($val['id'],$rbac_own_arr) || in_array(BaseCore::ADMIN_ID,$rbac_role_id_list)):?><!--2019-04-->
                                                                    <?php $active = in_array($val['id'],$_menuCurrentId)?'active':'';?>

                                                                    <?php if (isset($val['is_show'])
                                                                        && $val['is_show'] != 0
                                                                        && $val['status'] == 1
                                                                        && $val['type'] != 3
                                                                        && in_array($val['id'],$rbac_own_arr)):?>

                                                                        <li class="<?=$active?>">
                                                                        <?php $url = $val['route'];?>
                                                                        <?php if(strpos($url,'http://') === 0 || strpos($url,'https://') === 0):?>
                                                                              <a href="<?=$url?>">
                                                                              <?php else:?>
                                                                              <a href="<?=$projectName.$val['route']?>" data-menu-name="角色列表">
                                                                              <?php endif;?>

                                                                             <i class="fa fa-circle-o"></i>
                                                                              <?=$val['title']?>
                                                                             </a>
                                                                        <li>

                                                                    <?php endif;?>
                                                                    <?php endif;?><!--2019-04-->
                                                                <?php endforeach;?>
                                                            </ul>
                                                        <?php endif;?>
                                                    </li>
                                        <?php endif;?><!--2019-04-->
                                         <?php endif;endforeach;?>

                                    </ul>

                             <?php endif;?>
                         </li>
            <?php endif;?>

        <?php endif;?>


    <?php endforeach;?>
</ul>
<!-- 左侧菜单end-->
