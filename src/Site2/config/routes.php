<?php
$routes = 
[
    '/'=>[
        'method'=>'GET',
        'controller'=>['Controller\Home','index'],
    ],
    '/games'=>[
        'method'=>'GET',
        'controller'=>['Controller\Game','getGames'],
    ],
    '/game/{id}'=>[
        'method'=>'GET',
        'controller'=>['Controller\Game','getGame'],
    ],
    '/register'=>[
        'method'=>['GET', 'POST'],
        'controller'=>['Controller\User','register'],
    ],
    '/login'=>[
        'method'=>['GET', 'POST'],
        'controller'=>['Controller\User','login'],
    ],
    '/logout'=>[
        'method'=>'GET',
        'controller'=>['Controller\User','logout'],
    ],
    '/search'=>[
        'method'=>'GET',
        'controller'=>['Controller\Game','search'],
    ],
    '/cart'=>[
        'method'=>'GET',
        'controller'=>['Controller\Cart','viewCart'],
    ],
    '/cart/add'=>[
        'method'=>['POST'],
        'controller'=>['Controller\Cart','addToCart'],
    ],
    '/cart/remove'=>[
        'method'=>'POST',
        'controller'=>['Controller\Cart','removeFromCart'],
    ],
    '/cart/checkout'=>[
        'method'=>['GET', 'POST'],
        'controller'=>['Controller\Cart','checkout'],
    ],
    '/cart/clear' => [
        'method' => ['POST'],
        'controller' => ['Controller\Cart', 'clearCart'],
    ],
];
