<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConfigurationItem */

$this->title = 'Create Configuration Item';
$this->params['breadcrumbs'][] = ['label' => 'Configuration Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="configuration-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
