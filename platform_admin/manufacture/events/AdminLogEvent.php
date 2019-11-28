<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-26
 * Time: 09:58
 */

namespace events;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class AdminLogEvent
{
    public static $sqlarr = [];

    public static $instance = null;

    public static function className(){
        return get_called_class();
    }

    public static function write($event){
        $sql = $event->sender->getRawSql();

        self::$sqlarr[] = $sql;
    }

    public static function insert($agv){
        $sql = '';
        if(!empty(self::$sqlarr)){
            foreach (self::$sqlarr as $k => $v){
                $sql .= $v . "\n";
            }
        }
        $pa = $_REQUEST;
        unset($pa['r']);
        unset($pa['_csrf']);

        $params[':act'] = isset($agv[0])?$agv[0]:''; //操作
        $params[':admin_id'] = $_SESSION['uid']['uid'];
        $params[':ip'] = Yii::$app->request->userIP;
        $params[':params'] = json_encode($pa);
        $params[':description'] = isset($agv[1])?$agv[1]:'';
        $params[':sql'] = $sql;
        Yii::$app->adminLog->open();
        Yii::$app->adminLog->pdo->prepare('insert into working (`act`,`admin_id`,`ip`,`params`,`description`,`sql`) values (:act,:admin_id,:ip,:params,:description,:sql)')
            ->execute($params);
    }
}