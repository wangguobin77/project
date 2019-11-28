<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/8/13
 * Time: 下午5:51
 */

namespace app\controllers;

use yii;
use yii\web\Controller;
use app\models\AdminBranch;
class ComController  extends Controller
{
    private static $orgInfo;//全局组织部门所有信息
    /**
     * 根据参数id 返回自己的父类层级数据
     * @return mixed
     */
    public function actionGet_org_info()
    {
        if(Yii::$app->request->isPost){
            $o_id = Yii::$app->request->post('id');
        }else{
            $o_id = Yii::$app->request->get('id');
        }

        self::$orgInfo = $this->getAllOrgInfo();//所有路由


        /*  return $this->retBranchTtitle($data,$o_id);*/
        echo '<pre>';
        print_r($this->retBranchTtitle($o_id));
    }

    /**
     * 获取所有的组织或者部门 每条记录的索引时自己的id
     * @return array
     */
    protected function getAllOrgInfo()
    {
        $AdminBranch = AdminBranch::find()->asArray()->all();

        return $this->orderData($AdminBranch);
    }

    /**
     * 处理数组 把每个数字字段的id作为当前索引
     * @param $data
     */
    private function orderData($data){
        $list = [];
        foreach ($data as $key=>$val){
            $list[$val['id']] = $val;
        }

        return $list;
    }


    /**
     * 根据当前菜单的父类id 找出顶级id
     */
    private function retBranchTtitle($index=0)
    {
        $new_data = [];
        if(self::$orgInfo[$index]['pid'] && self::$orgInfo[$index]['pid'] != 0){
            $new_data[$index] = self::$orgInfo[$index];
            $new_data[$index]['children'] = $this->retBranchTtitle(self::$orgInfo[$index]['pid']);
        }else{
            $new_data[$index] = self::$orgInfo[$index];
        }
        return $new_data;

    }
}