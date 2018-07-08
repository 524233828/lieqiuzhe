<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/5
 * Time: 22:48
 */

namespace Logic\Admin;


use Logic\BaseLogic;

abstract class AdminBaseLogic extends BaseLogic
{
    /**
     * 可用筛选字段列表
     * @var array
     */
    protected $list_filter = [];

    /**
     * 列表
     * @param array $params
     * @return array
     */
    abstract function listAction($params);

    /**
     * 获取
     * @param array $params
     * @return array
     */
    abstract function getAction($params);

    /**
     * 删除
     * @param array $params
     * @return array
     */
    abstract function deleteAction($params);

    /**
     * 新增
     * @param array $params
     * @return array
     */
    abstract function addAction($params);

    /**
     * 更新
     * @param array $params
     * @return array
     */
    abstract function updateAction($params);
}