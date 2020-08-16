<?php

declare(strict_types=1);

use App\Middleware\AuthTokenMiddleware;
use App\Middleware\CorsMiddleware;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'http' => [
        CorsMiddleware::class
    ],
];
