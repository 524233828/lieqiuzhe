<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/9
 * Time: 14:09
 */

namespace Controller\Wxapp;


use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\WxappTabLogic;

class WxappTabController extends BaseController
{

    public function fetchTab(ServerRequest $request)
    {
        return $this->response(WxappTabLogic::getInstance()->listTab());
    }

    public function addTab(ServerRequest $request)
    {
        $data = [];

        $type = $request->getParam("type");
        $img = $request->getParam("img");
        $title = $request->getParam("title");
        $url = $request->getParam("url", null);
        $app_id = $request->getParam("app_id", null);

        $data['type'] = $type;
        $data['img'] = $img;
        $data['title'] = $title;

        if(!empty($url)){
            $data['url'] = $url;
        }

        if(!empty($app_id)){
            $data['app_id'] = $app_id;
        }

        return $this->response(WxappTabLogic::getInstance()->add($data));
    }

    public function deleteTab(ServerRequest $request)
    {
        $id = $request->getParam("id");

        return $this->response(WxappTabLogic::getInstance()->deleteTab($id));
    }

    public function updateTab(ServerRequest $request)
    {
        $id = $request->getParam("id");
        $type = $request->getParam("type", null);
        $img = $request->getParam("img", null);
        $title = $request->getParam("title", null);
        $url = $request->getParam("url", null);
        $app_id = $request->getParam("app_id", null);
        $status = $request->getParam("status", null);

        validator($request,[
            "id" => "required|integer"
        ]);

        if(!empty($url)){
            $data['url'] = $url;
        }

        if(!empty($app_id)){
            $data['app_id'] = $app_id;
        }

        if(!empty($type)){
            $data['type'] = $type;
        }

        if(!empty($img)){
            $data['img'] = $img;
        }

        if(!empty($title)){
            $data['title'] = $title;
        }

        if(!empty($status)){
            $data['status'] = $status;
        }

        $where['id'] = $id;

        return $this->response(WxappTabLogic::getInstance()->updateTab($data, $where));

    }
}