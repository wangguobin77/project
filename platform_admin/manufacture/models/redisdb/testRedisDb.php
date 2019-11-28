<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:47
 */
use app\common\core\doHanderRedis;
class testRedisDb extends doHanderRedis
{
    /**
     * 测试代码
     * @param $open_id
     * @return mixed
     */
    public function setRedisOpenid($open_id){
        return testRedisDb::$redis->set('mkmk', $open_id);
    }
}