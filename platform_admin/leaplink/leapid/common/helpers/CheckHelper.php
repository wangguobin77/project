<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 14:01
 */

namespace common\helpers;

/*
 * 常规校验助手类
 */
class CheckHelper
{
    /**
     * 非空验证
     * @param $value string
     * @return boolean
     */
    public static function unEmptyValidate($value)
    {
        return !static::isEmpty($value);
    }

    private static function isEmpty($value)
    {
        return $value === null || $value === '';
    }

    /**
     * 手机号验证
     * @param $value string
     * @return boolean
     */
    public static function phoneValidate($value)
    {
        $check = '/^(1(([35789][0-9])|(47)))\d{8}$/';
        if (preg_match($check, $value)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $value string 比较的值
     * @param $compareValue string 被比较的值
     * @param string $operator 比较方式
     * @return boolean
     */
    public static function compareValuesValidate($value, $compareValue, $operator = '==') {
        switch ($operator) {
            case '==':
                return $value == $compareValue;
            case '===':
                return $value === $compareValue;
            case '!=':
                return $value != $compareValue;
            case '!==':
                return $value !== $compareValue;
            case '>':
                return $value > $compareValue;
            case '>=':
                return $value >= $compareValue;
            case '<':
                return $value < $compareValue;
            case '<=':
                return $value <= $compareValue;
            default:
                return false;
        }
    }

    /**
     * @param string $passwordInput     用户输入的密码
     * @param string $password          服务器存储密码
     * @param string $salt              加盐
     * @return bool
     */
    public static function passwordValidate($passwordInput, $password, $salt)
    {
        $encryptPwd = StringsHelper::hashPwd($passwordInput, $salt)[0];
        return self::compareValuesValidate($encryptPwd, $password);
    }

}