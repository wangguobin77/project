<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-01
 * Time: 17:14
 */

namespace app\controllers\bus;

use app\controllers\traits\couponTrait;
use app\controllers\traits\weekTrait;
use app\models\coupon\ARCouponType;
use app\models\shop\ARShop;
use common\consequence\ErrorCode;
use common\exception\FixedException;
use common\helpers\ArrayHelper;
use common\helpers\StringsHelper;
use common\helpers\UtilsHelper;
use yii\data\Pagination;
use yii\db\Exception;

/**
 * 优惠券逻辑类
 * Class couponBus
 * @package app\controllers\bus
 */
class couponBus
{
    use weekTrait;
    use couponTrait;

    /**
     * @param $shopId
     * @param $params
     * @return array
     */
    public function getList($shopId, $params)
    {
        $query = ARCouponType::find()
            ->where([
                'shop_id' => $shopId,
            ]);

        if(isset($params['check_status']) && $params['check_status'] !== '') {
            $query->andWhere(['check_status' => $params['check_status']]);
        }
        if(isset($params['title']) && $params['title']) {
            $query->andWhere(['like','title', trim($params['title'])]);
        }

        $queryCount = clone $query;
        $count = $queryCount->count();

        $pages = new Pagination(['defaultPageSize'=>10, 'totalCount' => $count]);

        $datas = $query
            ->select('id,title,price,worth,start_at,end_at,available_date,number,check_status,updated_at,status')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_at desc')
            ->asArray()
            ->all();

        //对数据做一些处理
        foreach ($datas as &$item) {
            $item['start_at'] = UtilsHelper::int2date($item['start_at']); //时间戳转日期
            $item['end_at'] = UtilsHelper::int2date($item['end_at']); //时间戳转日期

            //不可用日期转换
            $item['week'] = $this->getWeekStatusLabel($item['available_date']);
        }
        unset($item);


        return [$datas, $pages];
    }

    public function getCouponById($shopId, $id)
    {
        $coupon = ARCouponType::find()
            ->where([
                'id' => $id,
                'shop_id' => $shopId,
            ])->asArray()->one();
        $coupon['price'] = UtilsHelper::fen2yuan($coupon['price']); //分转为元
        $coupon['worth'] = UtilsHelper::fen2yuan($coupon['worth']); //分转为元

        $coupon['start_at'] = UtilsHelper::int2date($coupon['start_at']); //时间戳转日期
        $coupon['end_at'] = UtilsHelper::int2date($coupon['end_at']); //时间戳转日期
        return $coupon;
    }

    /**
     * 更新优惠券
     * @param $shop_id
     * @param $data
     * @return boolean
     * @throws FixedException
     */
    public function updateCoupon($shop_id, $data)
    {
        $couponType = ARCouponType::find()
            ->where(['id' => $data['id']])
            ->one();
        if(empty($couponType)){
            throw new FixedException('兑换券信息不存在', ErrorCode::NOT_EXISTS);
        }elseif($couponType->shop_id != $shop_id){
            throw new FixedException('非法的修改(不是属于该商户的兑换券)', ErrorCode::NOT_OWNER);
        }

        if(isset($data['coupon_category'])){ //类别 id
            $couponType->coupon_category_id = $data['coupon_category'];
        }
        if(isset($data['title'])){  //优惠券名称
            $couponType->title = trim($data['title']);
        }
        if(isset($data['price'])){  //价格
            $couponType->price = UtilsHelper::yuan2fen($data['price']);
        }
        if(isset($data['worth'])){  //价值
            $couponType->worth = UtilsHelper::yuan2fen($data['worth']);
        }
        if(isset($data['scope'])){  //适用范围
            $couponType->scope = $data['scope'];
        }
        if(isset($data['info'])){  //描述
            $couponType->info = $data['info'];
        }
        if(isset($data['type'])){  //类型
            $couponType->type = $data['type'];
        }

        $couponType->updated_at = time();//更新时间

        try{

            $couponType->save();

            //缓存
            $this->setCache($couponType);

        }catch (\Exception $e){ //抓取sql 运行错误
//            throw new FixedException('修改失败', ErrorCode::ERROR);
            throw new FixedException($e->getMessage(), ErrorCode::ERROR);
        }

        return true;
    }

    public function setCache($couponType)
    {
        $couponType = \yii\helpers\ArrayHelper::toArray($couponType);

        $this->setCacheInfo($couponType['id'], $couponType); //数据
        $this->setCacheSearch($couponType);  //查询条件
        $this->setCacheSort($couponType);  //排序
    }

    /**
     * 添加优惠券
     * @param $datas
     * @return integer
     * @throws FixedException
     */
    public function createCoupon($shop_id ,$datas)
    {
        if(!ARShop::isPass($shop_id)){
            throw new FixedException('商户未通过审核,不能创建优惠券', ErrorCode::NOT_ALLOWED_STATUS);
        }

        $insert_id = StringsHelper::createId();
        if(!empty($datas)){
            $argv = [];
            foreach ($datas as $item){
                $tmp = [];
                $tmp['id'] = $insert_id;
                $tmp['coupon_category_id'] = ArrayHelper::getNoEmpty($item, 'coupon_category', 1);
                $tmp['title'] = trim($item['title']);
                $tmp['price'] = UtilsHelper::yuan2fen($item['price']);
                $tmp['worth'] = UtilsHelper::yuan2fen($item['worth']);
                $tmp['scope'] = trim($item['scope']);
                $tmp['info'] = trim($item['info']);
                $tmp['type'] = ARCouponType::TYPE1;
                $tmp['shop_id'] = $shop_id;
                $tmp['status'] = ARCouponType::STATUS0;
                $tmp['start_at'] = time();
                $tmp['end_at'] = strtotime('2050-12-31');
                $tmp['available_date'] = 32;
                $tmp['created_at'] = time();
                $tmp['updated_at'] = time();
                $tmp['number'] = (int)$item['number'];
                if(isset($item['resource_id'])){
                    $tmp['resource_id'] = (int)$item['resource_id'];
                }
                $argv[] = $tmp;
            }

            try{
                $tran = ARCouponType::getDb()->beginTransaction();

                ARCouponType::saveConponTypes($argv);

                $tran->commit();

                //缓存
//                $coupon = ARCouponType::find()->where(['id'=>$insert_id])->asArray()->one();
//                $this->setCache($coupon);

            }catch (\Exception $e){
                $tran->rollback();
                throw new FixedException($e->getMessage(), ErrorCode::ERROR);
                //throw new FixedException('新增失败', ErrorCode::ERROR);
            }
        }
        return $insert_id;
    }

    /**
     * @param $id
     * @throws FixedException
     * @throws \Throwable
     */
    public function del($id){
        $couponType = ARCouponType::findOne($id);
        if($couponType->check_status == ARCouponType::CHECK_STATUS2){
            throw new FixedException('已生效状态,不允许删除', ErrorCode::NOT_ALLOWED_STATUS);
        }
        try{
            $couponType->check_status = ARCouponType::CHECK_STATUS3;
            $couponType->updated_at = time();
            $couponType->save();

            //删除缓存中条件
            //$this->delMembers(\yii\helpers\ArrayHelper::toArray($couponType));

        }catch (\Exception $e){
            throw new FixedException('删除失败', ErrorCode::ERROR);
        }
    }

    /**
     * @param $id
     * @throws FixedException
     */
    public function check_pass($id){
        $couponType = ARCouponType::findOne($id);
        if($couponType->check_status !== ARCouponType::CHECK_STATUS0){
            throw new FixedException('非法状态', ErrorCode::NOT_ALLOWED_STATUS);
        }
        try{
            $couponType->check_status = ARCouponType::CHECK_STATUS1;
            $couponType->updated_at = time();
            $couponType->save();

        }catch (\Exception $e){
            throw new FixedException($e->getMessage(), ErrorCode::ERROR);
            //throw new FixedException('提交失败', ErrorCode::ERROR);
        }

    }

    public function status_rm($id)
    {
        $couponType = ARCouponType::findOne($id);
        try{
            $couponType->status = ARCouponType::STATUS1;
            $couponType->updated_at = time();
            $couponType->save();

        }catch (\Exception $e){
            throw new FixedException($e->getMessage(), ErrorCode::ERROR);
            //throw new FixedException('提交失败', ErrorCode::ERROR);
        }
    }
}