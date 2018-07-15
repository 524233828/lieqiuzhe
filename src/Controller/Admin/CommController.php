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
        return $this->response(["path" => CommLogic::getInstance()->uploadImage()]);
    }

    public function uploadVideo(ServerRequest $request)
    {
        $media_time = $request->getParam("media_time", 0);
        return $this->response(["path" => CommLogic::getInstance()->uploadVideo("file", $media_time)]);
    }

}