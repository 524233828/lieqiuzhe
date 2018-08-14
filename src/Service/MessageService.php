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

        $device_token = ["39da1f74bd7389aea8708021d58d18e25548de39c7698fbbc3d495a35152411c"];

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

            $result_a = $android->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text']
            );

            $result_i = $ios->sendListCast(
                $device_token,
                $data['ticker'],
                $data['title'],
                $data['text']
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
            $umeng = new Client($conf);

            $data = array(
                'title' => '比赛结束',
                'text' => "您关注的{$match['home']}vs{$match['away']}比赛已结束，终场比分为{$match['home_score']}:{$match['away_score']}",
                'device_tokens' => $device_token,
            );

            $result = $umeng->sendNotificationToDevices($data);

            return $result;
        }
    }
}