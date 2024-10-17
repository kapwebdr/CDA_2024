<?php
$routes = 
[
    '/'=>[
        'method'=>'GET',
        'controller'=>['Controller\Home','index'],
    ],
    '/users'=>[
        'method'=>'GET',
        'controller'=>['Controller\User','getUsers'],
    ],
    '/user/{id}'=>[
        'method'=>'GET',
        'controller'=>['Controller\User','getUser'],
    ],
    
];
