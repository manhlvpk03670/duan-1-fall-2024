<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        // Nếu chưa đăng nhập, chuyển hướng đến trang login.php
        header('Location: login.php');
        exit();
    }
}
?>
