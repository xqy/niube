<?php
namespace App\Process;

use Hyperf\Process\AbstractProcess;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Coroutine;
use Hyperf\Utils\Exception\ParallelExecutionException;
use Hyperf\Utils\Parallel;

class MyProcess extends AbstractProcess
{
    public function handle(): void
    {
        $parallel = new Parallel(3);

        $container = $this->container;

        for ($i = 2; $i--;) {
            $parallel->add(function() use ($container){
                $cid = Coroutine::id();

                $redis = $container->get(Redis::class);

                $num = 0;
                $lock = 0;
                $lockout_time = time();

                while ($num < 6) {
                    sleep(1);

                    $lockout_time = time() + rand(1, 5);
                    $lock = $redis->setnx("lock.foo", $lockout_time);

                    if ($lock == 1) {
                        if ($num >= 2) {
                            echo "\n[$cid] 获得了锁,但是次数已经大于2，模拟异常，不释放锁，直接退出\n\n";

                            break;
                        }

                        $num++;
                    } else {
                        $expire_time = $redis->get("lock.foo");

                        if ($expire_time < time() && $redis->getset("lock.foo", $lockout_time) < time() ) {
                            $lock = 1;

                            $num++;
                        } else {
                            echo "[$cid] 没有获得了锁，重新尝试去获得锁\n";
                            $lock = 0;

                            continue;
                        }
                    }
                    
                    echo "\n[$cid] 第{$num}次获得了锁,开始进行耗时2秒的任务\n\n";
                    sleep(2);
                    echo "[$cid] 任务完成，释放锁，重新参与竞争锁\n";

                    if ($lockout_time  >= time()) {
                        $redis->del("lock.foo");

                        $lock = 0;
                    }
                }
            });
        }
        
        try {
            $parallel->wait();
            echo "协程全部退出\n";
        } catch (ParallelExecutionException  $e) {
            $e->getResults();
        }
      
        while(true){}
    }
}