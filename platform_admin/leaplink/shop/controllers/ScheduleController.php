<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/10/28
 * Time: 上午9:54
 */

namespace app\controllers;

use PHPUnit\Framework\Error\Error;
use yii;

use  common\util\IFilter;
use  common\util\IReq;
use common\util\ITime;

use common\consequence\Result;
use app\models\broadcasts\Broadcast;
use app\models\broadcasts\Schedule;
use yii\data\Pagination;
use common\helpers\StringsHelper;
class ScheduleController  extends BaseController
{

    /**
     *  基于单条广告 的每组列表
     * 广告列表
     */
    public function actionList(){
        $bid = IFilter::act(IReq::get('bid'));//广告id
        $query = Schedule::find()->where(['bid' => $bid]);
        $countQuery = clone $query;
        $pages = new Pagination(['defaultPageSize'=>10,'totalCount' => $countQuery->count()]);
        $list = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('list',['data'=>$list,'pages'=>$pages,'bid'=>$bid]);
    }

    public function actionAdd(){

        $ret = new Result();

        if(Yii::$app->request->isPost){

            $bid = IFilter::act(IReq::get('bid'));//广告id
            $title = IFilter::act(IReq::get('title'));//
            $week = IFilter::act(IReq::get('day'));//周期执行 1-7
            $interval_type = IFilter::act(IReq::get('interval_type'));//是否是有间隔 0 没有间隔 只有一次执行 1 是有间隔
            $effective = IFilter::act(IReq::get('date'));//有效期 2017-10-23 10:23:30/2019-10-23 10:23:30 要切分字符串

            if(!$bid) {
                $ret->code = 1000000;
                $ret->msg = "广告id丢失";
                return $ret;
            }

            if(!$effective) {
                $ret->code = 1000000;
                $ret->msg = "广告推送的有效期不能为空";
                return $ret;
            }
            if(count($week) > 1){
                $week_str = implode(',',$week);// 转成以逗号分割的字符串
            }else{
                $week_str = $week[0];
            }

            $e_ts_arr = explode("/",$effective); //有效期 开始-结束

            $Schedule = new Schedule;


            $sid = StringsHelper::createId();
            $Schedule->id =  $sid;
            $Schedule->title =  $title;
            $Schedule->bid =  $bid;
            $Schedule->description = '';
            $Schedule->week = $week_str;
            $Schedule->type = 0;
            $Schedule->interval_type = intval($interval_type);
            $Schedule->start_ts = intval(strtotime($e_ts_arr[0]));// 有效期开始时间
            $Schedule->end_ts = intval(strtotime($e_ts_arr[1]));// 有效期结束时间

            if(intval($interval_type) == 1){//  有间隔时间
                $send_ts = IFilter::act(IReq::get('time'));//发送时间 10:23:10/11:23:10
                $interval_ts = IFilter::act(IReq::get('delaytime'));//间隔时间
                $send_ts_arr = explode("/",$send_ts); //发送时间 开始-结束
                $Schedule->send_start_ts = $send_ts_arr[0];// 有效期结束时间
                $Schedule->send_end_ts = $send_ts_arr[1];// 有效期结束时间
                $Schedule->interval_ts = $interval_ts;// 间隔

            }else{
                $dc = IFilter::act(IReq::get('once_time'));//单次开始时间
                $Schedule->send_start_ts = $dc;// 有效期结束时间
                $Schedule->send_end_ts = '';// 有效期结束时间
                $Schedule->interval_ts = 0;// 间隔

            }
            $Schedule->created_ts = time();// 有效期结束时间


            if($Schedule->save()){
                $ret->code = 0;
                $ret->msg = "添加规则成功";
                return $ret;
            }

            $ret->code = 100000;
            $ret->msg = "添加规则失败";
            return $ret;

        }

        $bid = IFilter::act(IReq::get('bid'));//广告id

        return $this->render('add',['bid'=>$bid]);


    }

    public function actionEdit(){

        $ret = new Result();
        if(Yii::$app->request->isPost){

            $sid = IFilter::act(IReq::get('sid'));//规则id

          //  $bid = IFilter::act(IReq::get('bid'));//广告id
            $title = IFilter::act(IReq::get('title'));//
            $week = IFilter::act(IReq::get('day'));//周期执行 1-7
            $interval_type = IFilter::act(IReq::get('interval_type'));//是否是有间隔 0 没有间隔 只有一次执行 1 是有间隔
            $effective = IFilter::act(IReq::get('date'));//有效期 2017-10-23 10:23:30/2019-10-23 10:23:30 要切分字符串

            $Schedule = Schedule::findOne($sid);

            if(!$Schedule){
                $ret->code = 1000000;
                $ret->msg = "当前规则信息不存在";
                return $ret;
            }

            if(count($week) > 1){
                $week_str = implode(',',$week);// 转成以逗号分割的字符串
            }else{
                $week_str = $week[0];
            }
            $e_ts_arr = explode("/",$effective); //有效期 开始-结束


            $Schedule->title =  $title;
            $Schedule->description = '';
            $Schedule->week = $week_str;
           // $Schedule->type = 0;
            $Schedule->interval_type = intval($interval_type);
            $Schedule->start_ts = intval(strtotime($e_ts_arr[0]));// 有效期开始时间
            $Schedule->end_ts = intval(strtotime($e_ts_arr[1]));// 有效期结束时间

            if(intval($interval_type) == 1){//  有间隔时间
                $send_ts = IFilter::act(IReq::get('time'));//发送时间 10:23:10/11:23:10
                $interval_ts = IFilter::act(IReq::get('delaytime'));//间隔时间
                $send_ts_arr = explode("/",$send_ts); //发送时间 开始-结束
                $Schedule->send_start_ts = $send_ts_arr[0];// 有效期结束时间
                $Schedule->send_end_ts = $send_ts_arr[1];// 有效期结束时间
                $Schedule->interval_ts = $interval_ts;// 间隔

            }else{
                $dc = IFilter::act(IReq::get('once_time'));//单次开始时间
                $Schedule->send_start_ts = $dc;// 有效期结束时间
                $Schedule->send_end_ts = '';// 有效期结束时间
                $Schedule->interval_ts = 0;// 间隔

            }

            if($Schedule->save()){
                $ret->code = 0;
                $ret->msg = "修改规则成功";
                return $ret;
            }

            $ret->code = 100000;
            $ret->msg = "修改规则失败";
            return $ret;

        }

        $sid = IFilter::act(IReq::get('sid'));//规则id

        if(!$sid) {
            $ret->code = 1000000;
            $ret->msg = "广告规则id丢失";
            return $ret;
        }

        $oneInfo = Schedule::find()->where(['id'=>$sid])->asArray()->one();

        return $this->render('edit',['data'=>$oneInfo]);


    }

    // 操做计划任务 开启 或者 关闭
    public function actionOperation(){

        $sid = IFilter::act(IReq::get('sid'));//规则id
        $type = IFilter::act(IReq::get('type'));//0 关闭 1 开启

        $ret = new Result();

        if(!$sid) {
            $ret->code = 1000000;
            $ret->msg = "广告规则id丢失";
            return $ret;
        }

        $Schedule = Schedule::findOne($sid);

        if(!$Schedule){
            $ret->code = 1000000;
            $ret->msg = "当前规则信息不存在";
            return $ret;
        }

        $oneInfo = Schedule::find()->where(['id'=>$sid])->asArray()->one();
        if($type == 1){// type 等于1 需要关闭 将状态修改为0
            $Schedule->type = 0;

            // 需要删除任务计划

            $this->delScheduleInfoKeyAndZsetInfo($sid);// 删除相关信息

            $ret->code = 100000;
            $ret->msg = "操作失败";
            if($Schedule->save()){
                $ret->code = 0;
                $ret->msg = "操作成功";
            }
            return $ret;
        }else{
            $Schedule->type = 1;

            $oneInfo['shop_id'] = $this->shopId;
            $oneInfo['client_id'] = 1;//默认为莱克

            try{
                if($this->disCheckDate(date('Y-m-d H:i:s',$oneInfo['end_ts']))){//判断活动是否过期  过期的话就不生成规则
                    $this->generate_schedule($sid,$oneInfo);// 生成规则 缓存到集合
                    $this->setScheduleInfoKey($sid,$oneInfo);// 将相关规则 缓存redis

                }

                if($Schedule->save()){
                    $ret->code = 0;
                    $ret->msg = "操作成功";
                    return $ret;
                }
                throw new \Exception('操作失败');
            }catch (\Exception $e){

                $ret->code = 100000;
                $ret->msg = "操作失败";
                return $ret;
                // var_dump($e->getMessage());die;
            }
        }




    }

    // 删除规则缓存 and 有序集合的数据
    public function delScheduleInfoKeyAndZsetInfo($sid){

        $redis14 = Yii::$app->redis14;

        $key = 'broadcast_schedule_key:'.$sid;//规则id 规则数据

        $redis14->del($key);

        $redis14->zremrangebyrank('broadcast_schedule:'.$sid,0,-1);

    }

    // 处理流程第一步
    // 一处理第一个规则 判断当前时间 是否有效，是否在有效期之内，超出则规则取消
    protected function disCheckDate($end_ts){
        // $end_ts 广告规则有效结束时间
        // 注意这里只判断当前时间如果超出有效期结束时间 则规则作废，因为其他任何时间，都是有效，需要提前生成有效规则
        $time = time();
        if($time > ITime::getTime($end_ts)){ // 如果当前时间 小于规则有效期开始时间 则规则不生效
            return false;
        }
        return true;
    }

    // 生成规则 /
    protected function generate_schedule($sid,$data){

        $exec_ts = $this->disWeekAndTime($data['interval_type'],$data['week'],$data['send_start_ts'],$data['send_end_ts']);

        //这里把当前执行时间 也放入缓存
        $data['exec_ts'] = $exec_ts;

        $this->addRuleFromRedis($sid,$exec_ts,json_encode($data));

        $this->SetSendScheduleDetail($sid,$exec_ts);//缓存执行时间
    }

    // 存储当前规则的上一条 下一条的信息 前端展示会用到
    protected function SetSendScheduleDetail($sid,$next_send_ts){

        $key = 'broadcast_schedule_send_datail_ts:'.$sid;// 记录同一条规则的 上一条 下一条的发送时间

        $redis14 = Yii::$app->redis14;

        $data = [
            'sid' => $sid,// 当前规则的id
            'pre_send_ts' => '0000-00-00 00:00',//时间只到时分
            'next_send_ts' => date('Y-m-d H:i',$next_send_ts),//时间只到时分
        ];

        $redis14->set($key,json_encode($data));
    }

    // redis key=>value 存储每条广告id的规则
    public function setScheduleInfoKey($sid,$data){

        $redis14 = Yii::$app->redis14;

        $key = 'broadcast_schedule_key:'.$sid;//规则id 规则数据

        $redis14->set($key,json_encode($data));
    }

    /**
     * 添加规则到有序集合中 供计划任务使用
     * @param $member  key 每条规则id
     * @param $field  等待推送的时间戳
     * @param $value  值 目前可以放当前广告id的规则信息
     */
    protected function addRuleFromRedis($sid,$field,$value){

        $key = 'broadcast_schedule:'.$sid;

        $redis14 = Yii::$app->redis14;

        // 注意新增规则的时候需要 将老的规则删掉
        $redis14->zremrangebyscore($key, 0, time());//
       // $redis14->zrem($key,$field);

        $redis14->zadd($key,$field,$value);

        // 注意这里需要将member key 添加到key里，后面获取有序队列需要根据member获取
        $redis14->lpush('broadcast_z_member_list',$key);


    }


    /**
     *  处理周期 与 当天发送的时间点
     * @param $interval_type 0 表示没有间隔周期 一次 1 存在间隔
     * @param $week_arr 周期 0表示周日 六是周六
     * @param $send_start_ts 开始发送时间
     * @param $send_end_ts 发送结束时间 interval_type如果是0 是不存在发送结束时间的
     */
    protected function disWeekAndTime($interval_type,$week_arr,$send_start_ts,$send_end_ts){

        $w = date('w');
        $rq_ts = time();//当前时间戳
        // 先判断是否有符合当天的
        if($exec_ts = $this->isCurrentOverdue($w,$rq_ts,$week_arr,$interval_type,$send_start_ts,$send_end_ts)){ // 满足当天情况的情况
            return $exec_ts;//如果符合当前时间 直接返回
        }else{
            $days = $this->iterationJs($w,$week_arr,0);
            //不符合 需要获取当前与下次相差的天数 + 当前时间戳

            return $rq_ts+intval($days)*86400; //下次执行时间
          //  var_dump([$days,$exec_ts]);die;
        }

    }

    //
    /**
     *  这里只需要判断 周期里是否有符合当天日期的 有过有符合当天的 直接判断发送时间是否符合 直接生成规则
     * @param $w
     * @param $week_arr
     * @return bool
     */
    protected function isCurrentOverdue($w,$rq_ts,$week_arr,$interval_type,$send_start_ts,$send_end_ts){
        if(!is_array($week_arr)){
            $week_arr = explode(',',$week_arr);
        }//如果没有设置周期，肯定是一次性活动，所以不参与活动计划

        $exec_ts = 0;
       // $w = date('w');
       // $rq_ts = time();//当前时间戳
        foreach ($week_arr as $k=>$v){
            if($w == $v){
                // 判断执行的类型
                //判断当前的时间是否超出
                //1.注意当前时间小于开始时间  直接以开始时间做规则
                //2.当前时间超出发送结束时间 直接寻找下次规则时间
                //3.当前时间 如果在有效时间之类，就直接以当前时间做规则key

                $send_start_ts_v = strtotime(date('Y-m-d') . $send_start_ts);//发送开始时间
                if (intval($interval_type) == 1) { // 存在间隔时间 发送时间存在开始-- 发送时间结束
                    $send_end_ts_v = strtotime(date('Y-m-d') . $send_end_ts);//发送结束时间
                    if ($rq_ts < $send_start_ts_v) { // 当前时间小于发送开始时间
                        $exec_ts = $send_start_ts_v;
                        break;
                    } else if ($rq_ts >= $send_start_ts_v && $rq_ts <= $send_end_ts_v) {
                        $exec_ts = $rq_ts;
                        break;
                    }

                } else {// 0 的时候 没有间隔时间
                    if ($rq_ts <= $send_start_ts_v) { // 当前时间小于发送开始时间
                        $exec_ts = $send_start_ts_v;
                        break;
                    }
                }
            }
        }

        return $exec_ts;//如果bool 为false 说明当前时间已经不再有效周期内了，只能更新当前时间 累加进行下次运算
    }

    // 计算下次执行相差天数
    protected function iterationJs($w,$week_arr,$days=0){

        if(!is_array($week_arr)){
            $week_arr = explode(',',$week_arr);
        }//如果没有设置周期，肯定是一次性活动，所以不参与活动计划

        // 当前日期如果不满足 就将当前日期增加1 判断是否在有效周期内 不存在继续增加 没增加一次 天数+1
        if ($w == 6) { // 如果当前周期 已经是礼拜六 那么下次执行是六百日 0
            $w = 0;//重置为0
        } else {
            $w = intval($w) + 1;// 往后累计
        }

        $days++;

        if(in_array($w,$week_arr)){
            return $days;
        }

        return $this->iterationJs($w,$week_arr,$days);
    }

}