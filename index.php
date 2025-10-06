<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/core/config.php';

require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/controllers/BaseController.php';


// Lấy Controller và Action từ URL (Mặc định là home và index)
$controllerName = ucfirst(strtolower(string: $_REQUEST['controller'] ?? 'home')) . 'Controller';
$actionName = ($_REQUEST['action'] ?? 'index');

echo'------'. $controllerName .'-----'. $actionName .'-----';
// 3. Nhúng Controller được định tuyến (Sử dụng __DIR__ và tên thư mục chữ thường)
include './controllers/' . $controllerName . '.php';

$controllerObj = new $controllerName;

$controllerObj->$actionName();