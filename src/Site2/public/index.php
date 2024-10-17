<?php
require_once __DIR__.'/../config/config.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use($routes) {
    foreach($routes as $uri=>$route)
    {
        $r->addRoute($route['method'], $uri, $route['controller']);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        \Controller\Error::HttpError('404');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        \Controller\Error::HttpError('405');
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($controller, $method) = $handler;
        $ObjectController = new $controller();
        call_user_func([$ObjectController,$method], $vars);
        break;
}
