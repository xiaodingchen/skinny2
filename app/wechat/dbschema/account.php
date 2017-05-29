<?php 
return [
    'columns' => [

        'id' => [
            'type' => 'integer',
            'autoincrement' => true,
            'required' => true,
            'unsigned' => true,
            'comment' => '自增id',
        ],

        'appid' => [
            'type' => 'string',
            'length' => 32,
            'default' => '',
            'required' => true,
            'comment' => '微信公众号的appid'
        ],

        'appsecret' => [
            'type' => 'string',
            'length' => 60,
            'default' => '',
            'required' => true,
            'comment' => '微信公众号的appsecret'
        ],

        'token' => [
            'type' => 'string',
            'length' => 32,
            'default' => '',
            'required' => true,
            'comment' => '公众号验证token'
        ],

        'encodingaeskey' => [
            'type' => 'string',
            'length' => 43,
            'default' => '',
            'required' => true,
            'comment' => '微信公众安全模式的加密密钥'
        ],

        'original' => [
            'type' => 'string',
            'length' => 30,
            'default' => '',
            'required' => true,
            'comment' => '微信公众号的原始id'
        ],

        'wechatcode' => [
            'type' => 'string',
            'length' => 50,
            'default' => '',
            'required' => true,
            'comment' => '公众号的微信号',
        ],

        'name' => [
            'type' => 'string',
            'default' => '',
            'required' => true,
            'comment' => '微信公众号名称'
        ],

        'description' => [
            'type' => 'text',
            'default' => '',
            'required' => true,
            'comment' => '公众号描述'
        ],

        'avatar' => [
            'type' => 'text',
            'length' => 1000,
            'default' => '',
            'required' => false,
            'comment' => '公众号头像地址',
        ],

        'qrcode' => [
            'type' => 'text',
            'length' => 1000,
            'default' => '',
            'required' => false,
            'comment' => '公众号二维码'
        ],

        'status' => [
            'type' => 'boolean',
            'default' => 2,
            'required' => false,
            'comment' => '公众号状态：1未授权，2已授权'
        ],

        'mode' => [
            'type' => [ 'normal' => '普通授权', 'authorizer' => '开放平台授权'],
            'default' => 'normal',
            'required' => true,
            'comment' => '授权模式'
        ],

        'created' => [
            'type' => 'integer',
            'required' => true,
            'unsigned' => true,
            'comment' => '添加时间',
        ],

        'updated' => [
            'type' => 'integer',
            'required' => true,
            'unsigned' => true,
            'comment' => '更改时间',
        ],
    ],

    'primary' => 'id',
    'index' => [
        'appid' => ['columns' => ['appid'], 'prefix' => 'unique'],
        'secret' => ['columns' => ['appsecret'], 'prefix' => 'unique'],
    ],
    'comment' => '公众账号表',
];

