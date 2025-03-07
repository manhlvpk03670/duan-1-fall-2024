<?php
session_start();
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $attribute_value = $_POST['attribute_value'];
    $new_price = $_POST['price'];

    // Cập nhật thuộc tính và giá trong giỏ hàng
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['attribute_value'] = $attribute_value;
        $_SESSION['cart'][$product_id]['price'] = $new_price;
    }

    echo json_encode(['status' => 'success']);
    exit();
}
?>