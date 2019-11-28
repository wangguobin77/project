<?php
/**
 * Created by PhpStorm.
 * User: local2
 * Date: 2018-12-28
 * Time: 16:52
 */

namespace common\helpers;

use Yii;
use common\ErrorCode;
use common\Result;

global $mimeTypes;

$mimeTypes = require_once('MimeTypes.php');

/**
 * 文件管理类
 */
class File
{
    /**
     * 验证文件是否合法
     */
    public static function validate($tmpFileName, $destFileName)
    {
        global $mimeTypes;

        $ret = new Result();

        //检查文件后缀是否与文件类型匹配
        $ext = self::getFileExt($destFileName);

        $mimeType = self::getFileMimeType($tmpFileName);

        if (!isset($mimeTypes[$mimeType]))
        {
            $ret->code = ErrorCode::MIMETYPE_UNKNOWN;
            $ret->message = '未知的mimetype: ' . $mimeType;
            return $ret;

        }

        if (stripos($mimeTypes[$mimeType],$ext . '|') === false)
        {
            $ret->code = ErrorCode::MIMETYPE_NOT_MATCHED;
            $ret->message = 'mimetype ' . $mimeType . '与后缀.' . $ext . '不匹配';
            return $ret;
        }

        return $ret;

    }

    /**
     * 移动上传文件到目标路径
     * @param $tmpFileName  //临时文件路径
     * @param $destFileDir   //服务器文件路径
     * @param $destFileName  //服务器文件名
     * @return Result
     */
    public static function upload($tmpFileName, $destFileDir, $destFileName)
    {
        $ret = new Result();
        /************线上专用：移动文件到cdn************/
        //todo ...
        /************线上专用：移动文件到cdn************/

        /************开发测试专用:移动文件到本地服务器************/


        if (!is_dir($destFileDir))
        {
            mkdir($destFileDir, 0700, true);
        }


        $bool = move_uploaded_file($tmpFileName, $destFileDir . $destFileName);

        /************开发测试专用:移动文件到本地服务器************/

        $ret->data['url'] = $destFileDir . $destFileName;

        if (false === $bool)
        {
            $ret->code = ErrorCode::FAILED_TO_MOVE_FILE;
            $ret->message = '移动文件' . $tmpFileName . '到路径' . $destFileDir . $destFileName . '失败';
            return $ret;
        }

        return $ret;
    }

    /**
     * 获取文件真实类型
     */
    public static function getFileMimeType($fileName)
    {
        return mime_content_type($fileName);

        $fi = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = strtolower(finfo_file($fi, $fileName));

        return $mime_type;
    }

    /**
     * 获取文件扩展名
     */
    public static function getFileExt($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    }

    /**
     * 获取文件大小
     * @param $unit 大小单位(0:byte 1:kb 2:mb 3:gb)
     */
    public static function getFileSize($fileName, $unit = 0)
    {
        $size = filesize($fileName);
        return $size>>($unit*10);
    }

}