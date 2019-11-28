<?php
if (!defined('__GOT_CONFIG__')){
    /* include configuration file */
    include BS_IPC_PATH.'config.inc.'.IPC_PHPEXT;

    /* current version of web_server */
    $curr_ver = '0.0.1'; $build_date = '2019/01/03';

    /* file locations */
    define('LIB_FUNCTIONS',      BS_IPC_PATH.'functions.lib.'.IPC_PHPEXT);

    /** start session */
    session_start();

    include LIB_FUNCTIONS;



    define('__GOT_CONFIG__', 1);
}