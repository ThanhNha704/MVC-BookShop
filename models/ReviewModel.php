<?php
// models/ReviewModel.php
class ReviewModel extends BaseModel
{
    protected $table = 'reviews';

    public function addReview(int $userId, int $bookId, int $rating, string $comment): bool
    {
        $sql = "INSERT INTO {$this->table} (user_id, book_id, content, rating, created_at)
                VALUES ('$userId', '$bookId', '$comment', '$rating', NOW())";

        return (bool) $this->execute_query($sql);
    }

    public function getReviewsForBook(int $bookId): array
    {
        $bookId = (int)$bookId;
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
}
