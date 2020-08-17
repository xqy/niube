<?php

namespace App\Utils;

/**
 * 基于snowflake算法实现的高效php分布式ID生成器
 */
class SnowFlake
{
    /**
     * 起始时间戳，毫秒
     */
    const TIMEEPOCH = 1597634256809;

    /**
     * worker编号 位数
     */
    const WORKER_BITS = 10;

    /**
     * 最大的worker数量
     */
    const WORKER_MAX = -1 ^ (-1 << self::WORKER_BITS);

    /**
     * 序列编号 位数
     */
    const SEQUENCE_BITS = 12;

    /**
     * 最大的序列值
     */
    const SEQUENCE_MAX = -1 ^ (-1 << self::SEQUENCE_BITS);

    /**
     * 时间的位置偏移数
     */
    const TIME_SHIFT = self::WORKER_BITS + self::SEQUENCE_BITS;

    /**
     * worker的位置偏移数
     */
    const WORKER_SHIFT = self::SEQUENCE_BITS;

    /**
     * worker 编号
     */
    protected $workerId;

    /**
     * 序列记录值
     */
    protected $sequence;

    /**
     * 运行时时间戳
     */
    protected $timeStamp;

    /**
     * 锁，用于线程安全
     */
    protected $lock;

    public function __construct($workerId)
    {
        if ($workerId < 0 || $workerId > self::WORKER_MAX) {
            throw new \Exception("workerId超出范围");
        }

        $this->timeStamp = 0;
        $this->workerId = $workerId;
        $this->sequence = 0;
        $this->lock = new \swoole_lock(SWOOLE_MUTEX);
    }

    /**
     * 生成分布式ID算法
     */
    public function generateId()
    {
        $this->lock->lock();
        $now = $this->getCurMicrotime();

        if ($this->timeStamp == $now) {
            $this->sequence++;

            if ($this->sequence > self::SEQUENCE_MAX) {
                // 停止本毫秒内的取值计算，阻塞直到取到下一毫秒的值，重置sequence
                while ($this->timeStamp >= $now) {
                    $now = $this->getCurMicrotime();
                }
            }
        } else {
            $this->sequence = 0;
        }

        $this->timeStamp = $now;
        $id = (($now - self::TIMEEPOCH) << self::TIME_SHIFT) | ($this->workerId << self::TIME_SHIFT) | $this->sequence;
        $this->lock->unlock();

        return $id;
    }

    /**
     * 获取当前毫秒数
     */
    public function getCurMicrotime()
    {
        return sprintf("%.0f", microtime(true) * 1000);
    }

    /**
     * 实践算法，订单编号生成,date低效，待优化
     */
    public function generateOrderId($prefix = 'p'){
        return $prefix . date("Ymd"). substr(implode(null, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 7);
    }

    /**
     * 实践算法，订单编号生成,date方法低效，待优化
     */
    public function generateOrderId2($prefix = 'p'){
        return $prefix . date("Ymd") . $this->generateId();
    }

}