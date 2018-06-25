<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/12
 * Time: 17:19
 */

namespace ServiceProvider;

use EasyWeChat\Foundation\Application;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;

class WechatServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return
     */
    public function register(Container $container)
    {
        $wechat = include app()->getPath().'/config/wechat.php';
        $container->add("wechat",new Application($wechat));
    }
}