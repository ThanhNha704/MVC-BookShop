var swiper = new Swiper('.swiper-container', {
    // Thêm các tùy chọn này để theo dõi thay đổi
    observer: true,
    observeParents: true,
    slidesPerView: 1, // Đảm bảo chỉ có một slide hiện tại
    spaceBetween: 10,
    loop: true,
    autoplay: {
        delay: 5500,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});