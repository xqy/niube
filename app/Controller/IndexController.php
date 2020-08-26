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
use Hyperf\Utils\Coroutine;
use GuzzleHttp\Client;
use Hyperf\Guzzle\CoroutineHandler;
use GuzzleHttp\HandlerStack;

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
        $cid = Coroutine::id();

        $pid = posix_getpid();

        var_dump($cid);
        var_dump($pid);

        $token = User::getToken(11, "user");

        $this->success('', $token);
    }

    public function api()
    {
        $appkey = "ajOqShL3EtTNdnTSMp1JtKchIRlXT7Rr";

        $data = [
            "apikey" => $appkey,
            "tkl"    => "fu至内容¢m6tKc22dRvK¢打楷τa0寳【新款3d立体一棵树花朵壁画北欧现代卧室客厅电视背景墙壁纸影视墙】"
        ];


        $client = new Client();

        $response = $client->request('POST', 'http://api.tbk.dingdanxia.com/tkl/coupon_tkl', $data);

        var_dump($response->getBody()->getContents());



        $this->success('', $response);
    }


}
