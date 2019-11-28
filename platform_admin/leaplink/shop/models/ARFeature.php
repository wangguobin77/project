<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-08
 * Time: 17:59
 */

namespace app\models;

/**
 * 网站操作模型类
 * Class ARFeature
 * @package app\models
 */
class ARFeature extends ARBaseActiveModel
{
    /** 增删改查 CURD */
    const CREATE_FEATURE = 1;
    const UPDATE_FEATURE = 2;
    const RETRIVE_FEATURE = 3;
    const DELETE_FEATURE = 4;

    /** CURD 对应 label */
    const FEATURE_LABELS = [
        self::CREATE_FEATURE => '创建',
        self::UPDATE_FEATURE => '修改',
        self::RETRIVE_FEATURE => '查询',
        self::DELETE_FEATURE => '删除',
    ];
}