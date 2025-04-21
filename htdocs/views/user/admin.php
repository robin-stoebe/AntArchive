<link rel="stylesheet" href="css/font-awesome-4.7.0/css/font-awesome.css" >
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;

use app\components\Debug;

use app\assets\AppAsset;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\Breadcrumbs;
use app\assets\TabulatorAsset;
use app\components\Tabulator;

use yii\data\ArrayDataProvider;


TabulatorAsset::register($this);
$dt = new Tabulator();


$url = Url::toRoute(['user/delete'],'https');



$js = <<< DOC
$(document).ready(function() {
        
          $('table').on('click', '.delete', function(){

            var id = $(this).attr('data-id');
            var fullname = $(this).attr('data-fullname');

            if (confirm('Are you sure you want to delete this User? (' + fullname + ')'))
            {
        
                $.ajax({
                    type: 'POST',
                    url: '$url',
                    data:{id:id},
                    success:  window.location.reload(),
                    dataType: 'html'
                })
                return false;
            } else {
                return false;
            }
        })
        
        
} );   
DOC;



?>
<style>
    table.dataTable tbody tr.even {
    background-color: #e9ecef;
}

.tiny {
    font-size: 85%;
    font-style: italic;
}

.container {
    width: 95%;
    max-width: 95%
}

   .body-content {
                width:90%;
                margin: 0em 1em;

    }
    .site-index {
        display:block;
    }

</style>
<div class="body-content">
<h1>Users</h1>
  <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Import User', ['import'], ['class' => 'btn btn-success']) ?>
    </p>

<div>
    <?php echo Html::errorSummary([$model]);?>
</div>




     <p />
    
</div>
<div id='example-table'></div>
<?php
    $misc = '
	"bSort":true,
	order:[[0,"asc"]],
	scrollY:"400px",
    scrollX: true,
	paging:false  
		
	
	';



    $misc = "
                    autoColumns:true, //create columns from data field names
                    height: '700px',
                    layout:'fitData',
                    columnMaxWidth: 350,
                    autoColumnsDefinitions:function(definitions){
                        //definitions - array of column definition objects
                
                        definitions.forEach((column) => {
                            
                            if(column.field=='Actions')
                                column.formatter='html'
                            else {
                                column.headerFilter = true; // add header filter to every column
                                
                            }
                
                        });
                
                        return definitions;
                    },
                
                    ";
                

$js .= $dt->showtable($table,'example',$misc);
 $this->registerJs($js);