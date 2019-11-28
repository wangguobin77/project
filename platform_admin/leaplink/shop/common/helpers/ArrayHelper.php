<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-25
 * Time: 12:28
 */

namespace common\helpers;

/**
 * 数组工具类
 */
class ArrayHelper
{
    /**
     * 获取数组中对应键的值
     * @param array $arr						数组
     * @param mixed $key						键
     * @param mixed $onNotExist					当数组中不存在所有取键时 返回的内容(默认为null)
     * @return mixed
     */
    public static function get( $arr, $key, $onNotExist = null ) {
        return isset($arr[$key]) ? $arr[$key] : $onNotExist;
    }

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


    /**
     * 数组 转 对象
     *
     * @param array $arr 数组
     * @return object
     */
    public static function array_to_object($arr) {
        if (gettype($arr) != 'array') {
            return;
        }
        foreach ($arr as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object') {
                $arr[$k] = (object)self::array_to_object($v);
            }
        }

        return (object)$arr;
    }

    /**
     * 对象 转 数组
     *
     * @param object $obj 对象
     * @return array
     */
    public static function object_to_array($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)self::object_to_array($v);
            }
        }

        return $obj;
    }
}