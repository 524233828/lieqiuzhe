<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 10:21
 */

namespace Logic;


use Exception\BaseException;
use Model\VideoCateModel;
use Model\VideoCollectModel;
use Model\VideoVCateModel;
use Model\VideoModel;
use Service\Pager;

class VideoLogic extends BaseLogic
{

    public function fetchVideo($cate = null,$page = 1, $size = 20)
    {
        $pager = new Pager($page, $size);

        $where = ["status" => 1];

        if(!empty($cate))
        {
            $vcate_list = VideoVCateModel::fetch("*", ["cate_id"=>$cate]);

            $ids = [];
            foreach ($vcate_list as $v)
            {
                $ids[] = $v['video_id'];
            }
            $where['id'] = $ids;
        }

        $count = VideoModel::count($where);

        $where["LIMIT"] = [$pager->getFirstIndex(),$size];
        $where["ORDER"] = ["sort" => "DESC","id" => "DESC"];

        $list = VideoModel::fetch("*", $where);

        $index_list = [];
        foreach ($list as $v)
        {
            $index_list[$v['id']] = $v;
            $index_list[$v['id']]['is_collect'] = 0;
            $index_list[$v['id']]['collect_num'] = 0;
        }

        //获取我关注的视频列表
        $user_id = UserLogic::$user['id'];
        $collect_list = VideoCollectModel::fetch(["video_id"], ["user_id" => $user_id]);

        foreach ($collect_list as $item)
        {
            if(isset($index_list[$item['video_id']]))
            {
                $index_list[$item['video_id']]['is_collect'] = 1;
            }
        }

        //获取关注数
        $count_list = VideoCollectModel::countCollectNum();

        foreach ($count_list as $value){
            if(isset($index_list[$value['video_id']]))
            {
                $index_list[$value['video_id']]['collect_num'] = $value['num'];
            }
        }


        $list = array_values($index_list);

        return ["list" => $list, "meta" => $pager->getPager($count)];
    }

    public function fetchVideoCate()
    {
        $list = VideoCateModel::fetch();

        return ["list" => $list ];
    }

    public function collectVideo($video_id)
    {
        $user_id = UserLogic::$user['id'];

        $result = VideoCollectModel::add([
            "user_id" => $user_id,
            "video_id" => $video_id
        ]);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();

    }

    public function uncollectedVideo($video_id)
    {
        $user_id = UserLogic::$user['id'];

        $result = VideoCollectModel::deleteVideoCollect([
            "user_id" => $user_id,
            "video_id" => $video_id
        ]);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();
    }
}