<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-04-12
 * Time: 14:27
 */

namespace common\consequence;

use common\helpers\Exception;
use Yii;
use yii\web\Controller;

/**
 * MVC模式返回值类
 */
class MvcResult
{
    private static $_instance;

    /**
     * 应用视图
     * @var string
     */
    public $view = 'site/error';

    public $response;

    /**
     * 返回状态码 0成功 非0失败
     * @var int
     */
    public $code = 0;

    /**
     * 返回描述，返回失败的时候需填写描述
     * @var string
     */
    public $message = 'ok';

    /**
     * 返回的数据
     * @var array|\ArrayObject
     */
    private $returnData = [];

    private function __construct(){}
    private function __clone() {}

    /**
     * @param object $controller
     * @return MvcResult instance
     */
    public static function getInstance($controller = null){
        if( !(self::$_instance instanceof self)) {
            if($controller == null || !($controller instanceof Controller)){
                die('请在控制器下调用 MvcResult::getInstance($this)');
            }
            self::$_instance = new self();
            self::$_instance->response = $controller;
        }

        return self::$_instance;
    }

    public function __toString()
    {
        if(ErrorCode::SUCCEED === $this->code){
            $content = $this->response->render($this->view, $this->returnData, $this->response);
            return $this->response->renderContent($content);
        }else{
            //TODO: 自定义错误页或错误提示
            $ret = new Result();
            $ret->code = $this->code;
            $ret->message = $this->message;
            return $ret;
        }
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->returnData[$name] = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        if(isset($this->returnData[$name])){
            return $this->returnData[$name];
        }
        return null;
    }

}