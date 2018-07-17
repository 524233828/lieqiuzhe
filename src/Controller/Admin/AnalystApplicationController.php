<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/9
 * Time: 10:19
 */

namespace Controller\Admin;


use FastD\Http\ServerRequest;
use Logic\Admin\AnalystApplicationLogic;

class AnalystApplicationController extends AdminBaseController
{
    public function __construct()
    {
        $this->logic = AnalystApplicationLogic::getInstance();

        $this->add_valid = [

        ];
    }

    public function passAction(ServerRequest $request)
    {
        $params['id'] = $request->getParam('id');
        return $this->response(AnalystApplicationLogic::getInstance()->applicationPass($params));
    }
}