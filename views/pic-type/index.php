<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PicTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pic Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-type-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pic Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',

            [
                'label' => '此类型的图片数目',
                'attribute'=> 'numOfPicSearch', //可以添加查询
                'value' => function ($model) {

                    if (array_key_exists($model->id, $model->numOfPic)){
                        return Html::a($model->numOfPic[$model->id],"/order?id={$model->id}", ['target'=> '_blank']);
                    }else{
                        return Html::a('0',"/order?id={$model->id}", ['target'=> '_blank']);
                    }

                },
                'format' => 'raw',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $url = "/index.php?r=pic-type/delete&id=" . $model->id;

                        if (array_key_exists($model->id, $model->numOfPic)){ //如何设置弹窗警告? from controller or view ,,,
                            //return Html::a('cannot delete', '', ['title' => 'delete', 'target' => '_blank', 'data-method' => 'post']);
                        }else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => 'delete', 'target' => '_blank', 'data-method' => 'post']); //'data-method' => 'post' must add
                        }


                    },
                ],
            ],
        ],
        'emptyText'=>'当前没有类型',
        'emptyTextOptions'=>['style'=>'color:red;font-weight:bold'],
        'showOnEmpty'=>false
        //'layout' => "{summary}\n{items}\n{pager}"  //remote summary can remote "Showing 1-2 of 4 items. "

    ]); ?>
</div>
