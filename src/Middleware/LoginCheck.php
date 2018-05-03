<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/8
 * Time: 15:05
 */

namespace Middleware;

use Constant\ErrorCode;
use Exception\UserException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Logic\UserLogic;
use Psr\Http\Message\ServerRequestInterface;
use Service\ApiResponse;

class LoginCheck extends Middleware
{

    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {
        if (!empty(UserLogic::$user['id'])) {
            $response = $next->process($request);
        } else {
            UserException::UserNotFound();
        }

        return $response;
    }
}
