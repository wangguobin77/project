<?php

namespace common\helpers;

use common\MvcResult;
use Yii;
use yii\log\Logger;
use common\Result;
use common\ErrorCode;


class ValidateHelper {
	
	 public static $builtInValidators = [
        'boolean' => 'common\validators\BooleanValidator',
        'captcha' => 'common\validators\CaptchaValidator',
        'compare' => 'common\validators\CompareValidator',
        'date' => 'common\validators\DateValidator',
        'datetime' => 'common\validators\DatetimeValidator',
        'time' => 'common\validators\TimeValidator',
        'double' => 'common\validators\DoubleValidator',
        'email' => 'common\validators\EmailValidator',
        'exist' => 'common\validators\ExistValidator',
        'file' => 'common\validators\FileValidator',
        'image' => 'common\validators\ImageValidator',
        'match' => 'common\validators\RegularExpressionValidator',
        'number' => 'common\validators\NumberValidator',
        'required' => 'common\validators\RequiredValidator',
        'string' => 'common\validators\StringValidator',
        'url' => 'common\validators\UrlValidator',
        'ip' => 'common\validators\IpValidator',
		'integer' => 'common\validators\IntegerValidator',
		'numeric' => 'common\validators\NumericValidator',
    ];
	
	/**
	 * 校验数据
	 * @param array $data					需要校验的数据
	 * @param array $rules					校验规则
	 * @return Result 
	 */
	public static function validate( $data, $rules ) {
		$ret = new Result();
		$returnError = [];
		if ( is_array($data) && is_array($rules) ) {
			foreach ( $rules as $rule ) {
				$built = $rule[1];
				$refClass = new \ReflectionClass( self::$builtInValidators[$built] );
				$validator = $refClass->newInstance();
				$error = $validator->validate( $data, $rule );
				if ( $error ) {
					$returnError = array_merge_recursive( $returnError, $error );
				}
			}
		}
		
		if ( count($returnError) > 0 ) {
			$ret->code = ErrorCode::LACK_PARAMS;
			$ret->message = $returnError;
		}
		
		return $ret;
	}

    /**
     * 校验数据
     * @param array $data					需要校验的数据
     * @param array $rules					校验规则
     * @return Result
     */
    public static function mvcValidate( $data, $rules ) {
        $ret = MvcResult::getInstance();
        $returnError = [];
        if ( is_array($data) && is_array($rules) ) {
            foreach ( $rules as $rule ) {
                $built = $rule[1];
                $refClass = new \ReflectionClass( self::$builtInValidators[$built] );
                $validator = $refClass->newInstance();
                $error = $validator->validate( $data, $rule );
                if ( $error ) {
                    $returnError = array_merge_recursive( $returnError, $error );
                }
            }
        }

        if ( count($returnError) > 0 ) {
            $ret->code = ErrorCode::LACK_PARAMS;
            $ret->message = $returnError;
        }

        return $ret;
    }
}
