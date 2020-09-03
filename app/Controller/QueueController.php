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
        $index = $this->request->input('index', 0);
        $param = $this->request->input('param', '');
        $delay = $this->request->input('delay', 0);

        $this->service->push($index, $param, $delay);

        return 'success';
    }
}
