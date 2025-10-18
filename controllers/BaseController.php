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
        // 1. Định nghĩa đường dẫn tuyệt đối cho thư mục VIEW
        // Giả sử tệp BaseController.php nằm trong thư mục controllers/
        // Và thư mục VIEWS nằm ở ../views
        $viewBaseDir = dirname(__DIR__) . '/views/frontend/';

        $viewFile = $viewBaseDir . $viewPath . '.php';

        // 2. Kiểm tra và include Header/Navbar
        // Sử dụng $viewBaseDir để đảm bảo đường dẫn chính xác
        include './views/frontend/layouts/header.php';
        include './views/frontend/layouts/navbar.php';

        // 3. Include View chính
        if (file_exists($viewFile)) {
            // Truyền dữ liệu vào view
            extract($data);
            include $viewFile;
        } else {
            die("View file '{$viewPath}' not found.");
        }

        // 4. Include Footer
        include './views/frontend/layouts/footer.php';
    }

    // Giả định trong BaseController.php
    protected function redirect($url)
    {
        header("Location: " . $url);
        exit(); // RẤT QUAN TRỌNG
    }
}
