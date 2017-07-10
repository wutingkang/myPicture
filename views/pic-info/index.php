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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('上传图片', ['upload'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
//    var_dump( $model->url);die;
 //   $str = 'www.storemypicture.com/2/201707/20170710141823_582.jpg';

    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [



           'url'=> [
                'label' => '图片',
                'format' => [
                    'image',
                    [
                        'height' => 500,
                        'width' => 700
                    ]
                ],
                'value' =>
                    //function($model) {return $model->url;}
                    function($model){

                    var_dump( $model);die;
                        return 'www.storemypicture.com/2/201707/20170710141823_582.jpg';

                  //  return $model->url;
                }
            ],

            'name',

            [
                'attribute' => 'type',
                'label' => '图片类型',
                'value' => function($model) {
                    return  PicType::get_type_text($model->type);
                },
                'filter' => PicType::get_type(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'emptyText'=>'当前没有图片',
        'emptyTextOptions'=>['style'=>'color:red;font-weight:bold'],
    ]); ?>
</div>
