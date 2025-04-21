<?php
require_once 'vault.php';

$user=getpw('sysops/data/webapps/test','dbuser') ?? 'antarchive';
$pass=getpw('sysops/data/webapps/test','dbpass') ?? 'fMExIRG.)VRYUv_u';
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mysql.ics-db00.uci.edu;dbname=antarchive',

    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];

