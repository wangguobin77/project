<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-06
 * Time: 09:51
 */

namespace app\controllers\bus;


use app\models\batch\ARLeapid;
use common\consequence\ErrorCode;
use common\exception\GanWuException;
use yii\data\Pagination;
use yii\db\Exception;

class leapidBus
{
    /**
     * 根据批次 id 获取列表
     * @param $batch_id
     * @param $params   //查询参数
     * @return array
     */
    public function getList($batch_id, $params)
    {
        $query = ARLeapid::find()
            ->where(['batch_id' => $batch_id]);

        $queryCount = clone $query;
        $count = $queryCount->count();

        $pages = new Pagination(['defaultPageSize'=>10, 'totalCount' => $count]);

        $datas = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id asc')
            ->asArray()
            ->all();

        return ['datas' => $datas, 'pages' => $pages];
    }

    /**
     * 批量设置 leapid 用途
     *
     * @param $start_id
     * @param $end_id
     * @param $use
     * @throws GanWuException
     * @throws \yii\db\Exception
     */
    public function setUsing($start_id, $end_id, $using)
    {
        try{
            $sql = 'update `leapid` set `using`=:using where `id` between :start_id and :end_id';
            ARLeapid::getDb()->createCommand($sql)->bindValues([
                ':using' => $using,
                ':start_id' => $start_id,
                ':end_id' => $end_id,
            ])->execute();
        } catch (Exception $e) {
            throw new GanWuException('设置失败,请重新尝试', ErrorCode::ERROR);

        }

    }
}