<?php
session_start();
if (!isset($_GET['order_id'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán trực tuyến</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Thanh toán VNPay thành công</h2>
        <div class="alert alert-info">
            <h4>Thông tin chuyển khoản:</h4>
            <p>Ngân hàng: BIDV</p>
            <p>Số tài khoản: 123456789</p>
            <p>Chủ tài khoản: NGUYEN VAN A</p>
            <p>Nội dung chuyển khoản: Thanh toan don hang <?php echo $_GET['order_id']; ?></p>
        </div>
        <a href="../index.php" class="btn btn-primary">Về trang chủ</a>
    </div>
</body>
</html>