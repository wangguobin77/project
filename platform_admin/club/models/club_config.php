<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/21
 * Time: 19:59
 */

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class club_config extends Model
{

    public function getClubList($offset,$limit,$from,$type)
    {
        $data = Yii::$app->db->createCommand("call sp_club_config_list_select_all(
            '". $offset ."' 
            ,'". $limit ."'
            ,'". $from ."'
            ,'". $type ."'
        );")->queryAll();

        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data,'count'=>$count['@rowCount']];
    }


    public function getClubConfigList($offset,$limit,$client_id)
    {
        $data = Yii::$app->db->createCommand("call club_config_list_select_all(
            '". $offset ."' 
            ,'". $limit ."'
            ,'". $client_id ."'
        );")->queryAll();

        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data,'count'=>$count['@rowCount']];
    }

    public function getExistClientId($client_id)
    {
        $spname = 'club_client_by_client_id';
        $res = Yii::$app->db->createCommand("call ".$spname."(
            '". $client_id ."'
        )")->queryOne();
        return $res;
    }

    public function getTypeSubmit($group_id,$sn)
    {
        $spname = 'club_config_type_add';
        Yii::$app->db->createCommand("call " . $spname . "(
            '" . $group_id . "'
            ,'" . $sn . "'
            ,@ret
        )")->query();
        $res = Yii::$app->db->createCommand("select @ret")->queryOne();
        return $res;
    }

    /**
     * 获取所有的类型
     *
     * */
    public function getClubTypesAll()
    {
        $res = Yii::$app->db->createCommand("call club_type_select_all(
            
        )")->queryAll();
        return $res;
    }


    /**
     * 获取所有的client
     *
     * */
    public function getClubClientsAll()
    {
        $res = Yii::$app->db->createCommand("call club_client_select_all(
            
        )")->queryAll();
        return $res;
    }



    /**
     * 删除type
     *
     * */
    public function getTypeDelete($id)
    {
        Yii::$app->db->createCommand("call club_config_del(
            $id,
            @ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /**
     * 查看是否点赞过，有则返回点赞信息 没有则无
     *
     * */
    public function getCheckLike($client_id,$type,$like_to,$uid)
    {
        $res = Yii::$app->db->createCommand("call sp_user_check_like(
            '". $client_id ."'
           , '". $type ."'
           , '". $like_to ."'
            ,'". $uid ."'
        )")->queryOne();
        return $res;
    }

    /**
     * 查看是否点赞过，有则返回点赞信息 没有则无
     *
     * */
    public function getCheckUserCollect($collect_id,$uid)
    {
        $res = Yii::$app->db->createCommand("call sp_user_check_collect(
            ". $collect_id ."
            ,". $uid ."
        )")->queryOne();
        return $res;
    }
    /**
     *  添加一个点赞记录
     * */
    public function insert_like($data)
    {
        Yii::$app->db->createCommand("call sp_user_like_add(
            '". $data['like_id'] ."'
            ,'". $data['client_id'] ."'
            ,'". $data['type'] ."'
            ,'". $data['like_to'] ."'
            ,". $data['user_id'] ."
            ,". $data['is_like'] ."
            ,". $data['ip'] ."
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }



    public function insert_collect($data)
    {
        Yii::$app->db->createCommand("call sp_user_collect_add(
            ". $data['collect_id'] ."
            ,". $data['uid'] ."
            ,". $data['status'] ."
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    public function remove_collect($data)
    {
        Yii::$app->db->createCommand("call sp_user_remove_collect(
            ". $data['collect_id'] ."
            ,". $data['uid'] ."
            ,". $data['status'] ."
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }
    /**
     *  添加一个点赞记录
     * */
    public function remove_like($data)
    {
        Yii::$app->db->createCommand("call sp_user_remove_like(
            '". $data['like_id'] ."'
            ,". $data['is_like'] ."
            ,". $data['ip'] ."
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /**
     * 检查该用户或者IP点赞过这个文章or活动
     */
    public function getLikeCount($data)
    {
        return Yii::$app->db->createCommand("call sp_user_like_count(
            '". $data['client_id'] ."'
            ,'". $data['type'] ."'
            ,'". $data['like_to'] ."'
        )")->queryOne();
    }

    public function getCollectCount($uid)
    {
        return Yii::$app->db->createCommand("call sp_user_collect_count(
            $uid
        )")->queryOne();
    }
    /**
     * 获取总里程排行
     * @param
     * @return array|bool
     */
    public function getRankSelectAll($offset,$limit)
    {
        $data = Yii::$app->db->createCommand("call sp_rank_select_all(
            '". $offset ."'
            ,'". $limit ."'
            );")->queryAll();
        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        if($data)
        {
            return ['data'=>$data,'count'=>$count['@rowCount']];
        }

        return $data;

    }

}