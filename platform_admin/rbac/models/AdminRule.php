<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "admin_rule".
 *
 * @property int $id
 * @property int $pid
 * @property string $route 路由
 * @property string $title 名称
 * @property string $icon 菜单使用的class样式类名
 * @property int $type 类型1：菜单+权限  2：菜单 3：权限
 * @property string $condition 描述
 * @property int $order 排序
 * @property string $tips 提示
 * @property int $is_show 是否显示 0：不显示 1：显示
 * @property int $status 是否禁用状态 0：禁用 1：启用
 * @property int $is_on_show 在pc或者移动端显示菜单 0：都显示 1：在pc端显示，2:在移动端显示
 * @property int $is_have_part 是否允许添加子集 0：不允许 1：允许
 */
class AdminRule extends BaseModel
{
    /**
     * 指定获取数据库
     * @return null|object
     */
    public static function getDb()
    {
        return Yii::$app->get('rbacDb');
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'order', 'is_show', 'status', 'is_on_show', 'is_have_part'], 'integer'],
            [['tips'], 'string'],
            [['route'], 'string', 'max' => 80],
            [['title'], 'string', 'max' => 50],
            [['icon'], 'string', 'max' => 255],
            [['condition'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'route' => 'Route',
            'title' => 'Title',
            'icon' => 'Icon',
            'type' => 'Type',
            'condition' => 'Condition',
            'order' => 'Order',
            'tips' => 'Tips',
            'is_show' => 'Is Show',
            'status' => 'Status',
            'is_on_show' => 'Is On Show',
            'is_have_part' => 'Is Have Part',
        ];
    }

    /**
     * 验证必要的字段
     * @param $data
     */
    public function disRuleFiled($data)
    {

        if(!$data['route']){
            show_json(100000,'lack route params');
        }

        if(!$data['title']){
            show_json(100000,'lack title params');
        }

        if(!$data['type'] || !in_array(intval($data['type']),[1,2,3])){
            show_json(100000,'type illegal');
        }

        if(!$data['is_show'] || !in_array(intval($data['is_show']),[0,1])){
            show_json(100000,'lack is_show params');
        }

        if(!$data['status'] || !in_array(intval($data['status']),[0,1])){
            show_json(100000,'lack is_show params');
        }
    }

    /**
     * 获取菜单
     * @param string $id
     * @return array
     *
     */
    public static function getAllMenu($id = '')
    {
        $all = self::find()
            ->andWhere(['status'=>1])
            ->orderBy('order')->asArray()->all();
        if ($id) {
            $all = self::find()
                ->andWhere(['status'=>1])
                ->where(['<>', 'id', $id])->asArray()->all();
        }
        $dataList = array();
        if ($all) {
            //生成线性结构, 便于HTML输出
            $dataList = Tree::makeTreeForHtml($all);

            $dataList = array_map(function ($item) {
                $nbsp = '';
                for ($i = 1; $i <= $item['level']; $i++) {
                    $nbsp .= '─';//制表符
                }
                $nbsp .= '╊';//制表符
                $item['title'] = $nbsp . $item['title'];
                return $item;
            }, $dataList);

            $dataList = ArrayHelper::map($dataList, 'id', 'title');

        }

        /* var_dump($dataList);
         $dataList = array_merge(self::$firstMenu, $dataList);*/
        //   $dataList[0] = '顶级菜单';
        // var_dump($dataList);die;
        return $dataList;
    }

    public static function getShowMenu()
    {
        $rules = self::find()
            ->where(['status'=>1, 'is_show'=>1])
            ->orderBy('order')
            ->asArray()
            ->all();
        return $rules;
    }

    public function attributeValues()
    {
        return [
            'type' => [
                '1' => '权限和菜单',
                '2' => '权限',
                '3' => '菜单',
            ],
            'status' => [
                0 => '关闭',
                1 => '开启'
            ],
            'is_show' => [
                0 => '隐藏',
                1 => '显示'
            ],
            'is_on_show' => [
                0 => '都显示',
                1 => 'pc显示',
                2 => '移动端显示'
            ],
            'is_have_part' => [
                0 => '不允许',
                1 => '允许'
            ]
        ];


    }
}
