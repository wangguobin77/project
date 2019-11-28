<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-19
 * Time: 11:02
 */

namespace common\exception;

/*
 * 混合异常类
 */
class FixedException extends GanWuException
{
    public function getName()
    {
        return "FixedException";
    }
}