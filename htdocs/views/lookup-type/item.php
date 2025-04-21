<?php
use yii\helpers\Html;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$id = $model->lookup_id;

?>



        <?= "<span class='ui-icon ui-icon-arrowthick-2-n-s' title='Drag items to re-sort.'></span>" ?>


        
        <?= Html::hiddenInput("items[$id][lookup_id]",$id);?>
        
        <?= Html::hiddenInput("items[$id][type_id]",$model->type_id);?> 
        
        <?= Html::textInput("items[$id][description]",$model->description,['size' => 40,'maxsize'=>100]);?>
        <?= Html::hiddenInput("items[$id][weight]",$model->weight,['dtype'=>'weight']);?>
        
   


