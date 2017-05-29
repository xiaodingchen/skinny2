<?php
/**
 * database.php
 *
 * */
return [
    'default' => 'default',
    'connections' => [
        'default' => [
            'driver'    => 'mysqli',
            'host'      => '127.0.0.1',
            'dbname'  => '',
            'user'  => '',
            'password'  => '',
            'charset'   => 'utf8',
         ]
    ],
    'type_define' => [
        /* 'time'=>array(
            'doctrineType' => ['integer', ['unsigned' => true]],
        ),
        'password'=>array(
            'doctrineType' => ['string', ['length' => 32]],
        ), */
    ],

    
];
