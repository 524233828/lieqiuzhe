<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 17:45
 */

namespace Controller;


use Constant\CacheKey;
use FastD\Http\ServerRequest;
use Logic\RecommendLogic;
use Logic\UserCenterLogic;
use Logic\UserLogic;
use Logic\AnalystLogic;
use Model\RecommendModel;

class UserCenterController extends BaseController
{

    public function getInfo(ServerRequest $request)
    {
        return $this->response(UserLogic::getInstance()->getUserInfo());
    }

    public function updateUserInfo(ServerRequest $request)
    {
        $nickname = $request->getParam("nickname",'');
        $avatar = $request->getParam("avatar",'');
        $sex = $request->getParam("sex",'');

        return $this->response(UserCenterLogic::getInstance()->updateUserInfo($nickname, $avatar, $sex));
    }

    public function getMyFollows(ServerRequest $request)
    {
        return $this->response(AnalystLogic::getInstance()->myFollows());
    }

    public function bindPhone(ServerRequest $request)
    {
        $validate = validator($request, [
            "phone" => "required",
            "code" => "required",
        ]);
        $params = $validate->data();

        return $this->response(UserCenterLogic::getInstance()->bindPhone($params['phone'],$params['code']));
    }

    public function sendBindCode(ServerRequest $request)
    {

        $validate = validator($request, [
            "phone" => "required|mobile"
        ]);
        $params = $validate->data();

        return $this->response(UserCenterLogic::getInstance()->sendCode($params['phone'],CacheKey::BIND_PHONE_CODE_KEY));
    }

    public function sendForgetCode(ServerRequest $request)
    {

        $validate = validator($request, [
            "phone" => "required|mobile"
        ]);
        $params = $validate->data();

        return $this->response(UserCenterLogic::getInstance()->sendCode($params['phone'],CacheKey::FORGET_PHONE_CODE_KEY));
    }


    public function validCode(ServerRequest $request)
    {
        $validate = validator($request, [
            "phone" => "required|mobile",
            "code" => "required"
        ]);
        $params = $validate->data();

        return $this->response(UserCenterLogic::getInstance()->validCode($params['phone'], $params['code']));
    }

    public function updateUserPassword(ServerRequest $request)
    {
        $validate = validator($request, [
            "password" => "required",
            "re_password" => "required"
        ]);
        $params = $validate->data();

        return $this->response(UserCenterLogic::getInstance()->updateUserPassword($params['password'], $params['re_password']));
    }

    public function fetchRecommendReadHistoryList(ServerRequest $request)
    {

        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 5);


        return $this->response(RecommendLogic::getInstance()->fetchReadHistoryList($page, $size));
    }
}