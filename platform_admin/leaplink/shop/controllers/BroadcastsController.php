<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/8/12
 * Time: 上午10:47
 */

namespace app\controllers;

use common\helpers\RedisHelper;
use common\helpers\UtilsHelper;
use yii;

use  common\util\IFilter;
use  common\util\IReq;

use common\consequence\Result;
use app\models\broadcasts\Broadcast;
use app\models\broadcasts\BroadcasrBase;
use app\models\broadcasts\BroadcastCoupons;

use common\helpers\ArrayHelper;
use common\helpers\StringsHelper;
use common\exception\UnavailableParamsException;
use common\util\IFile;
//广告
class BroadcastsController extends BaseController
{

    const  PARAMERRORCODE_TITLE = 2000010;
    const  DATAEXIST_TITLE = 2000011;
    const  CHECK_STATUS_YES = 2000012;
    const  CHECK_STATUS_NO = 2000013;
    const  BROADCAST_NO_DATA = 1000050;
    const  COUPONLIST_NO_DATA = 1000051;

    public $msg = [
        self::PARAMERRORCODE_TITLE => '标题参数不能为空',
        self::DATAEXIST_TITLE => '当前商家下该条广告名称已经存在，标题不能重名',
        self::CHECK_STATUS_YES => '广告审核通过',
        self::CHECK_STATUS_NO => '广告审核失败',
        self::BROADCAST_NO_DATA => '广告数据不存在',
        self::COUPONLIST_NO_DATA => 'coupon 列表为空，不能与广告关联',

    ];

    /**
     * 广告列表
     */
    public function actionList(){

        list($datas, $pages,$coupon_list) = (new BroadcasrBase)->getBroadcastsList($this->shopId);

        return $this->render('list', [
            'datas' => $datas,
            'coupon_list' => $coupon_list,
            'pages' => $pages,
        ]);
    }

    /**
     * 添加广告
     */
    public function actionAdd(){


        if(Yii::$app->request->isPost){ //提交表单

            $ret = new Result();

            $title = IFilter::act(IReq::get('name'));//标题
            $content = IFilter::act(IReq::get('content'));//内容
            $desc_short = IFilter::act(IReq::get('desc_short'));//简短描述
            $cover = IFilter::act(IReq::get('cover'));//封面
            //$url = IFilter::act(IReq::get('url'));//链接地址

            //$coupon_id = IFilter::act(IReq::get('cid'));//优惠券id
            if(!$title){
                $ret->code = 2000010;
                $ret->msg = $this->getMsgInfo(2000010);
                return $ret;
            }

            //判断相同同一个商家下广告名称是否存在
            $oneInfo = Broadcast::find()->where(['shop_id'=>$this->shopId,'title'=>$title])->one();
            if($oneInfo){
                $ret->code = 2000011;
                $ret->msg = $this->getMsgInfo(2000011);
                return $ret;
            }

            try{
                $Broadcast = new Broadcast;
                $time = time();

                $Broadcast->id = StringsHelper::createId();
                $Broadcast->shop_id = $this->shopId;//商家id
                $Broadcast->shop_name = $this->loginUserInfo['name'];//商家name
                $Broadcast->title = $title;//标题
                $Broadcast->desc_short = $desc_short;//简短描述
                $Broadcast->cover = $cover;//封面图
                $Broadcast->conts = trim($content);
                $Broadcast->url = '';//临时为空
                $Broadcast->status = 1;//广告需要审核 审核中
                $Broadcast->created_at = $time;

                if($Broadcast->save()){
                    $info = ArrayHelper::object_to_array($Broadcast);
                    $ret->code = 0;
                    $ret->msg = "广告添加成功";
                    $ret->data = $info;
                    return $ret;
                }

                $ret->code = 500;
                $ret->msg = "广告添加失败";
                return $ret;
            }catch (UnavailableParamsException $e){
                $ret->code = 500;
                $ret->msg = "处理数据发生异常错误";
                return $ret;
            }

        }

        return $this->render('add');
    }


    public function actionDetail(){
        $bid = IFilter::act(IReq::get('bid'));//广告id

        $info = Broadcast::find()->where(['id'=>$bid])->asArray()->one();

        $ret = new Result();

        if(!$info){
            $ret->code = 1000050;
            $ret->msg = $this->getMsgInfo(1000050);
            return $ret;
        }


        return $this->render('detail',['data'=>$info,'tt'=>stripslashes($info['conts'])]);
    }

    /**
     * 修改广告
     */
    public function actionUpdate(){

    }

    /**
     * 审核广告状态
     */
    public function actionCheckStatus(){
        $bid = IFilter::act(IReq::get('bid'));//广告id

        $Broadcast = Broadcast::findOne($bid);

        $ret = new Result();
        if(!$Broadcast){
            $ret->code = 0;
            $ret->msg = "当前广告数据不存在";
            return;
        }

        $Broadcast->status = 1;//广告状态切换成上线

        if($Broadcast->save()){
            $ret->code = 2000012;
            $ret->msg = $this->getMsgInfo(2000012);
            return ;
        }
        $ret->code = 2000013;
        $ret->msg = $this->getMsgInfo(2000013);
        return ;

    }

    /**
     * 删除广告
     */
    public function actionDel(){

    }

    /**
     * 根据code 获取msg
     * @param $code 错误码
     * @return mixed|string
     */
    protected function getMsgInfo($code){
      return  isset($this->msg[$code])?$this->msg[$code]:'系统错误';
    }

    /**
     * 绑定coupon list
     * @return Result
     */
    public function actionBindCouponList()
    {
        $bid = IFilter::act(IReq::get('b_id'));//广告id
        $coupon_list = IFilter::act(IReq::get('coupon'));//coupon list

        $ret = new Result();
        if(!$bid){
            $ret->code = 500;
            $ret->msg = "广告id缺失";
            return $ret;
        }

        if(empty($coupon_list)){
            $ret->code = 1000051;
            $ret->msg = $this->getMsgInfo(1000051);
            return $ret;
        }

        if((new BroadcasrBase)->disAjaxCouponList($bid,$coupon_list)){
            $ret->code = 0;
            $ret->msg = "绑定coupon 成功";
            return $ret;
        }

        $ret->code = 500;
        $ret->msg = "绑定coupon 失败";
        return $ret;
    }

    // 注册登记
    public function actionRegister_info(){
        $reqid = IFilter::act(IReq::get('reqid'));//广告id

        $ret = new Result();
        if(!$reqid) {
            $ret->code = 500;
            $ret->msg = "广告id缺失";
            return $ret;
        }

        try{


            $registerurl = "http://106.75.122.206:8001/api/openids";//注册登记

            // appid =1
            // appkey = '05903143B02E6729AFCFA6F78BC43210'

            $rules = 'laike';
            $ts = time();
            $md5str = '1'.'05903143B02E6729AFCFA6F78BC43210'.$reqid.$rules.$ts;
            $data = [
                'clientid' =>1,
                'reqid' => $reqid,
                'rules' => $rules,
                'ts' => $ts,
                'sign' => md5($md5str)
            ];
            $res = json_decode(UtilsHelper::postCurl_1($registerurl,$data),true);

            if(isset($res['code']) && $res['code'] == 0){

                // 修改数据路状态
                $Broadcast = Broadcast::findOne($reqid);
                $Broadcast->status = 1;//广告状态切换成上线

                $Broadcast->save();

                //放入待发广告队列
                RedisHelper::getRedis()->executeCommand('lpush', [
                    Yii::$app->params['cache_key_prefix']['pending_broadcast_list'] . $this->shopId,
                    $reqid . ':' . $ts
                ]);


                $ret->code = 0;
                $ret->msg = "登记成功";
                $this->write_log($data,$ret->code);//日志
                return $ret;
            }
            $ret->code = 500;
            $ret->msg = "登记失败";

            $this->write_log($data,$ret->code);//日志
            return $ret;
        }catch (\Exception $e){
            var_dump($e->getMessage());die;
            $ret->code = 500;
            $ret->msg = "登记失败";
            return $ret;
        }

    }


    //写日志文件
    protected function write_log($data,$status){

        $filename = date('Y-m-d').'_leaplink_ts.log';

        $log_name = Yii::$app->getRuntimePath() . '/'.$filename;

        $IFile = new IFile($log_name,'w+');

        $mesg = [
            'data' => $data,
            'time' => date('Y-m-d H:i:s'),
            'status' => $status
        ];
        $IFile->write(json_encode($mesg));

    }

}