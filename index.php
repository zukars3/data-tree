<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
    $router->addRoute('GET', '/', 'AuthorizationController@index');
    $router->addRoute('POST', '/', 'AuthorizationController@login');
    $router->addRoute('POST', '/logout', 'AuthorizationController@logout');
    $router->addRoute('GET', '/logout', 'AuthorizationController@logout');
    $router->addRoute('GET', '/register', 'AuthorizationController@registerShow');
    $router->addRoute('POST', '/register', 'AuthorizationController@register');
    $router->addRoute('GET', '/list', 'TreeController@show');
    $router->addRoute('GET', '/add/{id:\d+}', 'TreeController@add');
    $router->addRoute('POST', '/add/{id:\d+}', 'TreeController@create');
    $router->addRoute('GET', '/edit/{id:\d+}', 'TreeController@edit');
    $router->addRoute('POST', '/edit/{id:\d+}', 'TreeController@update');
    $router->addRoute('GET', '/delete/{id:\d+}', 'TreeController@delete');
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
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $params = $routeInfo[2];

        [$controller, $method] = explode('@', $handler);

        $controllerPath = '\App\Controllers\\' . $controller;
        echo (new $controllerPath)->{$method}($params);

        break;
}