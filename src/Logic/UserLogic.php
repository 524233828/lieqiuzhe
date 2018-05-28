<?php

namespace Logic;

use Exception\UserException;
use http\Exception;
use Model\RecommendModel;

class UserLogic extends BaseLogic
{

    static public $user;

    public function usedTickets($user_id)
    {
        $uid = UserLogic::$user['id'];
        try {
            $num = database()->update("user", [
                "ticket[-]" => 1
            ], [
                "id" => $uid
            ]);
        } catch (Exception $e) {
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
}
