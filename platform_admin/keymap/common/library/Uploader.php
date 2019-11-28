<?php

namespace common\library;

use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
class Uploader extends \yii\base\Object
{
    public $fileInputName = 'imagefile';//上传表单 file name
    public $savePath ;//图像保存根位置
    public $allowExt = ['jpg','png','jpeg','gif','bmp'];//允许上传后缀
    public $maxFileSize=1024100000;//最大大小
    public $allowType = ['image/jpeg','image/bmp','image/gif','image/png','image/pjpeg','image/bmp','image/gif','image/x-png','image/pjpeg','image/bmp', 'image/gif' ,'image/x-png','image/pjpeg','image/bmp','image/gif','image/x-png'];

    //绝对路径
    public $filePath_s;
    public $filePath_m;
    public $filePath_b;

    //相对路径
    public $x_filePath_s;
    public $x_filePath_m;
    public $x_filePath_b;

    public $imgW_s = 100;//图宽
    public $imgH_s = 100;//图高

    private $_uploadFile;//上传文件
    private $filePath;//文件路径
    private $urlPath;//访问路径
    private $res=false;//返回状态
    private $message;//返回信息
    public $extension;//后缀

    public $guid;//用户唯一guid

    public function getMessage(){
        return $this->message;
    }
    public function getUrlPath(){
        return $this->urlPath;
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function init(){

        if(!$this->fileInputName) throw new Exception('fileInputName属性不能为空');

       /* if(!$this->savePath) $this->savePath = \Yii::$app->basePath.'/web/uploads/images';*/
        if(!$this->savePath) $this->savePath = \Yii::$app->basePath.'/web/uploads/images';

        $this->savePath = rtrim($this->savePath,'/');

        if(!file_exists($this->savePath)){
            if(! FileHelper::createDirectory($this->savePath,0777)){
                $this->message = '没有权限创建'.$this->savePath;
                return false;
            }
        }

        $this->_uploadFile = UploadedFile::getInstanceByName($this->fileInputName);

        $fix = explode('/',$this->type);
        $this->extension = $fix[1];

        if(!$this->_uploadFile){
            $this->message = '没有找到上传文件';
            return false;
        }
        if($this->_uploadFile->error){
            $this->message = '上传失败';
            return false;
        }

        /* if(!in_array($this->extension,$this->allowExt) || !in_array($this->type,$this->allowType)){*/
        if(!in_array($this->extension,$this->allowExt) || !in_array($this->type,$this->allowType)){

            $this->message = '该文件类型不允许上传';

            return false;
        }

        if($this->_uploadFile->size> $this->maxFileSize){
            $this->message = '文件过大';

            return false;
        }

        $path = date('Ymd',time());

        if(!file_exists($this->savePath.'/'.$path)){
            FileHelper::createDirectory($this->savePath.'/'.$path);
        }

        //$name = substr(\Yii::$app->security->generateRandomString(),-4,4);
        /* $this->filePath = $this->savePath.'/'.$path.'/'.$this->baseName.'--'.$name.'.'.$this->extension;
         $this->urlPath = '/uploads/images/'.$path.'/'.$this->baseName.'--'.$name.'.'.$this->extension;*/

        $this->guid = createGuid();//生成guid
        //图片绝对路径
        $this->filePath_s = $this->savePath.'/'.$path.'/'.$this->guid.'.'.$this->extension;//图
        /* $this->filePath_m = $this->savePath.'/'.$path.'/'.$this->guid.'__'.$this->extension;//中图
         $this->filePath_b = $this->savePath.'/'.$path.'/'.$this->guid.'__'.$this->extension;//大图*/

        //相对路径 ／upload/
        $this->x_filePath_s = '/manufacture/web/uploads/images/'.$path.'/'.$this->guid.'.'.$this->extension;//图
        //$this->x_filePath_s = '/uploads/images/'.$path.'/'.$this->guid.'.'.$this->extension;//图
        /* $this->x_filePath_m = '/uploads/images/'.$path.'/'.$this->guid.'__'.$this->extension;//中图
         $this->x_filePath_b = '/uploads/images/'.$path.'/'.$this->guid.'__'.$this->extension;//大图*/

        $this->urlPath = $this->savePath.'/'.$path.'/'.$this->guid;//图片地址

    }

    public function save(){
        if($this->_uploadFile->saveAs($this->filePath_s)){
            $this->res = true;
        }else{
            $this->res = false;
        }
        if($this->res){
            $this->message = $this->_uploadFile->baseName.'.'.$this->_uploadFile->extension.'上传成功';
        }else{
            $this->message = $this->_uploadFile->baseName.'.'.$this->_uploadFile->extension.'上传失败';
        }
        //默认返回图片访问目录 与 图片目录绝对地址
        //绝对路径
        //return [$this->urlPath,[$this->filePath_s,$this->filePath_m,$this->filePath_b ]];
        //相对路径
        /**
         * 数组内容秒速
         * 0 图片绝对地址目录
         * 1 【
         *    0 相对图片地址
         *    1 图片扩展名
         *    2 图片源文件地址
         * 】
         */
        return [$this->urlPath,[$this->x_filePath_s,$this->extension,$this->filePath_s]];
        //return $this->res;
    }

    /**
     * 获取文件名字
     * @return null
     */
    public function getBaseName(){
        if($this->_uploadFile){
            return $this->_uploadFile->baseName;
        }else{
            return null;
        }
    }
    /**
     * 返回文件后缀
     * @return null
     */
    public function getExtension(){
        if($this->_uploadFile){
            return $this->_uploadFile->extension;
        }else{
            return null;
        }
    }
    /**
     * 返回文件类型
     * @return mixed
     */
    public function getType(){
        if($this->_uploadFile){
            return $this->_uploadFile->type;
        }
        return null;
    }

    /**
     * 生成保持原图纵横比的缩略图，支持.png .jpg .gif
     * 缩略图类型统一为.png格式
     * $srcFile     原图像文件名称
     * $toFile      缩略图文件名称，为空覆盖原图像文件
     * $toW         缩略图宽
     * $toH         缩略图高
     * @return bool
     */
    public static function CreateThumbnail($srcFile, $toFile="", $toW=100, $toH=100)
    {

        if ($toFile == "") $toFile = $srcFile;

        $data = getimagesize($srcFile);//返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
        if (!$data) return false;
        //将文件载入到资源变量im中
        switch ($data[2]) //1-GIF，2-JPG，3-PNG
        {
            case 1:
                if(!function_exists("imagecreatefromgif")) return false;
                $im = imagecreatefromgif($srcFile);
                break;
            case 2:
                if(!function_exists("imagecreatefromjpeg")) return false;
                $im = imagecreatefromjpeg($srcFile);
                break;
            case 3:
                if(!function_exists("imagecreatefrompng")) return false;
                $im = imagecreatefrompng($srcFile);
                break;
        }
        //计算缩略图的宽高
        $srcW = imagesx($im);
        $srcH = imagesy($im);
        $toWH = $toW / $toH;
        $srcWH = $srcW / $srcH;
        if ($toWH <= $srcWH) {
            $ftoW = $toW;
            $ftoH = (int)($ftoW * ($srcH / $srcW));
        } else {
            $ftoH = $toH;
            $ftoW = (int)($ftoH * ($srcW / $srcH));
        }

        if (function_exists("imagecreatetruecolor")) {
            $ni = imagecreatetruecolor($ftoW, $ftoH); //新建一个真彩色图像
            if ($ni) {
                //重采样拷贝部分图像并调整大小 可保持较好的清晰度
                imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
            } else {
                //拷贝部分图像并调整大小
                $ni = imagecreate($ftoW, $ftoH);
                imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
            }
        } else {
            $ni = imagecreate($ftoW, $ftoH);
            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        }

        switch ($data[2]) //1-GIF，2-JPG，3-PNG
        {
            case 1:
                imagegif($ni, $toFile);
                break;
            case 2:
                imagejpeg($ni, $toFile);
                break;
            case 3:
                imagepng($ni, $toFile);
                break;
        }
        ImageDestroy($ni);
        ImageDestroy($im);
        return $toFile;
    }
}
