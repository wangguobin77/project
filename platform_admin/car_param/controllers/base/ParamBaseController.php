<?php
namespace app\controllers\base;

use Yii;
use app\models\ParamModel;
class ParamBaseController extends BaseController
{
    /*
     * 获取小车分类列表
     */
    protected function getListDataLogic()
    {
        $data = ParamModel::find()
            ->select('*')
            ->orderBy('parent_id,id')
            ->asArray()
            ->all();

        $tree = [];

        $rootLevel = 0;
        $levelArr = [];
        $resultLevel = [];
        $this->setResultLevelData($data, [$rootLevel], $levelArr, $resultLevel);

        $this->getListDataTree($resultLevel, 0, $tree);
        //var_dump($tree);die;
        return $tree;
    }

    /*
     * 处理树
     */
    private function setResultLevelData($data, $pid, &$levelArr, &$tree)
    {
        for ($i=0;$i<3;$i++) {
            $level = $i + 1;
            foreach ($data as $k=>$v) {
                if (in_array($v['parent_id'], $pid)) {
                    $levelArr[$level][] = $v['id'];
                    $v['level'] = $level;
                    $tree[] = $v;
                    unset($data[$k]);
                }
            }
            $pid = &$levelArr[$level];
        }
    }

    /*
     * 处理树
     */
    private function getListDataTree($data, $level, &$tree)
    {
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $level) {
                $id = $v['id'];
                $tree[] = $v;
                unset($data[$k]);
                //父亲找到儿子
                $this->getListDataTree($data, $id, $tree);
            }
        }
    }

    /*
     * 验证 add ajax param
     */
    protected function checkAddAjaxParamLogic()
    {
        $id = intval(Yii::$app->request->post('id', 0));
        $parentId = intval(Yii::$app->request->post('parent_id', 0));
        $cnname = escape(Yii::$app->request->post('cnname'), 128 ,1);
        if (!$cnname) {
            show_json(100002, $this->getErrMessage(100002)); // 参数中文名称要在1-128个字符之间
        }
        $paramName = escape(Yii::$app->request->post('param_name'), 128 ,1);
        if (!$paramName) {
            show_json(100006, $this->getErrMessage(100006)); // 参数英文名称要在1-128个字符之间
        }
        $paramValue = escape(Yii::$app->request->post('param_value'), 128 ,1);
        if (!$paramValue) {
            show_json(100007, $this->getErrMessage(100007)); // 参数值要在1-128个字符之间
        }

        $id = $id > 0 ? $id : 0;
        $parentId = $parentId > 0 ? $parentId : 0;

        return [
            'id'    =>  $id,
            'parentId'=>$parentId,
            'cnname'    =>  $cnname,
            'paramName'    =>  strtoupper($paramName),
            'paramValue'  =>  $paramValue,
        ];
    }

    /*
     * 添加新分类
     */
    protected function addCategoryLogic($param)
    {
        $model = new ParamModel();

        return $model->addCategoryModel($param['id'],$param['parentId'], $param['paramName'], $param['cnname'], $param['paramValue']);
    }


    /*
     * 删除　category
     */
    protected function deleteCategoryLogic($id)
    {
        $model = new ParamModel();
        return $model->deleteCategory($id);

    }

    protected function getParamOne($id)
    {
        try {
            return ParamModel::find()
                ->select('a.*,b.cnname as parent_name')
                ->from('category a')
                ->join('left join', 'category b', 'a.parent_id=b.id')
                ->where(['a.id'=>$id])
                ->asArray()
                ->one();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 添加修改分类
     */
    protected function addCategory()
    {
        $param = $this->checkAddAjaxParamLogic();
        $code = $this->addCategoryLogic($param);
        show_json($code, $this->getErrMessage($code));
    }

    /**
     * 通过分类查找关联
     */
    protected function getExistsRelationByCategoryId($categoryId)
    {
        return (new ParamModel())->getExistsRelationByCategoryId($categoryId);
    }
}