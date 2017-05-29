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
            'required' => false,
            'comment' => '素材名称',
        ],

        'show_cover_pic' => [
            'type' => 'boolean',
            'default' => 0,
            'required' => true,
            'comment' => '是否显示封面，0为false，即不显示，1为true，即显示'
        ],

        'author' => [
            'type' => 'string',
            'length' => 30,
            'default' => '',
            'required' => true,
            'comment' => '作者',
        ],

        'digest' => [
            'type' => 'string',
            'length' => '300',
            'default' => '',
            'required' => true,
            'comment' => '  图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空'
        ],

        'update_time' => [
            'type' => 'integer',
            'unsigned' => true,
            'default' => 0,
            'required' => true,
            'comment' => '素材更新时间',
        ],

        'media_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => true,
            'comment' => '微信端返回的url',
        ],

        'thumb_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => true,
            'comment' => '图文消息的封面图片的地址',
        ],

        'content_source_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => true,
            'comment' => '图文消息的原文地址，即点击“阅读原文”后的URL',
        ],

        'title' => [
            'type' => 'string',
            'default' => '',
            'required' => true,
            'comment' => '图文消息的标题',
        ],

        'content' => [
            'type' => 'text',
            'length' => 20000,
            'default' => '',
            'required' => true,
            'comment' => '图文消息内容',
        ],

        'thumb_media_id' => [
            'type' => 'string',
            'length' => 100,
            'default' => '',
            'required' => true,
            'comment' => '缩略图的media_id',
        ],

        'thumb_local_url' => [
            'type' => 'string',
            'length' => 300,
            'default' => '',
            'required' => true,
            'comment' => '封面的本地地址',
        ],

    ],

    'primary' => 'id',
    'index' => [
        'material_id' => ['columns' => ['material_id'],],
        'mediaid' => ['columns' => ['media_id']],

    ],
    'comment' => '图文素材表',
];

