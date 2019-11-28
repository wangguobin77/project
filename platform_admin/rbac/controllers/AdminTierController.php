<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/6/19
 * Time: 下午8:06
 */

namespace rbac\controllers;

use yii;
use rbac\backend\models\AdminUserTier;
use rbac\backend\models\AdminUser;
use rbac\backend\helps\Tree;
use yii\web\Response;
use yii\data\Pagination;
class AdminTierController extends BaseController
{
    /**
     * 公司人员层级结构图
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        $AdminUserTier = AdminUserTier::find()->asArray();
        /*  $models = $AdminBranch->all();*/
        $pages = new Pagination(['totalCount'=>$AdminUserTier->count(),'pageSize'=>15]);
        $models = $AdminUserTier
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        /*  return $this->render('branch_list', ['model' => $models,'pages' => $pages]);*/
        return $this->render('list');


    }

    /**
     * 添加人员
     *
     * @return array|string
     */
    public function actionCreate()
    {

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();

            if(!filterData($info['username'],'string',255,1)){
                show_json(100000,'用户名不能为空');
            }


            //判断该用户名是否已经存在
            $u = AdminUserTier::find();//注意一个职位不能出现相同名字 如果的确有相同名字 名字+大小或者特殊字符已表示不同
            $d = $u->where(['username'=>Yii::$app->request->post('username')])->one();
            if($d){
                show_json(100000,'用户名已经存在');//该用户名已存在
            }

            //禁用部门不能创建子部门
            if($pid = Yii::$app->request->post('pid')){
                $AdminUserTier = AdminUserTier::find()->where(['pid'=>$pid])->one();

                if($AdminUserTier && $AdminUserTier['status'] == 0){//禁用的上级不允许添加子成员
                    show_json(100000,'不能添加下级');//上级被禁用 不能在添加下级
                }


            }

            $AdminUserTier = new AdminUserTier;
            $AdminUserTier->pid = $info['pid'];
            $AdminUserTier->username = filterData($info['username']);
            $AdminUserTier->img_path = '';//部门负责人
            $AdminUserTier->condition = $info['condition'];//描述
            $AdminUserTier->position = $info['position'];//职位头衔
            $AdminUserTier->is_show = 1;//默认显示
            $AdminUserTier->status = 1;//添加时默认都为1 显示

            if ($AdminUserTier->save()) {
                show_json(0,'添加成功');
            } else {
                show_json(100000,'添加失败');
            }

        } else {

            $AdminUserTier = AdminUserTier::find()->asArray();
            $models = $AdminUserTier->all();

            //$info = $this->disChildren($models);
            return $this->render('create',['list'=>$models]);//pid默认顶级

        }

    }


    /**
     * 更新人员层级 职位 等 todo 注意不存在删除操作  可以修改人员名字
     * 部门更新不能更新部门当前级别 否则会影响所有相关层级关系
     * @return array|string
     */
    public function actionUpdate()
    {
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');


            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();

            if(!filterData($info['username'],'string',255,1)){
                show_json(100000,'用户名不能为空');
            }


            //判断该用户名是否已经存在
            $u = AdminTier::find();//注意一个职位不能出现相同名字 如果的确有相同名字 名字+大小或者特殊字符已表示不同
            $d = $u->where(['username'=>Yii::$app->request->post('username')])->one();

            if($d && $d['id'] != $id){
                show_json(100000,'用户名已经存在');//该用户名已存在
            }

            //禁用部门不能创建子部门
            if($pid = Yii::$app->request->post('pid')){

                if(AdminTier::find()->where(['pid'=>$pid])->one() && AdminTier::find()->where(['pid'=>$pid])->one()['status'] == 0){//禁用的上级不允许添加子成员
                    show_json(100000,'上级被禁用 不能在添加下级');//上级被禁用 不能在添加下级
                }


            }
            $AdminTier = AdminTier::findOne($id);
            $AdminTier->pid = $info['pid'];
            $AdminTier->username = filterData($info['username']);
            $AdminTier->img_path = '';//部门负责人
            $AdminTier->condition = $info['condition'];//描述
            $AdminTier->position = $info['position'];//职位头衔
            $AdminTier->is_show = intval($info['is_show']);//默认显示
            $AdminTier->status = intval($info['status']);//添加时默认都为1 显示

            if ($AdminTier->save()) {
                show_json(0,'添加成功');
            } else {
                show_json(100000,'添加失败');
            }

        } else {
            $id = Yii::$app->request->get('id');
            $AdminTier = AdminTier::findOne($id);

            $AdminTier_list = AdminTier::find()->asArray();
            $list = $AdminTier_list->all();
            return $this->render('update', ['model' => $AdminTier,'list'=>$list]);
        }


    }



    /**
     * 处理遍历多级分类
     * 父级洗面有多个子目录
     */
    protected function disChildren($data)
    {
        $data = Tree::makeTree($data);//把所有部门数据按等级分成树形结构

        if(isset($data[0]['children'])){
            $data = $data[0]['children'];
        }
        return self::buildMenuHtml($data);
    }
    /**
     * 树形结构展示部门
     * 生成
     * @param $data
     * @param string $html
     * @return string
     */
    private static function buildMenuHtml($data, $html = '')
    {
        //顶级为系统添加的senseplay
        //默认从二级开始遍历

        if($data){
            foreach ($data as $k => $v) {
                $html .= '<ul>';

                $html .= '<li data-id="'.$v['id'].'" onclick=btn_branch(this) class="branch_check">';

                $html .= $v['title'].'--'.$v['principal'];//部门名称 负责人

                if (isset($v['is_show']) && $v['is_show'] != 0 && $v['status'] == 1) {

                    //需要验证是否有子菜单
                    if (isset($v['children']) && is_array($v['children'])) {
                        $html .= self::buildMenuHtml($v['children']);
                    }
                }

                $html .= '</li>';

                $html .= '</ul>';
            }

        }
        return $html;

        /* return ['html'=>$html,'H1'=>$H1title,'current_pid'=>$current_pid];*/

    }
}