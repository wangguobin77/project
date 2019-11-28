<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/12/24
 * Time: 下午3:51
 */
use yii\helpers\Url;
?>
<?php include_once(NAV_DIR."/header.php");?>
<link rel="stylesheet" href="/static/css/keymap/createBataVer.css">
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <div id='factory-list' class="">
        <h3 class='sl'><?=Yii::t('app','VERSION_NUMBER')?></h3>

        <form action="<?=Url::toRoute(['keymap/add_keymap'])?>" method="post" onsubmit="return ver_form(this);" class='creat-form'>
            <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
            <input type="hidden" name="remote_type_id" value="<?=$remote_type_id?>">
            <input type="hidden" name="category_id" value="<?=$category_id?>">
            <input type="hidden" name="k_type_id" value="1">
            <!-- 版本选择 -->
            <div class="sel-ver-box">
                <div class="ver-num col-md-3 col-sm-6 cool-xs-12">
                <input class='check_version_input' type="radio" onclick="btn_cut_ver(this)" name="version" value="b">

                    <h5 class="sl"><?=$b_ver?></h5>
                    <img src="../../keymap/web/images/shengji.jpg" alt="">
                    <span class="sl"><?=$ver?></span>
                    <a href="javascript:;" class="ver-box-num">
                        <?=Yii::t('app','BIG_VERSION')?>
                    </a>
                </div>
                <div class="ver-num col-md-3 col-sm-6 cool-xs-12">
                <input class='check_version_input' type="radio" onclick="btn_cut_ver(this)" name="version" value="m">
                    <h5 class="sl"><?=$m_ver?></h5>
                    <img src="../../keymap/web/images/shengji.jpg" alt="">
                    <span class="sl"><?=$ver?></span>
                    <a href="javascript:;" class="ver-box-num">

                        <?=Yii::t('app','MEDIUM_VERSION')?>
                    </a>
                </div>

                <div class="ver-num col-md-3 col-sm-6 cool-xs-12">
                <input class='check_version_input' type="radio" onclick="btn_cut_ver(this)" name="version" value="s">
                    <h5 class="sl"><?=$s_ver?></h5>
                    <img src="../../keymap/web/images/shengji.jpg" alt="">
                    <span class="sl"><?=$ver?></span>
                    <a href="javascript:;" class="ver-box-num">

                        <?=Yii::t('app','SMALL_VERSION')?>
                    </a>
                </div>

            </div>

            <!-- 开始设置按钮 -->
            <div class="row">
                <button class="start-set setting"><?=Yii::t('app','START_SETTING')?></button>
            </div>
        </form>



    </div>
</div>

<!-- /.content-wrapper end-->
<?php include_once(NAV_DIR."/footer.php");?>
<script>
      //验证提交表单
  function ver_form(obj)
    {
        var v = false;
        for(i=0;i<$('input[type="radio"]').length;i++){
            if($('input[type="radio"]').get(i).checked){
                v = true;
            }
        }
        if(v){
            return true;
        }
        return false;

    }
      //x选择大类切换图片
      function btn_cut_ver(obj)
    {
        $('.ver-num ').removeClass('ver-active');
        $(obj).parent().addClass('ver-active');

    }
    </script>

