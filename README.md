# message-notice

## 功能

* 监控发送应用异常
* 支持多种通道(钉钉群机器人、飞书群机器人、企业微信群机器人)
* 支持扩展自定义通道

## 安装

```bash
composer require openphp/message-notice -vvv
```

## 使用

```php
<?php


use Openphp\MessageNotice\MessageManager;

require 'vendor/autoload.php';
$config  = [
    // 钉钉群机器人
    'dingtalk' => [
        'token'    => '',
        'secret'   => '',
        'pipeline' => [
            'info'  => [
                'token'  => '',
                'secret' => '',
            ],
            'error' => [
                'token'  => '',
                'secret' => '',
            ]
        ]
    ],
    // 飞书群机器人
    'feishu'   => [
        'token'    => '',
        'secret'   => '',
        'pipeline' => [
            'info'  => [
                'token'  => '',
                'secret' => '',
            ],
            'error' => [
                'token'  => '',
                'secret' => '',
            ]
        ]
    ],
    // 企业微信群机器人
    'wechat'   => [
        'token'    => '',
        'pipeline' => [
            'info' => [
                'token' => ''
            ],
        ],
    ],
];
$message = new MessageManager($config);
$message->channels(['dingtalk'])->pipeline('info')->at(['手机号'])->content('发送的内容')->send();
```

