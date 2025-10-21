<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/Database.php';
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