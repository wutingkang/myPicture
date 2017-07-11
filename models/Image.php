<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/11/17
 * Time: 5:34 AM
 */

namespace app\models;

//图像处理类

class Image
{
    private $file;    //图片地址
    private $width;   //图片长度
    private $height;   //图片长度
    private $type;    //图片类型
    private $img;    //原图的资源句柄
    private $new;    //新图的资源句柄

    //构造方法，初始化
    public function __construct($_file) {
        $this->file = $_file;
        list($this->width, $this->height, $this->type) = getimagesize($this->file);
        $this->img = $this->getFromImg($this->file, $this->type);
    }
    //缩略图(固定长高容器，图像等比例，扩容填充，裁剪)[固定了大小，不失真，不变形]
    public function thumb($new_width = 0,$new_height = 0) {

        if (empty($new_width) && empty($new_height)) {
            $new_width = $this->width;
            $new_height = $this->height;
        }

        if (!is_numeric($new_width) || !is_numeric($new_height)) {
            $new_width = $this->width;
            $new_height = $this->height;
        }

        //创建一个容器
        $_n_w = $new_width;
        $_n_h = $new_height;

        //创建裁剪点
        $_cut_width = 0;
        $_cut_height = 0;

        if ($this->width < $this->height) {
            $new_width = ($new_height / $this->height) * $this->width;
        } else {
            $new_height = ($new_width / $this->width) * $this->height;
        }
        if ($new_width < $_n_w) { //如果新高度小于新容器高度
            $r = $_n_w / $new_width; //按长度求出等比例因子
            $new_width *= $r; //扩展填充后的长度
            $new_height *= $r; //扩展填充后的高度
            $_cut_height = ($new_height - $_n_h) / 2; //求出裁剪点的高度
        }

        if ($new_height < $_n_h) { //如果新高度小于容器高度
            $r = $_n_h / $new_height; //按高度求出等比例因子
            $new_width *= $r; //扩展填充后的长度
            $new_height *= $r; //扩展填充后的高度
            $_cut_width = ($new_width - $_n_w) / 2; //求出裁剪点的长度
        }

        $this->new = imagecreatetruecolor($_n_w,$_n_h);
        imagecopyresampled($this->new,$this->img,0,0,$_cut_width,$_cut_height,$new_width,$new_height,$this->width,$this->height);
    }

    //加载图片，各种类型，返回图片的资源句柄
    private function getFromImg($_file, $_type) {
        switch ($_type) {
            case 1 :
                $img = imagecreatefromgif($_file);
                break;
            case 2 :
                $img = imagecreatefromjpeg($_file);
                break;
            case 3 :
                $img = imagecreatefrompng($_file);
                break;
            default:
                $img = '';
        }
        return $img;
    }

    //图像输出
    public function out() {
        imagepng($this->new, $this->file);//第二个参数为新生成的图片名
        imagedestroy($this->img);
        imagedestroy($this->new);
    }



    //other
/*
$srcfile 原图地址；
$dir  新图目录
$thumbwidth 缩小图宽最大尺寸
$thumbheitht 缩小图高最大尺寸
$ratio 默认等比例缩放 为1是缩小到固定尺寸。
*/
    function makethumb($srcfile,$dir,$thumbwidth,$thumbheight,$ratio=0)
    {
        //判断文件是否存在
        if (!file_exists($srcfile))return false;
        //生成新的同名文件，但目录不同
        $imgname=explode('/',$srcfile);
        $arrcount=count($imgname);
        $dstfile = $dir.$imgname[$arrcount-1];
//缩略图大小
        $tow = $thumbwidth;
        $toh = $thumbheight;
        if($tow < 40) $tow = 40;
        if($toh < 45) $toh = 45;
        //获取图片信息
        $im ='';
        if($data = getimagesize($srcfile)) {
            if($data[2] == 1) {
                $make_max = 0;//gif不处理
                if(function_exists("imagecreatefromgif")) {
                    $im = imagecreatefromgif($srcfile);
                }
            } elseif($data[2] == 2) {
                if(function_exists("imagecreatefromjpeg")) {
                    $im = imagecreatefromjpeg($srcfile);
                }
            } elseif($data[2] == 3) {
                if(function_exists("imagecreatefrompng")) {
                    $im = imagecreatefrompng($srcfile);
                }
            }
        }
        if(!$im) return '';
        $srcw = imagesx($im);
        $srch = imagesy($im);
        $towh = $tow/$toh;
        $srcwh = $srcw/$srch;
        if($towh <= $srcwh){
            $ftow = $tow;
            $ftoh = $ftow*($srch/$srcw);
        } else {
            $ftoh = $toh;
            $ftow = $ftoh*($srcw/$srch);
        }
        if($ratio){
            $ftow = $tow;
            $ftoh = $toh;
        }
        //缩小图片
        if($srcw > $tow || $srch > $toh || $ratio) {
            if(function_exists("imagecreatetruecolor") && function_exists("imagecopyresampled") && @$ni = imagecreatetruecolor($ftow, $ftoh)) {
                imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
            } elseif(function_exists("imagecreate") && function_exists("imagecopyresized") && @$ni = imagecreate($ftow, $ftoh)) {
                imagecopyresized($ni, $im, 0, 0, 0, 0, $ftow, $ftoh, $srcw, $srch);
            } else {
                return '';
            }
            if(function_exists('imagejpeg')) {
                imagejpeg($ni, $dstfile);
            } elseif(function_exists('imagepng')) {
                imagepng($ni, $dstfile);
            }
        }else {
            //小于尺寸直接复制
            copy($srcfile,$dstfile);
        }
        imagedestroy($im);
        if(!file_exists($dstfile)) {
            return '';
        } else {
            return $dstfile;
        }
    }
}

