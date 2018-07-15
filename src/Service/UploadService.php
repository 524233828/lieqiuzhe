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

    const DOMAIN = "http://127.0.0.1:8002";

    public function __construct()
    {
        $this->http = new Client();

        $this->uri = new Uri(self::DOMAIN);
    }

    public function upload($file_path)
    {
        $uri = clone $this->uri;

        $uri->withPath("/admin/common/upload_image");

        $data = [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file_path, 'r')
                ],
            ]
        ];

        $response = $this->http->request("POST", (string)$uri, $data);

        return json_decode($response->getBody(),true);
    }

    public function uploadVideo($file_path, $media_time)
    {
        $uri = clone $this->uri;

        $uri->withPath("/admin/common/upload_video");

        $data = [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => fopen($file_path, 'r')
                ],
            ],
            "media_time" => $media_time,
        ];

        $response = $this->http->request("POST", (string)$uri, $data);

        return json_decode($response->getBody(),true);
    }

}