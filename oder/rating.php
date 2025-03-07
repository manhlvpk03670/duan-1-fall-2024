<?php
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Kiểm tra dữ liệu từ form đánh giá
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $user_id = $_SESSION['user_id'];
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Kiểm tra dữ liệu hợp lệ
    if (empty($rating) || $rating < 1 || $rating > 5) {
        echo "Đánh giá không hợp lệ!";
        exit();
    }

    // Kết nối cơ sở dữ liệu
    require_once '../config/config.php';

    // Kiểm tra nếu người dùng đã đánh giá đơn hàng này chưa
    $query = "SELECT * FROM order_reviews WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Bạn đã đánh giá đơn hàng này rồi!";
        exit();
    }

    // Lưu đánh giá vào cơ sở dữ liệu (không còn cột product_id)
    $query = "INSERT INTO order_reviews (order_id, user_id, rating, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiis", $order_id, $user_id, $rating, $comment);
    
    if ($stmt->execute()) {
        echo "Cảm ơn bạn đã đánh giá! , <a href='./order_detail.php?id=$order_id'>Xem đơn hang</a>";
    } else {
        echo "Lỗi khi lưu đánh giá!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Định dạng chung cho các thông báo */
.alert {
    padding: 15px;
    margin-top: 20px;
    border-radius: 5px;
    color: white;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}

.alert.success {
    background-color: #28a745; /* Màu xanh cho thông báo thành công */
}

.alert.warning {
    background-color: #ffc107; /* Màu vàng cho thông báo cảnh báo */
}

.alert.error {
    background-color: #dc3545; /* Màu đỏ cho thông báo lỗi */
}

/* Định dạng cho trang */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f7fc;
}

/* Container cho phần đánh giá */
.container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề trang */
h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

    </style>
</head>
<body>
    
</body>
</html>
