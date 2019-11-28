<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 11:49
 */

namespace common\consequence;


class ErrorCode
{
    //外部error code
    const SUCCEED = 0;

    /** 异常 */
    const ERROR = -1;

    /** 通用错误  100000 - 100199 预留 */
    /** 必传参数 */
    const REQUIRED_PARAMS = 100000;
    /** 错误的参数格式 */
    const CORRECT_FORMAT = 100001;


    /** 账号类错误 100200 - 100209 */
    /** 账号不能为空 */
    const UN_EMPTY_ACCOUNT = 100200;


    /** 用户名类错误 100300 - 100309 */
    /** 商户名称不能为空 */
    const UN_EMPTY_USER_NAME = 100300;


    /** 手机号类错误 100400 - 100409 */
    /** 手机号不正确 */
    const CORRECT_PHONE = 100400;
    /** 手机号已存在 */
    const EXISTS_PHONE = 100401;
    /** 手机号不存在 */
    const NOT_EXISTS_PHONE = 100402;


    /** 密码类错误 100500 - 100509 */
    /** 密码不能为空 */
    const UN_EMPTY_PASSWORD = 100500;
    /** 两次输入密码不一致 */
    const INCONSISTENT_PASSWORD = 100501;
    /** 密码错误 */
    const CORRECT_PASSWORD = 100502;


    /** 验证码类错误 100600 - 100609 */
    /** 验证码不能为空 */
    const UN_EMPTY_CODE = 100600;
    /** 验证码不正确 */
    const CORRECT_CODE = 100601;


    /** 其他类错误 101000 - 109999 */
    /** 商户类别不能为空 */
    const UN_EMPTY_CATEGORY = 101000;
    /** 详细地址不能为空 */
    const UN_EMPTY_ADDRESS = 101001;
    /** 开始营业时间不能为空 */
    const UN_EMPTY_OPEN_TIME = 101002;
    /** 打烊时间不能为空 */
    const UN_EMPTY_CLOSE_TIME = 101003;
    /** 开始营业时间必须小于打烊时间 */
    const MORE_THAN_OPEN = 101004;
    /** 操作过于频繁 */
    const TOO_OFTEN = 101005;
    /** 信息不存在 */
    const NOT_EXISTS = 101006;
    /** 参数错误 */
    const CORRECT_PARAM = 101007;
    /** 不允许的状态 */
    const NOT_ALLOWED_STATUS = 101008;

    /** 状态错误 */
    const CORRECT_STATUS = 101500;






}