<?php
namespace common\library;

use Yii;
class Upload
{
    /**
     * 上传头像 base64 加密方式上传
     * @param $img
     * @param $userid 用户的userid作为文件名
     * @param $rand_dir  随机目录名 一用户的userid 莫三位
     * @return mixed
     */
    public function upload($img,$userid,$rand_dir)
    {

        //上传图片生成
        $small = 'small';//72x72 附近玩家页面头像显示
        $middle = 'middle';//96x96 用于消息列表展示
        $l = 'l';//用于排行榜
        $xl = 'xl';//用于个人中心
        //文件夹日期
        //$ymd = date("Ymd");

        //图片路径地址
        $basedir = Yii::$app->basePath.'/web/upload/base64/'.$rand_dir;

        $http_small_url = '/upload/base64/'.$rand_dir.'/'.$small; //http 访问图片路径

        //创建对应的目录名
        $fullpath = $basedir.'/y';//原图目录
        $smallpath = $basedir.'/'.$small;
        $middlepath = $basedir.'/'.$middle;
        $lpath = $basedir.'/'.$l;
        $xlpath = $basedir.'/'.$xl;

        if(!is_dir($fullpath)){
            mkdir($fullpath, 0777, true);
        }

        if(!is_dir($smallpath)){
            mkdir($smallpath, 0777, true);
        }

        if(!is_dir($middlepath)){
            mkdir($middlepath, 0777, true);
        }

        if(!is_dir($lpath)){
            mkdir($lpath, 0777, true);
        }

        if(!is_dir($xlpath)){
            mkdir($xlpath, 0777, true);
        }

        $types = empty($types)? array('jpg', 'gif', 'png', 'jpeg'):$types;

        $img = str_replace(array('_','-'), array('/','+'), $img);

        $b64img = substr($img, 0,strlen($img));

        /* if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $b64img, $matches)){*/
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', trim($img), $matches)){
            $type = $matches[2];
            if(!in_array($type, $types)){
                return array('status'=>1,'info'=>'图片格式不正确，只支持 jpg、gif、png、jpeg哦！','url'=>'');
            }
            $img = str_replace($matches[1], '', $img);
            $img = base64_decode(chunk_split($img));

            // header('Content-Type:image/'.$type);

            $photo = '/'.$userid.'.'.$type;
            file_put_contents($fullpath.$photo, $img);

            $ary['status'] = 1;
            $ary['info'] = 'success';
            $ary['local_url'] = $fullpath.$photo;
            $ary['http_url'] = $http_small_url.$photo;

            //创建对应的缩略图

            $this->CreateThumbnail($fullpath.$photo,$smallpath.$photo,72,72);
            $this->CreateThumbnail($fullpath.$photo,$middlepath.$photo,96,96);
            $this->CreateThumbnail($fullpath.$photo,$lpath.$photo,120,120);
            $this->CreateThumbnail($fullpath.$photo,$xlpath.$photo,136,136);
            return $ary;

        }

        $ary['status'] = 0;
        $ary['info'] = 'please select upload picture';//请选择上传头像

        return $ary;


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
    public function CreateThumbnail($srcFile, $toFile="", $toW=100, $toH=100)
    {
        if ($toFile == "") $toFile = $srcFile;

        $data = getimagesize($srcFile);//返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
        /*  echo '<pre>';
          print_r($data);die;*/
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