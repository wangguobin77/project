<?php

namespace common\library\resource;

/**
 * 图片素材类
 */
class Image extends BaseResource
{
    public $file_type = parent::TYPE_IMAGE;

    /**
     * 删除素材
     */
    public static function delete($res_id)
    {
        parent::delete($res_id);
    }

    /**
     * 上传图片
     */
    public function save($fileData, $path = "")
    {
        $ret = $this->validateFile($fileData['tmp_name'], $fileData['name']);
        if ($ret->code != 0)
            return $ret;

        //获取图片文件宽度、高度
        $size = getimagesize($fileData['tmp_name']);

        if (isset($size)) {
            $this->file_width = $size[0];
            $this->file_height = $size[1];
        }

        return parent::save($fileData, $path);
    }
}
