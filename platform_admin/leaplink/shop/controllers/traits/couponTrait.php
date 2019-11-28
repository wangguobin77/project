<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-15
 * Time: 15:16
 */

namespace app\controllers\traits;

use common\helpers\RedisHelper;

/**
 * 优惠券特性
 * Trait couponTrait
 * @package app\controllers\traits
 */
trait couponTrait
{
    /*
     * 优惠券各种缓存前缀(hash)
     */
    private $info_key = "leap:coupon:info"; //详细信息 key

    /*
     * 条件(集合)
     */
    private $search_key_pre = "leap:coupon:condition"; //所属商户 key

    /**
     * 需要缓存的条件
     * k=>v  k数据中存在的key, v集合拼接用key
     */
    private $search_arr = [
        'shop_id' => 'shop',
    ];

    /*
     * 排序(有序集合)
     */
    private $sort_key_pre = "leap:coupon:sort"; //创建时间 key

    /**
     * 需要缓存的排序
     * k=>v  k数据中存在的key, v集合拼接用key
     */
    private $sort_arr = [
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    /**
     * 缓存优惠券信息
     * @param int $coupon_id 优惠券 id
     * @param array $info 优惠券信息
     * @return boolean|string
     */
    public function setCacheInfo($coupon_id, $info)
    {
        if(empty($info)) return false;

        $command[] = $this->info_key . ':__cache__' . $coupon_id;
        foreach ($info as $field => $item){
            $command[] = $field;
            $command[] = is_string($item) ? $item : json_encode($item);
        }

        $ret = RedisHelper::getRedis()->executeCommand('hmset', $command);
        return $ret;
    }

    /**
     * 缓存条件包含优惠券
     * @param array $collection 优惠券数组
     * @param string $pk 缓存的主键 key
     * @param array $unCacheKey 不缓存的条件字段
     * @return bool
     */
    public function setCacheSearch($collection, $pk = 'id', ...$unCacheKey)
    {
        if(empty($collection)) return false;
        if(!isset($collection[$pk])) return false;

        $ret = false;
        foreach ($this->search_arr as $key => $value) {
            if(!isset($collection[$key]) || in_array($key, $unCacheKey)) continue;

            $command = [];
            $command[] = $this->search_key_pre . ":$value:__cache__" . $collection[$key];
            $command[] = $collection[$pk];

            $ret = RedisHelper::getRedis()->executeCommand('sadd', $command);
        }

        return $ret;
    }

    //删除条件集合中成员
    public function delMembers($collection, $pk = 'id', ...$unCacheKey)
    {
        if(empty($collection)) return false;
        if(!isset($collection[$pk])) return false;

        $ret = false;
        foreach ($this->search_arr as $key => $value) {
            if(!isset($collection[$key]) || in_array($key, $unCacheKey)) continue;

            $command = [];
            $command[] = $this->search_key_pre . ":$value:__cache__" . $collection[$key];
            $command[] = $collection[$pk];

            $ret = RedisHelper::getRedis()->executeCommand('srem', $command);
        }

        return $ret;
    }

    //设置排序缓存
    public function setCacheSort($collection, $pk = 'id', ...$unCacheKey)
    {
        if(empty($collection)) return false;
        if(!isset($collection[$pk])) return false;

        $ret = false;
        foreach ($this->sort_arr as $key => $value) {
            if(!isset($collection[$key]) || in_array($key, $unCacheKey)) continue;

            $command = [];
            $command[] = $this->sort_key_pre . ":$value:__cache__";
            $command[] = $collection[$key];
            $command[] = $collection[$pk];

            $ret = RedisHelper::getRedis()->executeCommand('zadd', $command);
        }

        return $ret;
    }
}