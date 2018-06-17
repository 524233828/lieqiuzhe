<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/17
 * Time: 14:42
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\UploadLogic;
use Service\UploadService;

class CommonController extends BaseController
{

    public function uploadImage(ServerRequest $request)
    {
        return $this->response(["path"=>UploadLogic::getInstance()->uploadImage()]);
    }
}