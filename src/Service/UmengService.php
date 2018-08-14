<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/8/14
 * Time: 12:08
 */

namespace Service;


use GuzzleHttp\Client;

class UmengService
{

    protected $app_key;
    protected $app_secret;
    protected $os;
    protected $production_mode;

    public function __construct($app_key, $app_secret, $os = "android", $production_mode = true)
    {
        $this->app_key = $app_key;
        $this->app_secret = $app_secret;
        $this->os = $os;
        $this->production_mode = $production_mode;
    }

    public function sendListCast($device_tokens,$ticker,$title,$text)
    {

        if($this->os == "android"){
            $params = $this->getAndroidParams($device_tokens,$ticker,$title,$text);
        }else{
            $params = $this->getIOSParams($device_tokens,$ticker,$title,$text);
        }

        return $this->send($params);
    }

    public function send($params)
    {
        $http = new Client();

        $options['body'] = json_encode($params);
        $options['header'] = ["Content-Type" => "application/json"];

        $method = "POST";
        $url = "http://msg.umeng.com/api/send";

        $sign = $this->getSign($method, $url, $options['body']);

        $url .= "?sign={$sign}";

        var_dump($url);
        var_dump($options['body']);

        $response = $http->request("POST", $url , $options);
        $body = $response->getBody()->getContents();

        if($response->getStatusCode() != 200) {
            throw new \Exception($body);
        }

        return json_decode($body, true);
    }

    public function getSign($method, $url, $body)
    {
        return md5($method.$url.$body.$this->app_secret);
    }

    public function getAndroidParams($device_tokens,$ticker,$title,$text)
    {

        $token = implode(",",$device_tokens);
        $data = [
            "appkey" => $this->app_key,
            "timestamp" => time(),
            "type" => "listcast",
            "device_tokens" => $token,
            "payload" =>  [
                "display_type" => "notification",
                "body" => [
                    "ticker" => $ticker,
                    "title" => $title,
                    "text" => $text,
                    "play_vibrate" => "false",
                    "play_lights" => "false",
                    "after_open" => "go_app"
                ]
            ],
            "production_mode" => $this->production_mode
        ];

        return $data;
    }

    public function getIOSParams($device_tokens,$ticker,$title,$text)
    {

        $token = implode(",",$device_tokens);
        $data = [
            "appkey" => $this->app_key,
            "timestamp" => time(),
            "type" => "listcast",
            "device_tokens" => $token,
            "payload" =>  [
                "aps" => [
                    "alert" => [
                        "title" => $ticker,
                        "subtitle" => $title,
                        "body" => $text
                    ]
                ]
            ],
            "production_mode" => $this->production_mode
        ];

        return $data;
    }
}