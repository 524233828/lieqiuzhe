<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/7
 * Time: 10:21
 */

namespace Logic;


use Exception\BaseException;
use Model\IconsModel;
use Model\UserModel;
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

        $where = [VideoModel::$table.".status" => 1];

        if(!empty($cate))
        {
            $vcate_list = VideoVCateModel::fetch("*", ["cate_id"=>$cate]);

            $ids = [];
            foreach ($vcate_list as $v)
            {
                $ids[] = $v['video_id'];
            }

            if(empty($ids)){
                return ["list" => [], "meta" => $pager->getPager(0)];
            }

            $where[VideoModel::$table.'.id'] = $ids;
        }

        $count = VideoModel::count($where);

        $where["LIMIT"] = [$pager->getFirstIndex(),$size];
        $where["ORDER"] = [VideoModel::$table.".id" => "DESC"];

        $list = VideoModel::fetchVideoWithUser(
            [
                VideoModel::$table.".id",
                VideoModel::$table.".user_id",
                VideoModel::$table.".url",
                VideoModel::$table.".img_url",
                VideoModel::$table.".title",
                VideoModel::$table.".viewer",
                VideoModel::$table.".times",
                VideoModel::$table.".status",
                VideoModel::$table.".update_time",
                VideoModel::$table.".create_time",
                UserModel::$table.".nickname",
                UserModel::$table.".avatar"
            ],
            $where
        );

        //TODO: 先拿一个icons做代替
        $icon = IconsModel::getUserIcon(1);

        $index_list = [];
        foreach ($list as $v)
        {
            $index_list[$v['id']] = $v;
            $index_list[$v['id']]['is_collect'] = 0;
            $index_list[$v['id']]['collect_num'] = 0;
            $index_list[$v['id']]['icon'] = empty($v['nickname'])?null:$icon['icon'];
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
        $list = VideoCateModel::fetch("*",["status" => 1]);

        return ["list" => $list ];
    }

    public function collectVideo($video_id)
    {
        $user_id = UserLogic::$user['id'];

        $where = [
            "user_id" => $user_id,
            "video_id" => $video_id
        ];

        $collect = VideoCollectModel::getVideoCollect($where);

        if(!empty($collect))
        {
            BaseException::SystemError();
        }

        $result = VideoCollectModel::add($where);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();

    }

    public function uncollectedVideo($video_id)
    {
        $user_id = UserLogic::$user['id'];

        $where = [
            "user_id" => $user_id,
            "video_id" => $video_id
        ];

        $collect = VideoCollectModel::getVideoCollect($where);

        if(empty($collect))
        {
            BaseException::SystemError();
        }

        $result = VideoCollectModel::deleteVideoCollect($where);

        if($result)
        {
            return [];
        }

        BaseException::SystemError();
    }

    public function collectVideoList($page = 1, $size = 20)
    {
        $pager = new Pager($page, $size);

        $user_id = UserLogic::$user['id'];

        $count = VideoCollectModel::count(["user_id"=>$user_id]);

        $list = VideoCollectModel::videoCollectList($user_id, ["LIMIT" => [$pager->getFirstIndex(), $size]]);

        //TODO: 先拿一个icons做代替
        $icon = IconsModel::getUserIcon(1);

        $index_list = [];
        foreach ($list as $v)
        {
            $index_list[$v['id']] = $v;
            $index_list[$v['id']]['is_collect'] = 0;
            $index_list[$v['id']]['collect_num'] = 0;
            $index_list[$v['id']]['icon'] = empty($v['nickname'])?null:$icon['icon'];
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
}