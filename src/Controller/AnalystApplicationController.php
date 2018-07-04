<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/4
 * Time: 23:14
 */

namespace Controller;


use FastD\Http\ServerRequest;
use Logic\AnalystApplicationLogic;

class AnalystApplicationController extends BaseController
{

    public function addAnalystApplication(ServerRequest $request)
    {
        $validate = validator($request, [
            "nickname" => "required|range:1,32",
            "sex" => "required|in:男,女",
            "tag" => "required|range:1,5",
            "ball_year" => "required|in:1-5年,5-10年,10-20年,20-30年,30年以上,其他",
            "league" => "required|multi_in:英超,西甲,德甲,法甲,意甲,其他",
            "skill" => "required|in:保本盈利,冷门,其他",
            "good_at" => "required|multi_in:欧赔,精彩赔率,亚盘,交锋数据,球队近绩,球员数据,控球率,失球率,球队打法,天气,赛前信息,其他",
            "intro" => "required"
        ]);

        $data = $validate->data();

        //防止XSS攻击
        $config = \HTMLPurifier_Config::createDefault();

        $purifier = new \HTMLPurifier($config);

        $data['intro'] = $purifier->purify($data['intro']);

        return $this->response(AnalystApplicationLogic::getInstance()->addAnalystApplication($data));
    }

}