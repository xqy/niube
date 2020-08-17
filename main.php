<?php

use App\Utils\SnowFlake;
use Swoole\Process;

require __DIR__. "/vendor/autoload.php";




$snowFlake = new SnowFlake(1);

$num = 1000000;

$time = $snowFlake->getCurMicrotime();

echo $time . PHP_EOL;

for ($i=0; $i < $num; $i++) { 
    $snowFlake->generateOrderId2();
}

echo $num . "个消耗时间：" . ($snowFlake->getCurMicrotime() - $time) . PHP_EOL;

$time = $snowFlake->getCurMicrotime();

for ($i=0; $i < $num; $i++) { 
    $snowFlake->generateOrderId();
}

echo $num . "个消耗时间：" . ($snowFlake->getCurMicrotime() - $time) . PHP_EOL;

exit;

Co::set(["hook_flags" => SWOOLE_HOOK_SLEEP]);


class ApplicationContexts
{
    public static $name = 0;

    /**
     * @var null|ContainerInterface
     */
    private static $container;

    /**
     * @throws \TypeError
     */
    public static function getContainer()
    {
        return self::$container;
    }

    public static function hasContainer(): bool
    {
        return isset(self::$container);
    }

    public static function setContainer($container)
    {
        self::$container = $container;
        return $container;
    }
}

$container = new ApplicationContexts();

ApplicationContexts::setContainer($container);

Co\run(function(){
    
    for ($i = 10; $i--;) {
        go(function() use($i) {
            sleep(1);
            ApplicationContexts::$name ++;

            echo ApplicationContexts::$name . PHP_EOL;
        });
    }

    $container = ApplicationContexts::getContainer();

    var_dump($container);

});


exit;


$process_list = [];

for ($n = 0; $n < 5; $n++) {
    $process = new Process(function($process) use($n, &$container){

        $socket = $process->exportSocket();

        while ($data = $socket->recv()) {

            // echo "process{$n} recivce：" . $data . ", task start" . PHP_EOL;
    
            // sleep(1);
    
            // echo "process{$n} over, task end" . PHP_EOL;

            // $socket->send(time());

            try {
                $controller = $container->get("controller");

                $controller->incNum();

                echo "process{$n} num:" . $controller->getNum() . PHP_EOL;
            } catch (\Exception $e ) {
                echo "process{$n} controller not find" . PHP_EOL;
            }
        }
    
    }, false, SOCK_DGRAM, true);
    
    $process_list[] = $process;

    $process->start();
}


Co\run(function () {
    return;

    $wg = new \Swoole\Coroutine\WaitGroup();

    for ($n = 0; $n--;) {
        $wg->add();

        $result = [];

        go(function() use ( $n, &$result){
            echo "coroutine" . PHP_EOL;

            sleep($n);

            try{
                echo "开始请求 " . (1000 - $n) . PHP_EOL;
                $cli = new \Swoole\Coroutine\Http\Client('192.168.1.101', 80);
    
                $cli->setHeaders([
                    'Host' => 'xqy.haodaihuo.com'
                ]);
    
                $cli->get("/admin/test/test");
    
                if ($cli->errCode) {
                    echo  $cli->errMsg . PHP_EOL;
                } else {
                    $result[$n] = $cli->body;
                }
            } catch (\Exception $e) {
                echo "错误：" . $e . PHP_EOL;
            } 

            $cli->close();
    
            $wg->done();
        });
    }

    $cid = go(function(){
        co::sleep(1);
        echo "ttt start". PHP_EOL;
        co::yield();
        echo "ttt end". PHP_EOL;
    });

    $cid1 = go(function() use ($cid) {
        co::sleep(3);
        echo "yyy start". PHP_EOL;
        co::yield();
        echo "yyy end". PHP_EOL;
    });

    echo "uuu". PHP_EOL;
    co::sleep(4);

    co::resume($cid);
    co::sleep(1);
    co::resume($cid1);

    $wg->wait();

});

echo "parent" . PHP_EOL;

$controller = new Controller();
$container->bind("controller", $controller);

Co\run(function() use ($process_list, $container){

    $co_id = go(function() use ($process_list, $container) {
        $controller = $container->get("controller");
   
        while (true) {
        
            // $index = rand(0, 4);
            // $p = $process_list[$index];
            // $socket = $p->exportSocket();
            // $socket->send("data");
            // echo "parent recive: " . $socket->recv() . PHP_EOL. PHP_EOL;

            co::sleep(1);
            
            echo "inc..."  . PHP_EOL;

            $controller->incNum();

            if ($controller->getNum() > 7) {
                co::yield();
            }

        }
    });

    go(function() use ($container, $co_id) {

        $num = 0;

        while (true) {
            co::sleep(2);

            $num++;

            if ($num > 10) {
                co::resume($co_id);
            }

            $controller = $container->get("controller");

            echo "co num:" . $controller->getNum() . PHP_EOL;
        }
       
    });

});


Process::wait(true);
Process::wait(true);


// echo 'Parent #' . getmypid() . ' exit' . PHP_EOL;