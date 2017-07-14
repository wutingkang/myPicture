<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PicTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pic Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

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
                        return Html::a($model->numOfPic[$model->id],'/index.php?PicInfoSearch[name]=&PicInfoSearch[type]=' . $model->id . '&r=pic-info/index', ['target'=> '_self']);
                    }else{
                        return Html::a('0','/index.php?r=pic-type/index', ['target'=> '_self']);
                    }

                },
                'format' => 'raw',
            ],

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $url = "/index.php?r=pic-type/delete&id=" . $model->id;

                        if (0 === $model->id){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',  '/index.php?r=pic-type/index', ['title' => 'delete', //'target' => '_self',
                                'data' => [
                                    'confirm' => '默认分类，不能删除!',
                                    'method' => 'post',
                                ]]);
                        } elseif(array_key_exists($model->id, $model->numOfPic)){
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',  '/index.php?r=pic-type/index', ['title' => 'delete', //'target' => '_self',
                                'data' => [
                                'confirm' => '该类型下存在已上传的图片，不能删除!',
                                'method' => 'post',
                            ]]);
                        }else{
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['title' => 'delete', 'target' => '_self', //'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ]
                            ]);
                        }
                    },

                    'update' => function ($url, $model) {

                        if (0 == $model->id){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>',  '/index.php?r=pic-type/index', ['title' => 'delete', //'target' => '_self',
                                'data' => [
                                    'confirm' => '默认分类，不能编辑!',
                                    'method' => 'post',
                                ]]);
                        }else{
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', "/index.php?r=pic-type/update&id=" . $model->id, ['title' => 'update', //'target' => '_self', //'class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                ]
                            ]);
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
