<?php

use Swoole\Coroutine\WaitGroup;

Co\run(function() {
    $wg = new WaitGroup();

    $wg->add(2);

    go(function() use($wg) {
        $cid = Co::getCid();

        $redis = new Redis();
        $redis->connect("192.168.1.190", 6379);

        $a = $redis->hget("test", "a");

        echo "[协程{$cid}]开始执行，读取数据为{$a}\n";

        // WATCH 命令可以被调用多次。 对键的监视从 WATCH 执行之后开始生效， 直到调用 EXEC 为止。
        $redis->watch("test");

        echo "[协程{$cid}]执行3秒耗时任务，调度器切换到其他协程\n";

        Co::sleep(3);

        echo "[协程{$cid}]开启事务\n";

        $result = false;

        while($result == false) {
            $redis->multi();

            echo "[协程{$cid}]耗时任务执行完成，开始保存数据，a=100\n";
            $redis->hSet("test", "a", 100);
    
            $result = $redis->exec();
    
            if ($result) {
                echo "[协程{$cid}]乐观锁模式写入成功\n";

                $a = $redis->hget("test", "a");

                echo "[协程{$cid}]读取数据为{$a}\n";

                break;
            } else {
                echo "[协程{$cid}]乐观锁模式写入失败\n";

                $a = $redis->hget("test", "a");

                echo "[协程{$cid}]读取数据为{$a}\n";

                echo "[协程{$cid}]重新开启写入操作\n";
            }
        }

        $wg->done();
    });

    go(function() use($wg) {
        $cid = Co::getCid();

        $redis = new Redis();
        $redis->connect("192.168.1.190", 6379);

        $a = $redis->hget("test", "a");

        echo "[协程{$cid}]开始执行，读取数据为{$a}\n";

        echo "[协程{$cid}]执行1秒耗时任务，调度器切换到其他协程\n";

        Co::sleep(1);

        // WATCH 命令可以被调用多次。 对键的监视从 WATCH 执行之后开始生效， 直到调用 EXEC 为止。
        $redis->watch("test");

        $result = false;

        while($result == false) {
            echo "[协程{$cid}]开启事务，没有耗时任务，保存数据a=666\n";

            $redis->multi();

            $redis->hSet("test", "a", 666);

            $result = $redis->exec();

            if ($result) {
                echo "[协程{$cid}]乐观锁模式写入成功\n";

                $a = $redis->hget("test", "a");

                echo "[协程{$cid}]读取数据为{$a}\n";
                
                break;
            } else {
                echo "[协程{$cid}]乐观锁模式写入失败\n";

                $a = $redis->hget("test", "a");

                echo "[协程{$cid}]读取数据为{$a}\n";

                echo "[协程{$cid}]重新开启写入操作\n";
            }
        }

        $wg->done();
    });

    $wg->wait();
});

exit;

$workerNum = 3;

$pool = new Swoole\Process\Pool($workerNum);

$pool->on("WorkerStart", function($pool, $workerId){
    echo "Worker#{$workerId} is started\n"; 

    Swoole\Runtime::enableCoroutine($flags = SWOOLE_HOOK_ALL);

    Co\run(function(){
        for ($i = 5; $i--;) {

            go(function() {
                // echo "协程 is started\n"; 

                $name = md5(time() . rand(0,10000));

                $dbms   = 'mysql';     //数据库类型
                $host   = '192.168.1.101'; //数据库主机名
                $dbName = 'zhiyu';    //使用的数据库
                $user   = 'haodaihuo';      //数据库连接用户名
                $pass   = '';          //对应的密码
                $dsn    = "$dbms:host=$host;dbname=$dbName";

                try {
                    $db = new PDO($dsn, $user, $pass); //初始化一个PDO对象

                    echo "Connected\n";
                  } catch (Exception $e) {
                    echo("Unable to connect: " . $e->getMessage() .'\n');
                  }
                
                try {
                    
                    echo "{$name}开启事务\n";

                    $db->beginTransaction();

                    $statement = <<<SQL
                        SELECT * FROM `zy_fish_flow` WHERE id=1 for update
                    SQL;

                    $rows = $db->query($statement);

                    echo "{$name}获得写锁\n";

                    $rows = $rows->FetchAll(PDO::FETCH_ASSOC);

                    $flow_fishes = $rows[0]["flow_fishes"];

                    var_dump($flow_fishes);

                    if ($flow_fishes >= 1) {
                        $statement = <<<SQL
                            -- update zy_fish_flow a set a.flow_fishes = a.flow_fishes - 1 where id = 1 and a.flow_fishes = $flow_fishes;
                            update zy_fish_flow a set a.flow_fishes = a.flow_fishes - 1 where id = 1;
                        SQL;

                        echo "{$name}修改数据\n";

                        $res = $db->exec($statement);
                    }

                    co::sleep(1);

                    echo "提交事务\n";

                    $db->commit();
                   

                } catch (PDOException $e) {
                   echo "Error!: " . $e->getMessage() . "\n";

                   $db->rollBack();
                }
            });
        }
    });

    while(true){}
});

$pool->on("WorkerStop", function ($pool, $workerId) {
    echo "Worker#{$workerId} is stopped\n";
});

$pool->start();
