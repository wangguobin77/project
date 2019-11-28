<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-18
 * Time: 11:45
 */

namespace app\controllers\bus;

use app\models\ARUser;
use Yii;
use app\models\shop\ARShop;
use app\models\shop\ARCategory;
use app\models\shop\ARResource;
use app\models\shop\ARLoginLog;
use app\models\shop\ARShopEvent;
use common\helpers\CheckHelper;
use common\helpers\UtilsHelper;
use common\helpers\RedisHelper;
use common\helpers\StringsHelper;
use common\consequence\ErrorCode;
use common\exception\FixedException;
use app\controllers\traits\appTrait;
use app\controllers\traits\shopTrait;
use common\exception\UnavailableParamsException;



/**
 * 网站控制器业务类
 */
class appBus
{
    use appTrait;
    use shopTrait;


    /**
     * 登录状态检测逻辑(appcontroller->loginStatusCheck重载使用)
     */
    public function loginStatusCheck()
    {
        $ret = $this->hasSessionId();
        if($ret){
            return true;
        }
        return false;
    }

    /**
     * 商户注册数据校验
     * @param $params
     * @throws UnavailableParamsException
     */
    public function registerParamsCheck($params)
    {
        //手机校验
        if(!CheckHelper::unEmptyValidate($params['phone'])){
            throw new UnavailableParamsException('账号不能为空', ErrorCode::UN_EMPTY_ACCOUNT);
        }else if (!CheckHelper::phoneValidate($params['phone'])){ //手机号验证
            throw new UnavailableParamsException('手机号不正确', ErrorCode::CORRECT_PHONE);
        }else if(ARShop::existsPhone($params['phone'])) {
            throw new UnavailableParamsException('手机号已被注册', ErrorCode::EXISTS_PHONE);
        }

        //验证码校验
//        if(!CheckHelper::unEmptyValidate($params['code'])){
//            throw new UnavailableParamsException('验证码不能为空', ErrorCode::UN_EMPTY_CODE);
//        }else if (!$this->codeCheck($params['code'], $params['phone'], ARShopEvent::EVENT_LIST['REGISTER'])){
//            throw new UnavailableParamsException('验证码不正确', ErrorCode::CORRECT_CODE);
//        }

        //密码校验
        if(!CheckHelper::unEmptyValidate($params['password'])){
            throw new UnavailableParamsException('密码不能为空', ErrorCode::UN_EMPTY_PASSWORD);
        }

        //商户名称校验
        if(!CheckHelper::unEmptyValidate($params['name'])){
            throw new UnavailableParamsException('商户名称不能为空', ErrorCode::UN_EMPTY_USER_NAME);
        }

        //商户类别校验
        if(!CheckHelper::unEmptyValidate($params['category'])){
            throw new UnavailableParamsException('商户类别不能为空', ErrorCode::UN_EMPTY_CATEGORY);
        }

        //详细地址校验
        if(!CheckHelper::unEmptyValidate($params['address'])){
            throw new UnavailableParamsException('详细地址不能为空', ErrorCode::UN_EMPTY_ADDRESS);
        }

        //营业时间校验
        if(!CheckHelper::unEmptyValidate($params['open_time'])){ //开始营业时间 不能为空
            throw new UnavailableParamsException('请选择开始营业时间', ErrorCode::UN_EMPTY_OPEN_TIME);
        }else if(!CheckHelper::unEmptyValidate($params['close_time'])) {
            throw new UnavailableParamsException('请选择打烊时间', ErrorCode::UN_EMPTY_CLOSE_TIME);
        }else if($params['open_time'] >= $params['close_time']) {
            throw new UnavailableParamsException('开始营业时间必须小于打烊时间', ErrorCode::MORE_THAN_OPEN);
        }

    }

    /**
     * 商户登录
     * @param $params
     * @throws UnavailableParamsException
     */
    public function login($params)
    {
        //手机校验
        if(!CheckHelper::unEmptyValidate($params['account'])){
            throw new UnavailableParamsException('账号不能为空', ErrorCode::UN_EMPTY_ACCOUNT);
        }else if (!CheckHelper::phoneValidate($params['account'])){ //手机号验证
            throw new UnavailableParamsException('手机号不正确', ErrorCode::CORRECT_PHONE);
        }else if(!ARShop::existsPhone($params['account'])) {
            throw new UnavailableParamsException('手机号不存在', ErrorCode::NOT_EXISTS_PHONE);
        }

        $shop = $this->checkLogin($params['account'], $params['password']);

        if(false === $shop){
            throw new UnavailableParamsException('密码错误', ErrorCode::CORRECT_PASSWORD);
        }

        //通过验证,保存商户 session
        $this->setLoginShopInfo($shop['id']);

        //记录登录日志
        ARLoginLog::saveLog(
            ['shop_id' => $shop['id']],
            ['ip' => UtilsHelper::getUserLongIp()],
            ['type' => ARShopEvent::EVENT_LIST['LOGINLOGIN']]
        );
    }



    /**
     * @param $account
     * @param $password
     * @return array|boolean
     */
    public function checkLogin($account, $password)
    {
        //获取商户信息
        $shop = ARShop::getShop($account);

        //验证密码
        if(!CheckHelper::passwordValidate($password, $shop['password'], $shop['salt'])){
            //验证不通过返回 false
            return false;
        }
        //成功返回商户信息
        return $shop;
    }

    /**
     * 商户注册验证码校验
     * @param $code string
     * @return boolean
     */
    private function codeCheck($code, $phone, $type)
    {
        if($type === ARShopEvent::EVENT_LIST['REGISTER']){ //注册验证码校验
            $key = Yii::$app->params['cache_key_prefix']['register_phone_code'] . $phone;
        }elseif ($type === ARShopEvent::EVENT_LIST['FIND_PASSWORD']) {  //找回密码验证码校验
            $key = Yii::$app->params['cache_key_prefix']['findpwd_phone_code'] . $phone;
        }else{
            return false;
        }

        $result_code = RedisHelper::getRedis()->get($key);

        $ret = CheckHelper::compareValuesValidate($result_code, $code, '===');
        if($ret === true){ //验证通过,删除缓存
            RedisHelper::getRedis()->del($key);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 发送手机验证码
     * @param $phone
     * @param $type
     * @throws FixedException
     */
    public function sendPhoneCode( $phone, $type )
    {
        //获取 6 位手机验证码
        $code = StringsHelper::randInt(Yii::$app->params['cache_code']['length']);

        if($type === ARShopEvent::EVENT_LIST['REGISTER']){ //注册
            RedisHelper::getRedis()->set(Yii::$app->params['cache_key_prefix']['register_phone_code'] . $phone, $code, 'EX', Yii::$app->params['cache_code']['ttl']);

            if(!UtilsHelper::sendPhoneCode($phone, $code, 1)){ //发送短信,如果失败删除缓存
                RedisHelper::getRedis()->delete(Yii::$app->params['cache_key_prefix']['register_phone_code'] . $phone);
                throw new FixedException('验证码发送失败', ErrorCode::ERROR);
            }

        }elseif ($type === ARShopEvent::EVENT_LIST['FIND_PASSWORD']) { //找回密码
            RedisHelper::getRedis()->set(Yii::$app->params['cache_key_prefix']['findpwd_phone_code'] . $phone, $code, 'EX', Yii::$app->params['cache_code']['ttl']);

            if(!UtilsHelper::sendPhoneCode($phone, $code, 2)){ //发送短信,如果失败删除缓存
                RedisHelper::getRedis()->delete(Yii::$app->params['cache_key_prefix']['findpwd_phone_code'] . $phone);
                throw new FixedException('验证码发送失败', ErrorCode::ERROR);
            }

        }

    }

    /**
     * @param $phone
     * @param $type
     * @throws FixedException
     */
    public function checkPhoneCode($phone, $type)
    {
        //手机校验
        if(!CheckHelper::unEmptyValidate($phone)){
            throw new FixedException('手机号不能为空', ErrorCode::UN_EMPTY_ACCOUNT);
        }else if (!CheckHelper::phoneValidate($phone)){ //手机号验证
            throw new FixedException('手机号不正确', ErrorCode::CORRECT_PHONE);
        }

        //60秒只能发送一次
        if(RedisHelper::getRedis()->exists(Yii::$app->params['cache_key_prefix']['phone_code_repeat_key'] . $phone)) {
            throw new FixedException('验证码发送太频繁', ErrorCode::TOO_OFTEN);
        }else {
            //缓存
            RedisHelper::getRedis()->set(Yii::$app->params['cache_key_prefix']['phone_code_repeat_key'] . $phone, 1, 'EX', Yii::$app->params['cache_code']['phone_code_repeat_time']);
        }

        $phoneExists = ARShop::existsPhone($phone); //获取手机是否存在
        if($type === ARShopEvent::EVENT_LIST['REGISTER'] && $phoneExists){ //注册
            throw new FixedException('手机号已被注册', ErrorCode::EXISTS_PHONE);
        }elseif ($type === ARShopEvent::EVENT_LIST['FIND_PASSWORD'] && !$phoneExists) { //找回密码
            throw new FixedException('手机号不存在', ErrorCode::NOT_EXISTS_PHONE);
        }
    }


    /**
     * 保存商户
     * @param $shop
     * @throws \Exception
     */
    public function saveShop($shop)
    {
        if(!empty($shop)) {
            //密码处理
            $pwd_arr = StringsHelper::hashPwd($shop['password']);

            try{
                $trans = ARShop::getDb()->beginTransaction();
                $datas = [];
                $tmp = [];

                //生产商户 id
                $shop_id = StringsHelper::createId();

                $tmp['id'] = $shop_id;
                $tmp['phone'] = $shop['phone'];
                $tmp['username'] = $shop['phone'];
                $tmp['email'] = $shop['phone'];
                $tmp['name'] = $shop['name'];
                $tmp['password'] = $pwd_arr[0];
                $tmp['salt'] = $pwd_arr[1];
                $tmp['shop_category_id'] = $shop['category'];
                $tmp['code_p'] = $shop['code_p'];
                $tmp['code_c'] = $shop['code_c'];
                $tmp['code_a'] = $shop['code_a'];
                $tmp['address'] = $shop['address'];
                $tmp['open_time'] = $shop['open_time'];
                $tmp['close_time'] = $shop['close_time'];
                $tmp['ip'] = UtilsHelper::getUserLongIp(); //ip2long
                $datas[] = $tmp;

                ARShop::saveShops($datas);
                $this->saveShopImage($shop_id, $shop['logo'], $shop['plate'], $shop['license'], $shop['certificate']);

                //生产client id
                $bindParams[':clientid'] = StringsHelper::createId();
                $bindParams[':secretkey'] = StringsHelper::createGuid();
                $bindParams[':shop_id'] = $shop_id;
                ARShop::getDb()
                    ->createCommand('insert into client (clientid, secretkey, shop_id) VALUES (:clientid, :secretkey, :shop_id)')
                    ->bindValues($bindParams)
                    ->execute();

                $trans->commit();

                //缓存商户信息
                $this->setShopCacheAll($shop_id);
            } catch (\Exception $e) {
                $trans->rollback();
                throw $e;
            }

        }
    }

    /**
     * 添加店铺图片
     * @param $shopId integer 店铺shop_id
     * @param $logo string|array 保存店铺logo
     * @param $plate string|array 保存店铺插图
     * @param $license string|array 保存店铺营业执照
     * @param $certificate string|array 经营许可证
     * @throws \Exception
     */
    private function saveShopImage($shopId, $logo, $plate, $license, $certificate)
    {
        $ip = UtilsHelper::getUserLongIp(); //客户端 ip

        //保存 logo 及关系
        if(is_string($logo)) {
            if(!empty($logo)){
                $resourceId = ARResource::saveResource($logo, $ip, ARResource::POSITION_TYPE1);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }

        }elseif (is_array($logo)) {
            foreach ($logo as $v) {
                if (empty($v)) continue;
                $resourceId = ARResource::saveResource($v, $ip, ARResource::POSITION_TYPE1);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }else{
            throw new \Exception('logo 参数格式错误',  ErrorCode::CORRECT_FORMAT);
        }

        //保存 插图 及关系
        if(is_string($plate)) {
            if(!empty($plate)) {
                $resourceId = ARResource::saveResource($plate, $ip, ARResource::POSITION_TYPE2);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }elseif (is_array($plate)) {
            foreach ($plate as $v) {
                if (empty($v)) continue;
                $resourceId = ARResource::saveResource($v, $ip, ARResource::POSITION_TYPE2);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }else{
            throw new \Exception('插图 参数格式错误',  ErrorCode::CORRECT_FORMAT);
        }

        //保存 营业执照 及关系
        if(is_string($license)) {
            if(!empty($license)) {
                $resourceId = ARResource::saveResource($license, $ip, ARResource::POSITION_TYPE3);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }elseif (is_array($license)) {
            foreach ($license as $v) {
                if (empty($v)) continue;
                $resourceId = ARResource::saveResource($v, $ip, ARResource::POSITION_TYPE3);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }else{
            throw new \Exception('营业执照 参数格式错误',  ErrorCode::CORRECT_FORMAT);
        }

        //保存 经营许可证 及关系
        if(is_string($certificate)) {
            if(!empty($certificate)) {
                $resourceId = ARResource::saveResource($certificate, $ip, ARResource::POSITION_TYPE3);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }elseif (is_array($certificate)) {
            foreach ($certificate as $v) {
                if (empty($v))  continue;
                $resourceId = ARResource::saveResource($v, $ip, ARResource::POSITION_TYPE3);
                ARResource::saveShopResourceRelation($shopId, $resourceId);
            }
        }else{
            throw new \Exception('经营许可证 参数格式错误',  ErrorCode::CORRECT_FORMAT);
        }

    }

    /**
     * 获取商户类别
     * @return array
     */
    public function getCategory()
    {
        if($data = RedisHelper::getRedis()->get(Yii::$app->params['cache_key_prefix']['shop_category_key'])) {
            $category = json_decode($data, 512);
        }else{
            $category = ARCategory::getCategory();
            RedisHelper::getRedis()->set(Yii::$app->params['cache_key_prefix']['shop_category_key'], json_encode($category, 512));
        }


        return $category;
    }

    /**
     * @param string $phone     手机号
     * @param string $code      验证码
     * @return string $token    下一步需要的 token
     * @throws UnavailableParamsException
     */
    public function findPwdCheck($phone, $code)
    {
        //手机校验
        if(!CheckHelper::unEmptyValidate($phone)){
            throw new UnavailableParamsException('账号不能为空', ErrorCode::UN_EMPTY_ACCOUNT);
        }else if (!CheckHelper::phoneValidate($phone)){ //手机号验证
            throw new UnavailableParamsException('手机号不正确', ErrorCode::CORRECT_PHONE);
        }else if(!ARShop::existsPhone($phone)) {
            throw new UnavailableParamsException('手机号不存在', ErrorCode::NOT_EXISTS_PHONE);
        }

        //验证码校验
        if(!CheckHelper::unEmptyValidate($code)){
            throw new UnavailableParamsException('验证码不能为空', ErrorCode::UN_EMPTY_CODE);
        }else if (!$this->codeCheck($code, $phone, ARShopEvent::EVENT_LIST['FIND_PASSWORD'])){
            throw new UnavailableParamsException('验证码不正确', ErrorCode::CORRECT_CODE);
        }

        //到此验证通过,生产 token
        $token = StringsHelper::createToken();
        //存入 缓存,方便下一步验证手机是否对应
        RedisHelper::getRedis()->set(Yii::$app->params['cache_key_prefix']['findpwd_phone_token'] . $phone, $token, 'EX', Yii::$app->params['cache_code']['ttl']);

        return $token;
    }


    /**
     * 修改密码
     * @param string $phone         手机号
     * @param string $password      密码
     * @param string $rep_password  重复密码
     * @param string $token         token
     * @param integer $type         修改密码方式 1:忘记密码 2:直接修改密码
     * @return bool
     * @throws UnavailableParamsException
     */
    public function disPwd($phone, $password, $rep_password, $token, $type = 1)
    {
        //密码校验
        if(!CheckHelper::unEmptyValidate($password)){
            throw new UnavailableParamsException('密码不能为空', ErrorCode::UN_EMPTY_PASSWORD);
        }elseif (CheckHelper::compareValuesValidate($password, $rep_password, '!==')) {
            throw new UnavailableParamsException('两次输入密码不一致', ErrorCode::INCONSISTENT_PASSWORD);
        }

        if($type == 1){
            //验证 token
            $compare_token = RedisHelper::getRedis()->get(Yii::$app->params['cache_key_prefix']['findpwd_phone_token'] . $phone);
            if(CheckHelper::compareValuesValidate($token, $compare_token, '!==')) {
                throw new UnavailableParamsException('token 验证不通过,修改密码失败', ErrorCode::ERROR);
            }
        }

        //修改密码
        list($encry_password, $salt) = StringsHelper::hashPwd($password);
        $ret = ARShop::updatePassword($phone, $encry_password, $salt);
        return $ret;

    }
}

