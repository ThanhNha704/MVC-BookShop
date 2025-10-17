<?php
// BƯỚC 1: BẮT ĐẦU SESSION (Nên là bước đầu tiên)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// BƯỚC 2: NẠP CÁC TỆP CORE VÀ BASE
require_once __DIR__ . '/core/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/controllers/BaseController.php';

// XÓA DÒNG NÀY: require __DIR__ . '/../../components/FlashSale.php'; 
// (Component phải được gọi trong file View, không phải file index.php)

// BƯỚC 3: LẤY CONTROLLER VÀ ACTION TỪ URL
$controllerName = ucfirst(strtolower($_REQUEST['controller'] ?? 'home')) . 'Controller';
$actionName = ($_REQUEST['action'] ?? 'index');

// BƯỚC 4: ĐỊNH TUYẾN VÀ NHÚNG CONTROLLER
$controllerPath = './controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    include $controllerPath;
} else {
    die("Controller file '{$controllerPath}' not found.");
}

// BƯỚC 5: KHỞI TẠO VÀ GỌI ACTION
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    
    if (method_exists($controller, $actionName)) {
        // Gọi phương thức (action), nó sẽ gọi BaseController::view() và hiển thị trang
        $controller->$actionName();
    } else {
        die("Action '{$actionName}' not found in '{$controllerName}'.");
    }
} else {
    die("Class '{$controllerName}' not found.");
}