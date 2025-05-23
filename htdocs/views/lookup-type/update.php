<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LookupType */

$this->title = 'Update Lookup Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lookup Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->lookup_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lookup-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
