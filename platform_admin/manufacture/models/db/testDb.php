<?php

use app\common\core\doHanderDb;
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/11/26
 * Time: 下午3:47
 */
class testDb extends doHanderDb
{
    /**
     * 测试 sp 存储过程
     * @param $openid
     * @return mixed
     */
    public function setOpenid($openid)
    {


        $spname = 'pst_sp_third_login_from_openid_select';

        $res = testDb::$db->createCommand("call ".$spname."('"
            .$openid.
            "')")->queryOne();

        return $res;
    }


    public function test()
    {

        $command = testDb::$db->createCommand("select * from admin_role");
        return $command->queryAll();
    }

}