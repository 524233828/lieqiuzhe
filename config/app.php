<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [
    /**
     * The application name.
     */
    'name' => 'ant-fd',

    'timezone' => 'PRC',

    /**
     * Application logger path
     */
    'log' => [
        [\Monolog\Handler\StreamHandler::class, 'error.log', \Monolog\Logger::ERROR]
    ],

    /*
     * Exception handle
     */
    'exception' => [
        'response' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        },
        'log' => function (Exception $e) {
            return [
                'msg' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
    ],

    /**
     * Bootstrap service.
     */
    'services' => [
        \ServiceProvider\EnvConfigServiceProvider::class,
        \FastD\ServiceProvider\RouteServiceProvider::class,
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        \FastD\ServiceProvider\DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
        \ServiceProvider\RedisServiceProvider::class,
        \ServiceProvider\ValidationServiceProvider::class
    ],

    /**
     * Application Consoles
     */
    'consoles' => [

    ],

    /**
     * Http middleware
     */
    'middleware' => [
        // 分发
        'dispatch' => new Middleware\Dispatch(),
        //入参过滤
        'filter' => new Middleware\IncomeFilter(),
        //rsa解密
        'rsa' => new \Middleware\RSADecryptMiddleware(),
        //rsa验签
        'verify' => new \Middleware\RSAVerifyMiddleware(),
        //防止重放攻击
        'reply'  => new \Middleware\TimestampMiddleware(),
        //登录检查
        'login' => new \Middleware\LoginCheck(),
    ],
];