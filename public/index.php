<?php
include '../controllers/UserController.php'  ;

$userController = new UserController();
$userController->handleRequest();
?>