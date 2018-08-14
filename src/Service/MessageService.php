<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/27
 * Time: 3:39
 */

namespace Service;


use Logic\LoginLogic;
use Model\MatchCollectionModel;
use Model\MatchModel;
use Umeng\Message\Client;

class MessageService
{

    /**
     * 开赛推送
     * @param $match_id
     * @return bool
     */
    public function concernStartPush($match_id)
    {
        //获取所有关注的用户
        $list = MatchCollectionModel::fetchWithMatchId($match_id);

        $match = MatchModel::detail($match_id);

        $device_token = [];
        foreach ($list as $v)
        {
            if(in_array($v['openid_type'], [
                LoginLogic::PHONE_LOGIN,
                LoginLogic::WECHAT_LOGIN,
                LoginLogic::QQ_LOGIN,
                LoginLogic::WEIBO_LOGIN,
            ])){
                $device_token[] = $v['device_token'];
            }
        }

        //推送给友盟
        if(!empty($device_token)){
            $conf = config()->get("umeng");

            $data = array(
                'ticker' => '您关注的比赛开始了',
                'title' => '您关注的比赛开始了',
                'text' => "您关注的{$match['home']}vs{$match['away']}比赛开赛了",
                'device_tokens' => $device_token,
            );

            $android = new UmengService(
                $conf['android']['appkey'],
                $conf['android']['app_master_secret'],
                "android",
                false
            );

            $ios = new UmengService(
                $conf['ios']['appkey'],
                $conf['ios']['app_master_secret'],
                "ios",
                false
            );

            $extra = ["page_id" => 9, "params" => $match_id, "url"=>""];

            $result_a = $android->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text'],
                $extra
            );

            $result_i = $ios->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text'],
                $extra
            );
            return $result_i || $result_a;
        }

    }

    /**
     * 完赛推送
     * @param $match_id
     * @return bool
     */
    public function concernStopPush($match_id)
    {
        //获取所有关注的用户
        $list = MatchCollectionModel::fetchWithMatchId($match_id);

        $match = MatchModel::detail($match_id);

        $device_token = [];
        foreach ($list as $v)
        {
            if(in_array($v['openid_type'], [
                LoginLogic::PHONE_LOGIN,
                LoginLogic::WECHAT_LOGIN,
                LoginLogic::QQ_LOGIN,
                LoginLogic::WEIBO_LOGIN,
            ])){
                $device_token[] = $v['device_token'];
            }
        }

        //推送给友盟
        if(!empty($device_token)){
            $conf = config()->get("umeng");
            $data = array(
                'ticker' => '比赛结束',
                'title' => '比赛结束',
                'text' => "您关注的{$match['home']}vs{$match['away']}比赛已结束，终场比分为{$match['home_score']}:{$match['away_score']}",
                'device_tokens' => $device_token,
            );

            $android = new UmengService(
                $conf['android']['appkey'],
                $conf['android']['app_master_secret'],
                "android",
                false
            );

            $ios = new UmengService(
                $conf['ios']['appkey'],
                $conf['ios']['app_master_secret'],
                "ios",
                false
            );

            $extra = ["page_id" => 9, "params" => $match_id, "url"=>""];

            $result_a = $android->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text'],
                $extra
            );

            $result_i = $ios->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text'],
                $extra
            );
            return $result_i || $result_a;
        }
    }
}