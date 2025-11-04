<?php
class BaseController
{
    protected $data = [];
    protected $model;

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

    // Phương thức nạp View
    protected function view($viewPath, array $data = [], $isAdminLayout = false)
    {
        extract($data);

        $viewsBaseDir = dirname(__DIR__) . '/views/';
        $viewFile = $viewsBaseDir . $viewPath . '.php';
        if ($isAdminLayout) {
            $layoutFile = $viewsBaseDir . 'admin/';
            include $layoutFile . '/header.php';
            include $layoutFile . '/sidebar.php';
            if (!file_exists($layoutFile . $viewPath . '.php')) {
                echo $layoutFile . $viewPath . '.php';
            }
            include $layoutFile . $viewPath . '.php';
            echo '</div>';
            echo '</body>';
            echo '</html>';

            // include $layoutFile . '/footer.php';
        } else {
            $layoutFile = $viewsBaseDir . 'user/';
            include $layoutFile . '/header.php';
            include $layoutFile . '/navbar.php';
            include $layoutFile . $viewPath . '.php';
            include $layoutFile . '/footer.php';
        }
    }

    protected function redirect($url)
    {
        echo header("Location: " . $url);
        exit();
    }
}