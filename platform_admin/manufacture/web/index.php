<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

defined('SERVER_PATH') or define('SERVER_PATH',$_SERVER['DOCUMENT_ROOT']);//服务器根u

defined('ROOT_PATH') or define('ROOT_PATH', __DIR__.'/');

defined('STATIC_DIR') or define('STATIC_DIR', '.');//当前目录

defined('NAV_DIR') or define('NAV_DIR', __DIR__ . '/../views/layouts');//当前导航内容


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

//是否开启自定义全局日志
defined('YII_CUSTOM_LOG') or define('YII_CUSTOM_LOG', false);
//初始化引导
require(__DIR__ . '/../common/config/bootstrap.php');


require(__DIR__ . '/../common/helpers/fun.php');
$config = require(__DIR__ . '/../config/web.php');

$Application = new yii\web\Application($config);

//判断连接上是否设置语言

if(isset($_GET['language']) && in_array(strtolower($_GET['language']),Yii::$app->params['language_all'])){
    $_COOKIE['language'] = strtolower($_GET['language']);
    $Application->language = strtolower($_GET['language']);
}else if(isset($_COOKIE['language'])){
    $Application->language = strtolower($_COOKIE['language']);
}else{
    $Application->language = 'en';//默认为英文
}
$Application->run();

