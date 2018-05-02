<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/2
 * Time: 17:45
 */

namespace Controller;


use FastD\Http\ServerRequest;

class UserCenterController extends BaseController
{

    public function getInfo(ServerRequest $request)
    {
        return $this->response([]);
    }
}