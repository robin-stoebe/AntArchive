<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Widget;
?><?=($model->display_order+1);?>. <?=$model->question."\r\n";?><?php 
    $items = $auth->responseQ($model->parm_no);
    echo implode(', ',$items);
?>
