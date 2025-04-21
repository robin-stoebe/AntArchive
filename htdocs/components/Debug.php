<?php


   namespace app\components;
  
   use app\models\Configuration;

   use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;



   class Debug extends Component
   {


      public static function debug($item, $desc='debug statement',$verbose=false)
      {
          global $debug;
          
              $string = print_r($item,true);
              if(!$verbose and $debug==1)
                  $string=substr($string,0,1500)." ...";
          
              if($debug>=1 ){
                    Yii::debug("$desc:: ".$string);
                    return;
              }
      
          
          $debugMode =  Configuration::find()->where(['item'=>'debug'])->one();
          
          if($debug>=1 or !empty($debugMode->value))
            Yii::debug("$desc:: $string");

      }


   }



?>