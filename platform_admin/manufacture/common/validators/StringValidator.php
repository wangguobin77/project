<?php
/**
 * @link
 * @copyright Copyright (c)
 * @license
 */

namespace common\validators;

use Yii;
use yii\log\Logger;

/**
 * email地址格式验证器
 * 验证对应的参数值是否为正确的IP地址格式
 */
class StringValidator extends Validator {

    /**
     * @var string the encoding of the string value to be validated (e.g. 'UTF-8').
     * If this property is not set, [[\yii\base\Application::charset]] will be used.
     */
    public $encoding;

    /**
     * @var integer 定长
     */
    public $length;

    /**
     * 校验数据
     * @param array $data					需要校验的数据
     * @param array $rules					校验规则
     * @return array
     */
    public function validate( $data, $rules ) {
        if ( is_array($data) && is_array($rules) ) {
            $names = $rules[0];
            foreach ( $names as $name ) {
                if ( array_key_exists($name, $data) ) {
                    if ( (!is_string($data[$name])) ) {
                        $this->addError( $name, "$name 必须是字符串." );
                    }
                    if(isset($rules['length']) && strlen($data[$name]) != $rules['length']){
                        $this->addError( $name, "{$name}长度必须是 ".$rules['length'] );
                    }
                    if(isset($rules['max']) && strlen($data[$name]) > $rules['max']){
                        $this->addError( $name, "{$name} 长度必须大于 ".$rules['max'] );
                    }
                    if(isset($rules['min']) && strlen($data[$name]) > $rules['min']){
                        $this->addError( $name, "{$name} 长度必须小于 ".$rules['min'] );
                    }
                }
            }
        }

        return $this->error;
    }
}
