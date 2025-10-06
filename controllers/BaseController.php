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

    protected function set($key, $value)
    {
        $this->data[$key] = $value;
    }
    protected function view($viewPath)
    {
        $this->render($viewPath);
        include './views/frontend/layouts/header.php';
        include './views/frontend/layouts/navbar.php';
        include $viewPath;
        include './views/frontend/layouts/footer.php';
    }

}