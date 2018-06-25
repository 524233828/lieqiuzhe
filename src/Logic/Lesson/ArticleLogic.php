<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/22
 * Time: 19:13
 */


namespace Logic\Lesson;

use Logic\BaseLogic;
use Model\ArticleModel;

class ArticleLogic extends BaseLogic
{
    /**
     * 获取文章详情
     * @param $resource_id
     * @return bool|mixed
     */
    public function getArticle($resource_id)
    {
        return ArticleModel::getArticle($resource_id);
    }
}