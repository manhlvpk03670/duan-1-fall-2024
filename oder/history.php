<?php
// Đảm bảo người dùng đã đăng nhập
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Kết nối database
require_once '../config/config.php';

// Thiết lập số đơn hàng hiển thị trên mỗi trang
$items_per_page = 7;

// Lấy trang hiện tại từ tham số URL, mặc định là trang 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page); // Đảm bảo trang không nhỏ hơn 1

// Tính offset cho truy vấn
$offset = ($current_page - 1) * $items_per_page;

// Đếm tổng số đơn hàng
$count_query = "SELECT COUNT(*) as total FROM orders WHERE user_id = ?";
$count_stmt = $conn->prepare($count_query);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$total_result = $count_stmt->get_result();
$total_items = $total_result->fetch_assoc()['total'];

// Tính tổng số trang
$total_pages = ceil($total_items / $items_per_page);

// Điều chỉnh trang hiện tại nếu vượt quá tổng số trang
$current_page = min($current_page, $total_pages);

// Lấy danh sách đơn hàng của người dùng với phân trang
$query = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $user_id, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Mảng status hiển thị tiếng Việt
$status_labels = [
    'pending' => 'Chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đang giao hàng',
    'delivered' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy'
];

// Mảng phương thức thanh toán hiển thị tiếng Việt
$payment_labels = [
    'banking' => 'Chuyển khoản',
    'cod' => 'Thanh toán khi nhận hàng',
    'vnpay' => 'Thanh toán VNPAY'
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Mua Hàng</title>
    <link rel="stylesheet" href="../css/profile.css">

    <style>
        /* Giữ nguyên CSS hiện có */
        .order-table {
            margin-top: 20px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: #6c757d;
            text-decoration: none;
        }

        .status-pending { background-color: #ffeeba; color: #856404; }
        .status-processing { background-color: #b8daff; color: #004085; }
        .status-shipped { background-color: #c3e6cb; color: #155724; }
        .status-delivered { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }

        .detail-button {
            background-color: #ff7f32;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .detail-button:hover {
            background-color: red;
            color: white;
        }

        /* Thêm CSS cho phân trang */
        .pagination {
            margin-top: 20px;
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: #ff7f32;
            border-color: #ff7f32;
        }

        .pagination .page-link {
            color: brown;
        }

        .pagination .page-link:hover {
            color: #e67e22;
        }

        .page-info {
            text-align: center;
            margin-top: 10px;
            color: #6c757d;
        }

        .stars { font-size: 1.5em; color: #ffd700; }
        .stars.full { color: #ffd700; }
        .stars.half { color: #d3d3d3; }
        .stars.empty { color: #ccc; }
        .stars-empty { font-size: 1.5em; color: #ccc; }
        .detail-button {
            background-color: #ff7f32;
        }
    </style>
</head>
<body>
    <?php include 'header2.php'; ?>
    <div class="container" style="max-width: 80%">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="../profile.php">Thông tin cá nhân</a></li>
                <li class="breadcrumb-item active" aria-current="page">Lịch sử mua hàng</li>
            </ol>
        </nav>

        <h2 style="margin-top: 10px; color: brown;">Lịch sử mua hàng</h2>

        <!-- Bảng hiển thị đơn hàng -->
        <div class="table-responsive order-table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Đánh giá</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td><?php echo number_format($order['final_amount'], 0, ',', '.') . 'đ'; ?></td>
                                <td><?php echo isset($payment_labels[$order['payment_method']]) ? $payment_labels[$order['payment_method']] : 'N/A'; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                        <?php echo isset($status_labels[$order['order_status']]) ? $status_labels[$order['order_status']] : 'N/A'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($order['order_status'] == 'delivered'): ?>
                                        <?php
                                        $query_review = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS review_count FROM order_reviews WHERE order_id = ?";
                                        $stmt_review = $conn->prepare($query_review);
                                        $stmt_review->bind_param("i", $order['id']);
                                        $stmt_review->execute();
                                        $result_review = $stmt_review->get_result();
                                        $review = $result_review->fetch_assoc();
                                        $avg_rating = $review['avg_rating'];
                                        $review_count = $review['review_count'];

                                        if ($review_count > 0) {
                                            $full_stars = floor($avg_rating);
                                            $half_star = ($avg_rating - $full_stars) >= 0.5 ? 1 : 0;
                                            $empty_stars = 5 - $full_stars - $half_star;

                                            echo '<span class="stars full">' . str_repeat("★", $full_stars) . '</span>';
                                            if ($half_star) {
                                                echo '<span class="stars half">☆</span>';
                                            }
                                            echo '<span class="stars empty">' . str_repeat("☆", $empty_stars) . '</span>';
                                        } else {
                                            echo "<span class='stars-empty'>☆☆☆☆☆</span>";
                                        }
                                        ?>
                                    <?php else: ?>
                                        <span>Chờ xử lý</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" class="btn detail-button" style="background-color: #ff7f32; color: white; ">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Bạn chưa có đơn hàng nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Hiển thị thông tin phân trang -->
        <?php if ($total_pages > 1): ?>
            <div class="page-info">
                Trang <?php echo $current_page; ?> / <?php echo $total_pages; ?>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Nút Previous -->
                    <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" <?php echo ($current_page <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <?php
                    // Hiển thị các số trang
                    $start_page = max(1, $current_page - 2);
                    $end_page = min($total_pages, $current_page + 2);

                    // Hiển thị trang đầu nếu cần
                    if ($start_page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                        if ($start_page > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    // Hiển thị các trang giữa
                    for ($i = $start_page; $i <= $end_page; $i++) {
                        echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '">';
                        echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                        echo '</li>';
                    }

                    // Hiển thị trang cuối nếu cần
                    if ($end_page < $total_pages) {
                        if ($end_page < $total_pages - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '">' . $total_pages . '</a></li>';
                    }
                    ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" <?php echo ($current_page >= $total_pages) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
<br>
<br>
<br>
<br>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>