<?php
namespace common\library\resource;

/**
 * 音频素材类
 */
class Audio extends BaseResource
{
	public $file_type = parent::TYPE_AUDIO;
	
	/**
	 * 删除素材
	 */
	public static function delete($res_id)
	{
		parent::delete($res_id);
	}
	
	/**
	 * 保存音频
	 */
	public function save($fileData, $path="")
	{
		//检查文件合法性
		$ret = $this->validateFile($fileData['tmp_name'], $fileData['name']);
		if ($ret->code != 0)
			return $ret;
		
		return parent::save($fileData, $path);
	}
	
}
