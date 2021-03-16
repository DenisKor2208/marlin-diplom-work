<?php

if( !session_id() ) @session_start();

require '../vendor/autoload.php';

use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use DI\ContainerBuilder;
use League\Plates\Engine;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Engine::class => function() {
        return new Engine('../app/views');
    },

    QueryFactory::class => function() {
        return new QueryFactory('mysql');
    },

    PDO::class => function() {
        $driver = "mysql";
        $host = "localhost";
        $database_name = "marlin_exam_three";
        $charset = "utf8";
        $username = "root";
        $password = "root";

        return new PDO("$driver:host=$host;dbname=$database_name;charset=$charset", $username, $password);
    },

    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    },
]); //указываем исключения из правил
$container = $containerBuilder->build();

$templates = new League\Plates\Engine('../app/views');

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/[{num:\d+}]', ['App\controller\UserController', 'pageUsers']);
    $r->addRoute('GET', '/page-users[/{num:\d+}]', ['App\controller\UserController', 'pageUsers']);
    $r->addRoute('GET', '/page-all-users', ['App\controller\UserController', 'pageAllUsers']);

    $r->addRoute('GET', '/register', ['App\controller\RegisterController', 'showRegistration']);
    $r->addRoute('POST', '/register', ['App\controller\RegisterController', 'postRegistration']);

    $r->addRoute('GET', '/login', ['App\controller\AuthController', 'showLogin']);
    $r->addRoute('POST', '/login', ['App\controller\AuthController', 'postLogin']);
    $r->addRoute('GET', '/logout', ['App\controller\AuthController', 'logOut']);

    $r->addRoute('GET', '/page-edit/{id:\d+}', ['App\controller\UserController', 'showEdit']);
    $r->addRoute('POST', '/page-edit', ['App\controller\UserController', 'postEdit']);

    $r->addRoute('GET', '/page-status/{id:\d+}', ['App\controller\UserController', 'showStatus']);
    $r->addRoute('POST', '/page-status', ['App\controller\UserController', 'postStatus']);

    $r->addRoute('GET', '/page-profile/{id:\d+}', ['App\controller\UserController', 'showProfile']);

    $r->addRoute('GET', '/create-user', ['App\controller\RegisterController', 'showCreateUser']);
    $r->addRoute('POST', '/create-user', ['App\controller\RegisterController', 'postCreateUser']);

    $r->addRoute('GET', '/page-security/{id:\d+}', ['App\controller\UserController', 'showSecurity']);
    $r->addRoute('POST', '/page-security-email', ['App\controller\UserController', 'postSecurityEmail']);
    $r->addRoute('POST', '/page-security-password', ['App\controller\UserController', 'postSecurityPassword']);

    $r->addRoute('GET', '/page-media/{id:\d+}', ['App\controller\UserController', 'showMedia']);
    $r->addRoute('POST', '/page-media', ['App\controller\UserController', 'postMedia']);

    $r->addRoute('GET', '/delete-user/{id:\d+}', ['App\controller\RegisterController', 'deleteUser']);

    $r->addRoute('GET', '/page-social/{id:\d+}', ['App\controller\UserController', 'showSocial']);
    $r->addRoute('POST', '/page-social', ['App\controller\UserController', 'postSocial']);

    $r->addRoute('GET', '/appoint-admin', ['App\controller\HelpController', 'appointAdmin']);
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
        echo $templates->render('error/404');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo $templates->render('error/405');
        break;
    case FastRoute\Dispatcher::FOUND:

        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        $container->call($handler, [$vars]);

        break;
}
