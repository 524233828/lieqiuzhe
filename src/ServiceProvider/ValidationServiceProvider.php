<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/5/24
 * Time: 16:29
 */

namespace ServiceProvider;


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
        Validator::addExtension("mobile", function($field, $value, array $parameters = []){

            if (empty($value) || 0 === preg_match('/^(13[0-9]|14(5|7)|15([0-3]|[5-9])|17(0|[6-8])|18[0-9])\d{8}$/', $value)) {
                return false;
            }
            if ('13800138000' === $value || 0 !== preg_match('/^\d{3}(0{8}|1{8}|2{8}|3{8}|4{8}|5{8}|6{8}|7{8}|8{8}|9{8})$/', $value)) {
                return false;
            }
            return true;
        });
    }
}