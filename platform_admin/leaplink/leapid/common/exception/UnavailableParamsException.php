<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 11:34
 */

namespace common\exception;

/*
 * 参数错误异常类
 */
class UnavailableParamsException extends GanWuException
{
    public function getName()
    {
        return "UnavailableParamsException";
    }
}