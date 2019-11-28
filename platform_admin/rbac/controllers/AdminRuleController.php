<?php
// +----------------------------------------------------------------------
// | When work is a pleasure, life is a joy!
// +----------------------------------------------------------------------
// | User: ShouKun Liu  |  Email:24147287@qq.com  | Time:2016/12/20 22:56
// +----------------------------------------------------------------------
// | TITLE: 权限控制器
// +----------------------------------------------------------------------

namespace app\controllers;

use Yii;
use app\models\AdminRule;
use yii\data\Pagination;
use yii\web\Response;
use yii\helpers\Url;
use app\helps\Tree;
use yii\web\Controller;
use app\models\AdminRole;

/**
 * Class AdminRuleController
 * @package controllers
 */
class AdminRuleController extends BaseController
{
    public function init()
    {
        $this->layout = false;
    }
    /**
     * 列表
     * @return string
     */
    public function actionIndex()
    {
        /*  $AdminRule = AdminRule::find();
          $pages = new Pagination(['totalCount' => $AdminRule->count(), 'pageSize' => '15']);
          $models = $AdminRule->offset($pages->offset)
              ->limit($pages->limit)
              ->all();*/

       /* $role_list = $this->getRoleIdList($_SESSION['uid']['uid']);

        $this->menu = $this->disUnique($role_list);*/

        $AdminRuleList = AdminRule::find()->asArray()->all();

       /* return $this->render('list', ['listInfo' => $this->disChildren( $this->menu)]);//树行结构*/
        return $this->render('list', ['listInfo' => $this->disChildren( $AdminRuleList)]);//树行结构

    }

    /**
     * 注意：如果是每个项目的顶级 需要手动添加一条记录 父类id 为0
     * 创建
     * @return array|string
     */
    public function actionCreate()
    {

        if (Yii::$app->request->isPost) {

            $AdminRule = new AdminRule;

            $AdminRule->disRuleFiled(Yii::$app->request->post());//验证一些字段

            $AdminRuleData = Yii::$app->request->post();

            //判断当前父类下 是否能创建子类
            if($AdminRuleData['pid'] && $AdminRuleData['pid'] != 0){
                $model = AdminRule::findOne($AdminRuleData['pid']);

                if(isset($model['is_have_part']) && $model['is_have_part'] == 0){
                    show_json(100000,'该父类下不能创建菜单权限');//该父类下不能创建菜单权限
                }
            }

            $AdminRule->pid = $AdminRuleData['pid'];

            $AdminRule->route = $AdminRuleData['route'];
            $AdminRule->title = filterData($AdminRuleData['title'],'string',50,1);
            $AdminRule->icon = $AdminRuleData['icon'];
            $AdminRule->type = filterData($AdminRuleData['type'],'integer',1);
            $AdminRule->condition = $AdminRuleData['condition'];
            $AdminRule->order = isset($AdminRuleData['order'])?$AdminRuleData['order']:1;
            $AdminRule->tips = '';//目前没用到
            $AdminRule->is_show = isset($AdminRuleData['is_show'])?$AdminRuleData['is_show']:1;
            $AdminRule->status = isset($AdminRuleData['status'])?$AdminRuleData['status']:1;
            $AdminRule->is_on_show = isset($AdminRuleData['is_on_show'])?$AdminRuleData['is_on_show']:1;
            $AdminRule->is_have_part = isset($AdminRuleData['is_have_part'])?$AdminRuleData['is_have_part']:1;

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($AdminRule->save()) {
                show_json(0,'添加路由成功');
            }

            show_json(100000,'添加路由失败');

        } else {
            $id = 0;//

            $title = Yii::$app->request->get('title','');
            if(Yii::$app->request->get('id')){//如果url有该值 说明是基于父类下创建 如果不存在说明是顶级菜单  添加权限严格控制权限，不能随便赋予用户这个权限

                $id = Yii::$app->request->get('id');

                $model = AdminRule::findOne($id);

                if(!$model){
                    show_json(100000,'当前路由信息不存在');
                }
            }

            return $this->render('addrule', ['id' => $id,'title'=>$title]);//

        }
    }


    /**
     * 更新
     * @return string
     */
    public function actionUpdate()
    {

        if(Yii::$app->request->isPost){
            $id = Yii::$app->request->post('id');
        }else{
            $id = Yii::$app->request->get('id');
        }

        $title = Yii::$app->request->get('title','');

        $model = AdminRule::findOne($id);

        if(!$model){
            show_json(100000,'当前路由信息不存在');
        }

        if (Yii::$app->request->isPost) {

            $AdminRuleData = Yii::$app->request->post();

            $model->pid = $AdminRuleData['pid'];
            $model->route = $AdminRuleData['route'];
            $model->title = $AdminRuleData['title'];
            $model->icon = $AdminRuleData['icon'];
            $model->type = $AdminRuleData['type'];
            $model->condition = $AdminRuleData['condition'];
            $model->order = $AdminRuleData['order'];
            $model->tips = '';//战时没用到该字段
            $model->is_show = $AdminRuleData['is_show'];
            $model->status = $AdminRuleData['status'];
            $model->is_on_show = $AdminRuleData['is_on_show'];
            $model->is_have_part = $AdminRuleData['is_have_part'];
            $model->condition = $AdminRuleData['condition'];

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                show_json(0,'更新成功');
            }

            show_json(100000,'更新失败');
        } else {

            return $this->render('updaterule', ['model' => $model,'title'=>$title]);
        }

    }

    /**
     * 删除操作 存在有子集的目录不能删除  没有下级的可以删除
     * @return string
     */
    public function actionDel()
    {
        $id = Yii::$app->request->post('id');

        //先判断当前的id是否是父类 下面是否已添加了子集,如果已存在子集，就不允许删除
        $p_data = AdminRule::find()->where(['pid'=>$id])->all();

        if(count($p_data) > 0){
            show_json(100000,'当前路由下存在子集,不能删除');//当前路由存在自己所以不能删除
        }

        if (AdminRule::findOne($id)->delete()) {
           /* $url = Url::toRoute('admin-rule/index');
            header('location:'.$url);
            die;*/
            show_json(0,'删除成功');
        }

        show_json(100000,'删除失败');
    }

    /**
     * 处理遍历多级分类
     * 父级洗面有多个子目录
     */
    protected function disChildren($data)
    {
        $data = Tree::makeTree($data);//把所有部门数据按等级分成树形结构
        /*if(isset($data[0]['children'])){
            $data = $data[0]['children'];
        }*/
        return self::buildMenuHtml($data);
    }

    /**
     *  树形结构展示部门
     * 生成
     * @param $data
     * @param string $html
     * @param int $index 当前class 索引
     * @param int $t_index  如果有子类，他的父类 class 索引
     * @return string
     */
    protected static $index_k = 0;
    private static function buildMenuHtml($data, $html = '',$index=0,$t_index=0)
    {
        //顶级为系统添加的senseplay
        //默认从二级开始遍历

        if($data){

            foreach ($data as $k => $v) {

              //  if ($v['is_show'] != 0 && $v['status'] == 1) {

                    ++self::$index_k;
                    if(isset($v['pid']) && $v['pid'] == 0){//父类一级菜单 每个项目的顶级菜单
                        $html .= '<tr class="treegrid-'.self::$index_k.'">';
                    }else{
                        $html .= '<tr class="treegrid-'.self::$index_k.' treegrid-parent-'.$t_index.'">';
                    }

                    $html .= '<td>';
                    $html .= $v['title'];
                    $html .= '</td>';
                    $html .= '<td class="list-type">'.$v['route'].'</td>';

                    if($v['type'] == 1){
                        $html .= '<td class="sl">菜单+权限</td>';
                    }else if($v['type'] == 2){
                        $html .= '<td class="sl">菜单</td>';
                    }else{
                        $html .= '<td class="sl">权限</td>';
                    }
                    $html .= '<td>'.$v['order'].'</td>';

                    if($v['is_show'] == 1){
                        $html .= '<td class="list-type">显示</td>';
                    }else{
                        $html .= '<td class="list-type">不显示</td>';
                    }

                    if($v['is_on_show'] == 1){
                        $html .= '<td class="sl">pc显示</td>';

                    }else if($v['is_on_show'] == 2){
                        $html .= '<td class="sl">移动端显示</td>';
                    }else{
                        $html .= '<td class="sl">都显示</td>';
                    }

                    if($v['is_have_part'] == 1){
                        $html .= '<td class="sl"> 允许</td>';

                    }else{
                        $html .= '<td class="sl">不允许</td>';
                    }

                    if($v['status'] == 1){
                        $html .= '<td class="sl order kaiqi">';
                        $html .= '   <span>启用</span>';
                        $html .= '</td>';
                    }else{
                        $html .= '<td class="sl order weikq" >';
                        $html .= '   <span>禁用</span>';
                        $html .= '</td>';

                    }

                    $html .= '<td class="sl opr-box crud-box" >';
                    $html .= '    <a class="font_family" href="'.Url::toRoute(['admin-rule/update','id'=>$v['id'],'title'=>$v['title']]).'"><span style="font-size:14px">编辑</span></a>';
                    $html .= '    <span class="xian"></span>';
                    if($v['is_have_part'] == 1){
                        $html .= '    <a class="font_family glyphicon glyphicon-plus" href="'.Url::toRoute(['admin-rule/create','id'=>$v['id'],'title'=>$v['title']]).'"></a>';
                        $html .= '    <span class="xian"></span>';
                    }

                  /*  $html .= '    <a class=" edit-pancel cursor" href="'.Url::toRoute(['admin-rule/del','id'=>$v['id']]).'"><span style="font-size:14px">删除</span></a>';*/
                $html .= '    <a href="javascript:;" class="edit-pancel cursor" ><span onclick="sendDelRule(this)" data-id="'.$v['id'].'" style="font-size:14px">删除</span></a>';
                    $html .= '</td>';
                    $html .= '</tr>';

                    //需要验证是否有子菜单
                    if (isset($v['children']) && is_array($v['children'])) {
                        $html .= self::buildMenuHtml($v['children'],'',self::$index_k,self::$index_k);
                    }


                }

           // }

        }
        return $html;

        /* return ['html'=>$html,'H1'=>$H1title,'current_pid'=>$current_pid];*/

    }

}