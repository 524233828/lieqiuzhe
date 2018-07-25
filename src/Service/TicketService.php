<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/25
 * Time: 22:38
 */

namespace Service;


use Model\UserModel;

class TicketService
{

    public static function sendTicket($uid, $num)
    {
        UserModel::update(["ticket[+]" => $num],["id" => $uid]);
    }
}