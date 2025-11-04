<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/controllers/BaseController.php';

$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'home')) . 'Controller';
$actionName = ($_REQUEST['action'] ?? 'index');

$controllerPath = './controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    include $controllerPath;
} else {
    die("Controller file '{$controllerPath}' not found.");
}

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        die("Action '{$actionName}' not found in '{$controllerName}'.");
    }
} else {
    die("Class '{$controllerName}' not found.");
}