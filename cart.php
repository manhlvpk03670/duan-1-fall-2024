<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Kết nối đến CSDL
$conn = new mysqli('localhost', 'root', '', 'cookie');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý cập nhật số lượng
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $product_id = intval($_GET['id']);
    $quantity = intval($_GET['quantity']);

    // Lấy số lượng tồn kho của sản phẩm
    $sql = "SELECT stock FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $stock = intval($row['stock']);
        if ($quantity < 1) {
            $quantity = 1;
        } elseif ($quantity > $stock) {
            $quantity = $stock;
            echo "<script>alert('Quá số lượng tồn kho: $stock');</script>";
        }
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
}

// Xử lý cập nhật thuộc tính và giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attribute_value']) && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $attribute_value = $_POST['attribute_value'];

    // Lấy giá theo thuộc tính
    $price_sql = "SELECT price FROM product_attributes WHERE product_id = $product_id AND attribute_value = '$attribute_value'";
    $price_result = $conn->query($price_sql);

    if ($price_result && $price_row = $price_result->fetch_assoc()) {
        $_SESSION['cart'][$product_id]['attribute_value'] = $attribute_value;
        $_SESSION['cart'][$product_id]['price'] = $price_row['price'];
    }
}

// Tính tổng giá
$total_price = 0;
foreach ($_SESSION['cart'] as $product_id => $product) {
    $price = floatval($product['price']);
    $quantity = isset($product['quantity']) ? intval($product['quantity']) : 1;
    $total_price += $price * $quantity;
}
?>
<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>


    <link rel="stylesheet" href="./css/cart.css">
    <!-- Favicon cho các trình duyệt hiện đại -->
    <link rel="icon" type="image/png" sizes="32x32" href="./img/cc5.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./img/cc5.png">

    <!-- Favicon cho iOS -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

    <!-- Favicon dự phòng cơ bản -->
    <link rel="shortcut icon" href="favicon.ico">
    <style>
        .checkout {
    display: inline-block;
    color: #fff; /* Màu chữ */
    background-color: #ffc107; /* Màu nền vàng */
    padding: 10px 20px; /* Khoảng cách bên trong nút */
    font-size: 16px; /* Kích thước chữ */
    font-weight: bold; /* Đậm chữ */
    border-radius: 5px; /* Bo tròn góc nút */
    text-decoration: none; /* Loại bỏ gạch chân */
    transition: all 0.3s ease; /* Hiệu ứng hover */
}

.checkout:hover {
    background-color: #e55b50; /* Màu nền khi hover */
    color: #fefefe; /* Màu chữ khi hover */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Hiệu ứng đổ bóng */
    text-decoration: none; /* Đảm bảo không có gạch chân khi hover */
    transform: translateY(-3px); /* Hiệu ứng nổi lên */
}

.checkout i {
    margin-right: 8px; /* Khoảng cách giữa biểu tượng và chữ */
    font-size: 18px; /* Kích thước biểu tượng */
}

    </style>
</head>

<body>
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">Trang chủ</a>
            <span class="separator">&gt;</span>
            <a href="product.php">Sản phẩm</a>
            <span class="separator">&gt;</span>
            <span class="active">Giỏ hàng</span>
            <span class="separator">&gt;</span>

        </div>

        <!-- Cart Header -->
        <h1 class="cart-header">Giỏ hàng</h1>

        <!-- Freeship Notice -->
        <div class="freeship-notice">
            Freeship đơn từ 500K (tối đa 50K). Nhập mã <span class="freeship-code">FREESHIP</span>.
        </div>

        <!-- Cart Table -->
        <div class="cart-table">
            <div class="cart-table-header">
                <div>Tên sản phẩm</div>
                <div>Số lượng</div>
                <div>Thể Loại</div>
                <div>Tạm tính</div>
                <div></div>
            </div>

            <?php
            if (empty($_SESSION['cart'])) {
                echo "<div style='padding: 20px; text-align: center;'>Giỏ hàng của bạn trống</div>";
            } else {
                foreach ($_SESSION['cart'] as $product_id => $product) {
                    // Truy vấn thuộc tính và giá
                    $attribute_sql = "SELECT attribute_name, attribute_value, price FROM product_attributes WHERE product_id = $product_id";
                    $attribute_result = $conn->query($attribute_sql);
                    $attributes = [];
                    $selected_price = $product['price']; // Giá mặc định

                    if ($attribute_result) {
                        while ($row = $attribute_result->fetch_assoc()) {
                            $attributes[] = $row;
                            // Kiểm tra và cập nhật giá theo thuộc tính đã chọn
                            if (
                                isset($product['attribute_value']) &&
                                $row['attribute_value'] == $product['attribute_value']
                            ) {
                                $selected_price = $row['price'];
                            }
                        }
                    }

                    $quantity = $product['quantity'] ?? 1;
                    $total_product_price = $selected_price * $quantity;
            ?>
                    <div class="cart-item">
                        <div class="product-info">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            <div>
                                <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            </div>
                        </div>
                        <!-- Điều khiển số lượng -->
                        <div class="quantity-control">
                            <form action="cart.php" method="get" style="display: flex; align-items: center; gap: 8px;">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1" class="quantity-input" required>
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                                <input type="hidden" name="id" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="action" value="update">
                            </form>
                        </div>
                        <!-- Chọn thuộc tính -->
                        <div class="attribute-control ">
                            <form method="post">
                                <select name="attribute_value"  style="border: none;" onchange="this.form.submit()">
                                    <?php foreach ($attributes as $attribute): ?>
                                        <option value="<?php echo htmlspecialchars($attribute['attribute_value']); ?>"
                                            <?php echo (isset($product['attribute_value']) &&
                                                $product['attribute_value'] == $attribute['attribute_value']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($attribute['attribute_name']) . ": " . htmlspecialchars($attribute['attribute_value']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            </form>
                        </div>

                        <div class="price"><?php echo number_format($total_product_price, 0, ',', '.') . 'đ'; ?></div>
                        <button onclick="window.location.href='remove_from_cart.php?id=<?php echo $product_id; ?>'" class="remove-btn" style="color: brown; cursor: pointer; ">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m5 6v6m4-6v6M10 4h4a1 1 0 011 1v1H9V5a1 1 0 011-1z"></path>
                            </svg>
                        </button>
                    </div>
            <?php }
            } ?>
        </div>

        <!-- Cart Summary -->
        <!-- Tóm tắt giỏ hàng -->
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="cart-summary">
                <div class="summary-title">
                    Tổng cộng
                    <div class="total-amount"><?php echo number_format($total_price, 0, ',', '.') . 'đ'; ?></div>
                </div>
                <a href="checkout.php" class="checkout">
                    <i class="fas fa-shopping-cart"></i> Thanh toán
                </a>
            </div>
     </div>
     
<?php endif; ?>

</div>

<script>
    function updateQuantity(button, delta) {
        const input = button.parentElement.querySelector('.quantity-input');
        let newQuantity = parseInt(input.value) + delta;
        input.value = Math.max(newQuantity, 1);
        input.form.submit();
    }
</script>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
<?php include 'footer.php'; ?>