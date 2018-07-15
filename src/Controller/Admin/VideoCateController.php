<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:40
 */

namespace Controller\Admin;


use FastD\Http\ServerRequest;
use Logic\Admin\VideoCateLogic;

class VideoCateController extends AdminBaseController
{

    public function __construct()
    {
        $this->logic = VideoCateLogic::getInstance();

        $this->add_valid = [
            "cate" => "required|range_ch:1,4",
            "status" => "in:0,1",
        ];
    }

    public function listVideoByCate(ServerRequest $request)
    {
        validator($request, ["cate_id" => "required|integer"]);
        $cate_id = $request->getParam("cate_id");
        return $this->response(VideoCateLogic::getInstance()->listVideoByCate($cate_id));
    }


    public function addVideoByCate(ServerRequest $request)
    {
        validator($request, ["cate_id" => "required|integer","video_id" => "required|integer"]);
        $cate_id = $request->getParam("cate_id");
        $video_id = $request->getParam("video_id");
        return $this->response(VideoCateLogic::getInstance()->addVideoByCate($cate_id, $video_id));
    }

    public function deleteVideoByCate(ServerRequest $request)
    {
        validator($request, ["cate_id" => "required|integer","video_id" => "required|integer"]);
        $cate_id = $request->getParam("cate_id");
        $video_id = $request->getParam("video_id");
        return $this->response(VideoCateLogic::getInstance()->deleteVideoByCate($cate_id, $video_id));
    }
}