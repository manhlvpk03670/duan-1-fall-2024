<?php
session_start();

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy id đơn hàng từ form gửi lên
if (!isset($_POST['order_id'])) {
    header('Location: history.php');
    exit();
}

$order_id = $_POST['order_id'];

// Kết nối cơ sở dữ liệu
require_once '../config/config.php';

// Kiểm tra trạng thái đơn hàng
$query = "SELECT order_status FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Nếu đơn hàng không tồn tại hoặc không phải của người dùng hiện tại hoặc trạng thái không phải 'pending'
if (!$order || $order['order_status'] !== 'pending') {
    header('Location: history.php');
    exit();
}

// Cập nhật trạng thái đơn hàng thành 'cancelled'
$query_update = "UPDATE orders SET order_status = 'cancelled' WHERE id = ?";
$stmt_update = $conn->prepare($query_update);
$stmt_update->bind_param("i", $order_id);
$stmt_update->execute();

// Điều hướng người dùng về trang lịch sử đơn hàng với thông báo trạng thái là đã hủy
header('Location: history.php?status=cancelled');
exit();
?>
