<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/22
 * Time: 19:28
 */

namespace Logic;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Size;
use Service\UploadService;

class UploadLogic extends BaseLogic
{
    public function uploadImage($name = "file")
    {
        $file = $_FILES[$name];

        $uploader = new UploadService();



        //将图片裁成100 * 100 的头像
        $manager = new ImageManager(array('driver' => 'imagick'));

        $image = $manager->make($file['tmp_name']);

        $width = $image->getWidth();

        $height = $image->getHeight();

        //先压缩成100 * n 的等比例图片
        if($width > $height)
        {
            $image->resize(null, 100, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        }else{
            $image->resize(100, null, function ($constraint)
            {
                $constraint->aspectRatio();
            });
        }

        //裁剪成100*100
        $image->crop(100,100);

        $response = $uploader->upload($image);

        return $response['data'];
    }
}