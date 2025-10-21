<?php
// views/admin/layouts/flash_message.php

// Đảm bảo session được bắt đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Thông báo LỖI (error)
if (isset($_SESSION['error'])):
?>
    <div id="flash-error" class="flash-message fixed top-20 right-5 z-50 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 border border-red-400 flex items-center shadow-lg" role="alert">
        <span class="font-medium mr-2">Lỗi:</span> 
        <div class="flex-grow"><?= htmlspecialchars($_SESSION['error']); ?></div>
        
        <button type="button" class="close-button ml-4 text-red-900 hover:text-red-600 focus:outline-none transition-colors duration-150" aria-label="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
<?php
    unset($_SESSION['error']);
endif;

// 2. Thông báo THÀNH CÔNG (success)
if (isset($_SESSION['success'])):
?>
    <div id="flash-success" class="flash-message fixed top-20 right-5 z-50 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 border border-green-400 flex items-center shadow-lg" role="alert">
        <span class="font-medium mr-2">Thành công:</span> 
        <div class="flex-grow"><?= htmlspecialchars($_SESSION['success']); ?></div>
        
        <button type="button" class="close-button ml-4 text-green-900 hover:text-green-600 focus:outline-none transition-colors duration-150" aria-label="Close">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
<?php
    unset($_SESSION['success']);
endif;
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const flashMessages = document.querySelectorAll('.flash-message');
        const DURATION = 4000; // 4 giây

        function hideElement(element) {
            if (element) {
                element.style.transition = 'opacity 0.5s ease-out';
                element.style.opacity = '0'; 
                setTimeout(() => {
                    element.style.display = 'none';
                }, 500); 
            }
        }

        flashMessages.forEach(message => {
            let timeoutId;
            
            // 1. Tự động tắt sau 4s
            timeoutId = setTimeout(() => {
                hideElement(message);
            }, DURATION);

            // 2. Xử lý nút tắt thủ công
            const closeButton = message.querySelector('.close-button');
            if (closeButton) {
                closeButton.addEventListener('click', () => {
                    clearTimeout(timeoutId); 
                    hideElement(message);
                });
            }
        });
    });
</script>