<?php
// Bắt đầu Session một cách an toàn
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Cấu hình
require_once __DIR__ . '/core/config.php';

// 2. Các lớp lõi (Database, Base Models, Base Controllers)
require_once __DIR__ . '/core/Database.php';
// require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/controllers/BaseController.php';


// Lấy Controller và Action từ URL (Mặc định là home và index)
$controllerName = ucfirst(strtolower(string: ($_REQUEST['controllers']) ?? 'home')) . 'Controller';
$actionName = ($_REQUEST['action'] ?? 'index');

// 3. Nhúng Controller được định tuyến (Sử dụng __DIR__ và tên thư mục chữ thường)
include './controllers/' . $controllerName . '.php';

$controllerObj = new $controllerName;

// Kiểm tra xem phương thức (action) có tồn tại trong Controller hay không
if (!method_exists($controllerObj, $actionName)) {
    die("Action '{$actionName}' not found in Controller '{$controllerName}'");
}

$controllerObj->$actionName();

// echo $controllerName . " - " . $actionName . "<br>";