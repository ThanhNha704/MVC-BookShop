-- User 11 (nha) đánh giá Sách 1 (Nhà Giả Kim) - 5/5
INSERT INTO comments (`user_id`, `book_id`, `content`, `rating`, `created_at`) 
VALUES (11, 1, 'Một cuốn sách tuyệt vời, rất đáng đọc!', 5, NOW());

-- User 11 (nha) đánh giá Sách 2 (Đắc Nhân Tâm) - 4/5
INSERT INTO comments (`user_id`, `book_id`, `content`, `rating`, `created_at`) 
VALUES (11, 2, 'Tuyệt phẩm về đối nhân xử thế, nên có trong tủ sách.', 4, NOW());

-- User 12 (ct) đánh giá Sách 1 (Nhà Giả Kim) - 4/5
INSERT INTO comments (`user_id`, `book_id`, `content`, `rating`, `created_at`) 
VALUES (12, 1, 'Ý nghĩa sâu sắc, giúp mình thay đổi suy nghĩ.', 4, NOW());

-- User 12 (ct) đánh giá Sách 2 (Đắc Nhân Tâm) - 5/5
INSERT INTO comments (`user_id`, `book_id`, `content`, `rating`, `created_at`) 
VALUES (12, 2, 'Cách viết dễ hiểu, áp dụng được ngay vào cuộc sống!', 5, NOW());