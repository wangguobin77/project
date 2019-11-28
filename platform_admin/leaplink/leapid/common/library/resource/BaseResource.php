<?php
namespace common\library\resource;

use Yii;
use common\helpers\File;


/**
 * 素材基础类
 */
class BaseResource
{
	//素材文件类型
	const TYPE_IMAGE = 'image';				//图片文件
	const TYPE_AUDIO = 'audio';				//音频文件
	const TYPE_VIDEO = 'video';				//视频文件
	const TYPE_TEXT = 'text';				//文本文件
	const TYPE_GENERIC = 'generic';			//通用类型

	//素材类型，默认是附件类素材
	public $file_type = self::TYPE_GENERIC;

	public $file_mimeType;

	//文件大小，单位：字节
	public $file_size = 0;

	//文件扩展名
	public $file_ext;

	//文件相对下载地址
	public $url;

	//文件名
	public $file_name;

	//文件宽度，仅适用于图片和视频
	public $file_width;

	//文件宽度，仅适用于图片和视频
	public $file_height;

	/**
	 * 根据素材类型，创建对应实例
	 */
	public static function createInstance($fileType)
	{

		switch ($fileType)
		{
			case 'image': $resInst = new Image();break;
			case 'audio': $resInst = new Audio();break;
			case 'video': $resInst = new Video();break;
			case 'text': $resInst = new Text();break;
			default : $resInst = new Generic();break;
		}

		return $resInst;
	}

	/**
	 * 校验文件合法性
	 *
	 */
	public function validateFile($physicalFile, $fileName)
	{
		//检查文件合法性
		$ret = File::validate($physicalFile, $fileName);
		return $ret;

	}

    /**
     * 保存一个素材
     */
    public function save($fileData, $path = "")
    {
        if (count($fileData) > 0)
        {
            $upload_resource_path = Yii::$app->params['upload_resource_path'];

            //移动上传上来的临时文件
            $destFileDir = $upload_resource_path . $this->file_type . '/' . $path;

            //保存的真实文件名，MD5(原始文件名)+时间戳（毫秒）+后缀组成
            $destFileName = md5($fileData['name']) . time() . '.' . File::getFileExt($fileData['name']);;

            //将上传到PHP服务器的文件移动到文件服务器
            $ret = File::upload($fileData['tmp_name'], $destFileDir, $destFileName);

            return $ret;
        }
    }
}
