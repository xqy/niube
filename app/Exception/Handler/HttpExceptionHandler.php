<?php
namespace App\Exception\Handler;

use App\Exception\HttpResponseException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use ErrorException;
use Hyperf\Config\Config;

class HttpExceptionHandler extends  ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if ($throwable instanceof HttpResponseException) {
            $data = $throwable->getMessage();
        } else {
            if (env("APP_ENV") == "dev") {
                $data = json_encode([
                    'code'  => $throwable->getCode(),
                    'msg'   => $throwable->getMessage(),
                    'file'  => $throwable->getFile(),
                    'line'  => $throwable->getLine(),
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $data = json_encode([
                    'code'  => $throwable->getCode(),
                    'msg'   => "error",
                    'time'  => time()
                ], JSON_UNESCAPED_UNICODE);
            }
        }

        // 阻止异常冒泡
        $this->stopPropagation();

        return $response->withStatus(200)
                        ->withHeader("Content-Type", "application/json; charset=utf-8")
                        ->withHeader("Server", "XXX")
                        ->withBody(new SwooleStream($data));
    }

    /**
     * 判断该异常处理器是否要对该异常进行处理
     */
    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
