<?php

namespace common\library;

use Yii;
use common\library\MaiSmtp;
use yii\web\Session;
use test_manufacture\models\ManufactureRegisterVerifyLog;
class CommonCore
{
    const PRIXIP = 'pst_reglimit:';//ip前缀
    const PRIXSENDMOBILECODELOGKEY = 'pst_send_mobile_authcode_log_list';//发送的验证码 redis log记录
    const PRISENDEMAILCONTENTKEY = 'pst_send_email_content_log_list';//发送的邮件内容 log 推送到mq的log记录

    const USEREMAILTYPE = 2;//邮箱注册类型
    const USERMOBILETYPE = 3;//手机注册类型

    private static $appInfo_key = 'oauth_clients:';//appinfo 存储redis的键值

    private $postEmailQueueUrl = 'http://service.senseplay.com/iis/web/index.php?r=email/send_email_to_queue';//推送邮件内容到队列服务

    private $postAuthCodeQueueUrl = 'http://service.senseplay.com/iis/web/index.php?r=mobile/get_mobile_authcode';//获取手机验证码

    /**
     * c错误提示
     * @param $arr
     */
    public function hintError($arr)
    {
        $msg = '';
        if(is_array($arr)){
            foreach($arr as $v){
                $msg .= $v[0];
            }
        }else{
            $msg = $arr;
        }

        return $msg;
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

            $CommonCore = new CommonCore();

            $CommonCore->disposeFromRedisIpInfo($data,$key);

            return true;
        }catch (\Exception $e){
            show_json(200000,Yii::$app->params['errorCode'][200000]);
        }
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

            return_json(200001, 'The number of operations has exceeded the limit until later',$atLegalIp_arr);
        }

        Yii::$app->redis->expire($key,Yii::$app->params['regloglimittime']);//设置key 过期时间 清除注册后的脏数据;

    }

    /**
     * 缓存email 设置有限期 3天
     * key
     * value email
     * @param $email
     * @param $userid
     */
    protected static function setEmailFromRedis($email,$userid)
    {
        $redis = Yii::$app->redis;

        $key = Yii::$app->params['prix_email_pad_key'].$userid;

        $redis->set($key,$email);

        $redis->expire($key,Yii::$app->params['prix_email_pad_key_limittime']);
    }

    /**
     * 邮箱发送code键存储
     * 根据typeid 区分不同的键
     * @param $typeid
     * @return mixed|void
     */
    public function getSendEmailCodeContent($typeid)
    {
        $key_arr = [
            1 => 'send_email_activity_content:',//激活键值 + 邮箱地址
            2 => 'send_email_findpwd_content:',//找回密码键值 + 邮箱地址
            3 => 'send_email_fillup_content:',//填补邮箱键值 + 邮箱地址
        ];

        $key = isset($key_arr[$typeid])?$key_arr[$typeid]:show_json(100015,Yii::$app->params['errorCode'][100015]);

        return $key;
    }
    /**
     * 缓存系统生成的code 设置有限期 30分钟
     * @param $code 邮箱验证码
     * @param $email 邮箱
     * @param $typeid 区分不同场景的code码
     */
    protected function setEmailCodeFromRedis($code,$email,$typeid)
    {
        $redis = Yii::$app->redis;

        $key = $this->getSendEmailCodeContent($typeid).$email;//键

        $redis->set($key,$code);

        $redis->expire($key,Yii::$app->params['manufactureemailguid_limittime']);//设置过期时间
    }

    /**
     * 获取邮箱内容 code
     * @param $email 邮箱
     * @param $typeid 类型 不同类型有不同的键
     * @return mixed
     */
    public function getEmailCodeFromRedis($email,$typeid)
    {
        $redis = Yii::$app->redis;

        $key = $this->getSendEmailCodeContent($typeid).$email;//键

        return $redis->get($key);

    }

    /**
     * 客户端处理邮件内容推送到队列服务
     * @param $userid 平台用户唯一的id 必须参数
     * @param $email 用户邮箱 必须参数
     * @param $typeid 当前发送的邮件类型 必须参数
     * @return bool
     */
    public function sendEmailToQueue($email,$content,$typeid)
    {
        if(!$typeid){
            show_json(100015,Yii::$app->params['errorCode'][100015]);//必须参数 该值定义code类型 不然后面验证code值会错误
        }

        try{
            $info = postCurl_1($this->postEmailQueueUrl,['email'=>$email,'title'=>$this->emailTitle($typeid),'content'=>$this->emailView($content,$typeid)]);//curl请求

            $info = json_decode($info,true);
            if($info && $info['code'] == 0){//发送成功

                $this->setEmailCodeFromRedis($content,$email,$typeid);//根据不同的场景 以键值区分不同的code

                return true;//返回布尔值
            }

            show_json($info['code'],$info['message']);
        }catch (\Exception $e){
            show_json(100012,Yii::$app->params['errorCode'][100012]);
        }

    }

    /**
     * todo 临时发送验证码邮件模版
     * @param $userid
     * @param $guid
     */
    protected function emailView($code,$type)
    {

        $content = $code;//推送邮箱内容

        $str = '<div id="qm_con_body" style="width: 100%;height: 500px;background: #ccc;margin: 0 auto;padding:1.25rem/* 20px */;">';
        $str .=  '<div id="mailContentContainer" class="qmbox qm_con_body_content qqmail_webmail_only" style="">';
        $str .=  '<h2 style=" display: inline-block;width:100%;text-align:center;color:#333;font-size:18px;margin-bottom:1.875rem/* 30px */;">Content：</h2>';
        $str .=  '<p style="text-align:left;">Hello,Sir/madam：</p>';

        //激活账号链接
        if($type == 1) $str .= '<p style="text-align:left;">Please click on the link below to continue active your account.</p>';
        //修改密码链接
        if($type == 2) $str .= '<p style="text-align:left;">Please click on the link below to continue changing your password.</p>';
        //邮箱验证码
        if($type == 3) $str .= '<p style="text-align:left;">Enter the following verification code to continue.</p>';

        $str .= '<a style="text-align:left;margin-bottom: 1.25rem;width: 100%;height: 100px;overflow: hidden;text-align: center;color: #0073D2;word-wrap: break-word;">'.$content.'</a>';
        $str .= '<p class="cz" style="margin-bottom:1.25rem/* 20px */;">Sincerely</p>';
        $str .= '<p style="text-align:left;">Sensethink support</p>';

        $str .= '</div>';
        $str .= ' <div id="copy" style="margin:0 auto;">';
        // $str .= '<p style="text-align:center;font-size:12px;color:#888;">Copyright  2017  上海感悟通信科技有限公司版权所有  沪ICP备17022468</p >';
        $str .=  '<p style="text-align:center;font-size:12px;color:#888;">If you have any questions, please contact：service@sensethink.com</p >';
        $str .= '</div>';
        $str .= '</div>';

        return $str;

    }
    /**
     * 邮件标题 根据邮件的类型定义标题
     * @param int $typeid
     * @return mixed|string
     */
    protected function emailTitle($typeid = 1)
    {
        $arr = [
            1 => '标题：注册激活用户',
            2 => '标题：找回密码',
            3 => '标题：验证码',
        ];

        return $arr[$typeid]?$arr[$typeid]:'标题:未定义';

    }

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
            show_json(100003,Yii::$app->params['errorCode'][100003]);
        }

        $limitTime = Yii::$app->params['authcodeconf']['timelimit'];//系统限制时间

        $atLegalIp_arr = [];//当前时间段已合法 不同时间段的记录容器

        foreach($data as $k=>$value){

            /*if(!intval($value) <= 0){*/ //2018-02-09 update
            if(intval($value) <= 0){
                show_json(100003,Yii::$app->params['errorCode'][100003]);
            }

            //当前系统时间 - 限制时间段 判断记录值 是否大于商值 大于的就是时间段已有的log
            if(intval(time()-$limitTime) <= intval($value)){

                array_push($atLegalIp_arr,$value);

            }
        }

        /*判断 是否超出 系统配置 限制数 验证码*/
        if(count($atLegalIp_arr) >= Yii::$app->params['authcodeconf']['mobilelimit']){

            show_json(100009, Yii::$app->params['errorCode'][100009]);
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
            show_json(100011,Yii::$app->params['errorCode'][100011]);
        }

    }

    /**
     * 手机验证码
     * 客户端处理手机验证码内容推送到队列服务
     * @param $usermobile
     * @return bool
     */
    public function sendAuthCodeToQueue($usermobile)
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
            show_json(100013,Yii::$app->params['errorCode'][100013]);
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

    /****************************验证****************************/
    /**
     *处理图形验证码限制条件
     */
    public function disCaptchaInfo($captcha_c)
    {
        $session = new Session();
        $sessionid = $session->getid();
        //$captcha_c = Yii::$app->request->post('captcha');//获取验证码 用户输入

        $redis = Yii::$app->redis;
        $captcha_s = $redis->get(Yii::$app->params['regverifycodekey'].$sessionid);// 获取redis 缓存 图形验证码

        if(!$captcha_s){
            show_json(100023,Yii::$app->params['errorCode'][100023]);
        }

        if(strtolower($captcha_c) != strtolower($captcha_s)){
            show_json(100024,Yii::$app->params['errorCode'][100024]);
        }

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
     * 第三方登陆成功后 只要基于passport回掉 需要设置全局cookie
     * 设置全局cookie 维持客户端用户登陆状态
     */
    public function setCookie_userinfo($uid)
    {
        setcookie(Yii::$app->params['main_domain_cookie_name'],$uid,time()+3600*24*30,Yii::$app->params['main_domain_cookie']);
    }




    /**
     * 供 sdk 使用 必须判断 appkey 与 appsecret 合法性
     * 根据appid 与 appsecret 判断是否合法 合法返回组合信息
     * @param $config
     * @throws \Exception array('APP_KEY'=>'xx','APP_SECRET'=>'vfvf')
     */
    public function initThirdLoginApps($APP_ID)
    {
        if(!$APP_ID){
            show_json(100000,'Missing Client Id Paraments');
        }


        //先判断redis 是否存在数据 不存在 在判断db是否存在 都不存在 就是无效的key
        //todo 当前只判断redis的存值是否存在 数据库也同步保存了该相关信息
        $redis = Yii::$app->redis_1;

        $appinfo = json_decode($redis->get(self::$appInfo_key.$APP_ID),true);

        if(!$appinfo){
            show_json(100030,Yii::$app->params['errorCode'][100030]);
        }

        if($APP_ID != $appinfo['client_id']){
            show_json(100008,Yii::$app->params['errorCode'][100008]);//数据不存在或者不合法
        }else{
            return $appinfo;
        }
    }

    /**
     * 处理 判断 同一个手机用户 接收验证的限制条件
     * 1 相同手机号 规定时间只能注册 几次
     * 2 规定时间范围
     * @param $data 是时间戳的数组
     * @param $key
     */
    public static function disposeFromRedisUserMobileAuthCode($data)
    {

        $limitTime = Yii::$app->params['authcodeconf']['timelimit'];//系统限制时间

        $atLegalIp_arr = [];//当前时间段已合法 不同时间段的记录容器

        foreach($data as $k=>$value){
            //当前系统时间 - 限制时间段 判断记录值 是否大于商值 大于的就是时间段已有的log
            if(intval(time()-$limitTime) <= $value){

                array_push($atLegalIp_arr,$value);

            }
        }

        /*判断 是否超出 系统配置 限制数 验证码*/
        if(count($atLegalIp_arr) >= Yii::$app->params['authcodeconf']['mobilelimit']){

            show_json(5000, '1小时只能接收三次,稍后再继续',$atLegalIp_arr);
        }


    }

    /**
     * 缓存系统生成的guid 设置有限期 3天
     * @param $guid
     * @param $userid
     */
    protected static function setGuidFromRedis($guid,$userid)
    {
        $redis = Yii::$app->redis;

        $key = Yii::$app->params['prixuseremailguid'].$userid;

        $redis->set($key,$guid);

        $redis->expire($key,Yii::$app->params['prixuseremailguid_limittime']);
    }

    /**
     * 添加 推送用手机验证码 和邮箱链接 到客户端 成功或者失败log
     */
    public static function add_user_register_verify_log($data)
    {

        Yii::$app->db->createCommand("call pst_sp_add__user_register_verify_log('".$data['username']."','".$data['ip']."',".$data['authtype'].",'".$data['content']."','".$data['created_ts']."',".$data['state'].",@ret)")->query();

        $res = Yii::$app->db->createCommand("select @ret");
        $result = $res->queryOne();

    }

}