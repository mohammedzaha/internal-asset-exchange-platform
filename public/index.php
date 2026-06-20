<?php

session_start();

define('BASE_URL', '/internal-asset-exchange-platform');

require __DIR__ . '/../helpers/SessionHelper.php';
require __DIR__ . '/../helpers/AuthHelper.php';
require __DIR__ . '/../helpers/SecurityHelper.php';

require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Model.php';

require __DIR__ . '/../helpers/MailHelper.php';

// Autoload controllers/models
spl_autoload_register(function ($class) {
    foreach (['../controllers/', '../models/'] as $dir) {
        $file = __DIR__ . '/' . $dir . $class . '.php';
        if (file_exists($file)) require $file;
    }
});

$router = new Router();

$router->add('GET', '/create-company', ['AuthController', 'showCreateCompany']);
$router->add('POST', '/create-company', ['AuthController', 'createCompany']);
$router->add('GET', '/login', ['AuthController', 'showLogin']);
$router->add('POST', '/login', ['AuthController', 'login']);
$router->add('GET', '/logout', ['AuthController', 'logout']);
$router->add('GET', '/dashboard', ['DashboardController', 'index']);
$router->add('GET', '/', ['AuthController', 'showLogin']);

$router->add('GET', '/assets', ['AssetController', 'index']);
$router->add('GET', '/assets/create', ['AssetController', 'create']);
$router->add('POST', '/assets/store', ['AssetController', 'store']);
$router->add('GET', '/assets/show/{id}', ['AssetController', 'show']);
$router->add('POST', '/assets/delete/{id}', ['AssetController', 'destroy']);

$router->add('POST', '/transfers/request/{id}', ['TransferController', 'request']);
$router->add('GET', '/transfers/pending', ['TransferController', 'pending']);
$router->add('POST', '/transfers/approve/{id}', ['TransferController', 'approve']);
$router->add('POST', '/transfers/reject/{id}', ['TransferController', 'reject']);
$router->add('GET', '/transfers/history', ['TransferController', 'history']);

$router->add('GET', '/members/create', ['MemberController', 'create']);
$router->add('POST', '/members/store', ['MemberController', 'store']);
$router->add('GET', '/change-password', ['UserController', 'showChangePassword']);
$router->add('POST', '/change-password', ['UserController', 'changePassword']);

$uri = $_GET['url'] ?? '';
$router->dispatch('/' . $uri, $_SERVER['REQUEST_METHOD']);

?>