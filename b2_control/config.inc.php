<?php
/**
 * 设置最大开启数
 */
if (!defined('NODE_NUM_MAX'))        define('NODE_NUM_MAX', 4);

/**
 * 设置最多暂存消息条数
 */
if (!defined('MESSAGE_NUM_MAX'))        define('MESSAGE_NUM_MAX', 100);

/**
 * 数据暂存路径,类似DB作用
 */
if (!defined('DATA_PATH'))        define('DATA_PATH', BS_IPC_PATH.'data/');

/**
 * 告警文件路径
 * TODO: 板子环境在 "/apache/htdocs/alarm.txt"
 */
if (!defined('TIP_FILE_PATH'))        define('TIP_FILE_PATH', '/media/tips.txt');
//if (!defined('TIP_FILE_PATH'))        define('TIP_FILE_PATH', '/apache/htdocs/alarm.txt');


/**
 * 请求开启/关闭状态次数
 */
if (!defined('CHECK_STATUS_NUM'))        define('CHECK_STATUS_NUM', 2);

/**
 * 请求开启/关闭状态间隔时间（s）
 */
if (!defined('CHECK_STATUS_TIME'))        define('CHECK_STATUS_TIME', 3);


/**
 * 定义ipc开启的状态
 */
if (!defined('IPC_STATUS_OPEN'))        define('IPC_STATUS_OPEN', 1);


/**
 * 定义ipc关闭的状态
 */
if (!defined('IPC_STATUS_CLOSE'))        define('IPC_STATUS_CLOSE', 0);


/**
 * 告警信息本地存储位置
 */
if (!defined('MESSAGE_TXT_PATH'))        define('MESSAGE_TXT_PATH', DATA_PATH.'message/data.txt');

/**
 * 请求基本命令
 */
if (!defined('BASE_COMMAND'))        define('BASE_COMMAND', './socket_c');
//if (!defined('BASE_COMMAND'))        define('BASE_COMMAND', 'socket_c');