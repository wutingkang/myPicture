<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use \app\models\PicType;

/* @var $this yii\web\View */
/* @var $model app\models\PicInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pic Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-info-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

<!--    <p> --><?//=  Html::img($model->url,
//        [//'class' => 'img-circle',
//            'width' => 600,
//            'height' => 400
//        ]
//    ); ?><!-- </p>-->


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'url'=>[
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'height' => 300,
                        'width' => 500
                    ]
                ],
                'value' => function($model){
                    return $model->url;
                }
            ],

            'name',
            'type'=>[
                'attribute' => 'type',
                'label' => '图片类型',
                'value' => function($model) {
                    return  PicType::get_type_text($model->type);
                },
            ],
        ],
    ]) ?>

</div>
