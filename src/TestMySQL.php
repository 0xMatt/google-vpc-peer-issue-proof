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
class TestMySQL
{

    const MYSQL_HOST = '127.0.0.1';
    const MYSQL_PORT = 3306;
    const MYSQL_USER = 'root';
    const MYSQL_DATBASE = 'magento';
    const MYSQL_PASSWRD = 'root';

    private $client;

    private $readWriteLoops = 5;
    private $connectLoops = 500;


    private $readWritestart = 0;
    private $connectStart = 0;

    private $currentTest = '';


    public function test(): void
    {
        $this->log('testing connection to mysql..');
        $this->setMySQLConnect();
        $this->runTests();
    }

    private function setMySQLConnect(): void
    {
        $this->client = new \PDO(sprintf('mysql:host=%s;dbname=%s', self::MYSQL_HOST, self::MYSQL_DATBASE), self::MYSQL_USER, self::MYSQL_PASSWRD);
        $this->currentTest = 'connect';
    }


    public function runTests(): void
    {
        $this->startConnectTimer();

        $sth = $this->client->prepare("SHOW TABLES FROM " . self::MYSQL_DATBASE);
        $sth->execute();

        $result = $sth->fetchAll();
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



