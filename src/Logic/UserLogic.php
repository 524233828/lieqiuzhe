<?php

namespace Logic;

use Exception\UserException;
use Model\RecommendModel;

class UserLogic extends BaseLogic
{

    static public $user;

    public function usedTickets($user_id)
    {
        $uid = UserLogic::$user['id'];
        $num = database()->update("user", [
            "ticket[-]" => 1
        ], [
            "id" => $uid
        ]);

        if(!$num) {
            UserException::ticketNotEnough();
        }else{
            database()->update("user", [
                "ticket[+]" => 1
            ], [
                "id" => $user_id
            ]);
        }

        return [];
    }
}
