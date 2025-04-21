<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Configuration */

$this->title = 'Update Configuration: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Configurations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<style>
    .desc { width: 20em;}
    .value { width: 10em;}
</style>
    
<div class="configuration-update">

    <h1><?= Html::encode($this->title) ?></h1>

    
    <?php $form = ActiveForm::begin(); ?>
    <?php foreach($model->items() as $item=>$desc){
        
        $value = app\models\Configuration::configValue($item);
        
    
?>
        <div class="row">
            <div class="column desc">
                <?= Html::label($desc,'item') ?>
            </div>
            <div class="column value">
                <?= HTml::textArea("item[$item]", app\models\Configuration::configValue($item),['rows'=>1,'cols'=>50]); ?>
            </div>

        </div>
<?php } ?>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

   

</div>
