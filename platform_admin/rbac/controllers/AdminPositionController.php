<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/6
 * Time: 下午2:52
 */

namespace app\controllers;

use Yii;
use app\models\AdminPosition;
use app\models\AdminUserProfile;
use yii\data\Pagination;
use yii\web\Response;
class AdminPositionController extends BaseController
{
    public function init()
    {
        $this->layout = false;
    }

    /**
     * 职位列表
     * @return string
     */
    public function actionIndex()
    {

        $AdminPosition = AdminPosition::find();
       /* $pages = new Pagination(['totalCount'=>$AdminPosition->count(),'pageSize'=>20]);
        $models = $AdminPosition
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();*/
        $models = $AdminPosition->all();

        return $this->render('list',
            [
                'model' => $models
            ]);
    }

    /**
     * 创建部门
     * @return string
     */
    public function actionCreate()
    {

        if (Yii::$app->request->isPost) {

            $AdminPosition = new AdminPosition;

            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();
            if(!filterData($info['name'],'string',128,1)){
                show_json(100000,'职位不能为空');//职位不能为空
            }

            //职位名称不能相同
            $is_info = AdminPosition::find()->where(['name'=>$info['name']])->asArray()->one();

            if($is_info)  show_json(100000,'职位名称已经存在');//职位名称已经存在

            $AdminPosition->name = $info['name'];
            $AdminPosition->des = $info['name'];//描述
            $AdminPosition->created_ts = time();
            $AdminPosition->status = 1;//是否显示 默认显示

            if($AdminPosition->save()){
                show_json(0,'添加职位成功');
            }

            show_json(100000,'添加职位失败');
        }

        show_json(100000,'请求格式不合法');//请求格式不合法
    }

    /**
     * 修改部门
     * @return string
     */
    public function actionUpdate()
    {

        if (Yii::$app->request->isPost) {

            $AdminPosition = AdminPosition::findOne(Yii::$app->request->post('id'));

            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();
            if(!filterData($info['name'],'string',128,1)){
                show_json(100000,'职位名称不能为空');
            }

            //职位名称不能相同
            $is_info = AdminPosition::find()->where(['name'=>$info['name']])->asArray()->one();

            if($is_info)  show_json(100000,'职位名称已经存在');//职位名称已经存在

            $AdminPosition->name = $info['name'];
            $AdminPosition->des = $info['name'];//描述

            if($AdminPosition->save()){
                show_json(0,'修改职位成功');
            }

            show_json(100000,'修改职位失败');
        }else {
            $id = Yii::$app->request->get('id');

            $AdminPosition = AdminPosition::findOne($id);

            if(!$AdminPosition){
                show_json(100000,'部门不存在');//部门不存在
            }

            return $this->render('edit',['data'=>$AdminPosition]);
        }

    }

    /**
     * 删除职位
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('id');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = AdminPosition::findOne($id);

        if ($model) {

            //查询是否有用户已经绑定该职位信息
            $u = AdminUserProfile::find();
            $d = $u->where(['position_id'=>$id])
                ->all();

            if($d){
                show_json(100000,'当前已存在绑定信息,不能删除');
            }

            if($model->delete()){
                show_json(0,'删除成功');
            }else{
                show_json(100000,'删除失败');
            }
        } else {

            show_json(100000,'当前职位信息不存在');
        }
    }
}