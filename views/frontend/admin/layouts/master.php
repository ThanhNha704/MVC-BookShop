<?php
// views/admin/layouts/master.php

// Đảm bảo session được bắt đầu (Nếu chưa được gọi ở index.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// KHÔNG CẦN kiểm tra role ở đây vì đã kiểm tra trong AdminController, 
// nhưng để dự phòng thì giữ lại.

// Lấy biến $content được truyền từ BaseController
$content = $content ?? '<h1>Trang nội dung không tồn tại.</h1>';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BookShop</title>
    <link rel="stylesheet" href="/path/to/your/tailwind.css"> 
    <style>
        /* Thiết lập chiều cao tối thiểu cho toàn màn hình */
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="flex min-h-screen">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <div class="flex-grow flex flex-col">

        <?php include __DIR__ . '/header.php'; ?>

        <main class="flex-grow p-6 bg-gray-100 overflow-y-auto">
            
            <?php include __DIR__ . '/flash_message.php'; ?>
            
            <?= $content ?>
        </main>
        
        <footer class="p-4 text-center text-gray-600 bg-white border-t text-sm">
            © 2025 BookShop Admin. Phiên bản 1.0
        </footer>
    </div>

</body>
</html>