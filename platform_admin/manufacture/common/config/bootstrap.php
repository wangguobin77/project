<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-26
 * Time: 10:15
 */
Yii::setAlias('@events', dirname(dirname(__DIR__)) . '/events');

if(YII_CUSTOM_LOG){
    \yii\base\Event::on(yii\db\Command::className(), yii\db\Command::EVENT_PREPARE, [events\AdminLogEvent::className(), 'write']);
}
