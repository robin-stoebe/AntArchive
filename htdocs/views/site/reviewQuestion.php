<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\base\Widget;
?>


<div class='row'>
    <div class='column question <?=$bottom;?>'>
        <div class='row'>
            <div class="column qid">
                <?=($model->display_order+1);?>.
            </div>
            <div class="column qtext">
                <?=$model->question;?>
            </div>
        </div>
    </div>
    <div class='column answer <?=$bottom;?>'>
        <?php 
            $items = $auth->responseQ($model->parm_no);
            echo implode(', ',$items);
        ?>
    </div>
</div>