<?php
namespace Middleware;

use Constant\ErrorCode;
use Constant\JWTKey;
use Exception\BaseException;
use Exception\UserException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Firebase\JWT\JWT;
use Logic\UserLogic;
use Model\AdminModel;
use Model\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use Service\ApiResponse;

class AdminDispatch extends Middleware
{

    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {

        $token = $request->getHeaderLine('Authorization');
        if ($token) {
            // 只做拆分获取用户ID，不判断可用性
            try {
                $decoded = (array)JWT::decode($token, JWTKey::ADMIN_KEY, [JWTKey::ALG]);
                $user_id = isset($decoded['aud']) ? (string)$decoded['aud'] : 0;
                if (!empty($user_id)) {
                    $user = AdminModel::get($user_id, ['id', 'channel', 'type']);
                    if (!empty($user)) {
                        UserLogic::$user = $user;
                    }
                }
            } catch (\Exception $e) {
                UserLogic::$user = null;
            }
        }

        try {
            $response = $next->process($request);
        } catch (\Exception $e) {
            if ($e->getCode()!==0) {
                $response = new ApiResponse(
                    $e->getMessage(),
                    $e->getCode(),
                    null,
                    ErrorCode::status($e->getCode())
                );
            } else {
                BaseException::SystemError();
            }
        }

        return $response;
    }
}
