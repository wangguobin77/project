<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-07-24
 * Time: 15:48
 */

namespace app\controllers\bus;

use common\helpers\ArrayHelper;
use common\helpers\RedisHelper;
use Yii;
use app\models\shop\ARShop;
use app\models\shop\ARResource;
use common\helpers\CheckHelper;
use common\helpers\UtilsHelper;
use common\consequence\ErrorCode;
use app\controllers\traits\shopTrait;
use app\models\shop\ARShopResourceRelation;
use common\exception\UnavailableParamsException;


/**
 * 商户业务逻辑控制器
 * Class shopBus
 * @package app\controllers\bus
 */
class shopBus
{
    use shopTrait;
    /**
     * 获取商户信息
     * @param integer $shop_id    商户 id
     */
    public function getShopById($shop_id)
    {
        $shopInfo = ARShop::findOne($shop_id);


        //获取省市区名
        $sql = "select area_id,area_name from areas where area_id = {$shopInfo->code_p} or area_id = {$shopInfo->code_c} or area_id = {$shopInfo->code_a} limit 3";

        $tmp = Yii::$app->shop->createCommand($sql)->QueryAll();
        $area = [];
        foreach ($tmp as $v){
            $area[$v['area_id']] = $v;
        }
        $shopInfo->code_p = ArrayHelper::getNoEmpty($area, $shopInfo->code_p);
        $shopInfo->code_c = ArrayHelper::getNoEmpty($area, $shopInfo->code_c);
        $shopInfo->code_a = ArrayHelper::getNoEmpty($area, $shopInfo->code_a);

        return $shopInfo;
    }

    /**
     * 商户更新信息校验
     * @param $params
     * @throws UnavailableParamsException
     */
    public function shopEditCheck($params){
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
     * 修改商户信息
     * @param $shop_id
     * @param $shop
     * @throws \Exception
     */
    public function updateShop($shop_id, $shop)
    {
        //修改基本信息
        try{
            $trans = Yii::$app->shop->beginTransaction();
            $data = [];


            $data['name'] = $shop['name'];
            $data['shop_category_id'] = $shop['category'];

            if(isset($shop['code_p'])){ //前面没做校验的需判断
                $data['code_p'] = $shop['code_p'];
            }
            if(isset($shop['code_c'])){
                $data['code_c'] = $shop['code_c'];
            }
            if(isset($shop['code_a'])){
                $data['code_a'] = $shop['code_a'];
            }


            $data['address'] = $shop['address'];
            $data['open_time'] = $shop['open_time'];
            $data['close_time'] = $shop['close_time'];

            ARShop::updateShop($shop_id, $data);

            $this->updateShopImage($shop_id, $shop);


            $trans->commit();

            //更新 缓存 中的商户信息
            $this->setShopCacheAll($shop_id);


        } catch (\Exception $e) {
            $trans->rollback();
            throw $e;
//            throw new UnavailableParamsException('数据库错误', ErrorCode::ERROR);
        }

    }

    /**
     * 修改商户图片
     * 三种情况
     * 1.  无改动 logo=>'', logo_id=>1 //关系表 id
     * 2.  替换新图   logo=>'http://02imgmini.eastday.com/mobile/20190722/2019072223_4dfa38136a124414959cda50c3a85b5b_7636_cover_mwpm_03201609.jpg', logo_id=>1
     * 3.  新增   logo=>'http://02imgmini.eastday.com/mobile/20190722/2019072223_4dfa38136a124414959cda50c3a85b5b_7636_cover_mwpm_03201609.jpg'
     * 4.  未支持 选择替换 即 logo_id => 1, logo_repalce_id => 3, 将资源 id 为 1 的换为 3 的
     *
     * 多个图片时需要注意
     * 提交数据如下:修改了第三个图片,结果前段 1.2个空没有占位
     * plate[]: http://02imgmini.eastday.com/mobile/20190722/2019072223_4dfa38136a124414959cda50c3a85b5b_7636_cover_mwpm_03201609.jpg
     * plate[]:
     * plate_id[]: 3
     * plate_id[]: 4
     * plate_id[]: 5
     * plate_id[]:
     * 为了应付这种情况,直接倒序
     * @param $shop_id
     * @param $shop
     * @throws \Exception
     */
    public function updateShopImage($shop_id, $shop)
    {
        $ip = UtilsHelper::getUserLongIp(); //客户端 ip
        //logo
        list($add, $update) = $this->getUpdateData($shop['logo'], $shop['logo_id']);
        if(!empty($add)){ //新增
            foreach ($add as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE1);
                ARResource::saveShopResourceRelation($shop_id, $resourceId);
            }
        }
        if(!empty($update)){ //修改
            foreach ($update as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE1);
                ARShopResourceRelation::updateRelation($item['shop_resource_relation_id'], ['resource_id' => $resourceId]);
            }
        }

        //插图 plate
        list($add, $update) = $this->getUpdateData($shop['plate'], $shop['plate_id']);
        if(!empty($add)){ //新增
            foreach ($add as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE2);
                ARResource::saveShopResourceRelation($shop_id, $resourceId);
            }
        }
        if(!empty($update)){ //修改
            foreach ($update as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE2);
                ARShopResourceRelation::updateRelation($item['shop_resource_relation_id'], ['resource_id' => $resourceId]);
            }
        }

        //营业执照 license
        list($add, $update) = $this->getUpdateData($shop['license'], $shop['license_id']);
        if(!empty($add)){ //新增
            foreach ($add as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE3);
                ARResource::saveShopResourceRelation($shop_id, $resourceId);
            }
        }
        if(!empty($update)){ //修改
            foreach ($update as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE3);
                ARShopResourceRelation::updateRelation($item['shop_resource_relation_id'], ['resource_id' => $resourceId]);
            }
        }

        //经营许可证 certificate
        list($add, $update) = $this->getUpdateData($shop['certificate'], $shop['certificate_id']);
        if(!empty($add)){ //新增
            foreach ($add as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE4);
                ARResource::saveShopResourceRelation($shop_id, $resourceId);
            }
        }
        if(!empty($update)){ //修改
            foreach ($update as $item){
                $resourceId = ARResource::saveResource($item['new_remote_uri'], $ip, ARResource::POSITION_TYPE4);
                ARShopResourceRelation::updateRelation($item['shop_resource_relation_id'], ['resource_id' => $resourceId]);
            }
        }
    }

    private function getUpdateData($d, $i)
    {
        $data = [];
        if(!is_array($d) ){
            $data[] = $d;
        }else{
            $data = array_reverse($d);
        }

        $index = [];
        if(!is_array($i) ){
            $index[] = $i;
        }else{
            $index = array_reverse($i);
        }

        $add = [];
        $update = [];
        foreach ($data as $k => $item) {
            if(!empty($item)){
                if(!empty($index[$k])){
                    $update[] = [
                        'new_remote_uri' => $item,
                        'shop_resource_relation_id' => $index[$k]
                    ];
                }else{
                    $add[] = [
                        'new_remote_uri' => $item,
                    ];
                }
            }
        }
        return [$add, $update];
    }


    public function getShopFromCache($shop_id)
    {
        if(!$shopInfo = $this->getShopCache($shop_id)){
            $this->setShopCacheAll($shop_id);
        }

        return $shopInfo;
    }
}