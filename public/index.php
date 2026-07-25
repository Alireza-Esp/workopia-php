<?php

require "../helpers.php";
require basePath('Database.php');
require basePath('Router.php');

// Initiate Router obj
$router = new Router();

// Get routes
require basePath('routes.php');

// Get current URI and HTTP method
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Route the request
$router->route($uri, $method);
