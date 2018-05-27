<?php
$config = require  __DIR__ . '/web.php';
/**
 * Application configuration shared by all test types
 */
$config['components']['urlManager']['showScriptName'] = true;

return $config;
