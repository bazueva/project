<?php
$config = require  __DIR__ . '/web.php';
/**
 * Application configuration shared by all test types
 */
$config['components']['urlManager']['showScriptName'] = true;
$config['components']['redis'] =
    [
        'class' => 'yii\redis\Connection',
        'hostname' => 'localhost',
        'port' => 6379,
        'database' => 1,
    ];

return $config;
