<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 namespace app\components;
  
   use app\models\Configuration;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;



   class Tabulator extends Component
   {
       
       
       
       
       public function js($table,$class='example',$misc=''){

        $issued='';
        foreach($table as $r){
            $row='';
            foreach($r as $k=>$d)
                $row.="'$k':'$d',";
            $issued.="{".$row."},\n";
            
        }

$js = <<< DOC
$(function(){
	
    var ${class}tabledata = [${issued}];
    
    //initialize table
     table${class} = new Tabulator("#$class-table", {
        layout:"fitColumns",
        
        data:${class}tabledata, //assign data to table
        autoColumns:true, //create columns from data field names
        $misc
    });
});
DOC;

            return $js;
        
       }


       public function showtable($table,$class='example',$misc=''){
           
            if(!empty($table)){
                
                return $this->js($table, $class, $misc);
                

            }

       }
       
       
      

   }