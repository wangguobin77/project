<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 11:38
 */

namespace common\consequence;

/**
 * 接口返回值类
 * Class Result
 * @package common\consequence
 */
class Result
{
    /**
     * 返回码. 0 代表成功, 非 0 代表失败
     * @var integer
     */
    public $code = 0;

    /**
     * 返回描述. 返回失败时需要填写具体描述
     * @var string
     */
    public $message = 'success';

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