<?php
namespace app\controllers\base;

use Yii;
use yii\data\Pagination;
use app\models\IniModel;
use app\models\ParamModel;
class IniBaseController extends BaseController
{
    /*
     * ini 文件列表
     */
    public function getListDataLogic()
    {
        $query = IniModel::find()->select('*');
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize'=>Yii::$app->params['default_page_size']]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        return [
            'data'  =>  $models,
            'pages' =>  $pages,
        ];
    }

    /*
    * 验证 add/edit ini ajax 参数
     * 120001
    */
    public function checkAddEditIniAjaxParamLogic()
    {
        $data['file_name'] = escape(Yii::$app->request->post('filename'), 32 ,1);
        $data['ini_content'] = escape(Yii::$app->request->post('content'), 6*1024 ,1);
        $data['crc32'] = crc32($data['ini_content']);
        $data['desc'] = escape(Yii::$app->request->post('desc'), 255 ,1);
        $data['category_id'] = intval(Yii::$app->request->post('category'));

        if ($data['category_id'] < 0) {
            show_json(100015, $this->getErrMessage(100015));
        }

        if (!$data['file_name']) {
            show_json(120002, $this->getErrMessage(120002));
        }
        if (!$data['desc']) {
            show_json(120004, $this->getErrMessage(120004));
        }
        if (!$data['ini_content']) {
            show_json(120005, $this->getErrMessage(120005));
        }
        return $data;

    }

    /*
     * add ini ajax
     */
    public function addIniAjaxLogic()
    {
        try {
            $data = $this->checkAddEditIniAjaxParamLogic();
            $model = new IniModel();
            if ($model->load(['IniModel'=>$data]) && $model->save()) {
                show_json(0, $this->getErrMessage(0));
            }
            $error = current($model->getErrors());
            if ($error) {
                show_json(100000, $error);
            }
            throw new \Exception();
        } catch (\Exception $e) {
            print_r($e->getMessage());
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
    * edit ini ajax
    */
    public function editIniInfoAjaxLogic()
    {
        try {
            $data = $this->checkAddEditIniAjaxParamLogic();
            $data['id'] = trim(Yii::$app->request->post('id'));
            $model = IniModel::findOne($data['id']);
            if (!$model) {
                show_json(120007, $this->getErrMessage(120007));
            }
            $model->ini_content = $data['ini_content'];
            $model->file_name = $data['file_name'];
            $model->desc = $data['desc'];
            $model->crc32 = $data['crc32'];
            if ($model->save()) {
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception();
        } catch (\Exception $e) {
            print_r($e->getMessage());
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /*
     * 处理树
     */
    private function getListDataTree($data, $level)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $level) {
                $id = $v['id'];
                $v['child'] = $this->getListDataTree($data, $id);
                $tree[] = $v;
                unset($data[$k]);
            }
        }
        return $tree;
    }

    protected function addIniRelation()
    {
        $iniId = trim(Yii::$app->request->post('ini_id'));
        $categoryId = trim(Yii::$app->request->post('category'));
        $param = Yii::$app->request->post('param');
        if (empty($param) || !is_array($param)) {
            show_json(100015, $this->getErrMessage(100015));
        }
        $iniInfo = IniModel::findOne($iniId);
        if (!$iniInfo) {
            show_json(120007, $this->getErrMessage(120007));
        }

        $category = ParamModel::find()
            ->where(['in', 'id', $param])
            ->asArray()
            ->all();
        $param = array_values(array_filter($param));
        if (array_column($category, 'id') !== $param) {
            show_json(100013, $this->getErrMessage(100013));
        }
        $values = array_column($category, 'category_value');
        ksort($values);
        $hash = md5(implode('', $values));
        $exists = (new \yii\db\Query())
            ->select('b.group_name,c.file_name,c.desc')
            ->from('ini_files_group a')
            ->join('left join', 'group b', 'a.category_group_id=b.id')
            ->join('left join', 'ini_files c', 'a.ini_id=c.id')
            ->where(['category_hash'=>$hash])
            ->all();
        if ($exists) {
//            show_json(120012, json_encode($exists));
            show_json(120012, $this->getErrMessage(120012));
        }
        $tr = Yii::$app->db->beginTransaction();
        try {
            $rs1 = Yii::$app->db->createCommand()->insert('group', ['group_name'=>json_encode(array_column($category, 'cnname', 'id'))])->execute();
            if (!$rs1) {
                throw new \Exception('group fail');
            }
            $groupId = Yii::$app->db->getLastInsertID();

            $categoryGroupField = ['group_id', 'category_id'];
            $categoryGroupData = [];
            foreach ($param as $v) {
                $categoryGroupData[] = [$groupId, $v];
            }
            $rs2 = Yii::$app->db->createCommand()->batchInsert('category_group', $categoryGroupField, $categoryGroupData)->execute();
            if (!$rs2) {
                throw new \Exception('category_group fail');
            }

            $iniFilesGroupData = ['ini_id'=>$iniId, 'category_group_id'=>$groupId, 'category_hash'=>$hash, 'category_root_id'=>$categoryId];
            $rs3 = Yii::$app->db->createCommand()->insert('ini_files_group', $iniFilesGroupData)->execute();
            if (!$rs3) {
                throw new \Exception('ini_files_group fail');
            }
            $redis = Yii::$app->redis;
            $redis->set('car_param:'.$hash, $iniInfo['ini_content']);
            $tr->commit();
            show_json(0, $this->getErrMessage(0));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /**
     * 查找ini文件的关联配置
     * @param $id
     * @return array|bool
     */
    protected function getExistsRelation($id)
    {
        return (new IniModel())->getExistsRelation($id);
    }

    /**
     * 删除关联
     */
    protected function deleteIni()
    {
        $id = trim(Yii::$app->request->post('id'));
        $iniInfo = IniModel::findOne($id);
        if (!$iniInfo) {
            show_json(120013, $this->getErrMessage(120013));
        }
        //ini文件关联了分类不能删除
        if ($this->getExistsRelation($id)) {
            show_json(100000, 'ini文件下存在关联，不能删除');
        }
        $tr = Yii::$app->db->beginTransaction();
        try {
            $hashs = Yii::$app->db->createCommand("select `category_hash` from `ini_files_group` where `ini_id` =".$iniInfo['id'])->queryAll();
            $sql = "delete a,b,c,d from `ini_files` a left outer join `ini_files_group` b on b.ini_id=a.id left outer join `group` c on c.id=b.category_group_id left outer join `category_group` d on d.group_id=c.id where a.id =".$iniInfo['id'];
            $result = Yii::$app->db->createCommand($sql)->execute();
            if ($result) {
                if ($hashs) {
                    $redis = Yii::$app->redis;
                    foreach ($hashs as $hash) {
                        $redis->del('car_param:'.$hash['category_hash']);
                    }
                }
                $tr->commit();
                show_json(0, $this->getErrMessage(0));
            }
            throw new \Exception('');
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    /**
     * 删除关联
     */
    protected function deleteIniRelation()
    {
        $id = trim(Yii::$app->request->post('id'));
        $iniInfo = (new \yii\db\Query())
            ->select('*')
            ->from('ini_files_group')
            ->where(['id'=>$id])
            ->One();
        if (!$iniInfo) {
            show_json(120013, $this->getErrMessage(120013));
        }
        $tr = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand()->delete('group', 'id=:group_id', [':group_id' => $iniInfo['category_group_id']])->execute();
            Yii::$app->db->createCommand()->delete('category_group', 'group_id=:group_id', [':group_id' => $iniInfo['category_group_id']])->execute();
            Yii::$app->db->createCommand()->delete('ini_files_group', 'id=:group_id', [':group_id' => $iniInfo['id']])->execute();
            $redis = Yii::$app->redis;
            $redis->del('car_param:'.$iniInfo['category_hash']);
            $tr->commit();
            show_json(0, $this->getErrMessage(0));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $tr->rollBack();
            show_json(100000, $this->getErrMessage(100000));
        }
    }

    protected function getIniInfo($id)
    {
        try {
            return IniModel::findOne($id);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getFirstCategory()
    {
        return ParamModel::find()
            ->where(['parent_id'=>0])
            ->asArray()
            ->all();
    }

    protected function getCategoury($categoryId)
    {
        try {
            $data = ParamModel::find()
                ->where(['or', ['id'=>$categoryId], ['in', 'root', $categoryId]])
                ->asArray()
                ->all();
            return $data ? $this->getCategoryTree($data, $categoryId) : array();
        } catch (\Exception $e) {
            return array();
        }
    }

    protected function getCategoryTree($data, $categoryId)
    {
        if (empty($data)) return $data;
        $category['info'] = array();
        $category['children'] = array();
        foreach ($data as $key=>$item) {
            if ($item['id'] == $categoryId) {
                $category['info'] = $item;
                unset($data[$key]);
                break;
            }
        }
        $category['children'] = $this->getListDataTree($data, $category['info']['id']);
        return $category;
    }
}