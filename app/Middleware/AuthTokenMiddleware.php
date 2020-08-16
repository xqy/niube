<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Model\User;
use Exception;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthTokenMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);

        Context::set(ResponseInterface::class, $response);
        
        $token = $request->getHeader("Authorization");
        
        $authInfo = null;

        if (!empty($token)) {
            $authInfo = User::authToken($token[0]);

            Context::set("user", $authInfo["user"]);
            Context::set("tokenData", $authInfo["tokenData"]);
        }

        Context::set("isLogin", !is_null($authInfo));
        Context::set("uid", is_null($authInfo) ? 0 : $authInfo['user']->id);

        return $handler->handle($request);
    }
}