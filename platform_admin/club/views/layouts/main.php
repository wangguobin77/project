<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use ota\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <title>SENSEPLAY</title>
    <!-- 全局的提示样式 -->
    <link rel="stylesheet" type="text/css" href="/css/ts.css">
    <script type="text/javascript" src="/js/public/jquery-3.1.1.min.js"></script>
    <script src="/js/public/ts.js"></script>
</head>
<body class="drawer drawer--left o_web_client drawer-close">

<!--导航开始-->
<?php include_once(NAV_DIR."/nav.php");?>
<!--导航结束-->

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<?= Alert::widget() ?>
<?= $content ?>



<!-- 成功提示 -->
<div class="ts-box succ">
    <div class="ts-xx">
        <span class='font_family icon-judge_success_small'></span>
        <p>保存成功</p>

    </div>
</div>

<!-- 失败提示 -->
<div class="fail-ts ">
    <div class="ts-xx">
        <span class='font_family icon-warning_large'></span>
        <p>保存失败，请重新尝试！</p>

    </div>
</div>
<script type="text/javascript" src="/js/public/ts.js"></script>
</body>
</html>
<script type="text/javascript">

    // // 导航下拉菜单处理
    // $('.userinfo-box').click(function(){
    //     $(this).find('ul.dropdown-menu').toggle();
    // })
    // // 点击除了下拉框之外的区域  下拉框消失
    // $(document).click(function(e){
    //     var _con = $('.userinfo-box');   // 设置目标区域
    //     if(!_con.is(e.target) && _con.has(e.target).length === 0){
    //         $('.dropdown-menu').css('display','none');
    //     }
    // })
</script>
