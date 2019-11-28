<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/1/9
 * Time: 上午11:09
 */

namespace app\models;

use Yii;
class Oauth2 extends \yii\db\ActiveRecord
{
    /**
     * mmm
     * 创建code 成功后 系统自动创建openid 对应userid 的关系表
     * @param $userid
     * @param $nickname
     * @param $sex
     * @param $avatar
     */
    public function setTposInfoFromDb($userid,$client_id)
    {
        $userinfo = json_decode($this->getUserinfoToRedis($userid),true);//获取登陆时的用户缓存信息

        //先查询关系表是否已经有数据存在  存在不需要新建openid
        if($userinfo){

            $this->setUserinfoToRedis($userid,['uid'=>$userid,'openid'=>$userinfo['openid'],'client_id'=>$client_id]);

        }else{

            //添加新的用户关系信息
            $info = [
              'uid' => $userid,
              'openid' => createGuid(),
              'client_id' => $client_id,
            ];//目前只需要存储这些信息  后面带着扩展
            $this->setUserinfoToRedis($userid,$info);

        }
        //var_log([$userid,$nickname,$sex,$avatar,$status_msg],'oauth-com');//公用log

    }



    /**
     * mm
     * 将登陆的oauth的用户信息 存储redis
     * @param $userid
     * @param $userinfo
     */
    public function setUserinfoToRedis($userid,$userinfo)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_login_userinfo:'.$userid;
        $redis->set($key,json_encode($userinfo));//缓存redis
    }

    /**
     * 获取用户与oauth的等路信息
     * @param $userid
     * @return mixed
     */
    public function getUserinfoToRedis($userid)
    {
        $redis = Yii::$app->redis;

        $key = 'oauth_login_userinfo:'.$userid;
        return $redis->get($key);//缓存redis

    }

}