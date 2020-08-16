<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use App\Model\User;
use Hyperf\DbConnection\Db;
use App\Service\QueueService;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;

class IndexController extends AbstractController
{
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        $res = Db::select('SELECT * FROM zy_fish_flow WHERE id=1');

        return [
            'method' => $method,
            'message' => $res,
        ];
    }

    public function favicon()
    {
        return "favicon.ico";
    }

    public function queue()
    {
        $this->service->push([
            'group@hyperf.io',
            'https://doc.hyperf.io',
            'https://www.hyperf.io',
        ], 3);

        return 'success';
    }

    public function redisList()
    {
        $container = ApplicationContext::getContainer();

        $redis = $container->get(\Hyperf\Redis\Redis::class);

        $tasks = $redis->LRANGE("jobs", 0, -1);

        return $tasks;
    }

    public function test()
    {
        $list = User::query()->select()->get();

        // $this->request = $this->request->withAttribute('list', $list);

        Context::set('list', $list);

        $this->success("", Context::get('list'));
    }

    public function token()
    {
        $token = User::getToken(11, "user");

        $this->success('', $token);
    }
}
