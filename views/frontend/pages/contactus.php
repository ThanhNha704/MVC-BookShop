<main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl">
            <span class="text-bookaza-orange">Liên Hệ</span> Với Chúng Tôi
        </h1>
        <p class="mt-4 text-xl text-gray-500">
            Hãy cho chúng tôi biết bạn cần hỗ trợ về điều gì.
        </p>
    </div>

    <div class="lg:flex lg:space-x-10">
        <!-- Thông tin cửa hàng -->
        <div class="lg:w-1/3 p-6 bg-white rounded-xl shadow-lg mb-8 lg:mb-0">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Thông Tin Cửa Hàng</h3>
            <div class="space-y-6">
                <div>
                    <p class="font-semibold text-lg text-gray-700">📍 Địa Chỉ:</p>
                    <p class="text-gray-600">123 Đường Sách, Phường Tri Thức, Quận Văn Học, TP.HCM</p>
                </div>
                <div>
                    <p class="font-semibold text-lg text-gray-700">📞 Điện Thoại:</p>
                    <p class="text-gray-600">(+84) 123 456 789</p>
                </div>
                <div>
                    <p class="font-semibold text-lg text-gray-700">📧 Email Hỗ Trợ:</p>
                    <p class="text-gray-600">support@bookaza.vn</p>
                </div>
                <div>
                    <p class="font-semibold text-lg text-gray-700">🕒 Giờ Làm Việc:</p>
                    <p class="text-gray-600">Thứ Hai - Thứ Bảy: 8:00 - 20:00</p>
                </div>
            </div>
        </div>

        <!-- Form liên hệ -->
        <div class="lg:w-2/3 p-8 bg-white rounded-xl shadow-lg">
            <h3 class="text-2xl font-bold text-gray-900 mb-6 border-b pb-2">Gửi Tin Nhắn Cho Chúng Tôi</h3>

            <form action="index.php?controller=contactus&action=sendMail" method="POST" class="space-y-6">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
            Họ và Tên <span class="text-red-500">*</span>
        </label>
        <input type="text" id="name" name="name" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-bookaza-orange focus:border-bookaza-orange sm:text-sm"
               placeholder="Nguyễn Văn A">
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Địa chỉ Email <span class="text-red-500">*</span>
        </label>
        <input type="email" id="email" name="email" required
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-bookaza-orange focus:border-bookaza-orange sm:text-sm"
               placeholder="vidu@bookaza.vn">
    </div>

    <div>
        <label for="subject" class="block text-sm font-medium text-gray-700">Chủ đề</label>
        <input type="text" id="subject" name="subject"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-bookaza-orange focus:border-bookaza-orange sm:text-sm"
               placeholder="Về đơn hàng / Hợp tác / Khác">
    </div>

    <div>
        <label for="message" class="block text-sm font-medium text-gray-700">
            Nội dung <span class="text-red-500">*</span>
        </label>
        <textarea id="message" name="message" rows="4" required
                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-bookaza-orange focus:border-bookaza-orange sm:text-sm"
                  placeholder="Chi tiết yêu cầu hỗ trợ của bạn..."></textarea>
    </div>

    <div>
        <button type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-bookaza-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-bookaza-orange transition duration-150 ease-in-out">
            Gửi Tin Nhắn
        </button>
    </div>
</form>

        </div>
    </div>
</main>
