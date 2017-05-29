<?php
/**
 * cache.php
 * 
 * */

return [
    
    'drivers' => [
        'file'=>[
            'prefix' => '',
        ],
        
        'memecached'=>[
            'servers'=>[
                [
                    /*'host' => '127.0.0.1',
                    'port' => '11211',
                    'weight' => 1*/
                ]
            ],
        ],
        'redis' => [
            'servers' => [
                'host' => '10.63.0.30',
                'port' => 6379,
                'timeout' => 0,
                'passwd' => ''
            ],
            'prefix' => ''
        ],
    ],
    'default'=>'file',
    'prefix' => '',
];

