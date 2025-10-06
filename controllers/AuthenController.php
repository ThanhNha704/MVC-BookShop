<h1>Mo duoc AuthenController</h1>
<?php
class AuthenController extends BaseController
{
    protected $data = []; // Dữ liệu truyền từ Controller đến View

    // Phương thức để nạp View
    protected function render($viewPath)
    {
        // Kiểm tra nếu file View tồn tại
        if (file_exists($viewPath)) {
            // Truyền dữ liệu từ Controller đến View
            extract($this->data);
            include $viewPath;
        } else {
            die("View file '{$viewPath}' not found.");
        }
    }
    public function register()
    {
        echo"<h1>register trong AuthenController</h1>";
    }

    public function login(){
        echo"<h1>mo duoc login</h1>";
    }
}