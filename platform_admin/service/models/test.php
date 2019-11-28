<?php
namespace app\models;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:35
 */

use app\common\core\Model_core;
class test extends Model_core
{

    protected $db = 'db';//默认饮用yii2 db的数据库配置连接

    protected $redisDb = 'redis';//redis 连接的数据库配置

    protected $con_file = 'test';//关联的db与redis文件，默认三个文件名前缀相同

    /**
     * 测试用例 db
     * @param $open_id
     * @return mixed
     */
    public function setOpenid(){


        return $this->dbreader($this->db,$this->con_file)->test();

    }


    /**
     * redis 测试用例
     * @param $open_id
     * @return mixed
     */
    public function setRedisOpenid($open_id)
    {
         return $this->redisreader($this->redisDb,$this->con_file)->setRedisOpenid($open_id);

    }


}