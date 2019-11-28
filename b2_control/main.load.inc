<?php
/** 文件配置 */
//脚本路径
if(!defined('IPC_SCRIPT_PATH'))     define('IPC_SCRIPT_PATH', dirname(__FILE__));

//文件后缀
if(!defined('IPC_PHPEXT'))       define('IPC_PHPEXT', 'php');

//项目路径
if(!defined('BS_IPC_PATH'))    define('BS_IPC_PATH'   , IPC_SCRIPT_PATH.'/');

include BS_IPC_PATH.'main.'.IPC_PHPEXT;