<?php
require_once __DIR__.'/../config/config.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use($routes) {
    
    foreach($routes as $uri=>$route)
    {
        $r->addRoute($route['method'], $uri, $route['controller']);
    }
    // $r->addRoute('GET', '/', 'Home');
    // $r->addRoute('POST', '/toto/{titi}/{tata}/rrrr', 'Home');
    // $r->addRoute('GET', '/users', 'get_all_users_handler');
    // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri        = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404 page not found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler    = $routeInfo[1];
        $vars       = $routeInfo[2];
        // ... call $handler with $vars
        list($controller, $method) = $handler;
        //require_once DIR_CONTROLLER.$controller.'.php';
        $ObjectController = new $controller();
        // $ObjectController->$method($vars);
        call_user_func([$ObjectController,$method], $vars);
        break;
}