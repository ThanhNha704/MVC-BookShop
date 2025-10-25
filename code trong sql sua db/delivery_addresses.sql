-- Bảng delivery_addresses (Địa chỉ Giao hàng)
-- Cho phép 1 users.id có N địa chỉ.

CREATE TABLE delivery_addresses (
    id INT(11) NOT NULL AUTO_INCREMENT COMMENT 'ID Địa chỉ',
    user_id INT(11) NOT NULL COMMENT 'Khóa ngoại tới bảng users.id (Tài khoản sở hữu địa chỉ này)',
    
    recipient_full_name VARCHAR(200) NOT NULL COMMENT 'Họ tên người nhận hàng',
    phone_number VARCHAR(20) NOT NULL COMMENT 'Số điện thoại nhận hàng',
    address_line VARCHAR(255) NOT NULL COMMENT 'Địa chỉ chi tiết (Số nhà, đường)',
    ward VARCHAR(100) NULL COMMENT 'Phường/Xã',
    district VARCHAR(100) NULL COMMENT 'Quận/Huyện',
    city VARCHAR(100) NOT NULL COMMENT 'Tỉnh/Thành phố',
    
    is_default BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Địa chỉ mặc định (TRUE) hay không (FALSE)',
    
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id),
    
    -- Liên kết Ràng buộc Khóa ngoại (Foreign Key)
    FOREIGN KEY (user_id) REFERENCES users(id) 
        ON DELETE CASCADE     -- Nếu tài khoản users bị xóa, tất cả địa chỉ của họ cũng bị xóa.
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách Địa chỉ Nhận hàng của người dùng';