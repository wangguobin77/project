<?php

// +----------------------------------------------------------------------
// | TITLE: 角色
// +----------------------------------------------------------------------

namespace app\controllers;

use yii\helpers\Url;
use app\helps\Tree;
use app\models\AdminRule;
use Yii;
use app\models\AdminRole;
use app\models\AdminUser;
use app\models\AdminRoleRule;
use app\models\AdminUserRole;
use yii\web\Response;

class AdminRoleController extends BaseController
{

    public $check_roleId_arr = [];
    public function init()
    {
        $this->layout = false;
    }
    /**
     * 角色列表
     * @return string
     */
    public function actionIndex()
    {
        /* $AdminRole = AdminRole::find();
         $models = $AdminRole->all();
         return $this->render('role_list', ['model' => $models]);*/
        /* $AdminRole = AdminRole::find();
         $models = $AdminRole->all();*/

        $models = AdminRole::find()->asArray()->all();

        $data = Tree::makeTree($models);

        $b = $this->orderData($models);
        $i = $this->getCookieV();

        if($i) $this->check_roleId_arr =  array_merge($this->getPidArr($b,$i),[$i]);

        return $this->render('roleMan', ['model' => $this->getRoleListView($data),'pid'=>0,'i'=>$i,'tt'=>json_encode($this->check_roleId_arr)]);



    }

    protected function getCookieV()
    {
        return isset($_COOKIE['glo_id']) ? $_COOKIE['glo_id'] : '';
    }

    /**
     * 获取角色列表
     * @param $data
     * @param string $str
     * @param int $index
     * @param int $t_index
     * @return string
     */
    protected static $index_k = 0;
    protected function getRoleListView($data,$str='',$index=0,$t_index=1)
    {
        if(!$data) return $str;
         foreach ($data as $key=>$val) {
            if($val['status'] == 1){
                ++self::$index_k;
                $open_class = '';
                $style = '';
                if(in_array($val['id'],$this->check_roleId_arr)){$open_class='treegrid-expanded';$style='style="display:null"';}
                if(isset($val['pid']) && $val['pid'] == 0){//父类一级菜单 每个项目的顶级菜单
                    $str .= '<tr class="treegrid-'.self::$index_k.' '.$open_class.'" '.$style.'>';
                }else{
                    $str .= '<tr class="treegrid-'.self::$index_k.' treegrid-parent-'.$t_index.' '.$open_class.'" '.$style.'>';
                }
                if ($val['pid'] == 0) {

                    $str .= ' <td>';
                    $str .= '<div class="text-box sl" contenteditable="false" style="font-size: 12px;">';
                    $str .= $val['name'];
                    $str .= '</div>';
                    $str .= '</td>';

                    $str .= ' <td class="opr-box">';
                    $str .= '<div class="czuo-box">';
                    $str .= '<a class="add-btn" data-id="'.$val['id'].'" onclick="add(this)">添加</a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a class="edit-btn" href="javascript:;" data-name="'.$val['name'].'" data-id="'.$val['id'].'" onclick="edit(this)">编辑</a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a class="del-btn" data-id="'.$val['id'].'" onclick="del(this)">删除</a>';
                   /* $str .= '<span class="xian"></span>';
                    $str .= '<a href="'.Url::toRoute(['admin-role/rule-info','role_id'=>$val['id']]).'" class="distribute-btn">分配权限</a>';*/
                    $str .= '</div>';
                    $str .= '</td>';
                    $str .= '</tr>';

                }else {

                    //$str .= '<tr class="'.$style.' treegrid-parent-'.$t_index.'">';
                    $str .= '<td> '.$val['name'].'</td>';
                    $str .= '<td class="opr-box">';
                    $str .= '<div >';
                    $str .= '<a class="add-btn" data-id="'.$val['id'].'" onclick="add(this)">添加</a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a class="edit-btn" href="javascript:;" data-name="'.$val['name'].'" data-id="'.$val['id'].'" onclick="edit(this)">编辑</a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a class="del-btn" data-id="'.$val['id'].'" onclick="del(this)">删除</a>';
                    $str .= '<span class="xian"></span>';
                    $str .= '<a href="'.Url::toRoute(['admin-role/rule-info','role_id'=>$val['id']]).'" class="distribute-btn">分配权限</a>';
                    $str .= '</div>';
                    $str .= '</td>';
                    $str .= '</tr>';

                }


                if (isset($val['children']) && is_array($val['children'])) {
                    $str .= $this->getRoleListView($val['children'], '', self::$index_k, self::$index_k);
                }
            }

        }

        return $str;
    }

    /**
     * @param $data
     * @param string $str
     * @param int $index
     * @param int $t_index
     * @return string
     */
    protected function getRuleListView($data,$str='',$index=0,$t_index=1)
    {
        foreach ($data as $key=>$val) {
            if($val['checked'] == 1){
                $v = 'checked';
            }else{
                $v = '';
            }
            if($val['status'] == 1){
                ++$index;

                if (isset($val['children']) && is_array($val['children'])) {
                    $style = 'treegrid-'.$index;
                }else{
                    $style = '';
                }
                if ($val['pid'] == 0) {
                    $str .= '<tr class="treegrid-'.$index.'">';

                    $str .= ' <td>';
                    $str .= '<input type="checkbox" '.$v.' name="item[]"  onchange="change_set_role(this)" value="'.$val['id'].'" class="slelct_checkbox" />';
                    $str .= $val['title'];

                    $str .= '</td>';

                    $str .= '</tr>';

                }else {

                    $str .= '<tr class="'.$style.' treegrid-parent-'.$t_index.'">';
                    $str .= '<td> ';
                    $str .= '<input type="checkbox" '.$v.' name="item[]"  onchange="change_set_role(this)" value="'.$val['id'].'" class="slelct_checkbox" />';
                    $str .= $val['title'];
                    $str .= '</td>';

                    $str .= '</tr>';

                }


                if (isset($val['children']) && is_array($val['children'])) {
                    $str .= $this->getRuleListView($val['children'], '', $index, $index);
                }
            }

        }


        return $str;
    }

    /**
     * 展示所有的 渲染页面
     * @return array|mixed
     */
    public function actionRuleInfo_xx()
    {
        $current_role_id = Yii::$app->request->get('role_id');//前端将要设置的角色id

        if(!$current_role_id){
            show_json(100000,'缺少参数');//缺少参数
        }

        $role_all_rule_id = AdminRoleRule::getRuleId($current_role_id);//根据角色id获取拥有的路由权限
        $data_new = [];
        if($this->menu){
            foreach ($this->menu as $key=>$val){
                if(in_array($val['id'],$role_all_rule_id)){
                    $val['checked'] = 1;//选中状态

                }else{
                    $val['checked'] = 0;
                }
                $data_new[$key] = $val;
            }

            $data_new_str = $this->getRuleListView(Tree::makeTree($data_new));//获取登陆着者的角色所有拥有的权限 方便给其他用户设置自己权限范围类的权限点
        }
        //var_dump($data_new_str);die;
       /* echo '<pre>';
        print_r($data_new_str);die;*/
        return $this->render('select_rule',['rule_list'=>$str,'role_id'=>$current_role_id]);
    }

    /**
     *为角色 添加指定的路由权限点
     */
    /*public function actionAddRuleRole()
    {
        $role_id = Yii::$app->request->post('role_id');//前端将要设置的角色id


        $rule_list = Yii::$app->request->post('item');//勾选的rule

        if(!$role_id){
            show_json(100000,'Lack of parameter');
        }

        if(!$rule_list || !is_array($rule_list)){
            show_json(100000,'Lack of parameter');
        }


        $rule_list = implode(',',$rule_list);

        if((new AdminRoleRule)->addRoleRuleId($role_id,$rule_list)){
            show_json(0,'add role bind rule success');
        }
        show_json(100000,'add role bind rule error');
    }*/
    public function actionAddRuleRole()
    {
        $role_id = Yii::$app->request->post('role_id');//前端将要设置的角色id

        $type_id = Yii::$app->request->post('type');//1是添加  2是删除

        if(!$role_id){
            show_json(100000,'缺少参数');
        }

        $rule_list = $this->getRuleFromRoleId($role_id);

        $one_rule_id = Yii::$app->request->post('rule_id');//勾选的rule 单条

        if($type_id == 1){
            if(count($rule_list) <= 0) $rule_list = [];//声明空数组

            array_push($rule_list,$one_rule_id);


        }elseif ($type_id == 2){
            $rule_list = array_diff($rule_list,[$one_rule_id]);
        }else{
            show_json(100000,'角色与路由绑定错误');
        }
        if(!is_array($rule_list)){
            show_json(100000,'缺少参数');
        }

        if(empty($rule_list)){//空数组
            if(AdminRoleRule::deleteAll(['role_id'=>$role_id])){
                show_json(0,'角色绑定权限成功');
            }//删除该角色的所有相关路由
            show_json(100000,'角色绑定权限失败');
        }

        $rule_list = implode(',',$rule_list);
        //echo $rule_list;die;
        if((new AdminRoleRule)->addRoleRuleId($role_id,$rule_list)){

            show_json(0,'角色绑定权限成功');
        }
        show_json(100000,'角色绑定权限失败');
    }

    //组装前端的权限html
    protected function addRulePidTitle($menu,$str = '')
    {
        foreach ($menu as $key=>$val){
            if($val['checked'] == 1){
                $v = 'checked';
            }else{
                $v = '';
            }
            if($val['pid'] == 0){
                $str .= '<h4 class="T0LSTittle row xz">';
                /*  $str .= ' <input type="checkbox" name="item">';*/
                $str .= '<input type="checkbox" '.$v.' name="item[]"  onchange="change_set_role(this)" value="'.$val['id'].'">';
                $str .= ' <label for="item"></label>';
                $str .= $val['title'];
                $str .= '</h4>';

                if(isset($val['children']) && $val['status'] == 1){
                    $str .= '<div class="row ">';
                    $str .= $this->addRulePidTitle($val['children']);
                    $str .= '</div>';
                }
            }else{
                $str .= '<div class="xz col-md-4 col-xs-6">';

                $str .= '<input type="checkbox" '.$v.' name="item[]"  onchange="change_set_role(this)" value="'.$val['id'].'">';
                $str .= '<label for="item"></label>';
                $str .= '<h5>'.$val['title'].'</h5>';
                $str .= '</div>';

                if(isset($val['children']) && $val['status'] == 1){
                    $str .= $this->addRulePidTitle($val['children']);
                }

            }
        }

        return $str;
    }

    /**
     * 创建角色
     * @return array|string
     */
    public function actionCreate()
    {
        $AdminRole = new AdminRole;
        if (Yii::$app->request->isPost) {

            Yii::$app->response->format = Response::FORMAT_JSON;

            $AdminRoleData = Yii::$app->request->post();

            //职位名称不能相同
            $is_info = AdminRole::find()->where(['name'=>$AdminRoleData['name']])->asArray()->one();

            if($is_info)  show_json(100000,'角色名称已经存在');//角色名称已经存在

            $AdminRole->pid = $AdminRoleData['pid'];
            $AdminRole->name = $AdminRoleData['name']?$AdminRoleData['name']:show_json(100000,'lack name params');
            $AdminRole->code = '001';//战时无用字段
            $AdminRole->des = '';//战时无用字段

            $AdminRole->create_date = time();
            $AdminRole->update_date = time();
            $AdminRole->status = 1;//默认启用

            if ($AdminRole->save()) {
                show_json(0,'添加角色成功');
            }

            show_json(100000,'添加角色失败');

        }

        show_json(100000,'请求格式错误');

    }


    /**
     * 更新角色
     * @return array|string
     */
    public function actionUpdate()
    {

        $id = Yii::$app->request->post('id');
        $AdminRole = AdminRole::findOne($id);

        if(!$AdminRole){
            show_json(100000,'角色用户信息不存在');
        }
        if (Yii::$app->request->isPost) {

            $AdminRoleData = Yii::$app->request->post();

            //职位名称不能相同
            $is_info = AdminRole::find()->where(['name'=>$AdminRoleData['name']])->asArray()->one();

            if($is_info)  show_json(100000,'The role name already exists');//角色名称已经存在


            $AdminRole->name = $AdminRoleData['name']?$AdminRoleData['name']:show_json(100000,'lack name params');

            $AdminRole->update_date = time();

            if ($AdminRole->save()) {
                show_json(0,'更新角色成功');
            }
            show_json(100000,'更新角色失败');
        }
        show_json(100000,'请求方式错误');


    }


    /**
     * 选择该角色下的用户
     * @return string
     */
    public function actionCheck_user()
    {
        if(Yii::$app->request->isPost){

            $role_id = Yii::$app->request->post('role_id');

            $member_list_id = Yii::$app->request->post('members');

            if(!$role_id){
                show_json(100000,'缺少角色id参数');
            }

            if(!$member_list_id){
                if(!AdminUserRole::deleteAll("role_id= $role_id")){
                    show_json(100000,'error!');
                }
            }else{
                $select = implode(",", $member_list_id);

                $AdminRole = new AdminRole;

                if(!$AdminRole->addUserRoleFromRoleId($role_id,$select)){
                    show_json(100000,'error!');
                }
            }

            show_json(0,'success');
        }else{
            $id = Yii::$app->request->get('id');

            $AdminRole = AdminRole::findOne($id);

            if(!$AdminRole){
                show_json(100000,'当前角色信息不存在');
            }

            //查询所有的用户 按部门分组

            $user_list = $this->getUserAll($id);//获取所有的用户

            return $this->render('select_role',['user_list'=>$user_list,'role_id'=>$id]);
        }

    }




    /**
     * 根据角色id 获取用户id 列表 前端只需判断该用户的id是否在在当前存在
     * @param $role_id
     * @return array
     */
    protected function getRoleToUser($role_id)
    {
        //判断该用户名是否已经存在
        $role_id_list = AdminUserRole::find()->where(['role_id'=>$role_id])->all();//获取用户已经拥有的角色id

        $new_list = [];
        if($role_id_list){
            foreach ($role_id_list as $key=>$val){
                array_push($new_list,$val['uid']);
            }
        }

        return $new_list;
    }

    /**
     * 获取所有的用户 按部门分组
     * @return array|\yii\db\ActiveRecord[]
     */
    protected function getUserAll($role_id)
    {
        $AdminRule = AdminUser::find();
        $AdminRule->where(['status' => 1]);

        $user_list = $AdminRule->asArray()->all();

        $checked_user_list = $this->getRoleToUser($role_id);//获取改角色下已经被选中的用户

        $new_list = [];
        if($user_list){
            foreach ($user_list as $key=>$val){

                if($val['id'] == 1 || $val['username'] == 'admin'){
                    continue;//系统用户不参与选中
                }

                if(in_array($val['id'],$checked_user_list)){
                    $val['state'] = 'checked';//锁定被选中的用户
                }else{
                    $val['state'] = '';
                }

                if(isset($new_list[$val['branch_id']])){
                    array_push($new_list[$val['branch_id']],$val);
                }else{
                    $new_list[$val['branch_id']][] = $val;
                }

            }
        }

        return $new_list;
    }

    public function getRoleIdFromRuleInfo($role_id)
    {
        $AdminRoleRule = AdminRoleRule::find()->where(['role_id' => $role_id])->asArray()->all();

        if($role_id == 1){//超级管理员
            $sql     = "select * from admin_rule";//超级管理员直接获取所有权限带你

        }else{
            $sql     = "select a.rule_id,a.role_id,b.id,b.pid,b.title.b.is_show,b.status form admin_role_rule a left join admin_rule b on a.rule_id = b.id where b.is_show =1 and b.status=1 and a.role_id=".$role_id;

        }
        $connection  = Yii::$app->db;

        $command = $connection->createCommand($sql);
        $model     = $command->queryAll();
    }

    /**
     * 删除 角色
     * @return array
     */
    public function actionDelete()
    {
        $id = Yii::$app->request->post('role_id');
        $AdminRole = AdminRole::findOne($id);

        $role_list = AdminRole::find()->where(['pid'=>$id])->asArray()->all();//查询该角色下是否存在子类

        if($role_list){
            show_json(100000,'不能删除，角色下存在子类或者已经关联权限');//不能删除，角色下存在子类或者已经关联权限
        }

        $role_rule_list = AdminRoleRule::find()->where(['role_id'=>$id])->asArray()->all();//角色已经关联的路由权限点

        if($role_rule_list){
            show_json(100000,'不能删除，角色下存在子类或者已经关联权限');//不能删除，角色下存在子类或者已经关联权限
        }

        if($id == 1){//admin 系统角色不能操作
            show_json(100000,'There is no permission to delete the role');
        }

        if(!$AdminRole){
            show_json(100000,'角色信息不存在！');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($AdminRole) {

            // $AdminRole->status = 0;//默认启用

            if($AdminRole->delete()){
                show_json(0,'删除角色成功');
            }
        }
        show_json(100000,'删除角色失败');

    }

    /**
     * 处理遍历多级分类
     * 父级洗面有多个子目录
     */
    protected function disChildren($data)
    {
        $data = Tree::makeTree($data);//把所有部门数据按等级分成树形结构

        /* if(isset($data[0]['children'])){
             $data = $data[0]['children'];
         }*/


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

                $html .= '<span data-id="'.$v['id'].'" onclick=btn_branch(this) class="branch_check">'.$v['name'].'</span>';//部门名称 负责人

                if ($v['status'] == 1) {

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
     * 设置角色拥有权限
     * @return array|string
     */
    /* public function actionSetRule()
     {

         $roleId = Yii::$app->request->get('id');
         if (empty($roleId)) {
             Yii::$app->response->format = Response::FORMAT_JSON;
             show_json(100000,'params error');
         }
         $model = AdminRole::findOne($roleId);
         $model->rule = explode(',', $model->rule);
         if (Yii::$app->request->post()) {
             $rule = Yii::$app->request->post('rule');
             $rule = array_filter($rule);
             krsort($rule);
             $rule = implode(',', $rule);

             $model->rule = $rule;
             Yii::$app->response->format = Response::FORMAT_JSON;
             if ($model->save()) {
                 show_json(0,'save success');
             } else {
                 show_json(100000,'save error');
             }

         } else {
             $ruleAll = AdminRule::find()->where(['status' => 1])->asArray()->all();
             $ruleAll = array_map(function ($item) use ($model) {
                 (in_array($item['id'], $model->rule)) ?
                     $item['state'] = ['checked' => true] : '';
                 $item['text'] = $item['title'];
                 return $item;
             }, $ruleAll);
             $ruleAll = Tree::makeTree($ruleAll, ['children_key' => 'nodes']);
            return $this->render('setRule', ['ruleAll' => $ruleAll, 'model' => $model]);

         }

     }*/

    public function actionRuleInfo()
    {

        $roleId = Yii::$app->request->get('role_id');
        if (empty($roleId)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            show_json(100000,'缺少参数');
        }
        $model = AdminRole::findOne($roleId);

        $rule_list = $this->getRuleFromRoleId($roleId);

        if (Yii::$app->request->post()) {
            $rule = Yii::$app->request->post('rule');

            if(!$rule){
                show_json(100000,'没有选择任何路由权限');//没有选择任何路由权限
            }

            $this->disAddRoleRuleInfo($roleId,$rule);

            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->save()) {
                show_json(0,'保存成功');
            } else {
                show_json(100000,'保存失败');
            }

        } else {
            $ruleAll = AdminRule::find()->where(['status' => 1])->asArray()->all();
            $ruleAll = array_map(function ($item) use ($rule_list) {
                (in_array($item['id'], $rule_list)) ?
                    $item['state'] = ['checked' => true] : '';
                $item['text'] = $item['title'];
                return $item;
            }, $ruleAll);
            $ruleAll = Tree::makeTree($ruleAll, ['children_key' => 'nodes']);

         /*   return $this->render('setRule', ['ruleAll' => $ruleAll, 'model' => $model,'rule_list'=>$rule_list]);*/
            return $this->render('select_rule', ['ruleAll' => $ruleAll, 'model' => $model,'rule_list'=>$rule_list,'role_id'=>$roleId]);

        }

    }

    /**
     * 处理角色与路由的关系  拥有一条路由权限就是一条记录
     * 添加角色与路由关系
     * @param $data
     */
    protected function disAddRoleRuleInfo($roleId,$rule_id_arr)
    {
        if(is_array($rule_id_arr) && count($rule_id_arr) > 0){
            $rule = array_filter($rule_id_arr);
            krsort($rule);
            $rule = implode(',', $rule);
            $AdminRoleRule = new AdminRoleRule;


            if(!$AdminRoleRule->addRoleRuleId($roleId,$rule)){
                show_json(100000,'error!');
            }
        }else{
            if(!AdminRoleRule::deleteAll("role_id= $roleId")){
                show_json(100000,'error!');
            }
        }

        show_json(0,'add role and rule success');
    }

    /**
     * 根据角色id 获取关系表中的所有路由 将二维数组 拆解成一维 rule_id数组
     * @param $roleId
     */
    protected function getRuleFromRoleId($roleId)
    {
        if($roleId && $roleId == 1){
            show_json(100000,'adminstrator no set');//管理员账号不能设置
        }

        $rule_id_list = AdminRoleRule::find()->where(['role_id' => $roleId])->asArray()->all();//获取所有路由id

        $new_list = [];
        if(!$rule_id_list){
            return $new_list;
        }

        foreach ($rule_id_list as $key=>$val){
            array_push($new_list,$val['rule_id']);
        }

        return $new_list;
    }

}