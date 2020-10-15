<?php

declare(strict_types=1);

namespace App\Model;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Hyperf\DbConnection\Db;
use UnexpectedValueException;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Utils\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 用户模型
 */
class User extends Model
{

    protected $table = 'user';

    const KEY = "id";

    /**
     * 获取token
     */
    public static function getToken($id, string $type, array $params = []): array
    {
        $request = Context::get(ServerRequestInterface::class);

        $host = $request->getHeader("Host");
        $time = time();

        $params += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => strtotime('+ 3hour'),
        ];
        $params['jti'] = compact('id', 'type');
        $token = JWT::encode($params, env('App_KEY', 'default'));

        return compact('token', 'params');
    }

    /**
     * 解析token
     */
    public static function parseToken(string $jwt): array
    {
        JWT::$leeway = 60;

        $data = JWT::decode($jwt, env('App_KEY', 'default'), array('HS256'));

        return [Db::table("user")->where('id', $data->jti->id)->first(), $data->jti->type];
    }

    /**
     * 验证token
     */
    public static function authToken($token): array
    {
        if (!$token || !$tokenData = Db::table("user_token")->where('token', $token)->first())
            throw new \Exception('请登录', 410000);
        try {
            [$user, $type] = self::parseToken($token);
        } catch (\Throwable $e) {
            Db::table("user_token")->where('token', $token)->delete();
            throw new \Exception('登录已过期,请重新登录', 410001);
        }

        if (!$user || $user->id != $tokenData->uid) {
            Db::table("user_token")->where('token', $token)->delete();
            throw new \Exception('登录状态有误,请重新登录', 410002);
        }

        $tokenData->type = $type;

        return compact('user', 'tokenData');
    }

}
