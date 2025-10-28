<?php
class BaseController
{
    protected $data = [];

    // --- Nạp Model ---
    protected function loadModel($modelName)
    {
        $modelPath = './models/' . $modelName . '.php';
        if (file_exists($modelPath)) {
            include $modelPath;
            $this->model = new $modelName();
        } else {
            die("Model file '{$modelPath}' not found.");
        }
    }

    // --- Nạp View ---
    protected function view($viewPath, array $data = [], $isAdminLayout = false)
    {
        extract($data);

        // Thư mục gốc chứa tất cả views
        $viewsBaseDir = dirname(__DIR__) . '/views/';

        if ($isAdminLayout) {
            // --- ADMIN ---
            $layoutDir = $viewsBaseDir . 'admin/';
            $viewFile = $layoutDir . $viewPath . '.php';

            if (!file_exists($viewFile)) {
                die("Admin view '{$viewFile}' not found.");
            }

            include $layoutDir . 'header.php';
            include $layoutDir . 'sidebar.php';
            include $viewFile;
            // include $layoutDir . 'footer.php'; // tùy bạn có muốn hay không

        } else {
            // --- FRONTEND ---
            $layoutDir = $viewsBaseDir . 'frontend/';

            // Nếu $viewPath có chứa 'frontend/', loại bỏ nó để tránh trùng
            if (strpos($viewPath, 'frontend/') === 0) {
                $viewPath = str_replace('frontend/', '', $viewPath);
            }

            $viewFile = $layoutDir . $viewPath . '.php';

            if (!file_exists($viewFile)) {
                die("Frontend view '{$viewFile}' not found.");
            }

            include $layoutDir . 'header.php';
            include $layoutDir . 'navbar.php';
            include $viewFile;
            include $layoutDir . 'footer.php';
        }
    }

    // --- Chuyển hướng ---
    protected function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}
