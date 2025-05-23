<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigurationItem */

$this->title = 'Update Configuration Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Configuration Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="configuration-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
