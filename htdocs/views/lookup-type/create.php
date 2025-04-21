<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LookupType */

$this->title = 'Create Lookup Type';
$this->params['breadcrumbs'][] = ['label' => 'Lookup Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lookup-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
