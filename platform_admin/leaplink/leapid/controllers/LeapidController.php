<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-11-06
 * Time: 09:43
 */

namespace app\controllers;

use common\consequence\ErrorCode;
use common\consequence\Result;
use common\exception\GanWuException;
use Yii;
use app\controllers\base\BaseController;
use app\controllers\bus\leapidBus;
use common\helpers\Utils;
use common\helpers\ArrayHelper;

class LeapidController extends BaseController
{
    public $layout = false;
    public function actionList()
    {
        $bus = new leapidBus();
        $params = Utils::getHttpInput();
        $batch_id = (int)(ArrayHelper::getNoEmpty($params, 'batch_id'));
        $ret = $bus->getList($batch_id, $params);

        return $this->render('list', ['datas' => $ret['datas'], 'pages' => $ret['pages'], 'params'=>$params]);
    }

    //设置用途
    public function actionSetUse()
    {
        $params = Utils::getHttpInput();
        if (Yii::$app->request->isPost) {
                $ret = new Result();
            try{
                $start_id = (int)(ArrayHelper::getNoEmpty($params, 'start_id'));
                $end_id = (int)(ArrayHelper::getNoEmpty($params, 'end_id'));
                $using = (int)(ArrayHelper::getNoEmpty($params, 'using'));

                if ($start_id > $end_id){
                    throw new GanWuException('开始leapid必须小于结束leapid', ErrorCode::ERROR);
                }
                if (!$start_id) {
                    throw new GanWuException('开始leapid不能为空', ErrorCode::ERROR);
                }
                if (!$end_id) {
                    throw new GanWuException('结束leapid不能为空', ErrorCode::ERROR);
                }
                if (!$using) {
                    throw new GanWuException('请选择用途', ErrorCode::ERROR);
                }

                $bus = new leapidBus();
                $bus->setUsing($start_id, $end_id, $using);

            }catch (GanWuException $e){
                $ret->code = $e->getCode();
                $ret->message = $e->getMessage();
            }catch (\Exception $e){
//                $ret->code = ErrorCode::ERROR;
//                $ret->message = '系统错误';
                throw $e;
            }
            return $ret;
        }

        return $this->render('set', ['params' => $params]);
    }
}