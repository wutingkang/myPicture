<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/11/17
 * Time: 5:34 AM
 */

namespace app\components;

//图像处理类
// need php have GD library

class ImageHelper
{
    private $file;    //图片地址
    private $width;   //图片长度
    private $height;   //图片长度
    private $type;    //图片类型
    private $img;    //原图的资源句柄

    //构造方法，初始化, 使用前一定要确保文件存在
    public function __construct($_file)
    {
        $this->file = $_file;
        list($this->width, $this->height, $this->type) = getimagesize($this->file);
        $this->img = $this->getFromImg($this->file, $this->type);
    }

    //加载图片，各种类型，返回图片的资源句柄
    private function getFromImg($_file, $_type)
    {
        $img = '';
        switch ($_type) {
            case IMAGETYPE_GIF :
                $img = imagecreatefromgif($_file);
                break;
            case IMAGETYPE_JPEG :
                $img = imagecreatefromjpeg($_file);
                break;
            case IMAGETYPE_PNG :
                $img = imagecreatefrompng($_file);
                break;
            default:
                break;
        }

        return $img;
    }


    /*
    $dstDir  新图目录,与原图相同则覆盖原图
    $newWidth 新图Width尺寸
    $newHeight 新图Height尺寸
    */
    function resize($dstDir, $newWidth = 800, $newHeight = 450)
    {
        //判断该目录是否存在,不存在则创建
        if (!file_exists($dstDir)) {
            if (false === mkdir($dstDir, 0777, true)) { //第三个参数 ture
                return false;
            }
        }

        //生成新的同名文件，但目录不同
        $imgName = explode('/', $this->file);
        $arrcount = count($imgName);
        $dstPic = $dstDir . $imgName[$arrcount - 1];

        //
        if (function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$newImage = imagecreatetruecolor($newWidth, $newHeight)) {
            imagecopyresampled($newImage, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
        } elseif (function_exists("imagecreate") && function_exists("imagecopyresized") && @$newImage = imagecreate($newWidth, $newHeight)) {
            imagecopyresized($newImage, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
        } else {
            return false;
        }

        switch ($this->type) {
            case IMAGETYPE_JPEG :
                imagejpeg($newImage, $dstPic, 100); // 存储图像
                break;
            case IMAGETYPE_PNG :
                imagepng($newImage, $dstPic, 100);
                break;
            case IMAGETYPE_GIF :
                imagegif($newImage, $dstPic);
                break;
            default:
                break;
        }

        imagedestroy($this->img);
        imagedestroy($newImage);

        if (file_exists($dstPic)) {
            return $dstPic;
        } else {
            return false;
        }
    }

    /*
     * 上传到远端文件服务器
     * @return string $result
     * */
    public function uploadToRemoteFileServer($filePath, $uploadUrl, $id){

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$uploadUrl);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); //把CRUL获取的内容赋值到变量,设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch,CURLOPT_POST,true); //启用POST提交

        //加@符号curl就会把它当成是文件上传处理
        $data = array("level" => 1,
            "status" => 2,
            "upfile" => "@" . realpath($filePath));

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data); //set in last!!!

        //curl_setopt($ch, CURLOPT_INFILESIZE,filesize($filePath)); //告诉远程服务器，文件大小
        //curl_setopt($ch, CURLOPT_HEADER, 0);   // 设置是否显示header信息 0是不显示，1是显示  默认为0
        //curl_setopt($ch, CURLOPT_REFERER, $Ref_url);       //伪装REFERER

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
