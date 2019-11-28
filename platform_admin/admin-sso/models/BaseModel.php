<?php

namespace app\models;


use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public  function enumeration($field='',$key)
    {
        $Values = $this->attributeValues();

        return $Values[$field][$key];
    }
}