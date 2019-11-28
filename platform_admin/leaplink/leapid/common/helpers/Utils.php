<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-12
 * Time: 14:04
 */

namespace common\helpers;

use Yii;

class Utils
{
    /**
     * 获取http请求参数
     */
    public static function getHttpInput(){
        if(Yii::$app->request->isPost){
            $argv = Yii::$app->request->post();
        }else{
            $argv = Yii::$app->request->get();
        }
        return $argv;
    }
}