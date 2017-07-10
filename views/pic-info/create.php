<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PicInfo */

$this->title = 'Create Pic Info';
$this->params['breadcrumbs'][] = ['label' => 'Pic Infos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-info-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
