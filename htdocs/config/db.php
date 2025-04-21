<?php
require_once 'vault.php';
$user=getpw('sysops/data/webapps/test','dbuser');
$pass=getpw('sysops/data/webapps/test','dbpass');
return [
    'class' => 'yii\db\Connection',

    'dsn' => 'mysql:host=mysql.ics.uci.edu;dbname=test',

    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

