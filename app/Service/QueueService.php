<?php

declare(strict_types=1);

namespace App\Service;

use App\Job\ExampleJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

class QueueService
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    /**
     * 生产消息.
     * 序列化后如果值一样，重复消息会覆盖，导致时间一直延后，这是redis有序集合的特性导致的bug
     * @param $params 数据
     * @param int $delay 延时时间 单位秒
     */
    public function push($index, $params, int $delay = 0): bool
    {
        $job = [
            0 => ExampleJob::class
        ];

        $class = $job[$index];

        return $this->driver->push(new $class($params), $delay);
    }
}