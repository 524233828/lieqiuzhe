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

    "wechat_open"=>[
        "app_id" => "wx45d4e558ae0284a4",
        "app_secret" => "789d683ce4306dca61b3b768421f9a5b"
    ],

    "sms" => [
        "accessKeyId" => "",
        "accessKeySecret" => "",
        "signName" => "阿里云短信测试专用"
    ],

    "socialite" => [

        "wechat"=>[
            "client_id" => "wx45d4e558ae0284a4",
            "client_secret" => "789d683ce4306dca61b3b768421f9a5b"
        ]

    ],

    "payment" => [
        // 微信支付参数
        'wechat' => [
            'debug'      => false, // 沙箱模式
            'app_id'     => '', // 应用ID
            'mch_id'     => '', // 微信支付商户号
            'mch_key'    => '', // 微信支付密钥
            'ssl_cer'    => '', // 微信证书 cert 文件
            'ssl_key'    => '', // 微信证书 key 文件
            'notify_url' => '', // 支付通知URL
            'cache_path' => '',// 缓存目录配置（沙箱模式需要用到）
        ],
        // 支付宝支付参数
        'alipay' => [
            'debug'       => false, // 沙箱模式
            'app_id'      => '', // 应用ID
            'public_key'  => '', // 支付宝公钥(1行填写)
            'private_key' => '', // 支付宝私钥(1行填写)
            'notify_url'  => '', // 支付通知URL
        ]
    ]
];