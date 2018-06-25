<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/24
 * Time: 12:22
 */

namespace Controller\Lesson;

use Controller\BaseController;
use FastD\Http\ServerRequest;
use Logic\Lesson\ArticleLogic;

class ArticleController extends BaseController
{
    /**
     * @name 获取课程文章（购买后）
     * @apiParam resource_id|int|课程ID|true
     * @returnParam id|int|文章ID
     * @returnParam title|string|文章标题
     * @returnParam img_url|string|文章图片
     * @returnParam content|string|文章内容
     * @param ServerRequest $request
     * @return \Service\ApiResponse
     */
    public function getArticle(ServerRequest $request)
    {
        $resource_id = $request->getParam("resource_id");

        return $this->response(ArticleLogic::getInstance()->getArticle($resource_id));
    }
}