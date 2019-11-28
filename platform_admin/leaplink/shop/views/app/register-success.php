<?php
use yii\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册成功</title>
</head>
<body>
    <div>恭喜注册成功...</div>
    <div>去<a href="<?php Url::toRoute(['app/register-success', []]) ?>">登录</a></div>
</body>
</html>