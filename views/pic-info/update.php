<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\PicInfo */

$this->title = 'Update Pic Info: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pic Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pic-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=  Html::img($model->url,
        [//'class' => 'img-circle',
            'width' => 500,
            'height' => 300
        ]
    ); ?>

    <div class="pic-info-form">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map($typeData,'id', 'name')) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


</div>
