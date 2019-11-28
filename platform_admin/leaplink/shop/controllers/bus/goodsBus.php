<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2019-08-16
 * Time: 16:07
 */

namespace app\controllers\bus;


use app\models\goods\ARGoods;
use common\consequence\ErrorCode;
use common\exception\FixedException;
use common\helpers\ArrayHelper;
use common\helpers\UtilsHelper;
use yii\data\Pagination;

class goodsBus
{
    /**
     * @param $shopId
     * @param $params
     * @return array
     */
    public function getList($shopId, $params)
    {
        $query = ARGoods::find()
            ->where([
                'shop_id' => $shopId,
            ]);

        if(isset($params['check_status']) && $params['check_status'] !== '') {
            $query->andWhere(['check_status' => $params['check_status']]);
        }
        if(isset($params['name']) && $params['name']) {
            $query->andWhere(['like','name', trim($params['name'])]);
        }
        if(isset($params['upc']) && $params['upc']) {
            $query->andWhere(['like','upc', trim($params['upc'])]);
        }

        $queryCount = clone $query;
        $count = $queryCount->count();

        $pages = new Pagination(['defaultPageSize'=>10, 'totalCount' => $count]);

        $datas = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_at desc')
            ->asArray()
            ->all();

        return [$datas, $pages];
    }

    public function getGoodsById($shopId, $id)
    {
        $coupon = ARGoods::find()
            ->where([
                'id' => $id,
                'shop_id' => $shopId,
            ])->asArray()->one();

        return $coupon;
    }

    /**
     * 更新商品
     * @param $shop_id
     * @param $data
     * @return boolean
     * @throws FixedException
     */
    public function updateGoods($shop_id, $data)
    {
        $goods = ARGoods::find()
            ->where(['id' => $data['id']])
            ->one();
        if(empty($goods)){
            throw new FixedException('商品不存在', ErrorCode::NOT_EXISTS);
        }elseif($goods->shop_id != $shop_id){
            throw new FixedException('非法的修改(不是属于该商户的商品)', ErrorCode::NOT_OWNER);
        }

        if(isset($data['upc'])){  //核销码
            $goods->upc = trim($data['upc']);
        }
        if(isset($data['name'])){  //品名
            $goods->name = trim($data['name']);
        }
        if(isset($data['price'])){  //价格
            $goods->price = UtilsHelper::yuan2fen($data['price']);
        }
        if(isset($data['worth'])){  //价值
            $goods->worth = UtilsHelper::yuan2fen($data['worth']);
        }

        $goods->updated_at = time();//更新时间

        try{

            $goods->save();

            //缓存
            //$this->setCache($goods);

        }catch (\Exception $e){ //抓取sql 运行错误
//            throw new FixedException('修改失败', ErrorCode::ERROR);
            throw new FixedException($e->getMessage(), ErrorCode::ERROR);
        }

        return true;
    }

    public function setCache($goods)
    {
        $goods = \yii\helpers\ArrayHelper::toArray($goods);

        $this->setCacheInfo($goods['id'], $goods); //数据
        $this->setCacheSearch($goods);  //查询条件
        $this->setCacheSort($goods);  //排序
    }

    /**
     * 添加优惠券
     * @param $datas
     * @return integer
     * @throws FixedException
     */
    public function createGoods($shop_id ,$datas)
    {
        $insert_id = -1;
        if(!empty($datas)){
            $argv = [];
            foreach ($datas as $item){
                $tmp = [];
                $tmp['name'] = trim($item['name']);
                $tmp['price'] = UtilsHelper::yuan2fen($item['price']);
                $tmp['worth'] = UtilsHelper::yuan2fen($item['worth']);
                $tmp['shop_id'] = $shop_id;
                $tmp['created_at'] = time();
                $tmp['updated_at'] = time();
                $tmp['upc'] = isset($item['upc']) && $item['upc']?trim($item['upc']):'';
                $argv[] = $tmp;
            }

            try{
                $tran = ARGoods::getDb()->beginTransaction();

                $insert_id = ARGoods::saveGoods($argv);

                //todo  保存图片关联

                $tran->commit();

                //缓存


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
        $goods = ARGoods::findOne($id);
        if($goods->check_status == ARGoods::CHECK_STATUS2){
            throw new FixedException('已生效状态,不允许删除', ErrorCode::NOT_ALLOWED_STATUS);
        }
        try{
            $goods->check_status = ARGoods::CHECK_STATUS3;
            $goods->updated_at = time();
            $goods->save();

            //todo 修改缓存


        }catch (\Exception $e){
            throw new FixedException('删除失败', ErrorCode::ERROR);
        }
    }

    /**
     * @param $id
     * @throws FixedException
     */
    public function check_pass($id){
        $goods = ARGoods::findOne($id);
        if($goods->check_status !== ARGoods::CHECK_STATUS0){
            throw new FixedException('非法状态', ErrorCode::NOT_ALLOWED_STATUS);
        }
        try{
            $goods->check_status = ARGoods::CHECK_STATUS1;
            $goods->updated_at = time();
            $goods->save();

            //todo 修改缓存


        }catch (\Exception $e){
            throw new FixedException($e->getMessage(), ErrorCode::ERROR);
            //throw new FixedException('提交失败', ErrorCode::ERROR);
        }

    }
}