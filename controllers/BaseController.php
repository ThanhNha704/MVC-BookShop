<?php
class BaseController
{
    protected $data = []; // Dữ liệu truyền từ Controller đến View

    // Phương thức để nạp Model
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

    // Phương thức để nạp View
    protected function view($viewPath, array $data = [])
    {
        $viewPath = './views/frontend/layouts/' . $viewPath . '.php';
        include './views/frontend/layouts/header.php';
        include './views/frontend/layouts/navbar.php';
        include $viewPath;
        include './views/frontend/layouts/footer.php';
    }
}