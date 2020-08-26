<?php

declare(strict_types=1);

use App\Middleware\AuthTokenMiddleware;
use App\Middleware\CorsMiddleware;
use App\Utils\Router;

Router::get('/', 'App\Controller\IndexController@index');
Router::get('/favicon.ico', 'App\Controller\IndexController@favicon');
Router::get('/queue', 'App\Controller\IndexController@queue');
Router::addRoute(['GET', 'POST', 'HEAD'], '/rl', 'App\Controller\IndexController@redisList');

Router::get('/token', 'App\Controller\IndexController@token');
Router::get('/tb', 'App\Controller\IndexController@api');

Router::addGroup(
    '/mp', function () {
        Router::get('/index', [\App\Controller\IndexController::class, 'index']);
        Router::get('/article/list', [\App\Controller\ArticleController::class, 'list']);

        Router::get('/test', 'App\Controller\IndexController@test');

    },
    ['middleware' => [AuthTokenMiddleware::class]]
);


