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
 * 布尔型数值验证器
 * 验证对应的参数值是否为布尔类型
 */
class BooleanValidator extends Validator {
	
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
					if ( !is_bool($data[$name]) ) {
						$this->addError( $name, "$name 必须是 \"true\" 或 \"false\"." );
					}
				}
			}
		}
		
		return $this->error;
	}
}
