<?php
class BaseController
{
    protected $data = []; // Dữ liệu truyền từ Controller đến View

    // Phương thức để nạp View
    protected function render($viewPath)
    {
        $viewPath = './views/frontend/layouts/' . $viewPath . '.php';
        // Kiểm tra nếu file View tồn tại
        if (file_exists($viewPath)) {
            // Truyền dữ liệu đến View
            extract($this->data);
            include $viewPath;
        } else {
            die("View file '{$viewPath}' not found.");
        }
    }

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
    protected function view($viewPath)
    {
        $this->render($viewPath);
        include './views/frontend/layouts/header.php';
        include './views/frontend/layouts/navbar.php';
        include $viewPath;
        include './views/frontend/layouts/footer.php';
    }

}