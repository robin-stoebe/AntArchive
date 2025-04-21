<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

 


?>

<div class="user-form">

    <?php echo Html::beginForm(); ?>

    <?php 
    
//    print_r($people);
    
    
        if(!empty($people)){
            
            
            
            $gridViewDataProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $people,
                'sort' => [
                    'attributes' => ['Name','UCINet ID'],
                ],
            ]);
            
            
           echo DataTables::widget([
                'dataProvider' => $gridViewDataProvider,
                'columns' => [
                    'Import'=>[
                                'label'=>'Import',
                                'format'=>'raw',
                                'value'=>function($data){ 
                                    if($data['Import']!='In App')
                                        return Html::checkbox('toimport[]',false,['value'=>serialize($data['Import'])]);
                                    else
                                        return $data['Import'];
                                }
                        ],
                    'Name','UCINet ID','Affiliation','Title'
                ],
                'clientOptions' => [
                    'paging'=>false,
                    "lengthMenu"=> [[10,20,-1], [10,20,Yii::t('app',"All")]],
                    'scrollY' => 400,
                    "info"=>true,
                    "responsive"=>true, 
//                    "dom"=> 'lfrtip',
                    
                ]
            
            ]);
        }
    
    ?>
</div>
    <div class="form-group">
        <?= Html::submitButton('Import', ['class' => 'btn btn-success']) ?>
    </div> 
    <?= Html::endForm(); ?>

</div>
