<?php
/**
 * Created by PhpStorm.
 * User: localuser1
 * Date: 2019/8/20
 * Time: 上午10:58
 */

namespace app\models\broadcasts;

use yii;
use yii\data\Pagination;
class BroadcasrBase extends Broadcast
{
    /**
     * 返回该商家的所有广告
     * @param $shopId
     * @return array
     */
    public function getBroadcastsList($shopId)
    {

        $query = Broadcast::find()
            ->where([
                'shop_id' => $shopId,
            ]);

        $queryCount = clone $query;
        $count = $queryCount->count();

        $pages = new Pagination(['defaultPageSize'=>10, 'totalCount' => $count]);

        $datas = $query
            ->joinWith(['broadcast_coupon'])
            ->select('broadcast.*,broadcast_coupon.broadcast_id,broadcast_coupon.coupon_type_id')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('created_at desc')
            ->asArray()
            ->all();

        $coupon_list = $this->getCouponList_shopId($shopId,$pages->offset,$pages->limit);
        return [$datas, $pages,$coupon_list];
    }

    public function getCouponList_shopId($shop_id,$offset,$limit)
    {
        /*
        $data = [
          'shop_id' =>$shop_id,
            'offset' =>$offset,
            'limit' => $limit,
            'orderby' => 'desc'
        ];*/
       // postCurl_1("http://api/coupon/coupons",);
        $data['code'] = 0;
        $data['data'] = [
          'items'  => [
              [
                "available_date"=> "96",
                "end_at"=> "1567267200",
                "id"=> "20",
                "info"=> "这是比较长的描述，上面的available_date ga96 ，表示周六周日可以使用",
                "price"=> "5000",
                "scope"=> "这是使用范围的描述",
                "shop_id"=> "1",
                "start_at"=> "1561910400",
                "title"=> "这是50抵扣101元",
                "url"=> "https://shop.leaplink.cn/api/coupon/coupons/20",
                "worth"=> "10000"
              ],
              [
                  "available_date"=> "97",
                  "end_at"=> "1567267200",
                  "id"=> "21",
                  "info"=> "这是比较长的描述，上面的available_date ga96 ，表示周六周日可以使用",
                  "price"=> "5000",
                  "scope"=> "这是使用范围的描述",
                  "shop_id"=> "1",
                  "start_at"=> "1561910400",
                  "title"=> "这是50抵扣102元",
                  "url"=> "https://shop.leaplink.cn/api/coupon/coupons/20",
                  "worth"=> "10000"
              ],
              [
                  "available_date"=> "96",
                  "end_at"=> "1567267200",
                  "id"=> "22",
                  "info"=> "这是比较长的描述，上面的available_date ga96 ，表示周六周日可以使用",
                  "price"=> "5000",
                  "scope"=> "这是使用范围的描述",
                  "shop_id"=> "1",
                  "start_at"=> "1561910400",
                  "title"=> "这是50抵扣103元",
                  "url"=> "https://shop.leaplink.cn/api/coupon/coupons/20",
                  "worth"=> "10000"
              ],
              [
                  "available_date"=> "96",
                  "end_at"=> "1567267200",
                  "id"=> "23",
                  "info"=> "这是比较长的描述，上面的available_date ga96 ，表示周六周日可以使用",
                  "price"=> "5000",
                  "scope"=> "这是使用范围的描述",
                  "shop_id"=> "1",
                  "start_at"=> "1561910400",
                  "title"=> "这是50抵扣104元",
                  "url"=> "https://shop.leaplink.cn/api/coupon/coupons/20",
                  "worth"=> "10000"
              ],
          ]
        ];
        $data['limit'] = 3;
        $data['offset'] =  0;
        $data['total'] = 10;

        return $this->disDataIndexFKey($data['data']['items']);


    }

    protected function disDataIndexFKey($data)
    {
        $list = [];
        if(!is_array($data)) return $list;

        foreach ($data as $key=>$val){
            $list[$val['id']] = $val;
        }

        return $list;
    }


    /**
     * 处理前端绑定广告绑定的coupon
     * @param $b_id 广告id
     * @param $data 绑定的coupon list
     */
    public function disAjaxCouponList($b_id,$data)
    {
        $sql = '';
        foreach ($data as $key=>$val){

            if($val){//周几
                $sql .= '('
                    .$b_id.
                    ','.$val.
                    ','.time().
                    '),';
            }

        }

        if($sql == '') return false;

        $sql = 'insert into broadcast_coupon(`broadcast_id`,`coupon_type_id`,`created_at`)values'.$sql;

        $sql = substr($sql,0,strlen($sql)-1);

        $connection = Yii::$app->broadcast;

        $command = $connection->createCommand($sql);
        return $command->execute();
    }

}