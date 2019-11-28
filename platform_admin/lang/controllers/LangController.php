<?php
namespace app\controllers;

use Yii;
use yii\data\Pagination;
use app\models\LanguageModel;
use lang\models;
class LangController extends base\LangBaseController
{
//    public $layout = false;
    protected $app_file_type = [1,2,3];

    /**key value 管理
     *
     */
    public function actionManage()
    {
        $model = new LanguageModel();

        $file = filterData(Yii::$app->request->get('file', 0), 'integer'); //file_id

        $language = filterData(Yii::$app->request->get('language',0), 'integer'); //当前第三语言id 没有0

        if (Yii::$app->request->isPost){     //添加key

                $key = filterData(Yii::$app->request->post('key'), 'string', 255, 1);    //key不能为空也不能超过255\

                $file_id = filterData( Yii::$app->request->post('file_id'), 'integer');

                $res = $model->langAppFileKeyAdd($file_id,$key);

                if ($res['@ret'] == 1) {
                    show_json(0, '添加成功');
                }
                if ($res['@ret'] == 200001) {
                    show_json(100001, '已存在');
                }
                show_json(100000, '添加失败');
        }

        $language_data = $model->langAppLangSelectByFileId($file);

        if ($language_data) {

            $arr = [];
            foreach ($language_data as $key=>$val) {
                if ($val['lang_short'] != 'zh' || $val['lang_short'] != 'cz') {
                    $arr[] = $val['id'];
                }
            }
            if ($language != 0) {
                if (!in_array($language,$arr)) {
                    show_json(100000, '参数不合法！');
                }
            }

            //根据语言和file取key value
            $data = $model->langData($language,$file);

            // 得到key的总数和value不为空的总数
            $value_all = 0;   //初始化key的总数
            $cn_value_full = 0; //初始化key在中文下已翻译的数量
            $en_value_full = 0; //初始化key在英文下已翻译的数量
            $th_value_full = 0; //初始化key在第三门下已翻译的数量
            foreach ($data as $key=>$val) {
                $value_all += 1;
                if (isset($val['en_value']) && $val['en_value']) {
                    $en_value_full += 1;
                }
                if (isset($val['cn_value']) && $val['cn_value']) {
                    $cn_value_full += 1;
                }
                if (isset($val['th_value']) && $val['th_value']) {
                    $th_value_full += 1;
                }
            }
            $count['all'] =  $value_all;    //key的总数
            $count['cn'] =  $cn_value_full; //key在中文下已翻译的数量
            $count['en'] =  $en_value_full; //key在中文下已翻译的数量
            $count['th'] =  $th_value_full; //key在中文下已翻译的数量

            //添加key需要的参数
            $app_data = $model->langAppSelectByFileId();

            foreach ($app_data as $key=>$val) {
                $add_data['app'][$val['app_id']] = $val['app_name'];
                $add_data['file_name'][] = $val['file_name'];
                $add_data['file_path'][] = $val['file_path'];
            }
            //去除重复数据
            $add_data['file_name'] = array_unique($add_data['file_name']);
            $add_data['file_path'] = array_unique($add_data['file_path']);

            return $this->render('/language/trans',[
                'data'=>$data,
                'third'=>$language,
                'file'=>$file,
                'language'=>$language_data,
                'count' =>$count,
                'add_data' =>$add_data, //添加key需要的参数
            ]);
        }
    }

    /*  下载文件
     *  @params
     */
    public function actionDown()
    {
        $file = models\LangAppFile::find()->where(['id'=>trim(Yii::$app->request->get('file'))])->asArray()->one();
//        var_dump($file);die;
        if ($file) {
            //验证语言
            $lang = models\LangAppLang::find()
                ->select('*')
                ->from('lang_app_lang a')
                ->join('left join', 'lang b', 'a.lang_id = b.id')
                ->where(['a.app_id'=>$file['app_id'], 'a.lang_id' => trim(Yii::$app->request->get('lang'))])
                ->asArray()
                ->one();
//            var_dump($lang);die;
            if ($lang) {
                $url = $file['function_name'] == 'u3d_make_file' ? $file['file_path'].$file['file_name'] : $file['file_path'].'/'.$lang['lang_short'].'/'.$file['file_name'];
//                var_dump($url);die;
                //文件不存在就生成文件
                if (!file_exists($url)) {
                    //todo 生成文件
                    show_json(100000, 'The file is not generated, please file it as a file.');
                }
                $content = file_get_contents($url);

                return Yii::$app->response->sendFile($url)->send();
            }
        }
    }

    /*  下载文件
     *  @params
     */
    public function actionDownload(){
        $data = models\LangAppFile::find()
            ->select('a.id file_id, d.*')
            ->from('lang_app_file a')
            ->join('left join', 'lang_app b', 'a.app_id = b.id')
            ->join('left join', 'lang_app_lang c', 'c.app_id = b.id')
            ->join('left join', 'lang d', 'c.lang_id = d.id')
            ->where(['a.id' => trim(Yii::$app->request->get('file_id'))])
            ->asArray()
            ->all();

        return $this->render('/language/download',[
            'data'  =>  $data,
        ]);
    }

    /**
     * 生成文件
     */
    public function actionMake_file()
    {
        if (Yii::$app->request->isPost) {
            $file = models\LangAppFile::find()->where(['id'=>trim(Yii::$app->request->post('file_id'))])->asArray()->one();
            if (!$file) {
                show_json(100000, 'App file not exists.');
            }
            $lang = models\LangAppLang::find()
                ->select('b.*')
                ->from('lang_app_lang a')
                ->join('left join', 'lang b', 'a.lang_id = b.id')
                ->where(['a.app_id'=>$file['app_id']])
                ->asArray()
                ->all();
            $value = (new models\LangAppFileKey())->getKeyValueByFileId($file['id']);
            $function = $file['function_name'];
            $this->$function($file, $lang, $value);
        }
    }
}