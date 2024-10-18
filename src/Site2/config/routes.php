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
    '/account' => [
        'method' => ['GET'],
        'controller' => ['Controller\Account', 'myAccount'],
    ],
    '/profile' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\Account', 'myProfile'],
    ],
    '/games/filter' => [
        'method' => 'GET',
        'controller' => ['Controller\Game', 'filterGames'],
    ],
    '/admin' => [
        'method' => 'GET',
        'controller' => ['Controller\Admin', 'index'],
    ],
    '/admin/categories' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminCategoryController', 'index'],
    ],
    '/admin/categories/create' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminCategoryController', 'create'],
    ],
    '/admin/categories/edit/{id}' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminCategoryController', 'edit'],
    ],
    '/admin/categories/delete/{id}' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminCategoryController', 'delete'],
    ],
    '/admin/users' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminUserController', 'index'],
    ],
    '/admin/users/create' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminUserController', 'create'],
    ],
    '/admin/users/edit/{id}' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminUserController', 'edit'],
    ],
    '/admin/users/delete/{id}' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminUserController', 'delete'],
    ],
    '/admin/editors' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminEditorController', 'index'],
    ],
    '/admin/editors/create' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminEditorController', 'create'],
    ],
    '/admin/editors/edit/{id}' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminEditorController', 'edit'],
    ],
    '/admin/editors/delete/{id}' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminEditorController', 'delete'],
    ],
    '/admin/games' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminGameController', 'index'],
    ],
    '/admin/games/create' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminGameController', 'create'],
    ],
    '/admin/games/edit/{id}' => [
        'method' => ['GET', 'POST'],
        'controller' => ['Controller\AdminGameController', 'edit'],
    ],
    '/admin/games/delete/{id}' => [
        'method' => 'GET',
        'controller' => ['Controller\AdminGameController', 'delete'],
    ],
    // Ajoutez d'autres routes CRUD ici...
];

