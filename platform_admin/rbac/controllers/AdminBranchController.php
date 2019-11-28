<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/6/6
 * Time: 下午3:56
 */

namespace app\controllers;

use yii;
use app\models\AdminBranch;
use app\models\AdminUserBranch;
use app\models\AdminBranchType;
use app\models\AdminInstitutionsType;
use app\helps\Tree;
use common\library\area;
use yii\web\Response;
use yii\helpers\Url;
use yii\data\Pagination;
class AdminBranchController extends BaseController
{

    public $AdminBranchTypeInfo;

    public $AdminInstitutionsTypeInfo;

    public function init()
    {
        $this->layout = false;

        $this->AdminBranchTypeInfo = (new AdminBranchType)->disAdminBranchTypeName();//部门

        $this->AdminInstitutionsTypeInfo = (new AdminInstitutionsType)->disAdminInstitutionsTypeName();//组织
    }
    /**
     * 部门列表
     * @return string
     */
    public function actionIndex()
    {
        /* $AdminBranch = AdminBranch::find()->asArray();//test1111

         $pages = new Pagination(['totalCount'=>$AdminBranch->count(),'pageSize'=>15]);
         $models = $AdminBranch
             ->offset($pages->offset)
             ->limit($pages->limit)
             ->all();*/

        $AdminBranch = AdminBranch::find()->orderBy('pid,id')->asArray()->all();

        /*  return $this->render('branch_list', ['model' => $models,'pages' => $pages]);*/
        return $this->render('department', ['model' => $this->disHtmlView(Tree::makeTree($AdminBranch))]);//树行结构
//        return $this->render('department', ['model' => $AdminBranch]);//树行结构


    }

    protected static $index_k = 0;
    protected function disHtmlView($data,$str='',$index=0,$t_index=1)
    {
        if(!$data) return $str;

        foreach ($data as $key=>$val) {

            if(!isset($val['is_show']) || $val['is_show'] != 0 || $val['status'] == 1) {
                ++self::$index_k;

                    if (isset($val['pid']) && $val['pid'] == 0) {//父类一级菜单 每个项目的顶级菜单
                        $str .= '<tr class="treegrid-' . self::$index_k . '">';
                    } else {
                        $str .= '<tr class="treegrid-' . self::$index_k . ' treegrid-parent-' . $t_index . '">';
                    }

                    if ($val['type'] == 0) {

                        $str .= '<td>';

                        $str .= '<div class="text-box sl" contenteditable="false" style="font-size: 12px;">';
                        $str .= $val['title'];
                        $str .= '</div>';
                        $str .= '</td>';
                        $str .= '<td></td>';
                        $str .= '<td class="sl"></td>';

                        $str .= '<td class=" opr-box" >';
                        $str .= '<div style="width:125px;position:relative;">';
                        $str .= '<a class="font_family glyphicon glyphicon-plus" style="float:right;"></a>';

                        $str .= '<ul class="add-drop">';
                        $str .= '<li><a href="' . Url::toRoute(['admin-branch/create', 'type_id' => 2, 'id' => $val['id']]) . '">添加部门</a></li>';
                        $str .= '<li><a href="' . Url::toRoute(['admin-branch/create', 'type_id' => 1, 'id' => $val['id']]) . '">添加机构</a></li>';
                        $str .= '</ul>';
                        $str .= '</div>';
                        $str .= '</td>';
                        $str .= '</tr>';
                    } else {

                        $str .= '<td>' . $val['title'] . '</td>';
                        $B_name = $val['type'] == 1 ? $this->AdminInstitutionsTypeInfo[$val['i_b_type_id']] : $this->AdminBranchTypeInfo[$val['i_b_type_id']];
                        $str .= '<td>' . $B_name.'</td>';
                        $str .= '<td>' . $val['c_address'] . '</td>';

                        $str .= '<td class=" opr-box">';
                        $str .= '<div style="width:125px;position:relative;">';
                        $update_url = $val['type'] == 1 ? Url::toRoute(["admin-branch/update", "type_id" => 1, "id" => $val["id"]]) : Url::toRoute(['admin-branch/update', 'type_id' => 2, 'id' => $val['id']]);
                        $str .= '<a class="fa fa-edit" href="'.$update_url.'"></a>';
                    $str .= '<span class="xian"></span>';

                    $str .= '<a class="fa fa-trash" onclick="Del('.$val['id'].')"></a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a class="font_family glyphicon glyphicon-plus"></a>';
                    $str .= '<ul class="add-drop">';
                    if ($val['type'] == 1) {
                        $str .= '<li><a href="'.Url::toRoute(['admin-branch/create', 'type_id' => 1, 'id' => $val['id']]).'">添加机构</a></li>';
                        $str .= '<li><a href="'.Url::toRoute(['admin-branch/create', 'type_id' => 2, 'id' => $val['id']]).'">添加部门</a></li>';
                    }
                    if ($val['type'] == 2) {
                        $str .= '<li><a href="'.Url::toRoute(['admin-branch/create', 'type_id' => 2, 'id' => $val['id']]).'">添加部门</a></li>';
                    }
                    $str .= '</ul></div></td></tr>';
                }
            }

            if (isset($val['children']) && is_array($val['children'])) {
                $str .= $this->disHtmlView($val['children'],'',self::$index_k,self::$index_k);
            }
        }
        return $str;
    }

    /**
     * 判断当前父类下面是否可添加对应的大类
     * @param $pid 父类id
     * @param $type_id  当前需要添加的类型id 1 组织 2 部门
     * @return bool
     */
    protected function isCanAddB_O($pid,$type_id)
    {
        $u = AdminBranch::find()->all();

        if(!$u){
            return true;
        }

        foreach ($u as $key=>$val){
            if($val['id'] == $pid){
                if($val['type'] == 2 && $type_id == 1){
                    show_json(100000,'部门下面不能创建组织 组织结构比部门大');//部门下面不能创建组织 组织结构比部门大
                }
            }
        }

        return true;
    }
    /**
     * 创建部门
     * 注意禁用的部门不能在创建下级部门 可以隐藏
     * @return array|string
     */
    public function actionCreate()
    {



        $type_id = Yii::$app->request->get('type_id');//类型id 1：机构表达 2：部门表单

        $id = Yii::$app->request->get('id');//当前组织或者部门的id

        if(!$type_id){
            show_json(100000,'缺少定义适用什么表单的类型');//缺少定义适用什么表单的类型
        }

        //判断该用户名是否已经存在
        $u = AdminBranch::find();

        $d = $u->where(['id'=>$id])->one();

        if(!$d){
            show_json(100000,'上级组织或者部门不存在');//上级组织或者部门不存在
        }
        /* echo '<pre>';
         print_r((new area)->areaChild());die;*/
        if(intval($type_id) == 1){
            return $this->render('addorg',['id'=>$id]);//使用组织表单
        }else{
            return $this->render('addbranch',['id'=>$id]);//使用部门表单
        }



    }

    /**
     * 添加部门信息
     */
    public function actionAddbranch(){

        if (Yii::$app->request->isPost) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();

            //判断该用户名是否已经存在
            $u = AdminBranch::find();

            $d = $u->where(['title'=>$info['title']])->one();

            if($d){
                show_json(100000,'不能重复添加部门');//不能重复添加部门
            }

            $info['type'] = 2;//2 代表部门类型
            $info['is_show'] = 1;//默认显示
            $info['status'] = 1;//默认启用

            //添加部门
            $this->disBranchInfo($info);

        }

        show_json(100000,'请求格式错误');
    }

    /**
     * 添加组织信息
     */
    public function actionAddorg()
    {
        if (Yii::$app->request->isPost) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();

            //判断该用户名是否已经存在
            $u = AdminBranch::find();

            $d = $u->where(['title'=>$info['title']])->one();

            if($d){
                show_json(100000,'当前名称以存在');//不能重复添加部门
            }

            $info['type'] = 1;//1 代表组织类型
            $info['is_show'] = 1;//默认显示
            $info['status'] = 1;//默认启用

            //添加组织
            $this->disOrganizationInfo($info);

        }

        show_json(100000,'请求格式错误');

    }

    /**
     * 部门组织架构 处理部门添加部门相关信息
     * @param $info
     */
    protected function disBranchInfo($info)
    {
        if(!isset($info['title']) || strlen($info['title']) > 128){
            show_json(100000,'名称不能为空或者字符不能超过128位');//名称不能为空或者字符不能超过128位
        }


        $AdminBranch = new AdminBranch;

        $AdminBranch->pid = $info['pid'];//父类id
        $AdminBranch->title = $info['title'];//部门名称
        $AdminBranch->c_address = $info['c_address'];//部门地址
        $AdminBranch->condition = $info['condition'];//描述
        $AdminBranch->i_b_type_id = $info['i_b_type_id'];//定义部门或者组织所属的子类型id 如：仓库，普通
        $AdminBranch->type = $info['type'];//所属大类类型 组织或者部门 1组织  2部门
        $AdminBranch->is_show = $info['is_show'];//默认显示
        $AdminBranch->status = $info['status'];//默认启用


        if($AdminBranch->save()){
            show_json(0,'添加部门成功');
        }

        show_json(100000,'添加部门失败');
    }

    /**
     * 处理组织架构信息数据
     * @param $info
     */
    protected function disOrganizationInfo($info)
    {

        if(!isset($info['title']) || strlen($info['title']) > 128){
            show_json(100000,'名称不能为空或者字符不能超过128位');//名称不能为空或者字符不能超过128位
        }

        if(!isset($info['region'])){
            show_json(100000,'所在区域地址不能为空');//所在区域地址不能为空
        }

        if(!isset($info['contact_name']) || strlen($info['contact_name']) > 128){
            show_json(100000,'联系人姓名不能为空或者字符长度不能超出128');//联系人姓名不能为空或者字符长度不能超出128
        }


        if(!isset($info['c_address']) || strlen($info['c_address']) > 128){
            show_json(100000,'联系人姓名不能为空或者字符长度不能超出128');//联系人姓名不能为空或者字符长度不能超出128
        }

        if(!isset($info['mobile']) || strlen($info['mobile']) > 128){
            show_json(100000,'联系人姓名不能为空或者字符长度不能超出128');//联系人姓名不能为空或者字符长度不能超出128
        }

        $AdminBranch = new AdminBranch;

        $AdminBranch->pid = $info['pid'];//父类id
        $AdminBranch->title = $info['title'];//部门名称
        $AdminBranch->contact_name = $info['contact_name'];//联系人
        $AdminBranch->region = $info['region'];//所在区域地址  地址以 xx-xx-xx以中华线拼接
        $AdminBranch->mobile = $info['mobile'];//手机联系方式
        $AdminBranch->c_address = $info['c_address'];//机构地址
        $AdminBranch->c_email = $info['c_email'];//邮箱地址
        $AdminBranch->condition = $info['condition'];//描述
        $AdminBranch->i_b_type_id = $info['i_b_type_id'];//定义部门或者组织所属的子类型id 如：仓库，普通
        $AdminBranch->type = $info['type'];//所属大类类型 组织或者部门 1组织  2部门
        $AdminBranch->is_show = $info['is_show'];//默认显示
        $AdminBranch->status = $info['status'];//默认启用

        if($AdminBranch->save()){
            show_json(0,'添加成功');
        }

        show_json(100000,'添加失败');
    }


    /**
     * 更新部门
     * 部门更新不能更新部门当前级别 否则会影响所有相关层级关系
     * @return array|string
     */
    public function actionUpdate()
    {


        if (Yii::$app->request->isPost) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $info = Yii::$app->request->post();

            $AdminBranch = AdminBranch::findOne($info['id']);

            //判断该用户名是否已经存在
            $u = AdminBranch::find();
            $d = $u->where(['title'=>$info['title']])->one();
            if($d && $AdminBranch['id'] != $d['id']){//不能重复添加相同的名称 除了自己
                show_json(100000,'不能重复添加部门');//不能重复添加部门
            }


            if(intval($info['type']) == 1){//以组织的数据结构处理

                $AdminBranch->pid = $info['pid'];//父类id
                $AdminBranch->title = $info['title'];//部门名称
                $AdminBranch->contact_name = $info['contact_name'];//联系人
                $AdminBranch->region = $info['region'];//所在区域地址  地址以 xx-xx-xx以中华线拼接
                $AdminBranch->mobile = $info['mobile'];//手机联系方式
                $AdminBranch->c_address = $info['c_address'];//机构地址
                $AdminBranch->c_email = $info['c_email'];//邮箱地址
                $AdminBranch->condition = $info['condition'];//描述
                $AdminBranch->i_b_type_id = $info['i_b_type_id'];//定义部门或者组织所属的子类型id 如：仓库，普通
                $AdminBranch->type = $info['type'];//所属大类类型 组织或者部门 1组织  2部门

            }else{//部门数据结构处理
                $AdminBranch->pid = $info['pid'];//父类id
                $AdminBranch->title = $info['title'];//部门名称
                $AdminBranch->c_address = $info['c_address'];//部门地址
                $AdminBranch->condition = $info['condition'];//描述
                $AdminBranch->i_b_type_id = $info['i_b_type_id'];//定义部门或者组织所属的子类型id 如：仓库，普通
                $AdminBranch->type = $info['type'];//所属大类类型 组织或者部门 1组织  2部门

            }


            if($AdminBranch->save()){
                show_json(0,'修改成功');
            }
            show_json(100000,'修改失败');
        } else {

            $id = Yii::$app->request->get('id');

            $type_id = Yii::$app->request->get('type_id');//类型id 1：机构表达 2：部门表单

            if(!in_array($type_id,[1,2])){
                show_json(100000,'部门类型错误，不存在该类型');//部门类型错误，不存在该类型
            }

            $AdminBranch = AdminBranch::findOne($id);


            if(!$AdminBranch){
                show_json(100000,'部门不存在');//部门不存在
            }

            if(intval($type_id) == 1){
                return $this->render('editOrg',['model' => $AdminBranch]);//使用组织表单
            }else{
                return $this->render('editbranch',['model' => $AdminBranch]);//使用部门表单
            }

        }


    }


    /**
     * 切换部门状态  禁用切换正常   正常切换禁用
     * 禁用 部门
     * 只能修改状态 不能真的删除
     * @return array
     */
    public function actionDel()
    {
        $id = Yii::$app->request->get('id');

        Yii::$app->response->format = Response::FORMAT_JSON;

        $u = AdminBranch::find();//查找当前是否有子类 有的话不能删除
        $d = $u->where(['pid'=>$id])->all();

        $u1 = AdminUserBranch::find();//查找当前部门或者组织 是否已经与用户建立绑定关系 如果存在不能删除
        $d1 = $u1->where(['branch_id'=>$id])->all();


        if ($d || $d1) {

            show_json(100000,'不能删除，已经存在绑定关系');//不能删除，已经存在绑定关系

        }
        if(AdminBranch::findOne($id)->delete()){
            show_json(0,'删除成功');
        }else{
            show_json(100000,'删除失败');
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

                $html .= '<li>';

                $html .= '<span data-id="'.$v['id'].'" onclick=btn_branch(this) class="branch_check">'.$v['title'].'--'.$v['principal'].'</span>';//部门名称 负责人

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

    /**
     * 前端选择组织后跳转首页
     */
    public function actionSet_organization()
    {
        $parent_id = Yii::$app->request->post('parent_id');//一级
        $current_id = Yii::$app->request->post('current_id');//所属当前id

        if($this->setOrganizationToSess($parent_id,$current_id)){
            $this->redirect(['/index/index']);
            return ;
        }

        show_json(100000,'设置存储组织id 错误');//设置存储组织id 错误
    }

}