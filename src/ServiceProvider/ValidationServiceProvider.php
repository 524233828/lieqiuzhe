<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/24
 * Time: 16:29
 */

namespace ServiceProvider;


use Constant\ErrorCode;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use Runner\Validator\Validator;

class ValidationServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed
     */
    public function register(Container $container)
    {
        //检查手机号
        Validator::addExtension("mobile", function($field, $value, array $parameters = []){

            if (empty($value) || 0 === preg_match('/^(13[0-9]|14(5|7)|15([0-3]|[5-9])|17(0|[6-8])|18[0-9])\d{8}$/', $value)) {
                return false;
            }
            if ('13800138000' === $value || 0 !== preg_match('/^\d{3}(0{8}|1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8})$/', $value)) {
                return false;
            }
            return true;
        });

        //从列表中多选
        Validator::addExtension("multi_in", function($field, $value, array $parameters = []){

            //value是逗号分隔的多个选项
            $options = explode("," , $value);

            foreach ($options as $v){
                //只要有一个值不在数组中，就不通过
                if(!in_array($v, $parameters, true)){
                    return false;
                }
            }

            return true;
        });

        //中文字数限制
        Validator::addExtension("range_ch", function($field, $value, array $parameters = []){

            $size = mb_strlen($value,"UTF-8");
            if (!isset($parameters[0])) {
                error(ErrorCode::OUT_OF_RANGE);
                return false;
            }
            if (isset($parameters[1])) {
                if ('' === $parameters[0]) {
                    if ('' === $parameters[1]) {
                        error(ErrorCode::OUT_OF_RANGE);
                    }

                    return $size <= $parameters[1];
                }
                if ('' === $parameters[1]) {
                    return $size >= $parameters[0];
                }

                return $size >= $parameters[0] && $size <= $parameters[1];
            }

            return '' === $parameters[0] ? error(ErrorCode::OUT_OF_RANGE) : ($size >= $parameters[0]);
        });
    }
}