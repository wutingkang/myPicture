<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/30/17
 * Time: 1:41 AM
 */


use app\widgets\file_upload\FileUpload;   //引入扩展

echo FileUpload::widget();

$url = "";

echo FileUpload::widget(['value'=>$url]);  //编辑时要带默认图，$url为图片地址

?>
