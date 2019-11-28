<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/21
 * Time: 11:25
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class client extends Model
{
    /**
     * 获取UPC  via SKUID
     * @param $sku_id
     * @return array|false
     */
    public function getClientAdd($product_name,$product_code,$status)
    {
        $spname = 'club_client_add';
        Yii::$app->db->createCommand("call ".$spname."(
            '". $product_name ."'
            ,'". $product_code ."'
            ,'". $status ."'
            ,@ret
        )")->query();
        $res =  Yii::$app->db->createCommand("select @ret")->queryOne();
        return $res;
    }
    /**
     * 获取UPC  via SKUID
     * @param $sku_id
     * @return array|false
     */
    public function getClubClientEdit($id,$client_id,$client_name,$status)
    {
        $spname = 'club_client_update';
        Yii::$app->db->createCommand("call ".$spname."(
            ". $id ."
            ,'". $client_id ."'
            ,'". $client_name ."'
            ,'". $status ."'
            ,@ret
        )")->query();
        $res =  Yii::$app->db->createCommand("select @ret")->queryOne();
        return $res;
    }


    /**
     * 获取是否存在client_id
     * @param $client_id
     * @return array|false
     */
    public function getExistClientId($client_id)
    {
        $spname = 'club_client_by_client_id';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $client_id ."'
        )")->queryOne();
        return $res;
    }


    /**
     * 获取是否存在client_id
     * @param $client_id
     * @return array|false
     */
    public function getExistType($client_id)
    {
        $spname = 'club_config_by_client_id';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $client_id ."'
        )")->queryOne();
        return $res;
    }
    /**
     * 获取是否存在client_id
     * @param $client_id
     * @return array|false
     */
    public function getDetailClientId($client_id)
    {
        $spname = 'club_client_by_client_id';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $client_id ."'
        )")->queryOne();
        return $res;
    }

    /**
     * 获取是否存在client_name
     * @param $client_name
     * @return array|false
     */
    public function getExistClientName($client_name)
    {
        $spname = 'club_client_by_client_name';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $client_name ."'
        )")->queryOne();

        return $res;
    }


    public function getClubClientById($id)
    {
        $spname = 'club_client_select_one_by_id';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $id ."'
        )")->queryOne();

        return $res;
    }

    public function getClubClientList($offset,$limit,$client_name)
    {
        $data = Yii::$app->db->createCommand("call club_client_list_select_all(
            '". $offset ."'
            ,'". $limit ."'
            ,'". $client_name ."'
        );")->queryAll();

        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data,'count'=>$count['@rowCount']];
    }
    public function getExistProduct($pro_id)
    {
        $spname = 'ota_exist_product_version_by_pro_id';
        $res = Yii::$app->db->createCommand("call " . $spname . "(
            '" . $pro_id . "'
        )")->queryOne();
        return $res;
    }

    public function getClientDelete($client_id)
    {
         Yii::$app->db->createCommand("call club_client_delete_one(
            '". $client_id ."'
            ,@ret
        );")->query();
        $res =  Yii::$app->db->createCommand("select @ret")->queryOne();

        return $res;
    }

}
