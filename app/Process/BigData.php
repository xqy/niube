<?php
namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;
use Swoole\Process;
use Swoole\Table;
use Swoole\Timer;

class BigData extends AbstractProcess
{
    /**
     * @var string
     */
    public $name = 'bigData';

    /**
     * @var int
     */
    public $nums = 5;

    public function handle(): void
    {
 

        
        while(true){}
    }
}