<?php
/* @var $this yii\web\View */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map($typeData,'id', 'name')) ?>

    <?= $form->field($model, 'dstWight')->textInput() ?>

    <?= $form->field($model, 'dstHeight')->textInput() ?>

    <p><?= Html::submitButton('Upload', ['class' => 'btn btn-primary']) ?></p>

<?php ActiveForm::end() ?>
