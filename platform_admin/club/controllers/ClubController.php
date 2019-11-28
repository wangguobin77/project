<?php
namespace app\controllers;

use app\controllers\base\BaseController;
use yii\web\Controller;
use Yii;

class ClubController extends Controller
{
    public function actionTest()
    {
        $arr = array(
            'like_id' => createGuid(),
            'client_id' => "A6E8838711D4FD3FF8B61A5E",
//            'client_id' => $this->client_id,
            'type' => filterData(Yii::$app->request->post('type','video'),'string',32),
            'like_to' => filterData(Yii::$app->request->post('like_to','123'),'string',32), //点赞的对象
            'user_id' => "1122", // 点赞的用户user_id
//            'user_id' => $this->userid,
            'ip' => 0,
            'time' => time(),
//            'ip' => ip2long(getIp()),
        );
        $arr['is_like'] = 1;
        $community = new clubConfig();
        $re = $community->insert_like($arr);

        var_dump($re);
    }
}