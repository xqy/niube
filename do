#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

$client = new GuzzleHttp\Client();

$res = $client->request('GET', 'http://xqy.haodaihuo.com/api/platform/filters', [
    'auth' => ['user', 'pass']
]);
echo $res->getStatusCode();
echo $res->getHeader('content-type') . PHP_EOL;
echo $res->getBody() . PHP_EOL . PHP_EOL;


// 发送一个异步请求
$request = new \GuzzleHttp\Psr7\Request('GET', 'http://xqy.haodaihuo.com/api/platform/filters');

$promises[] = $client->sendAsync($request)->then(function ($response) {
    echo 'I completed! '. PHP_EOL . $response->getBody() . PHP_EOL;
});

$promises[] = $client->sendAsync($request)->then(function ($response) {
    echo 'I completed2! '. PHP_EOL . $response->getBody() . PHP_EOL;
});

//吸引合伙人的办法 资金？股权？理想？
//异步 技术的产品化？http协议web网站，ftp协议上传下载，长连接直播游戏及时通信，都能实现，但是产品没有优势，投资回报不明，
//接触一个做过一个类型业务的同行,咨询投资回报和市场如何做
//curd没难度，挑战更有难度的事情，框架和合伙创业
//既然选择了低难度的技术，就不要从技术角度去竞争，去从合伙方式去做事情
//不要被影响，合伙不合伙是要看产品，不要为了合伙而合伙
//外包行情，懂行？ 找外包做演示版本 拉风投。 做自己的产品，自己花钱做服务赚钱，投资太大，没决心。
//谋可寡而不可众,不赚钱是因为盲目的竞争，门槛变低了
//面向技术的创新，做框架开源代码，更快更强更方便，客户是开发者，目的是提升名气，提升自己的势能，赚钱基本是思路。
//面向产品的创新，实现没有的业务，客户是to b或to c，to b卖代码，to c搞一些弄来弄去的玩法，社交，电商，游戏微创新的东西

foreach($promises as $promise)$promise->wait();

