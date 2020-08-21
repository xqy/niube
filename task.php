<?php

use Swoole\Timer;
use Swoole\Process\Pool;

$table = new \Swoole\Table(1024);
$table->column('id', \Swoole\Table::TYPE_INT, 4); //1,2,4,8
$table->column('name', \Swoole\Table::TYPE_STRING, 64);
$table->column('num', \Swoole\Table::TYPE_FLOAT);
$table->create();

$pool  = new Pool(1, SWOOLE_IPC_UNIXSOCK, null, true);

$pool->on("WorkerStart", function($pool, $workId) use ($table) {
    echo "Work {$workId} is started\n";

    $pool->write("$workId writting\n");
});

$pool->on("Message", function($pool, $message) use ($table) {
    $workId = ($process = $pool->getProcess())->pid;
    echo "Work {$workId} is doing, message: $message\n";


});

$pool->listen('unix:/tmp/php.sock');

$pool->on("WorkerStop", function($pool, $workId) {
    echo "Work {$workId} is stopped\n";
});

$pool->start();



