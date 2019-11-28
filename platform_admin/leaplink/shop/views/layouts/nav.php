<?php
use yii\helpers\Url;
use common\menu\MenuCore;

$rbac_data = MenuCore::$rule_list;

$rbac_own_arr = MenuCore::$menu_key;

$rbac_role_id_list = MenuCore::$role_list;

$_menuCurrentId = MenuCore::$_menuCurrentId;



?>
<!-- 左侧菜单 -->
<ul class="sidebar-menu" data-widget="tree">
    <!-- Optionally, you can add icons to the links -->
    <!--<li class="treeview active menu-open">
        <a href="#"><i class="fa fa-link"></i> <span>设置</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
        <ul class="treeview-menu">
            <li class="active"><a href="<?/*=Url::toRoute('shop/set-merchants')*/?>">商户设置</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#"><i class="fa fa-link"></i> <span>广告</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="#">Link in level 2</a></li>
            <li><a href="#">Link in level 2</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#"><i class="fa fa-link"></i> <span>优惠</span>
            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="#">代金券</a></li>
        </ul>
    </li>
    <li class="active">
        <a href="#">
            <i class="fa fa-link"></i>
            <span>Link</span>
        </a>
    </li>-->

    <?php foreach ($rbac_data as $k=>$v):?>
        <?php  if ($v['is_show'] != 0): ?>

            <?php if(in_array($v['id'],$rbac_own_arr) || in_array(MenuCore::ADMIN_ID,$rbac_role_id_list)):?>
                <?php $active = in_array($v['id'],$_menuCurrentId)?'active menu-open':'';?>

                <li class="treeview <?=$active?>">
                    <a href="#">
                        <i class="fa fa-gear"></i>  <span><?=$v['title']?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                    </a>

                    <?php if(isset($v['children']) && $v['status'] == 1 && $v['is_show'] != 0):?>
                        <ul class="treeview-menu">
                            <?php foreach ($v['children'] as $kks=>$vvs):?>

                                <?php if(in_array($vvs['id'],$rbac_own_arr) || in_array(MenuCore::ADMIN_ID,$rbac_role_id_list)):?><!--2019-04-->
                                    <?php $active = in_array($vvs['id'],$_menuCurrentId)?'active':'';?>

                                    <li class="<?=$active?>">
                                        <a href="<?=Url::toRoute($vvs['route'])?>"><?=$vvs['title']?></a>
                                    </li>
                            <?php endif;endforeach;?>

                        </ul>

                    <?php endif;?>
                </li>
            <?php endif;?>

        <?php endif;?>

    <?php endforeach;?>

</ul>
<!-- 左侧菜单end-->
