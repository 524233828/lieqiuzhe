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
            "client_secret" => "789d683ce4306dca61b3b768421f9a5b"
        ]

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
            'app_id'      => '2088821648121441', // 应用ID
            'public_key'  => 'MIgGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRAFljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQEB/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5KsiNG9zpgmLCUYuLkxpLQIDAQAB', // 支付宝公钥(1行填写)
            'private_key' => 'MIICWwIBAAKBgQDYDNrZEPOsXdLecCSwKeYDe6VbnBIVzBCWXnCW2y1ZcNVVGoJiW3PtrjghYjxROrOk300jQ7SRXLdeghz3x1vt+az2j/Roy57mC9fCynWQdVcUs4UjLBDWjra8kcjzGYyNYfhPYheTC1nGdhn08DHX0d/6B2o/BxMYTcxVDAzufQIDAQABAoGAEUElkURrUY7EsoMeSvttpUWQtTpHr3n2sSulrkae3o/GWd+eHiDTp13Mmc3wp0Qa6MX0sSZNG3beJiwaCCfhzYANK7iT6aTFClFtDhAr4QRmdFs86vHgJU2HvUu9WxbHywMCI2c8z+DS36sGfWpREN6HQObh5OUUjVwK3liAK7kCQQD44tyVo4EOL9IvtkLIR4hqh4korsoxT3tBOA1iCv3QchRFjAuCO67UimBtTqXP9RhnaYBtuI1p+yNA2eG+CNRzAkEA3jm6OPCjOzMPPUx4BmOUZy4zIFg/BJT8VV4Rt/nh6P/HIjxo+r/cHNm+X1veTDvPL19Rrq/YmNEDyuf25/ZlTwJAQSveiQyEebuJ9VZrjFg233ZYMx/58AmZA83yqy6nodKNflyakuKf+CW39Ed44ciTOFkG+TQvS4YoiA3Fr+ZOAwJAU02CgEu8dLmcMddTetmjTNZlte8+mEIdIQclTzjttYEELdJFbBG1ul7pXSe7+gnFjbWGkhw67nYTnOE9jhCwQwJAQ1H3BPkq4+0ltFcpk782+xc8GEJ0oy47tZuRzpsi1eZp6JSrglwQXlx07UL6IUTWjmOeiHJLwl8uyyuaYrPZ3Q==', // 支付宝私钥(1行填写)
            'notify_url'  => '', // 支付通知URL
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