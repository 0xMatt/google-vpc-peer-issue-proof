<?php


require_once './vendor/autoload.php';

use Testing\TestMySQL;
use Testing\TestRedis;


echo 'Testing Redis...' . PHP_EOL;
$testRedis = new TestRedis();
$testRedis->test();

echo PHP_EOL;
echo 'Testing MySQL...' . PHP_EOL;
$testMysql = new TestMySQL();
$testMysql->test();
