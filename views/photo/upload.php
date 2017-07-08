<?php
/* @var $this yii\web\View */

//可以考虑加别名 /home/file/pic/

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'type')->dropDownList(ArrayHelper::map($typedata,'id', 'name')) ?>

    <p><?= Html::submitButton('Upload', ['btn btn-primary']) ?></p>

<?php ActiveForm::end() ?>
