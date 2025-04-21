<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= Html::encode($message) ?>
    </div>


<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <p>Token
        <?=Html::textInput('auth_token','',['size'=>50,'maxlength'=>50]);?>
    </p>
        <?=Html::submitButton('Submit');?>
    <?php ActiveForm::end(); ?>

</div>
