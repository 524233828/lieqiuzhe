<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/6/16
 * Time: 23:25
 */

namespace Service;


use GuzzleHttp\Client;

class UploadService
{
    private $http;

    private $uri;

    const DOMAIN = "http://127.0.0.1:8001";

    public function __construct()
    {
        $this->http = new Client();

        $this->uri = new Uri(self::DOMAIN);
    }

    public function upload($data)
    {
        $uri = clone $this->uri;

        $uri->withPath("/tao_hua");

        $uri->withQuery($data);

        $response = $this->http->request("GET", (string)$uri);

        return json_decode($response->getBody(),true);
    }

}