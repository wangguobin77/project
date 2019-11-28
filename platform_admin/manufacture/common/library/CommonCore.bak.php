<?php

namespace common\library;

use Yii;
use common\library\Sending;//推送邮箱
use common\library\Mailer;
class CommonCore
{
    const PRIXIP = 'pst_reglimit:';//ip前缀
    const PRIXSENDMOBILECODELOGKEY = 'pst_send_mobile_authcode_log_list';//发送的验证码 redis log记录
    const PRISENDEMAILCONTENTKEY = 'pst_send_email_content_log_list';//发送的邮件内容 log 推送到mq的log记录

    const USEREMAILTYPE = 2;//邮箱注册类型
    const USERMOBILETYPE = 3;//手机注册类型
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

        show_json(400, $msg);
    }

    /**
     * 用户激活邮件链接
     * 发送邮件 会由sever调用
     *
     * typeid 为1 时 邮件发送激活 会调用此方法
     */
    public static function sendEmail($data)
    {
        //组装url
        $userid = $data['userid'];//18位userid

        $guid = $data['guid'];

        $content = Yii::$app->params['avtive_email_url'].'&userid='.$userid.'&g='.$guid;

        /*$message = [
            'to' => '13952354147@163.com',
            'subject' => 'test',
            'content' => 'test',
        ];*/

        //发送模板
        $message = [
            'to' => $data['useremail'],
            'subject' => '邮件标题',
            'view' => 'mail-template',
            'params' => [
                'name' => $content,
            ]
        ];

        $mailer = new Mailer(Mailer::TYPE_1, $message);
        $result = $mailer->sendMessage();
        var_dump($result);
        //发送成功失败 都要记log
        if($result){
            $state = 2;

            self::setGuidFromRedis($guid,$data['userid']);//缓存guid

        }else{
            $state = 1;
        }
        $res = [
            'username' => $data['useremail'],
            'ip' => getIp(),
            'authtype' => 2,
            'content' =>$content,
            'created_ts' => setDateTime(),
            'state' => $state
        ];
        self::add_user_register_verify_log($res);//添加log记录
    }

    /**
     * 用户找回密码是做邮箱验证推送
     * 发送邮件 会由sever调用
     * typeid  为2 时 会调用此方法
     */
    public static function sendFindPwdVerifyEmail($data)
    {
        //组装url
        $userid = $data['userid'];//18位userid

        $guid = $data['guid'];

        $content = Yii::$app->params['findpwdverifyemail_url'].'&userid='.$userid.'&g='.$guid;

        /*$message = [
            'to' => '13952354147@163.com',
            'subject' => 'test',
            'content' => 'test',
        ];*/

        //发送模板
        $message = [
            'to' => $data['useremail'],
            'subject' => '邮件标题',
            'view' => 'mail-template',
            'params' => [
                'name' => $content,
            ]
        ];

        $mailer = new Mailer(Mailer::TYPE_1, $message);
        $result = $mailer->sendMessage();
        var_dump($result);
        //发送成功失败 都要记log
        if($result){
            $state = 2;
            self::setGuidFromRedis($guid,$data['userid']);//缓存guid

        }else{
            $state = 1;
        }

        $res = [
            'username' => $data['useremail'],
            'ip' => getIp(),
            'authtype' => 2,
            'content' =>$content,
            'created_ts' => setDateTime(),
            'state' => $state
        ];
        self::add_user_register_verify_log($res);//添加log记录
    }

    /**
     * 会由server端调用
     * 推送手机验证码 到 用户手机
     * @param $data 用户数据  authcode验证码
     */
    public static function sendMobile($data)
    {
        //var_dump($data);die;
        $redis = Yii::$app->redis;

        //发送信息 到用户 手机
        if(Yii::$app->smser->send($data['usermobile'], $data['authcode'])){

            $key = Yii::$app->params['prixusermobilekey'].$data['usermobile'];

            $redis->set($key,$data['authcode']);//缓存验证码
            $redis->expire($key,Yii::$app->params['mobileauthcode']);

            $status = 2;//发送成功 状态码
        }else{
            $status = 1;//发送失败 状态码
        }
        //todo 临时 后面会同步db
        //$arr = ['usermobile'=>$data['usermobile'],'code'=>$data['authcode'],'status'=>$status,'time'=>date('Y-m-d H:i:s')];
        //$redis->lpush(self::PRIXSENDMOBILECODELOGKEY,json_encode($arr));//发送验证码 记录redis log


        $redis->lpush(Yii::$app->params['userauthcodekey'].$data['usermobile'],time());//记录同一个用户 每次接受验证码的系统时间 后面用于判断用户接受验证码限制条件

        $redis->expire(Yii::$app->params['userauthcodekey'].$data['usermobile'],Yii::$app->params['userauthcodekey_expire_time']);
        //数据同步db
        //发送成功失败 都要记log
        $res = [
            'username' => $data['usermobile'],
            'ip' => getIp(),
            'authtype' => 1,
            'content' =>$data['authcode'],
            'created_ts' => setDateTime(),
            'state' => $status
        ];

        self::add_user_register_verify_log($res);//添加log记录
        /* return $status;*/
        // 发送模板短信
        //Yii::$app->smser->sendByTemplate($data['usermobile'], ['123456'], 1);
    }

    /**
     * 用户填补邮箱 做邮箱验证推送
     * 发送邮件 会由sever调用
     * typeid  为2 时 会调用此方法
     */
    public static function sendPadVerifyEmailInfo($data)
    {
        //组装url
        $userid = $data['userid'];//18位userid

        $guid = $data['guid'];

        $content = Yii::$app->params['padverifyemailinfo_url'].'&userid='.$userid.'&g='.$guid;


        //发送模板
        $message = [
            'to' => $data['useremail'],
            'subject' => '邮件标题',
            'view' => 'mail-template',
            'params' => [
                'name' => $content,
            ]
        ];

        $mailer = new Mailer(Mailer::TYPE_1, $message);
        $result = $mailer->sendMessage();
        var_dump($result);
        //发送成功失败 都要记log
        if($result){
            $state = 2;

            self::setEmailFromRedis($data['useremail'],$data['userid']);//缓存email
            self::setGuidFromRedis($guid,$data['userid']);//缓存guid
        }else{
            $state = 1;
        }

        $res = [
            'username' => $data['useremail'],
            'ip' => getIp(),
            'authtype' => 2,
            'content' =>$content,
            'created_ts' => setDateTime(),
            'state' => $state
        ];
        self::add_user_register_verify_log($res);//添加log记录
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
     * 判断当前ip是否受限制
     */
    public function isLimitIp()
    {
        $redis = Yii::$app->redis;

        $ip = ip2long(getIp());//获取ip 特殊字符处理

        $key = self::PRIXIP.$ip;//限制ip redis key

        $redis->rpush($key,time());//只记录当前时间

        $data = $redis->lrange($key,0,-1);//获取全部数据

        $this->disposeFromRedisIpInfo($data,$key);
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

            show_json(400, 'The number of operations has exceeded the limit until later',$atLegalIp_arr);
        }

        Yii::$app->redis->expire($key,Yii::$app->params['regloglimittime']);//设置key 过期时间 清除注册后的脏数据;

    }

    /**
     * 设置邮件内容 推送到rabbitmq 服务器
     * @param $data
     * @return int
     * $typeid  默认为1 是发送激活邮件链接   2 为 自定义链接
     */
    public static function setEmailContentFromMq($data,$typeid=1)
    {
        $redis = Yii::$app->redis;

        $Sending = new Sending();

        $guid = createGuid();//获取生成的guid

        //$msg = 'cdscs';//邮件内容 临时替代  后面会统一生成一定的格式

        $info = ['userid'=>$data['userid'],'useremail'=>$data['useremail'],'regtype'=>self::USEREMAILTYPE,'content'=>$guid,'time'=>date('Y-m-d H:i:s'),'typeid'=>$typeid];//临时数据
        $status = $Sending->send_email_to_rabbitmq($info);

        //self::setGuidFromRedis($guid,$data['useremail']);//缓存guid
        //self::setGuidFromRedis($guid,$data['userid']);//缓存guid

        $key = self::PRISENDEMAILCONTENTKEY;
        if($status){
            $sendStatus = 200;//成功
        }else{
            $sendStatus = 400;//失败
        }
        $value = ['userid'=>$data['userid'],'useremail'=>$data['useremail'],'content'=>$guid,'time'=>date('Y-m-d H:i:s'),'status'=>$sendStatus,'typeid'=>$typeid];
        $redis->lpush($key,json_encode($value));//缓存已推送过mq的邮件记录

        return $status;
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
     * 手机验证码 推送到rabbitmq 服务器
     * @param $data
     * @return int
     * $typeid 默认为1 是发送手机验证码  2 为 自定义 以后方便扩展
     */
    public static function setMobileContentFromMq($data,$typeid=1)
    {
        //验证码 code
        $redis = Yii::$app->redis;

        $Sending = new Sending();


        $info = ['usermobile'=>$data['usermobile'],'regtype'=>self::USERMOBILETYPE,'authcode'=>$data['authcode'],'time'=>date('Y-m-d H:i:s'),'typeid'=>$typeid];//临时数据
        $status = $Sending->send_mobile_to_rabbitmq($info);

        $key = self::PRIXSENDMOBILECODELOGKEY;
        if($status){
            $sendStatus = 200;//成功
        }else{
            $sendStatus = 400;//失败
        }
        $value = ['usermobile'=>$data['usermobile'],'authcode'=>$data['authcode'],'time'=>date('Y-m-d H:i:s'),'status'=>$sendStatus,'typeid'=>$typeid];
        $redis->lpush($key,json_encode($value));//缓存已发送过的邮件记录

        return $status;
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