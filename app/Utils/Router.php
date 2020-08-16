<?php

namespace App\Utils;

use App\Middleware\CorsMiddleware;
use Hyperf\HttpServer\Router\Router as RouterP;

/**
 * @method static addRoute($httpMethod, string $route, $handler, array $options = [])
 * @method static addGroup($prefix, callable $callback, array $options = [])
 * @method static get($route, $handler, array $options = [])
 * @method static post($route, $handler, array $options = [])
 * @method static put($route, $handler, array $options = [])
 * @method static delete($route, $handler, array $options = [])
 * @method static patch($route, $handler, array $options = [])
 * @method static head($route, $handler, array $options = [])
 */
class Router
{

    public static function addRoute($httpMethod, string $route, $handler, array $options = [])
    {
        RouterP::addRoute($httpMethod, $route, $handler, $options);
    }

    public static function addGroup($prefix, callable $callback, array $options = [])
    {
        RouterP::addGroup($prefix, $callback, $options);
    }

    public static function get($route, $handler, array $options = [])
    {
        RouterP::get($route, $handler, $options);
    }

    public static function post($route, $handler, array $options = [])
    {
        RouterP::post($route, $handler,  $options);
    }

    public static function put($route, $handler, $options)
    {
        RouterP::put($route, $handler, $options);
    }

    public static function patch($route, $handler, array $options = [])
    {
        RouterP::patch($route, $handler, $options);
    }

    public static function head($route, $handler, array $options = [])
    {
        RouterP::head($route, $handler, $options);
    }



}