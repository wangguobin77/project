<?php

namespace common;

/**
 * 返回值类
 *
 */
class Result
{
    /**
     * 返回代码。0代表成功，非0代表失败
     *
     * @var integer
     */
    public $code = 0;

    /**
     * 返回描述。返回失败时需填写描述
     *
     * @var string
     */
    public $message = "OK";

    /**
     * 返回的具体数据
     *
     * @var \ArrayAccess
     */
    public $data = [];

    public function __construct() {
        $this->data = new \ArrayObject();
    }

    /**
     * 响应字符串化
     *
     * @return string
     */
    public function __toString() {
        $ret =  json_encode($this, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $ret;
    }
}