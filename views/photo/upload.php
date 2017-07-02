<?php
/* @var $this yii\web\View */

//可以考虑加别名 /home/file/pic/

use yii\widgets\ActiveForm;

?>

<?php $form=ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
<?=$form->field($model,'title')?>
<?=$form->field($model,'content')->textarea()?>
<?=$form->field($model,'thumb')->fileInput()?>
    <p>
        <input type="submit" class="btn btn-success" value="提交">
    </p>
<?php ActiveForm::end();?>