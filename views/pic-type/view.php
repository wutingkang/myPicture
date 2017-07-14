<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PicType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pic Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(0 != $model->id): ?>
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
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
        ],
    ]) ?>

</div>
