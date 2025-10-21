<?php
// Tải các Model cần thiết để lấy dữ liệu tổng hợp
// require_once 'UserModel.php';
// require_once 'OrderModel.php';
// require_once 'ReviewModel.php';
// require_once '..\core\database.php';

class DashboardModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    /**
     * Lấy tất cả các số liệu tổng quan cho Dashboard
     * @return array Dữ liệu tổng hợp (Total Revenue, Total Orders, etc.)
     */
    public function getDashboardData() {
        $orderModel = new OrderModel();
        $userModel = new UserModel();
        $reviewModel = new ReviewModel();

        // 1. Lấy số liệu chính
        $totalRevenue = $this->getTotalRevenue();
        $totalOrders = $orderModel->countTotalOrders();
        $newUsers = $userModel->countNewUsersLastMonth(); // Ví dụ: trong tháng qua
        $newReviews = $reviewModel->countNewReviewsLast24Hours(); // Ví dụ: trong 24h qua
        $recentOrders = $orderModel->getRecentOrders(5); // Lấy 5 đơn hàng gần đây

        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'new_users' => $newUsers,
            'new_reviews' => $newReviews,
            'recent_orders' => $recentOrders
        ];
    }
    
    /**
     * Tính tổng doanh thu từ các đơn hàng đã 'thành công' hoặc 'đã giao'
     */
    private function getTotalRevenue() {
        $query = "SELECT SUM(total) as total_revenue FROM orders WHERE status IN ('thành công', 'đã giao')";
        $result = $this->db->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            return $row['total_revenue'] ?? 0;
        }
        return 0;
    }

    // Bạn có thể thêm các hàm khác như getMonthlyRevenueChartData() ở đây
}
?>