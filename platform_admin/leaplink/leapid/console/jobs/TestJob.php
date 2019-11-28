<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-01
 * Time: 12:54
 */

namespace app\console\jobs;


class TestJob extends Job
{
    public $file = '/Users/local2/www/leap_platform_admin/leapid/console/runtime/test/test.log'; //这里最好使用绝对路径,没搞懂相对路径的工作目录是哪里
    public function execute($queue)
    {
        echo '我正在执行队列。。。';
        var_dump($this);
        var_dump($queue);
        $res = file_put_contents($this->file, 'khjfhfhfhfghfhg');
        var_dump($res);
    }
}