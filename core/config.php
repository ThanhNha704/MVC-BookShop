<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri_parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
$project_name = $uri_parts[0];

define('BASE_URL', $protocol . $host . '/' . $project_name . '/');

// $assets = array(
//     'logo_icon' => BASE_URL . 'public/assets/frontend/logo_icon.svg',
//     'search_icon' => BASE_URL . 'public/assets/frontend/search_icon.svg',
//     'menu_icon' => BASE_URL . 'public/assets/frontend/menu_icon.svg',
//     'cart_icon' => BASE_URL . 'public/assets/frontend/cart_icon.svg',
//     'user_icon' => BASE_URL . 'public/assets/frontend/user_icon.svg',
//     'support_icon' => BASE_URL . 'public/assets/frontend/support_icon.svg',
//     'tracking_icon' => BASE_URL . 'public/assets/frontend/tracking_icon.svg',
//     // Thêm các tài nguyên khác nếu cần
// );