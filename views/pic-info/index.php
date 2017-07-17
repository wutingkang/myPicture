<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\models\PicType;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PicInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pic Infos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-info-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('上传图片', ['/pic-info/upload'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [


           'url'=> [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'width'=>'120',
                        'height'=>'80'
                    ]
                ],
                'value' => function($model){
                    return $model->url;
                }
            ],

            'name',

            [
                'attribute' => 'type',
                'label' => '图片类型',
                'value' => function($model) {
                    return  PicType::getTypeName($model->type);
                },
                'filter' => PicType::getTypes(),
            ],

            ['class' => 'yii\grid\ActionColumn'], //有个问题：如果是从site控制器打开图片列表，则 view，update，delete三个按钮的动作的默认控制器都是site，这样会出错。
        ],
        'emptyText'=>'当前没有图片',
        'emptyTextOptions'=>['style'=>'color:red;font-weight:bold'],
    ]); ?>
</div>
