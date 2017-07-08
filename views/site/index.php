<?php

/* @var $this yii\web\View */

$this->title = 'My Picture';
use yii\helpers\Html;

?>
<div class="site-index">

    <div class="jumbotron">
        <h1>图片管理</h1>

        <hr>
        <p><?= Html::a('上传图片', ['photo/upload'], ['class' => 'btn btn-success']) ?></p>
        <p><?= Html::a('图片类型管理', ['pic-type/index'], ['class' => 'btn btn-success']) ?></p>
        <p><?= Html::a('查看我的图片', ['photo/index'], ['class' => 'btn btn-success']) ?></p>
    </div>

    <div class="body-content">



    </div>
</div>
