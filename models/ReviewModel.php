<?php
// models/ReviewModel.php

class ReviewModel extends BaseModel
{
    protected $table = 'reviews';

    // Tìm kiếm đánh giá theo tiêu đề sách
    public function searchReviewsByBookTitle(string $title): array
    {
        $safeTitle = $this->db->real_escape_string($title);
        $sql = "SELECT 
                    r.id, 
                    r.rating, 
                    r.content AS comment, 
                    r.created_at,
                    r.status,
                    u.username AS reviewer_name,
                    b.title AS book_title
                FROM {$this->table} r
                JOIN users u ON r.user_id = u.id
                JOIN books b ON r.book_id = b.id
                WHERE b.title LIKE '%$safeTitle%'
                ORDER BY r.created_at DESC";

        $result = $this->execute_query($sql);

        if (!$result) {
            return [];
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $result->free();
        return $data;
    }

    // Thêm đánh giá mới
    public function addReview(int $userId, int $bookId, int $rating, string $comment): bool
    {
        $sql = "INSERT INTO {$this->table} (user_id, book_id, content, rating, created_at, status)
                 VALUES ('$userId', '$bookId', '$comment', '$rating', NOW(), 'hiện')"; // Thêm trạng thái mặc định

        $this->execute_query($sql);
        $currentRating = $this->execute_query("SELECT rating FROM books WHERE id = '$bookId'");
        $currentRating = $currentRating->fetch_assoc()['rating'] ?? 0;
        if ($currentRating == 0) {
            $this->execute_query("UPDATE books SET rating = '$rating' WHERE id = '$bookId'");
        } else {
                $newRating = ($currentRating + $rating) / 2;
                $this->execute_query("UPDATE books SET rating = '$newRating' WHERE id = '$bookId'");
            }
        return true;
    }

    // Xóa đánh giá theo ID
    public function deleteReview(int $reviewId): bool
    {
        $reviewId = (int) $reviewId;
        $sql = "DELETE FROM {$this->table} WHERE id = $reviewId";
        return (bool) $this->execute_query($sql);
    }

    // Kiểm tra người dùng đã đánh giá sản phẩm chưa
    public function haveReview(int $userId, int $bookId): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = $userId AND book_id = $bookId";
        $result = $this->execute_query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }
        return false;
    }

    // Lấy tất cả đánh giá cho một sản phẩm cụ thể
    public function getReviewsForBook(int $bookId): array
    {
        $bookId = (int) $bookId;
        $sql = "SELECT r.*, u.username FROM {$this->table} r JOIN users u ON r.user_id = u.id WHERE r.book_id = $bookId ORDER BY r.created_at DESC";
        $res = $this->execute_query($sql);
        $out = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $out[] = $row;
            }
            $res->free();
        }
        return $out;
    }

    // lay tong so danh gia moi
    public function getTotalNewReviews(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $result = $this->execute_query($sql);
        if ($result) {
            $row = $result->fetch_assoc();
            return (int) $row['total'];
        }
        return 0;
    }

    // Lấy tất cả đánh giá cho trang admin
    public function getAllReviewsAdmin(): array
    {
        $sql = "SELECT 
                    r.id, 
                    r.rating, 
                    r.content AS comment, 
                    r.created_at,
                    r.status,
                    u.username AS reviewer_name,
                    b.title AS book_title
                FROM {$this->table} r
                JOIN users u ON r.user_id = u.id
                JOIN books b ON r.book_id = b.id
                ORDER BY r.created_at DESC";

        $result = $this->execute_query($sql);

        if (!$result) {
            return [];
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $result->free();
        return $data;
    }

    // Cập nhật trạng thái đánh giá
    public function updateStatus(int $reviewId, string $status): bool
    {
        $safeStatus = $this->db->real_escape_string($status);
        $sql = "UPDATE {$this->table} SET status = '$safeStatus' WHERE id = $reviewId";
        return $this->execute_query($sql) !== false;
    }
}