<?php
namespace app\models;

use Yii;
class LanguageModel extends \yii\db\ActiveRecord
{

    /** 根据language id和file id取对应的key value
     */
    public function langData($language, $file)
    {
        return Yii::$app->db->createCommand("call sp_lang_select(
            '".$language ."'
            ,'". $file ."'
        )")->queryAll();
    }

    /** 语言表（取全部）
     */
    public function langSelectAll()
    {
        return Yii::$app->db->createCommand("call sp_lang_select_all()")->queryAll();
    }

    /** 语言表(取一条)
     * @params $id int 语言表id
     */
    public function langSelectById($id)
    {
        return Yii::$app->db->createCommand("call sp_lang_select_by_id(
            '". $id ."'
        )")->queryOne();
    }

    /** 取lang_app表数据
     * @params $appid int appid
     */
    public function langAppSelectOne($appid)
    {
        return Yii::$app->db->createCommand("call sp_lang_app_select_one(
                '". $appid ."'
        )")->queryOne();
    }

    /** 根据app id 获取app信息
     * @params $id int appid
     */
    public function langAppSelectById($id)
    {
        return Yii::$app->db->createCommand("call sp_lang_app_select_by_id(
            '". $id ."'
        )")->queryAll();
    }

    /** 根据app id 获取app信息
     * @params $fileid int file_id
     */
    public function langAppSelectByFileId($fileid=0)
    {
        if ($fileid == 0) {
            return Yii::$app->db->createCommand("call sp_lang_app_select_by_fileid(
                '". $fileid ."'
            )")->queryAll();
        } else {
            return Yii::$app->db->createCommand("call sp_lang_app_select_by_fileid(
                '". $fileid ."'
            )")->queryOne();
        }
    }

    /** 删除（伪删除）|恢复app
     * @params $appid int appid
     * @params $deleted int (0:未删除,1:已删除.)
     */
    public function changeAppIsDeleteStatus($appid, $deleted=1)
    {
        Yii::$app->db->createCommand("call sp_lang_app_change_is_delete(
            '". $appid ."'
            ,'". $deleted ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 删除app（真删除）
     * @params $appid int appid
     */
    public function langAppDelete($appid)
    {
        Yii::$app->db->createCommand("call sp_lang_app_delete(
            '". $appid ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 修改lang_app表
     * @params $data['app_name'] string app name
     * @params $data['file_name'] string 最终生成的文件的文件名
     * @params $data['file_path'] string 生成文件的路劲
     * @params $data['type'] string 最终生成语言包文件函数
     * @params $data['language'] string language id
     */
    public function langAppAdd($data)
    {
        Yii::$app->db->createCommand("call sp_lang_app_add(
            '". $data['app_name'] ."'
            ,'". $data['language'] ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 修改lang_app表
     * @params $data['id'] int lang_app 表 id
     * @params $app_name string app name
     * @params $data['app_name'] string 最终生成的文件的文件名
     * @params $data['file_path'] string 生成文件的路劲
     * @params $data['type'] string 最终生成语言包文件函数
     * @params $data['language'] string language id
     */
    public function langAppEdit($data)
    {
        Yii::$app->db->createCommand("call sp_lang_app_edit(
            '". $data['id'] ."'
            ,'". $data['app_name'] ."'
            ,'". $data['language'] ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** lang_app_file表新增数据
     * @params $app_id int lang_app 表 app_id
     * @params $app_name string app name
     * @params $file_name string 最终生成的文件的文件名
     * @params $file_path string 生成文件的路劲
     * @params $function string 最终生成语言包文件函数
     */
    public function langAppFileAdd($data)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_add(
            '". $data['app_id'] ."'
            ,'". $data['file_path'] ."'
            ,'".  $data['file_name'] ."'
            ,'". $data['type'] ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 修改lang_app_file表
     * @params $data['file_id'] int lang_app 表 主键id
     * @params $data['file_name'] string 最终生成的文件的文件名
     * @params $data['type'] int 方法名
     */
    public function langAppFileEdit($data)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_edit(
            '". $data['file_id'] ."'
            ,'". $data['file_path'] ."'
            ,'". $data['file_name'] ."'
            ,'". $data['type'] ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 伪删除|恢复lang_app_file表
     * @params $id int lang_app_file 表 id
     */
    public function changeAppFileIsDeleteStatus($id, $deleted = 1)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_change_is_delete(
            '". $id ."'
            ,'". $deleted ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 删除lang_app_file表
     * @params $id int lang_app_file 表 id
     */
    public function langAppFileDelete($id)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_delete(
            '". $id ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 通过file id取lang_app_lang表的记录
     * @params $fileid int lang_app 表 file_id
     */
    public function langAppLangSelectByFileId($fileid)
    {
        return Yii::$app->db->createCommand("call sp_lang_app_lang_select_by_fileid(
            '". $fileid ."'
        )")->queryAll();
    }

    /** 通过file id取lang_app_lang表的记录
     * @params $appid int lang_app 表 app_id
     */
    public function langAppLangSelectByAppId($appid)
    {
        return Yii::$app->db->createCommand("call sp_lang_app_lang_select_by_appid(
            '". $appid ."'
        )")->queryAll();
    }

    /** 添加key(lang_app_file_key表)
     * @params $fileid int lang_app_file_key表主键id
     * @params $key string key
     */
    public function langAppFileKeyAdd($fileid, $key)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_key_add(
            '". $fileid ."'
            ,'". $key ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 删除lang_app_file_key表中数据
     * @params $id int lang_app_file_key表主键id
     */
    public function langAppFileKeyDelete($id)
    {
        Yii::$app->db->createCommand("call sp_lang_app_file_key_delete(
            '".$id."'
            ,@ret
        )")->query();

        return Yii::$app->db->createCommand("select @ret")->queryScalar();
    }

    /** 修改lang_app_file_key_value表id对应的value值
     * @params $id int lang_app_file_key_value id
     * @params $value string value
     */
    public function valueEdit($id, $value)
    {
        Yii::$app->db->createCommand("call sp_value_edit(
            '". $id ."'
            ,'". $value ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /** 向lang_app_file_key_value表中增加一条记录
     * @params $keyid int key id
     * @params $langid string 语言id
     * @params $value string value
     */
    public function valueAdd($keyid, $langid, $value)
    {
        Yii::$app->db->createCommand("call sp_value_add(
            '". $keyid ."'
            ,'". $langid ."'
            ,'". $value ."'
            ,@ret
        )")->query();
        return Yii::$app->db->createCommand("select @ret")->queryOne();
    }

    /*  语言包管理列表页面
     *  @params
     */
    public function language_list($offset, $limit, $deleted=0){

        $data = Yii::$app->db->createCommand("call sp_language_list(
            '". $offset ."'
            ,'". $limit ."'
            ,'". $deleted ."'
        )")->queryAll();

        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data, 'count'=>$count['@rowCount']];
    }

    /*  语言包管理列表页面
     *  @params $offset
     *  @params $limit
     *  @params $deleted 是否被删除(0:未删除,1:已删除.)
     */
    public function langAppSelectAll($deleted=0, $offset=0, $limit=0){

        $data = Yii::$app->db->createCommand("call sp_lang_app_select_all(
            '". $offset ."'
            ,'". $limit ."'
            ,'". $deleted ."'
        )")->queryAll();

        $count = Yii::$app->db->createCommand("select @rowCount")->queryOne();

        return ['data'=>$data, 'count'=>$count['@rowCount']];
    }




    /*  根据file id取key value
     *  @params $fileid int lang_app_file表id
     */
    public function keyValue($fileid){

        return  yii::$app->db->createCommand("call sp_lang_app_file_value_select_by_fileid(
            '". $fileid ."'
        )")->queryAll();

    }




    /*  lang_app_file
     *  @params $fileid int lang_app_file表id
     */
    public function langAppFileSelectOne($fileid,$deleted=0){

        return  Yii::$app->db->createCommand("call sp_lang_app_file_select_one(
            '". $fileid ."'
            ,'". $deleted ."'
        )")->queryOne();

    }
}
