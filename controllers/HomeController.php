<h1>mo duoc HomeController</h1>
<?php
class HomeController extends BaseController {
    public function index() {
        // Gọi view home.php
        $this->render('home');
    }
}   