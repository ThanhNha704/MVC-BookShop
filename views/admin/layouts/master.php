<?php
// views/frontend/layouts/master.php
// File này sẽ bao quanh nội dung $content

include __DIR__ . '/header.php'; // Chứa thẻ <html>, <head>, <body> mở
include __DIR__ . '/navbar.php';

// Đây là nội dung View chính ($content được tạo từ ob_start())
echo $content;

include __DIR__ . '/footer.php'; // Chứa thẻ </body> và </html> đóng
?>