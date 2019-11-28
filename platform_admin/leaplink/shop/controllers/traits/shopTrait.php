<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-26
 * Time: 11:39
 */

namespace app\controllers\traits;

use Yii;
use app\models\shop\ARShop;
use app\models\shop\ARResource;
use common\helpers\RedisHelper;
/**
 * 商户相关特性
 * Class shopTrait
 * @package app\controllers\traits
 */
trait shopTrait
{
    /**
     * 获取商户缓存信息
     * @param $shop_id
     * @param mixed ...$name 可变参数-域,如果该参数不为 null,则取出该缓存中域{$name}的值,否则取出所有
     * @return mixed
     */
    public function getShopCache($shop_id, ...$name)
    {
        $returnVal = null;
        $key = Yii::$app->params['cache_key_prefix']['shop_info'] . $shop_id;

        if([] !== $name) {
            $resVal = RedisHelper::getRedis()->hmget($key, ...$name);
            if(!empty($resVal)){
                foreach ($name as $key => $item){
                    $returnVal[$item] = $resVal[$key];
                }
            }

        }else{
            $resVal = RedisHelper::getRedis()->hgetall($key);
            $c = count($resVal);
            for ($i = 0; $i < $c; $i++){
                $returnVal[$resVal[$i]] = $resVal[$i+1];
                $i++;
            }

        }
        return $returnVal;
    }

    /**
     * 缓存商户所有信息
     * @param integer $shop_id 商户 id
     */
    public function setShopCacheAll($shop_id){
        $shopCache = ARShop::find()
            ->with('resourceRelations')
            ->where(['id' =>$shop_id])
            ->asArray()
            ->one();
        foreach ((array)$shopCache['resourceRelations'] as $item) {
            $resource = $item['resource'];
            if($resource['position_type'] == ARResource::POSITION_TYPE1) {
                $shopCache['logo'][] = $resource;
            }elseif ($resource['position_type'] == ARResource::POSITION_TYPE2) {
                $shopCache['plate'][] = $resource;
            }elseif ($resource['position_type'] == ARResource::POSITION_TYPE3) {
                $shopCache['license'][] = $resource;
            }elseif ($resource['position_type'] == ARResource::POSITION_TYPE4) {
                $shopCache['certificate'][] = $resource;
            }
        }
        unset($shopCache['resourceRelations']);
        $this->setShopCache($shop_id, $shopCache);
    }

    /**
     * 这是商户缓存
     * 备注:这里虽然无差别将商户所有信息存入缓存,有些信息不能使用缓存
     * 如: password,salt,...
     * @param $shop_id
     * @param $shopInfo
     */
    public function setShopCache($shop_id, $shopInfo)
    {
        $key = Yii::$app->params['cache_key_prefix']['shop_info'] . $shop_id;

        $command[] = $key;
        foreach ($shopInfo as $field => $item){
            $command[] = $field;
            $command[] = is_string($item) ? $item : json_encode($item);
        }

        $ret = RedisHelper::getRedis()->executeCommand('hmset', $command);
        return $ret;
    }



}

