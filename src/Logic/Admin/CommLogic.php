<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/7/8
 * Time: 15:43
 */

namespace Logic\Admin;


use Logic\BaseLogic;
use Service\UploadService;

class CommLogic extends BaseLogic
{

    public function uploadImage($name = "file")
    {
        //存成文件以后再上传，上传完毕删除
        $file = $_FILES[$name];

        move_uploaded_file($_FILES["file"]["tmp_name"],"/tmp/".$file['name']);

        $uploader = new UploadService();

        $response = $uploader->upload("/tmp/".$file['name']);

        unlink("/tmp/".$file['name']);

        return $response['data']['path'];
    }
}