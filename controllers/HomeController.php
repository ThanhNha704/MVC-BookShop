<h1>mo duoc HomeController</h1>
<?php
class HomeController extends BaseController
{

    private $productModel;
    public function loadModel($modelName)
    {
        $this->loadModel("ProductModel");
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        // Gọi view home.php
        $this->render('home');
    }
}
