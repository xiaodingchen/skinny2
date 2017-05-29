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

        'material_id' => [
            'type' => 'integer',
            'unsigned' => true,
            'default' => 0,
            'required' => true,
            'comment' => '素材id,同material表中的自增id关联',
        ],

        'media_id' => [
            'type' => 'string',
            'length' => 100,
            'default' => '',
            'required' => false,
            'comment' => '微信返回的media_id',
        ],

        'name' => [
            'type' => 'string',
            'length' => 255,
            'default' => '',
            'required' => true,
            'comment' => '素材名称,一般是文件的名称加后缀',
        ],

        'update_time' => [
            'type' => 'integer',
            'unsigned' => true,
            'default' => 0,
            'required' => false,
            'comment' => '素材更新时间',
        ],

        'media_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => false,
            'comment' => '微信端返回的url',
        ],

        'local_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => true,
            'comment' => '本地存储的url',
        ],

    ],

    'primary' => 'id',
    'index' => [
        'material_id' => ['columns' => ['material_id'], 'prefix' => 'unique'],
        'mediaid' => ['columns' => ['media_id']],
        
    ],
    'comment' => '多媒体素材表(视频除外)',
];

