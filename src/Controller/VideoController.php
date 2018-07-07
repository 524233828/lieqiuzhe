<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 10:42
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\VideoLogic;

class VideoController extends BaseController
{


    public function fetchVideo(ServerRequest $request)
    {
        $page = $request->getParam("page", 1);
        $size = $request->getParam("size", 20);
        $cate = $request->getParam("cate_id", null);

        return $this->response(VideoLogic::getInstance()->fetchVideo($cate, $page, $size));
    }

    public function fetchCate(ServerRequest $request)
    {
        return $this->response(VideoLogic::getInstance()->fetchVideoCate());
    }

    public function collectVideo(ServerRequest $request)
    {
        $video_id = $request->getParam("video_id");
        return $this->response(VideoLogic::getInstance()->collectVideo($video_id));
    }

    public function uncollectedVideo(ServerRequest $request)
    {
        $video_id = $request->getParam("video_id");
        return $this->response(VideoLogic::getInstance()->uncollectedVideo($video_id));
    }
}