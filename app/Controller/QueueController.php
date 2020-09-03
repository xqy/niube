<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\QueueService;

class QueueController extends AbstractController
{
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;

    public function produce()
    {
        $index = 0;
        $param = ['a','b','c', microtime()];
        $delay = 3;

        $this->service->push($index, $param, $delay);

        return 'success';
    }
}
