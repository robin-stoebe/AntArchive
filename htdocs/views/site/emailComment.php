<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Widget;
?>
<?=($model->display_order+1);?>. <?=$model->question."\r\n";?>
<?php 
$p =$model->parm_no;
echo wordwrap($auth->responseC($p));
?>