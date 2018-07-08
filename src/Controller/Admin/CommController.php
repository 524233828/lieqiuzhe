<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/8
 * Time: 15:40
 */

namespace Controller\Admin;


use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Admin\CommLogic;

class CommController extends BaseController
{


    public function uploadImage(ServerRequest $request)
    {
        return $this->response(CommLogic::getInstance()->uploadImage());
    }

}