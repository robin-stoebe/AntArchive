<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lookup */

$this->title = 'Update Lookup: ' . $model->lookup_id;
$this->params['breadcrumbs'][] = ['label' => 'Lookups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lookup_id, 'url' => ['view', 'id' => $model->lookup_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lookup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
