<?php

namespace Logic;

use Exception\UserException;
use http\Exception;
use Model\AnalystInfoModel;
use Model\FansModel;
use Model\IconsModel;
use Model\RecommendModel;
use Model\UserLevelOrderModel;
use Model\UserModel;

class UserLogic extends BaseLogic
{

    static public $user;

    public function usedTickets($user_id)
    {
        $uid = UserLogic::$user['id'];
        try{
            $num = database()->update("user", [
                "ticket[-]" => 1
            ], [
                "id" => $uid
            ]);
        } catch (\Exception $e) {
            UserException::ticketNotEnough();
            exit();
        }

        database()->update("user", [
            "ticket[+]" => 1
        ], [
            "id" => $user_id
        ]);

        return [];
    }

    public function getUserInfo()
    {
        $uid = UserLogic::$user['id'];
        $current_level = UserLevelOrderModel::getUserCurrentLevel($uid);
        $info = UserModel::getUserInfo($uid,['nickname','avatar','user_type','ticket']);

        $level_icon = IconsModel::fetch('icon', ['type'=> $info['user_type'],  'level' => $current_level]);
        $fans = 0;
        if(1 == $info['user_type']) {
            $fans = FansModel::getCountFansByAnalystId($uid);
        }

        $info['level'] = $current_level;
        $info['level_icon'] = $level_icon;
        $info['gifts'] = $info['ticket'];
        unset($info['ticket']);
        $info['bill'] = 10;
        $fans && $info['fans'] = $fans;

        return $info;
    }
}
