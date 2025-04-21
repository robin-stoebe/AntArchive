<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=localhost;dbname=evote2_test';

//return $db;





return [
    'class' => 'yii\db\Connection',

    'dsn' => 'mysql:host=mysql.ics.uci.edu;dbname=evote2_test',

    'username' => 'evote2_test',
    'password' => 'jfEupPavml7nyyOw',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];

