<?php
// Đảm bảo người dùng đã đăng nhập
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra id đơn hàng
if (!isset($_GET['id'])) {
    header('Location: ../history.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'];

// Kết nối database
require_once '../config/config.php';

// Lấy thông tin đơn hàng
$query = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header('Location: history.php');
    exit();
}

// Lấy chi tiết đơn hàng
$query = "SELECT * FROM order_details WHERE order_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$details = $stmt->get_result();

// Mảng status hiển thị tiếng Việt
$status_labels = [
    'pending' => 'Chờ xử lý',
    'processing' => 'Đang xử lý',
    'shipped' => 'Đang giao hàng',
    'delivered' => 'Đã giao hàng',
    'cancelled' => 'Đã hủy'
];

$payment_labels = [
    'banking' => 'Chuyển khoản',
    'cod' => 'Thanh toán khi nhận hàng',
    'vnpay' => 'Thanh toán qua VNPAY'
];
// Kiểm tra nếu người dùng đã đánh giá cho đơn hàng này
$query_check_review = "SELECT * FROM order_reviews WHERE order_id = ? AND user_id = ?";
$stmt_check_review = $conn->prepare($query_check_review);
$stmt_check_review->bind_param("ii", $order_id, $user_id);
$stmt_check_review->execute();
$result_check_review = $stmt_check_review->get_result();

$has_reviewed = $result_check_review->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân</title>
    <!-- Thêm Google Fonts và Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thêm Font Awesome từ CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="../css/profile.css">
    <style>
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
            font-weight: 500;
        }

        .status-pending {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-processing {
            background-color: #b8daff;
            color: #004085;
        }

        .status-shipped {
            background-color: #c3e6cb;
            color: #155724;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .back-button {
            background-color: #ff7f32;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #e67e22;
            color: white;
        }

        .rating {
            display: flex;
            gap: 5px;
        }

        .rating input[type="radio"] {
            display: none;
        }

        .rating label i {
            font-size: 1.5em;
            color: #f5b300;
            cursor: pointer;
        }

        .rating label i:hover {
            color: #f39c12;
        }

        /* Định dạng chung cho form đánh giá */
        .rating-form {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Hiệu ứng cho các sao */
        .rating-stars {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .star input {
            display: none;
        }

        .star i {
            font-size: 30px;
            color: #d3d3d3;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .star input:checked~i,
        .star:hover~i {
            color: #ffcc00;
            /* Vàng khi chọn hoặc hover */
        }

        .comment-box {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            resize: none;
            box-sizing: border-box;
            margin-bottom: 15px;
            transition: border-color 0.3s ease;
        }

        .comment-box:focus {
            border-color: #ffcc00;
            /* Màu vàng khi focus */
            outline: none;
        }

        .submit-btn {
            background-color: #ffcc00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #e6b800;
        }
    </style>
</head>

<body>
    <?php include 'header2.php'; ?>
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="../profile.php">Thống tin cá nhân</a></li>
                <li class="breadcrumb-item"><a href="../oder/history.php">Lịch sử mua hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></li>
            </ol>
        </nav>

        <h2 style="margin-top: 10px; color: brown;">Chi tiết đơn hàng #<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></h2>

        <!-- Thông tin đơn hàng -->
        <div class="order-info">
            <div class="row">
                <div class="col-md-6">
                    <h4>Thông tin đặt hàng</h4>
                    <p><strong>Người đặt:</strong> <?php echo $order['customer_name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo $order['customer_phone']; ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo $order['customer_address']; ?></p>



                    <?php if ($order['order_status'] == 'delivered'): ?>
                        <p><strong>Đánh giá đơn hàng:</strong></p>
                        <?php if ($has_reviewed): ?>
                            <!-- Nếu người dùng đã đánh giá, hiển thị thông báo cảm ơn -->
                            <p>Cảm ơn bạn đã đánh giá đơn hàng này!</p>
                            <?php
                            // Lấy thông tin đánh giá từ cơ sở dữ liệu
                            $query_review = "SELECT * FROM order_reviews WHERE order_id = ? AND user_id = ?";
                            $stmt_review = $conn->prepare($query_review);
                            $stmt_review->bind_param("ii", $order_id, $user_id);
                            $stmt_review->execute();
                            $review_result = $stmt_review->get_result();
                            $review = $review_result->fetch_assoc();
                            ?>
                            <div class="user-review">
                                <?php
                                // Hiển thị đánh giá sao
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $review['rating']) {
                                        echo '<i class="fas fa-star" style="color: #f5b300; font-size: 1.5em;"></i>';
                                    } else {
                                        echo '<i class="far fa-star" style="color: #f5b300; font-size: 1.5em;"></i>';
                                    }
                                }
                                ?>
                                </p>
                                <p><strong>Bình luận:</strong> <?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                            </div>
                        <?php else: ?>
                            <!-- Nếu chưa đánh giá, hiển thị form đánh giá -->
                            <form action="./rating.php" method="POST" class="rating-form">
                                <div class="rating-stars">
                                    <label for="rating-1" class="star">
                                        <input type="radio" name="rating" id="rating-1" value="1">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <label for="rating-2" class="star">
                                        <input type="radio" name="rating" id="rating-2" value="2">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <label for="rating-3" class="star">
                                        <input type="radio" name="rating" id="rating-3" value="3">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <label for="rating-4" class="star">
                                        <input type="radio" name="rating" id="rating-4" value="4">
                                        <i class="fas fa-star"></i>
                                    </label>
                                    <label for="rating-5" class="star">
                                        <input type="radio" name="rating" id="rating-5" value="5">
                                        <i class="fas fa-star"></i>
                                    </label>
                                </div>

                                <textarea name="comment" rows="3" placeholder="Viết nhận xét của bạn..." class="form-control comment-box"></textarea>

                                <button type="submit" class="btn btn-primary submit-btn">Gửi đánh giá</button>

                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            </form>
                        <?php endif; ?>

                    <?php endif; ?>
                    <script>
                        // JavaScript để thay đổi màu các sao khi chọn
                        const stars = document.querySelectorAll('.star input');
                        const starIcons = document.querySelectorAll('.star i');

                        stars.forEach((star, index) => {
                            star.addEventListener('change', () => {
                                // Làm lại màu cho tất cả sao
                                starIcons.forEach((icon, i) => {
                                    if (i <= index) {
                                        icon.style.color = "#ffcc00"; // Đổi màu vàng cho các sao trước đó
                                    } else {
                                        icon.style.color = "#d3d3d3"; // Bỏ màu vàng cho các sao sau
                                    }
                                });
                            });
                        });
                    </script>
                </div>
                <div class="col-md-6">
                    <h4>Thông tin thanh toán</h4>
                    <p><strong>Phương thức:</strong> <?php echo $payment_labels[$order['payment_method']]; ?></p>
                    <p><strong>Trạng thái:</strong>
                        <span class="status-badge status-<?php echo $order['order_status']; ?>">
                            <?php echo $status_labels[$order['order_status']]; ?>
                        </span>
                    </p>
                    <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                    <?php if ($order['coupon_code']): ?>
                        <p><strong>Mã giảm giá:</strong> <?php echo $order['coupon_code']; ?></p>
                    <?php endif; ?>



                </div>
            </div>
        </div>

        <!-- Chi tiết sản phẩm -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thuộc tính</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $details->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo number_format($item['product_price'], 0, ',', '.') . 'đ'; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo $item['attribute_value'] ?: 'N/A'; ?></td>
                            <td class="text-end"><?php echo number_format($item['subtotal'], 0, ',', '.') . 'đ'; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tổng tiền:</strong></td>
                        <td class="text-end"><?php echo number_format($order['total_amount'], 0, ',', '.') . 'đ'; ?></td>
                    </tr>
                    <?php if ($order['discount_amount'] > 0): ?>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Giảm giá:</strong></td>
                            <td class="text-end"><?php echo number_format($order['discount_amount'], 0, ',', '.') . 'đ'; ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Thành tiền:</strong></td>
                        <td class="text-end"><strong><?php echo number_format($order['final_amount'], 0, ',', '.') . 'đ'; ?></strong></td>
                    </tr>
                    <td> <?php if ($order['order_status'] == 'pending'): ?>
                                <form action="cancel_order.php" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng?');">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" class="btn btn-danger w-50" >Hủy đơn hàng</button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <br>
                        <br>
        
      