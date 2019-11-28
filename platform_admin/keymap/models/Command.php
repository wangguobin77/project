<?php


namespace app\models;

class Command extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'km_command';
    }

    public function rules()
    {
        return [
            [['key','code','version','analog_params','can_map','category_id','normal_params','op_style','canmap_once'],'required'],
            [['key'],'unique'],
            [['key'],'match','pattern' => '/^[a-zA-Z0-9\-\_\. ]{4,32}$/u'],
            [['code','version'],'match','pattern' => '/^[0]{1}[xX]{1}[a-fA-F0-9]{2,3}$/u'],
            ['tag','string'],
            ['category_id', 'filter', 'filter' => function($value) {
                if (in_array('0', $value)) {
                    return 0;
                }
                $where = ['and',['=','is_deleted',0],['in','id',$value]];
                $count = (new \yii\db\Query())
                    ->select('id')
                    ->from('category')
                    ->where($where)
                    ->count();
                if ($count != count($value)) {
                    $this->addError('category', 'Category is not exists.');
                }
                return implode(',', $value);
            }],
            [['can_map','op_style','canmap_once'],'in','range' => [0, 1]],
            [['analog_params', 'normal_params'], 'integer'],
            [['analog_params','normal_params'],'compare','compareValue'=>0,'operator'=>'>='],
            ['tag','default','value'=>''],
        ];
    }

    public function attributeLabels()
    {
        return [
            'key' => 'Key',
            'code' => 'Code',
            'version' => 'Version',
            'analog_params' => 'Analog Params',
            'can_map' => 'Can Map',
            'category_id' => 'Category',
            'normal_params' => 'Normal Params',
            'op_style' => 'Op Style',
            'canmap_once' => 'Can Map Once',
        ];
    }
}
