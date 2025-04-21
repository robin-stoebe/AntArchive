<?php



//error_reporting(-1);
//error_reporting(2039);
ini_set('display_errors', true);
ini_set("display_errors", true);
ini_set("display_startup_errors",true);
ini_set("error_reporting",2039);




// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$debug = 1;

(new yii\web\Application($config))->run();
