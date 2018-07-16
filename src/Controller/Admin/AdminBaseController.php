<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 22:12
 */

namespace Controller\Admin;


use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Admin\AdminBaseLogic;

class AdminBaseController extends BaseController
{
    /**
     * @var AdminBaseLogic
     */
    protected $logic;

    /**
     * @var array
     */
    protected $add_valid = [];

    /**
     * 列表不作过滤
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function listAction(ServerRequest $request)
    {
//        $validate = validator($request,[]);

//        $params = $validate->data();
        $params = $request->getQueryParams();

        return $this->response($this->logic->listAction($params));
    }

    /**
     * 获取单一数据元，只过滤id
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function getAction(ServerRequest $request)
    {
        $params['id'] = $request->getParam('id');

        return $this->response($this->logic->getAction($params));
    }

    /**
     * 新增数据元，需定义过滤数据
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function addAction(ServerRequest $request)
    {
        $validate = validator($request,$this->add_valid);

        $params = $validate->data();

        return $this->response($this->logic->addAction($params));
    }

    /**
     * 删除，筛选ID
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function deleteAction(ServerRequest $request)
    {
        $params['id'] = $request->getParam('id');

        return $this->response($this->logic->deleteAction($params));
    }

    /**
     * 更新，需要筛选字段
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function updateAction(ServerRequest $request)
    {
        $validate = validator($request,$this->add_valid);

        $params = $validate->data();
//        $params = $request->getParsedBody();

        $params['id'] = $request->getParam('id');

        return $this->response($this->logic->updateAction($params));
    }

}