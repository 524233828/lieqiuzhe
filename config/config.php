<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

return [

    "wxapp"=>[
        "app_id" => "wxc0088367df4dd2b1",
        "app_secret" => "043cd4f44a6f5357b86ea103c6c2ec8f"
    ],

    "zuqiubisai1"=>[
        "app_id" => "wxe0a4c5b9f85f9cf5",
        "app_secret" => "87d22ff09ec5a09748dc6249f354c0bd"
    ],

    "shijiebei"=>[
        "app_id" => "wx0c2d51b7b4337c3a",
        "app_secret" => "8161f0a1499bed47dae73ac599e03782"
    ],

    "sms" => [
        "accessKeyId" => "LTAIxmGYETDXXOwG",
        "accessKeySecret" => "YUZ96Jt0ghzoWfaFJGa4cHWAaD3ZNL",
        "signName" => "阿里云短信测试专用"
    ],

    "socialite" => [

        "wechat"=>[
            "client_id" => "wx45d4e558ae0284a4",
            "client_secret" => "789d683ce4306dca61b3b768421f9a5b",
            "redirect" => ""
        ],
        "qq" => [
            "client_id" => "1105851211",
            "client_secret" => "VcGLIqlKPlbwmGHF",
            "redirect" => ""
        ],
    ],

    "payment" => [
        // 微信支付参数
        'wechat' => [
            'debug'      => false, // 沙箱模式
            'app_id'     => 'wx45d4e558ae0284a4', // 应用ID
            'mch_id'     => '1493544892', // 微信支付商户号
            'mch_key'    => 'yaoyuan123*', // 微信支付密钥
            'ssl_cer'    => '', // 微信证书 cert 文件
            'ssl_key'    => '', // 微信证书 key 文件
            'notify_url' => '', // 支付通知URL
            'cache_path' => '',// 缓存目录配置（沙箱模式需要用到）
        ],
        // 支付宝支付参数
        'alipay' => [
            'debug'       => false, // 沙箱模式
            'app_id'      => '2018052160205069', // 应用ID
            'public_key'  => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB', // 支付宝公钥(1行填写)
            'private_key' => "MIIEowIBAAKCAQEAxM/OIshZ4OnmUu4v98gpU9g8HPg56DqWMaDvcgzVsFn3zgMIo29Tm6bTQvjKWBG67ZG+atTGgJ+hd1p7zI4HZOqmRIvFFWEQ4KdwJrBClO/+Eazv+bGJSILgxu/p8DfwzsqSWqucJtU6nU9ViQS6LurvId3ue2p/UWJQP7ViewuMRzGbXL1PR45ggtOGJZYMu+34i7uJuHHpb/2SQsfDL0ONNQce3TTcL9rb2T0xtNSC54cFOphppnLyJIyOryJkUnHfL4gpNoHUTnwLdIKMY0bpPqqJm13/PN7HxmnnUMr+kr4KnrHk7S+3QQdk8AII+5RhRsK5KxSVZEJOxyoqRQIDAQABAoIBABKOUS4gW7ED/I5HHMis25CnK4vDr5oApBaLyOek5CTbZqzKxm66WVSslvCSimlhSpGJkz15UDniUxPwuQlhPrs6EHEYCH3qh+/WeZF8PtrSAc9i0cFmBr6KcGzxQ8o9S/wDR2c7FN7obb1VNIhVpMQ1rFQyG4ERWm2u6kgHbwCQvMwdBKUz3vuD74wCSx+W8EVAqfDI4ij5CxCgj9QwAT+qRVIFPFBpAg3OpR1nLqhJUhHWIQQmn5+myJ4Ac8lp4am0KbFc+/ZHei19jYTi8yvC3Kb6JSXm9SS/2JDreqCaA5Y70zwNeGM/AfnFP6fBjZzUxGzDRDDn+ubymhn5xIUCgYEA6oHvkrfNgS+3LHRlLWSRTFizulmrdOTuyI0ugqCDKtnY9IaT8vvSEBKrpLe5jN4PTAD4v1oeW89kKn6Iy87cFTP8GXYN38kMkptP2lhBSjsuijTcXhpbou57p0wJ38F+IEKgUcG2KIeK75FUvPXxaWjUt7cq2LxvYe6wAOVPtyMCgYEA1tlxO7gZBVh50OpEyUjTXzmNDgjYu3dcreNc5pYiumdAYRHHDftutcpghWQ0jN9jjDMnHeJ6+ZGvmj/YBOwOAGr9W8O5dIEpK8jPgtW4c7g7J+aZAlyBIu/XyFLD+qFLyI01kRhIZ+PobVXTU8oe/AfQ4wtxOUs3C3tPOdjo43cCgYEAvL7QIHqngO7ys2kLdjmXaKeMINTDV1Zbijd309N1PywPnuAifFOKgz1DwVPOmD6yeS3fB8R04thNepZVbBSWtsocgjGugQvEfstavhaClkiD8OES7Pqx/rWL+N8Oo3WNGlIFz0fmYUCW5rNGTMB3CaxCaYuXhNJFo8EFD/OA8ZkCgYA8QvUduP9bnntce7kbdA/Fb9D+lMClpE8cft851fabrgZCs8fPRizBVKhKAdczhBzZ4CcinLm9cn18mFew2bz7pQa3TGiiIvA3VbXOjr+TxaLiCC32mZenAvrVN1G85Kzq7aCOt+7nJOe2cxI5OEIEkvSmGjmBxnUEBWwtX4fC9QKBgHliVeqT39Pe2KVNpJJBrn1rwApz8/7Mq1GLb+eLQ+EvyISvQd7XaDqwFHeCn/7QUq3R/HKKMTY7kwDfPPGRwAtpVSDNMBTKlLl92DI/s5ZzIwnAJLPfbZJUDY5kGsvZQAugspFjtJen6LHu2Hufy77bvC1Ind9MOLe8XDK2uGpd",
            'notify_url'  => 'https://api-qiuwen.ym8800.com/buy/notify', // 支付通知URL
        ]
    ],

    "umeng" => [
        'appkey'            => '534ce13b56240b219b00106d', //按照友盟后台填写
        'app_master_secret' => 'j96jq7s8vfdt9ldhsrivjxkzbwezi4uv', //按照友盟后台填写
    ],

    //会员设置
    'user' => [
       0 => 3,
       1 => 3,
       2 => 6,
       3 => 10,
       4 => 15,
       5 => 20,
    ],

    'analyst' => [
        0 => 3,
        1 => 3,
        2 => 6,
        3 => 10,
        4 => 15,
        5 => 20,
    ]
];