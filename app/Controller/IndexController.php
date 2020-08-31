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


        $url = "http://api.tbk.dingdanxia.com/tkl/coupon_tkl";


        var_dump($response = self::post($url, $data));



        $this->success('', $response);
    }

    public static function post($url, $data = [], $options = [])
    {
        $options['data'] = $data;
        return self::doRequest('post', $url, $options);
    }

    /**
     * CURL模拟网络请求
     * @param string $method 请求方法
     * @param string $url 请求方法
     * @param array $options 请求参数[headers,data,ssl_cer,ssl_key]
     * @return boolean|string
     * @throws LocalCacheException
     */
    public static function doRequest($method, $url, $options = [])
    {
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }
        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }
        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($options['data']));
        }
        // 证书文件设置
        if (!empty($options['ssl_cer'])) if (file_exists($options['ssl_cer'])) {
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLCERT, $options['ssl_cer']);
        } else throw new \Exception("Certificate files that do not exist. --- [ssl_cer]");
        // 证书文件设置
        if (!empty($options['ssl_key'])) if (file_exists($options['ssl_key'])) {
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($curl, CURLOPT_SSLKEY, $options['ssl_key']);
        } else throw new \Exception("Certificate files that do not exist. --- [ssl_key]");
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content) = [curl_exec($curl), curl_close($curl)];

        return $content;
    }



}
