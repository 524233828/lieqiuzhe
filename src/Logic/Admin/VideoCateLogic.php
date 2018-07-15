<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/15
 * Time: 11:45
 */

namespace Logic\Admin;


use Constant\ErrorCode;
use Exception\BaseException;
use Model\VideoCateModel;
use Model\VideoModel;
use Model\VideoVCateModel;
use Service\Pager;

class VideoCateLogic extends AdminBaseLogic
{

    protected $list_filter = [
        "cate",
        "status",
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
        $count = VideoCateModel::count($where);

        //分页
        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $list = VideoCateModel::fetch([
            "id",
            "cate",
            "status",
            "update_time",
        ],$where);

        return ["list"=>$list, "meta" => $pager->getPager($count)];
    }

    public function getAction($params)
    {
        $id = $params['id'];

        if(empty($id)){
            BaseException::SystemError();
        }

        return VideoCateModel::get($id);
    }

    public function deleteAction($params)
    {
        //软删除，只更新表里的状态，已冻结则解冻
        $id = $params['id'];

        if(empty($id))
        {
            BaseException::SystemError();
        }

        $item = VideoCateModel::get($id, ["status"]);

        $status = 1 - $item['status'];

        $result = VideoCateModel::update(["status" => $status],["id" => $id]);

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

        $result = VideoCateModel::add($data);

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

        $result = VideoCateModel::update($data, ["id" => $id]);

        if($result){
            return [];
        }

        BaseException::SystemError();
    }

    public function listVideoByCate($cate_id, $page = 1, $size = 20)
    {

        $pager = new Pager($page, $size);

        $where = ["cate_id" => $cate_id];

        $count = VideoVCateModel::count($where);

        $where["LIMIT"] = [$pager->getFirstIndex(), $size];

        $where["ORDER"] = ["video_id" => "DESC"];
        //获取该分类下，所有视频的ID，按照id倒序排列
        $list = VideoVCateModel::fetch(["video_id"],$where);

        if(empty($list))
        {
            return ["list" => [], "meta" => $pager->getPager(0)];
        }

        $video_ids = [];

        foreach ($list as $v)
        {
            $video_ids[] = $v['video_id'];
        }
        $video_list = VideoModel::fetch(
            [
                "id",
                "title",
                "img_url",
                "url",
                "viewer",
                "times",
                "status",
                "update_time",
            ],
            [
                "id" => $video_ids,
                "ORDER" => ["id" => "DESC"]
            ]
        );

        if($video_list){
            return ["list" => $video_list, "meta" => $pager->getPager($count)];
        }

        BaseException::SystemError();
    }

    public function addVideoByCate($cate_id, $video_id)
    {
        $vc = VideoVCateModel::getVideoCate($cate_id,$video_id);

        if(!empty($vc))
        {
            error(ErrorCode::VIDEO_CATE_EXISTS);
        }

        $result = VideoVCateModel::add(["video_id"=>$video_id,"cate_id",$cate_id]);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();
    }

    public function deleteVideoByCate($cate_id, $video_id)
    {
        $vc = VideoVCateModel::getVideoCate($cate_id,$video_id);

        if(empty($vc))
        {
            error(ErrorCode::VIDEO_CATE_NOT_EXISTS);
        }

        $result = VideoVCateModel::deleteVideoCate($cate_id, $video_id);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();
    }
}