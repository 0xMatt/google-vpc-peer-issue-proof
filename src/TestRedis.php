<?php


namespace Testing;

use Predis\Client;

/**
 *  A quick benchmark between pconnect and connect for redis in php
 *
 *  to use just execute the php file directly `php php_redis_loadtest_connect_pconnect.php`
 *
 * To safe you the hassle, there isnt much difference, the main savings is the connection pooling and it isnt seen here. A better test would be to load test multiple connections
 *
 * testing connect..
 * took 16.954024076462ms to test connect
 * sleeping for 10 seconds to cooldown
 * testing pconnect..
 * took 17.165184020996ms to test pconnect
 */
class TestRedis
{

    const REDIS_HOST = '127.0.0.1';
    const REDIS_PORT = 6379;
    const SLEEP_BETWEEN_TESTS = 10;

    private $client;

    private $readWriteLoops = 5;
    private $connectLoops = 2000;


    private $readWritestart = 0;
    private $connectStart = 0;

    private $currentTest = '';


    public function test(): void
    {
        $this->log('testing connection to redis..');
        $this->setRedisConnect();
        $this->runTests();
    }

    private function setRedisConnect(): void
    {
        $this->redis = new Client();
        $this->redis->connect(self::REDIS_HOST, self::REDIS_PORT);
        $this->currentTest = 'connect';
    }


    public function runTests(): void
    {
        $this->startConnectTimer();

        for ($x = 0; $x <= $this->connectLoops; $x++) {

            for ($i = 0; $i <= $this->readWriteLoops; $i++) {
                $this->redis->ping();
            }

            for ($i = 0; $i <= $this->readWriteLoops; $i++) {
                $this->redis->setEx('connect_test_' . $i, 1, 'test');
            }

            for ($i = 0; $i <= $this->readWriteLoops; $i++) {
                $this->redis->setEx('connect_test_' . $i, 1, 'test');
            }
        }
        $this->resetConnectTimer();
    }


    public function log($msg): void
    {
        echo $msg . PHP_EOL;
    }


    public function startConnectTimer(): void
    {
        $this->connectStart = microtime(true);
    }

    public function resetConnectTimer(): void
    {
        $now = microtime(true);
        $time = $now - $this->connectStart;
        $this->log('took ' . $time . 'ms to test ' . $this->currentTest);
        $this->startConnectTimer();
    }
}



