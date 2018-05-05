<?php
namespace Middleware;

use Constant\ErrorCode;
use Constant\JWTKey;
use Exception\BaseException;
use FastD\Middleware\DelegateInterface;
use FastD\Middleware\Middleware;
use Firebase\JWT\JWT;
use Logic\UserLogic;
use Model\UserModel;
use Psr\Http\Message\ServerRequestInterface;
use Service\ApiResponse;

class Dispatch extends Middleware
{

    public function handle(ServerRequestInterface $request, DelegateInterface $next)
    {

        $token = $request->getHeaderLine('Authorization');
        if ($token) {
            // 只做拆分获取用户ID，不判断可用性
            try {
                $decoded = (array)JWT::decode($token, JWTKey::KEY, [JWTKey::ALG]);
                $user_id = isset($decoded['aud']) ? (string)$decoded['aud'] : 0;
                if (!empty($user_id)) {
                    $user = UserModel::getUserInfo($user_id, ['id', 'openid']);
                    if (!empty($user)) {
                        UserLogic::$user = $user;
                    }
                }
            } catch (\Exception $e) {
                UserLogic::$user = null;
            }
        }

        $content_type = $request->getHeaderLine("content_type");

        if($content_type == "application/json"){
            $body = (string)$request->getBody();
            $params = json_decode($body, true);

            $request->withQueryParams($params);
        }

        try {
            $response = $next->process($request);
        } catch (\Exception $e) {
            if ($e->getCode()!==0) {
                $response = new ApiResponse(
                    $e->getMessage(),
                    $e->getCode(),
                    ["file" => $e->getFile(),"line"=>$e->getLine()],
                    ErrorCode::status($e->getCode())
                );
            } else {
                BaseException::SystemError();
            }
        }

        return $response;
    }
}
