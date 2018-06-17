<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/22
 * Time: 19:28
 */

namespace Logic;

class UploadLogic extends BaseLogic
{
    public function uploadImage($name = "file")
    {
        var_dump($_POST);
        $file = $_POST[$name];
    }
}