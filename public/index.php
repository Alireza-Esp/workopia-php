<?php
require __DIR__ . "/../vendor/autoload.php";
require "../helpers.php";

use Framework\Router;

// $autoloader = function (string $class) {
//     $path = basePath('Framework/' . $class . '.php');
//     if (file_exists($path)) {
//         require $path;
//     }
// };
// spl_autoload_register($autoloader);

// Initiate Router obj
$router = new Router();

// Get routes
require basePath('routes.php');

// Get current URI and HTTP method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);
