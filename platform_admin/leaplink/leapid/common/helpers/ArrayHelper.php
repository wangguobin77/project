<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-25
 * Time: 12:28
 */

namespace common\helpers;


class ArrayHelper
{
    /**
     * 获取数组中对应键的值
     * 如果数组中不存在该键 则返回NULL
     * 如果获取到的值为空的情况下 同样返回NULL
     * @param array $arr						数组
     * @param mixed $key						键
     * @param mixed $onNotExist					当数组中不存在所有取键时 返回的内容(默认为null)
     * @return mixed
     */
    public static function getNoEmpty( $arr, $key, $onNotExist = null ) {
        return isset($arr[$key]) && $arr[$key] != '' ? $arr[$key] : $onNotExist;
    }
}