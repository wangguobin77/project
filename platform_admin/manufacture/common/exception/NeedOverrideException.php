<?php

namespace common\exception;

//use Yii;

class NeedOverrideException extends Exception {

	public function getName() {
        return 'NeedOverrideException';
    }
}
