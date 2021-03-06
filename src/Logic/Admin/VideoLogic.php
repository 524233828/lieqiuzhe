<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:09
 */

namespace Logic\Admin;


use Exception\BaseException;
use Model\VideoModel;
use Model\VideoVCateModel;
use Service\Pager;

class VideoLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "title",
        "status",
        "viewer",
        "url",
        "img_url",
        "times",
    ];

    public function listAction($params)
    {
        //列表分页参数
        $page = isset($params['page'])?$params['page']:1;
        $size = isset($params['size'])?$params['size']:20;

        $pager = new Pager($page, $size);

        $where = null;

        //检查请求参数中是否有筛选可用参数，有则按参数筛选
        foreach ($this->list_filter as $v){
            if(isset($params[$v]))
            {
                $where[$v] = $params[$v];
            }
        }

        //计算符合筛选参数的行数
        $count = VideoModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = VideoModel::fetch([
            "id",
            "title",
            "img_url",
            "url",
            "viewer",
            "times",
            "status",
            "update_time",
        ],$where);

        $video_index_list = [];
        $ids = [];
        foreach ($list as $value)
        {
            $ids[] = $value['id'];
            $video_index_list[$value['id']] = $value;
            $video_index_list[$value['id']]['is_cate'] = 0;
        }

        $cate_list = [];
        if(!empty($params['cate_id'])){
            $cate_list = VideoVCateModel::fetch("*",["cate_id" => $params['cate_id'], "video_id" => $ids]);
        }

        foreach ($cate_list as $v)
        {
            if(isset($video_index_list[$v['video_id']])){
                $video_index_list[$v['video_id']]['is_cate'] = 1;
            }

        }

        $list = array_values($video_index_list);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return VideoModel::get($id);
    }

    public function deleteAction($params)
    {
        //软删除，只更新表里的状态，已冻结则解冻
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        $item = VideoModel::get($id, ["status"]);

        $status = 1 - $item['status'];

        $result = VideoModel::update(["status" => $status],["id" => $id]);

        if($result)
        {
            return [];
        }else{
            BaseException::SystemError();
        }
    }

    public function addAction($params)
    {
        $data = [];

        foreach ($this->list_filter as $v){
            if(isset($params[$v])){
                $data[$v] = $params[$v];
            }
        }

        $data['create_time'] = time();

        $result = VideoModel::add($data);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }

    public function updateAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        $data = [];

        foreach ($this->list_filter as $v){
            if(isset($params[$v])){
                $data[$v] = $params[$v];
            }
        }

        $result = VideoModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }
}