<style>
       @media print {
        .no-print {
            display: none;
        }
    }
    
</style>
<?php

/* @var $this \yii\web\View */
/* @var $content string */

//use app\widgets\Alert;
use yii\bootstrap4\Alert;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\models\Configuration;
use yii\web\JqueryAsset;
use app\components\Logout;

$this->registerCssFile('https://use.fontawesome.com/releases/v5.7.1/css/all.css');
      $this->title = 'Test Site';
  
$logged_in_username = "";
$timeoutjs='';        

/////////////////////
// set auto logout if use is not a guest
$logout = new Logout();
$js= $logout->autoLogout();
if(!empty($js))
    $this->registerJs($js);

/////////////////////
$url = Url::base();
        
global $debug;


    $splash='';
    if(Configuration::debugMode()===true)
        $splash = "<span style='font-size:65%;'> [Debug]</span>";


AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>









<div class="container">





<?

//<nav class="navbar navbar-expand-lg navbar-dark NOTbg-primary"   style='background-color: #0064a4; '>

?>


<?php



	// in the "logout" menu label we show the logged-in user name, but we can only access if identity is defined
	$logged_in_username = "";
	if (!Yii::$app->user->isGuest)
		$logged_in_username = Yii::$app->user->identity->username;

//    $menuItems=[array('label'=>'','visible'=>false,'url'=>'#','items'=>'')];
    if($_REQUEST[r]!='site/submit'){

    $menuItems = [

                  //['label' => 'About', 'url' => ['/site/about']],

                  

                [
                  'label' => 'DB Maint',
                  'visible' => !(Yii::$app->user->isGuest) && User::isAnalyst($logged_in_username),
                  'items' => [
                       ['label' => 'Users', 'url' => ['user/index']],
                       ['label' => 'Lookups', 'url' => ['/lookups']],
                       ['label' => 'Departments', 'url' => ['/departments'],
                           'visible' => !(Yii::$app->user->isGuest) && User::isAdmin($logged_in_username)],
                       ['label' => 'Configruation', 'url' => ['configuration/index'],
                          'visible' => !(Yii::$app->user->isGuest) && User::isAdmin($logged_in_username)
                           ],
                       ['label' => 'Configruation Items', 'url' => ['configuration-item/index'],
                          'visible' => !(Yii::$app->user->isGuest) && User::isAdmin($logged_in_username)
                           ],
                  ],
              ],
//              [                
//                  'label' => 'Help',
//                  'visible' => !(Yii::$app->user->isGuest) && User::isAnalyst($logged_in_username),
//                  'url'=>'Evote2.pdf'
//              ],

                ];


                $loginoutMenuItems = [
                  [
                    'label' => 'Login',
                    'url' => ['site/login'],
                    'options' => ['class' => 'mr-auto'],
                    'visible' => Yii::$app->user->isGuest,
                  ],
                  [
                    'label' => 'Logout ',
                    'url' => ['site/logout'],
                    'options' => ['class' => 'mr-auto' ,'id'=>'timeout','title'=>$logged_in_username],
                    'visible' => !(Yii::$app->user->isGuest)
                  ],
                ];
    

    NavBar::begin(['brandLabel' => Yii::$app->name .$splash,
                   'brandUrl' => Yii::$app->homeUrl,
                   'options' => ['class' => 'navbar-dark navbar-expand-lg', 'style' => 'background-color: #0064a4; ', 'title' => 'Home' ]]);

   echo Nav::widget(['options' => ['class' => 'navbar-nav'], 'items' => $menuItems]);

   echo Nav::widget(['options' => ['class' => 'navbar-nav ml-auto'], 'items' => $loginoutMenuItems]);

    NavBar::end(); 
    
    } else {

        
    NavBar::begin(['brandLabel' => "<img src='anteater.png' style='max-height: 25px; margin-right: 10px; '>". Yii::$app->name.$splash,
                   'brandUrl' => '#',
                   'options' => ['class' => 'navbar-dark navbar-expand-lg', 'style' => 'background-color: #0064a4; ', 'title' => 'Home' ]]);


    NavBar::end(); 
    
        
    }






?>




</div>



    <div class="container "   style="padding: 1em; "   >
        <div class="no-print">
<?php
    echo Breadcrumbs::widget([
                    'itemTemplate' => "<li><i>{link}</i></li>\n", //
                    'links' => isset($this->params['breadcrumbs']) ? 
                            $this->params['breadcrumbs'] : [],
                            
        	]);
?>
        </div>
        <?= $content ?>

    </div>



















<?php



   if (isset($this->params['full_width_div']))
      echo "<div style='width: 100%; padding: 1em; '><div class='row'><div class='col p-1 bg-light border'>". $this->params['full_width_div'] ."</div></div></div>";



?>








































<div class="container">

<footer class="footer   mt-3 ">
        <p class="pull-left   float-left px-3 ">&copy; Bren:ICS <?= date('Y') ?></p>

        
</footer>

</div>





<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>










<style>

   .pagination li  { border: 1px solid #abc; margin: 0 1px; padding: 0 5px; }
   .pagination li.active  { border: 1px dotted #123; background-color: #def; }

   .breadcrumb li  { border: 1px solid #abc; margin: 0 1px; padding: 0 5px; }
   .breadcrumb li.active  { border: 1px dotted #123; background-color: #def; }

</style>



