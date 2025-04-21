 
<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\jui\Sortable;
use app\assets\AppAsset;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Breadcrumbs;

$this->registerCss("\n\t.jquery-sortable li {\n\t\tlist-style: none;\n\t}\n\t.jquery-sortable {\n\t\tlist-style-type: none;\n\t\tmargin: 0;\n\t\tpadding: 0;\n\t}\n\n\t.jquery-sortable li {\n\t\tmargin: 0 3px 3px 3px;\n\t\tpadding: 4px;\n\t\tpadding-left: 24px;\n\t\theight: 2.5em;\n\t}\n\n\t.jquery-sortable li span {\n\t\tposition: absolute;\n\t\tmargin-left: -20px;\n\t}\n");


echo Breadcrumbs::widget([
    'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
    'links' => [
        [
            'label' => 'Lookups',
            'url' => ['lookup-type/index'],
        ],
    ],
]);
$fitems = array();

$url = Url::base();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$id = $model->lookup_type_id;
?>



<h2>Editing Lookup table for :: <?=$model->name;?></h2>

<div class="lookup-item-form">

    
    
    
    <?php $form = ActiveForm::begin(); ?>
    
    
     <?= $form->field($model, 'name')->textInput(['size' => 45,'maxsize'=>45]) ?>
<!--

    <?= $form->field($model, 'sort_direction')->dropDownList(['ASC' => 'ACS','DESC'=>'DESC'],['width'=>'50px;']) ?>

    -->
    <div class="col-2 column"> </div>
    <div class="col-6 column">
       
    <?php
        foreach($items as $item){
            $fitems[]=$this->render('item',['model'=>$item,'type_id'=>$model->lookup_type_id]);
//            echo $this->render('item',['model'=>$item,'type_id'=>$model->lookup_type_id]);
//            $id = $item->lookup_id;
//            echo Html::hiddenInput("items[$id][lookup_id]",$id);
//            echo Html::textInput("items[$id][description]",$item->description,['size' => 40,'maxsize'=>100]);
//            echo Html::textInput("items[$id][weight]",$item->weight,['size' => 3,'maxsize'=>3]);
//            echo "<br>";
        }
      echo Sortable::widget(['items' => $fitems, 'options' => ['tag' => 'ul', 'class' => 'jquery-sortable'], 'itemOptions' => ['tag' => 'li', 'class' => 'ui-state-default']]);
  
    ?>
    
    </div>
    <p />
    <div class="form-group">
        <?= Html::button('+', ['id'=>'addItem','class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success','id'=>'save']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php


$script = "
    var newItems = 0;
    $('#save').click(function(){
        var x=0
        $('#w1').children().each(function(){
            $(this).children().each(function(){
           if($(this).attr('dtype')=='weight'){
             $(this).val(x)
             x++
           }
           });
        })
        
    })

    $( '#sortable' ).sortable();
    $( '#sortable' ).disableSelection();
    
    $('#addItem').click(function(){
        $.post('$url/lookup-type/blank',{id:$id,new:newItems},function(data,status,xhr){
             $('#w1').append('<li class=\"ui-state-default\">'+data.html+'</li>');
             newItems++
        },'json'
        )
       
    });
"; 

$this->registerJs($script,View::POS_READY);

