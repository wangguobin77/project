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
 * 整型数值验证器
 * 验证对应的参数值是否为整数类型
 */
class IntegerValidator extends Validator {
	
	public $integerPattern = '/^\s*[+-]?\d+\s*$/';
	
	/**
	 * 校验数据
	 * @param array $data					需要校验的数据
	 * @param array $rules					校验规则
	 * @return array 
	 */
	public function validate( $data, $rules ) {
		if ( is_array($data) && is_array($rules) ) {
			$names = $rules[0];
			$message = "{name} 必须是整数.";

            if(array_key_exists('message', $rules)) $message = $rules['message'];

			foreach ( $names as $name ) {
				if ( array_key_exists($name, $data) ) {
					if ( $data[$name] != '' && preg_match($this->integerPattern, $data[$name]) <= 0 ) {
						$this->addError( $name, str_replace('{name}', $name, $message) );
					}
				}
			}
		}
		
		return $this->error;
	}
}
