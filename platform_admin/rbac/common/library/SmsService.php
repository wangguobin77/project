<?php
/**
 *
 * 手机
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2018/7/5
 * Time: 下午4:30
 */

namespace common\library;

use yii;
class SmsService
{
    const PRIXIP = 'pst_reglimit:';//ip前缀

    private $postAuthCodeQueueUrl = 'http://service.senseplay.com/iis/web/index.php?r=mobile/get_mobile_authcode';//获取手机验证码

    /**
     * 将密码推送到手机端
     * @param $usermobile
     * @param $password
     * @return bool
     */
    public function sendAuthCodeToQueue($usermobile,$password)
    {

        $msg = '您的账号:'.$usermobile.',在后台登陆密码为:%s,请保管好自己的密码【'.Yii::$app->params['mobileauthqm'].'】';

        try{
            $info = postCurl_1($this->postAuthCodeQueueUrl,['usermobile'=>$usermobile,'authcode'=>$password,'msg'=>$msg,'authtype'=>1]);//curl请求

            $info = json_decode($info,true);
            if($info && $info['code'] == 0){//发送成功
                //这里不做任何验证
                return true;//返回布尔值
            }

            show_json($info['code'],$info['message']);
        }catch (\Exception $e){
            show_json(100000,'Push password failed');
        }

    }

    /******************************处理手机验证码********************************/
    /**
     *  接收手机短信验证码的限制条件 （同一个手机用户）
     *  $res = $redis->lrange(Yii::$app->params['userauthcodekey'].$data['usermobile'],0,-1);//获取接收的验证码全部数据
     *  $res 为缓存redis的数据队列
     *  判断手机接收验证码 是否超出系统配置 时间 与限制次数
     * 1 相同手机号 规定时间只能注册 几次
     * 2 规定时间范围
     * @param $data 是时间戳的数组 缓存redis的数据对了 【'时间戳1','时间戳2'....】如：上操作
     * @param $key action
     *
     */
    protected function IsLimitMobileAuthCode($data)
    {

        if(!$data){
            //show_json(100001,Yii::$app->params['errorCode'][100001]);
            return true;
        }

        if(!is_array($data)){
            show_json(100003,'parameter illegal');
        }

        $limitTime = Yii::$app->params['authcodeconf']['timelimit'];//系统限制时间

        $atLegalIp_arr = [];//当前时间段已合法 不同时间段的记录容器

        foreach($data as $k=>$value){

            /*if(!intval($value) <= 0){*/ //2018-02-09 update
            if(intval($value) <= 0){
                show_json(100003,'parameter illegal');
            }

            //当前系统时间 - 限制时间段 判断记录值 是否大于商值 大于的就是时间段已有的log
            if(intval(time()-$limitTime) <= intval($value)){

                array_push($atLegalIp_arr,$value);

            }
        }

        /*判断 是否超出 系统配置 限制数 验证码*/
        if(count($atLegalIp_arr) >= Yii::$app->params['authcodeconf']['mobilelimit']){

            show_json(100009, 'exceed the limit,continue later');
        }


    }

    /**
     * 获取最后一次获得验证码时间戳 在系统配置的时间段里只能发送一次 不能重复发送
     * 目前根前端统一 发送验证码之后倒计时六十秒 六十秒之类不能重新获取验证码
     * @param $usermobile
     */
    protected function getLastAuthCodeLog($usermobile)
    {
        $redis = Yii::$app->redis;

        $res = $redis->lrange(Yii::$app->params['userauthcodekey'].$usermobile,0,-1);//获取接收的验证码全部数据

        $this->IsLimitMobileAuthCode($res);//判断手机接收验证码 是否超出系统配置 时间 与限制次数

        $oneinfo = $redis->lpop(Yii::$app->params['userauthcodekey'].$usermobile);//弹出最后一次记录

        if(!$oneinfo){
            return true;
        }
        //2018-02-09
        $oneinfo = json_decode($oneinfo,true);
        //判断上一次 发送 与当前 系统时间比较 小于 60秒内 不在发送验证码 超出则重新发送
        if(intval(time()-$oneinfo['time']) <= 60){
            show_json(100011,'The verification code cannot be obtained many times in a short time');
        }

    }

    /**
     * 手机验证码
     * 客户端处理手机验证码内容推送到队列服务
     * @param $usermobile
     * @return bool
     */
    public function sendAuthCodeToQueue2($usermobile)
    {

        $this->getLastAuthCodeLog($usermobile);//限制验证条件

        $authcode = getIntCode(6);//随机获取四位整数

        $msg = '验证码：%s，请于'.intval(Yii::$app->params['mobileauthcode']/60).'分钟内输入使用 【'.Yii::$app->params['mobileauthqm'].'】';

        try{
            $info = postCurl_1($this->postAuthCodeQueueUrl,['usermobile'=>$usermobile,'authcode'=>$authcode,'msg'=>$msg,'authtype'=>1]);//curl请求

            $info = json_decode($info,true);
            if($info && $info['code'] == 0){//发送成功

                $this->setRegAuthcodeToRedis($usermobile,$authcode);//缓存验证码在redis

                $this->setRegAuthcodeLimitLog($usermobile);//记录获取验证码当前的时间戳 为了验证码限制条件

                return true;//返回布尔值
            }

            show_json($info['code'],$info['message']);
        }catch (\Exception $e){
            show_json(100013,'Text messaging failed');
        }

    }

    /**
     * 缓存注册发送的手机验证码
     * @param $usermobile
     * @param $authcoe 验证码
     */
    protected function setRegAuthcodeToRedis($usermobile,$authcode)
    {

        $redis = Yii::$app->redis;

        $key = Yii::$app->params['prixusermobilekey'].$usermobile;

        $redis->set($key,$authcode);//缓存验证码
        $redis->expire($key,Yii::$app->params['mobileauthcode']);
    }

    /**
     * 缓存用户每次发送验证码的时间戳 用于判断短时间类不能多次获取验证码
     */
    protected function setRegAuthcodeLimitLog($usermobile)
    {
        $redis = Yii::$app->redis;

        $redis->lpush(Yii::$app->params['userauthcodekey'].$usermobile,time());//记录同一个用户 每次接受验证码的系统时间 后面用于判断用户接受验证码限制条件

        $redis->expire(Yii::$app->params['userauthcodekey'].$usermobile,Yii::$app->params['userauthcodekey_expire_time']);
    }

    /**
     * 验证手机验证码是否合法
     * @param $usermobile 手机号码
     * @param $authCode  验证码code
     */
    public function disUsermobileAuthcode($usermobile,$authCode)
    {
        //获取验证码 比对验证码 是否合法 。。。。
        $key = Yii::$app->params['prixusermobilekey'].$usermobile;

        $redis = Yii::$app->redis;
        $serverCode = $redis->get($key);//获取缓存redis 有效的验证码

        //判断用户输入 与 服务器缓存验证码是否相同
        if(!$serverCode){
            show_json(100000,'The verification code is invalid,please reacquire');//验证码失效 重新获取
        }

        if($serverCode && $authCode != $serverCode){
            show_json(100000,'The verification code is incorrect,please reacquire');
        }

        return true;
    }

    /**
     * 处理ip数据
     * @param $data
     * @param $key
     */
    protected function disposeFromRedisIpInfo($data,$key)
    {

        $limitTime = Yii::$app->params['limitTime'];//系统限制时间

        $atLegalIp_arr = [];//当前时间段已合法 不同时间段的记录容器

        /*遍历循环 取当前系统时间 与 每个时间段 时间做比较 排除系统规定时间外的数据*/
        foreach($data as $k=>$value){
            //当前系统时间 - 限制时间段 判断记录值 是否大于商值 大于的就是时间段已有的log
            if(intval(time()-$limitTime) <= $value){

                array_push($atLegalIp_arr,$value);

            }
        }

        /*判断当前记录总数是否超过 系统配置 限制数*/
        if(count($atLegalIp_arr) >= Yii::$app->params['limitipnum']){

            Yii::$app->redis->rpop($key);//弹出当前已插入 不合法的数据

            show_json(200001, 'The number of operations has exceeded the limit until later',$atLegalIp_arr);
        }

        Yii::$app->redis->expire($key,Yii::$app->params['regloglimittime']);//设置key 过期时间 清除注册后的脏数据;

    }

    /**
     * 处理ip防刷
     * 判断当前ip是否受限制 1
     * (注意过滤如：注册次数过多 限制当前的ip)
     *
     * 请求方式 curl post
     */
    public function isLimitIp()
    {
        $redis = Yii::$app->redis;

        $ip = ip2long(getIp());//获取ip 特殊字符处理

        try{
            $key = self::PRIXIP.$ip;//限制ip redis key

            $redis->rpush($key,time());//只记录当前时间

            $data = $redis->lrange($key,0,-1);//获取全部数据

            $this->disposeFromRedisIpInfo($data,$key);

            return true;
        }catch (\Exception $e){
            show_json(200000,'be defeated');
        }
    }
}