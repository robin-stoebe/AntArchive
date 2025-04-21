<?php


namespace app\components;

 use Yii;
   use yii\base\Component;
   use yii\base\InvalidConfigException;

   use yii\helpers\Url;

   
   
   class Logout{
       
       
       public function autoLogout($timeout=''){
           $timeoutjs='';
           
            if (!Yii::$app->user->isGuest){
                    if (!Yii::$app->user->isGuest)
                            $logged_in_username = Yii::$app->user->identity->username;
                if(empty($timeout))    
                    $timeout = Yii::$app->params['authTimeout'];

                $url = Url::base();
$timeoutjs = <<<DOC




        var d= new Date();
        var n= d.getTime()/1000;
        var timeout=(n+$timeout);

        setInterval(function(timeout){
            var d= new Date();
            var n= d.getTime()/1000;
            var outin = Math.ceil((timeout - n)/60);
            if(outin<6)
                $('#timeout').html('<a class="nav-link" href="$url/site/logout" title="$logged_in_username">Logout in '+outin+'</a>')
            if((timeout-n)<0)  
                location.assign('$url/site/logout')
        }, 10000,timeout);


        

DOC;



            }
            
            return $timeoutjs;
           
       }


       public static function checkTime(){
        // Check only when the user is logged in
        if ( !Yii::$app->user->isGuest)  {
         if ( yii::$app->session->get('userSessionTimeout') < time() ) {
             // timeout
             Yii::$app->user->logout();
             return Yii::$app->response->redirect(['/site/logout']);  //
             
         } else {
             Yii::$app->session->set('userSessionTimeout', 
                      time() + Yii::$app->params['authTimeout']); 


         }
         
      }else {
         return Yii::$app->response->redirect(['/site/login']);  //
      }

    }

       
   }
   
   