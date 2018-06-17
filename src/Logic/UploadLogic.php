<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/22
 * Time: 19:28
 */

namespace Logic;

use Service\UploadService;

class UploadLogic extends BaseLogic
{
    public function uploadImage($name = "file")
    {
        $file = $_FILES[$name];

        $uploader = new UploadService();

        $response = $uploader->upload($file['tmp_name']);

        return $response['data'];
    }
}