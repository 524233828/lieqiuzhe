<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/17
 * Time: 14:42
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\NotifyLogic;
use Logic\UploadLogic;
use Service\UploadService;

class CommonController extends BaseController
{

    public function uploadImage(ServerRequest $request)
    {
        return $this->response(["path"=>UploadLogic::getInstance()->uploadImage()]);
    }

    public function orderNotify(ServerRequest $request)
    {
        $log = myLog("Common_orderNotify");

        $log->addDebug("支付回调开始");
        $log->addDebug("out_trade_no:".$request->getParam("out_trade_no"));
        validator($request,[
           "out_trade_no" => "required"
        ]);

        NotifyLogic::getInstance()->payNotify($request);
    }
}