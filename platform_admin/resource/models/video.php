<?php
/**
 * Created by PhpStorm.
 * User: OEMUSER
 * Date: 2018/8/21
 * Time: 11:25
 */

namespace club\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class video extends Model
{

    /**
     * 获取是否存在client_id
     * @param $client_id
     * @return array|false
     */
    public function getDetailVideoId($video_id)
    {
        $spname = 'club_video_by_video_id';
        $res = Yii::$app->club_db->createCommand("call ".$spname."(
            '". $video_id ."'
        )")->queryOne();
        return $res;
    }



    public function getClubVideoList($offset,$limit,$status)
    {
        $data = Yii::$app->club_db->createCommand("call club_video_list_select_all(
            '". $offset ."'
            ,'". $limit ."'
            ,'". $status ."'
        );")->queryAll();

        $count = Yii::$app->club_db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data,'count'=>$count['@rowCount']];
    }

    /**
     *  设置推荐
     * @param $is_recommended
     * @return mixed
     */

    public function getEditVideoId($video_id,$is_recommended)
    {
         Yii::$app->club_db->createCommand("call club_video_set_recommend(
            ". $video_id ."
            ,". $is_recommended ."
            ,@ret
        );")->query();
        $res =  Yii::$app->club_db->createCommand("select @ret")->queryOne();

        return $res;
    }


    /**
     *  删除视频
     * @param $is_recommended
     * @return mixed
     */

    public function getVideoDel($video_id)
    {
        Yii::$app->club_db->createCommand("call club_video_del(
            ". $video_id ."
            ,@ret
        );")->query();
        $res =  Yii::$app->club_db->createCommand("select @ret")->queryOne();

        return $res;
    }

}
