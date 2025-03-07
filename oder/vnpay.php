<?php
session_start();
include('../config/config.php'); // Bao gồm kết nối CSDL

// Hàm xử lý thanh toán qua VNPAY
function online_checkout() {
    // Kiểm tra nếu người dùng chưa đăng nhập
    if (!isset($_SESSION['user_id'])) {
        echo '<p>Bạn cần đăng nhập để thực hiện thanh toán. <a href="../login.php">Đăng nhập tại đây</a>.</p>';
        return; // Dừng hàm nếu người dùng chưa đăng nhập
    }
    // Kiểm tra nếu form đã được gửi và lấy dữ liệu từ form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $customer_address = $_POST['customer_address'];
        $customer_phone = $_POST['customer_phone'];
        $payment_method = $_POST['payment_method'];

        // Lấy tổng tiền từ giỏ hàng
        $total_amount = 0;
        $cart_items = $_SESSION['cart']; // Giả sử giỏ hàng được lưu trong session
        foreach ($cart_items as $product_id => $product) {
            $price = floatval($product['price']);
            $quantity = isset($product['quantity']) ? intval($product['quantity']) : 1;
            $total_amount += $price * $quantity;
        }

        // Tính toán final_amount nếu có giảm giá (sử dụng discount_amount)
        $discount_amount = 0;  // Nếu có mã giảm giá, tính toán và gán vào
        $final_amount = $total_amount - $discount_amount;

        // Kiểm tra phương thức thanh toán
        if ($payment_method == "cod") {
            // Lưu thông tin đơn hàng vào bảng `orders`
            global $conn;
            $user_id = $_SESSION['user_id']; // Lấy ID người dùng đang đăng nhập
            $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, discount_amount, final_amount, payment_method, order_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("issssdsds", $user_id, $customer_name, $customer_email, $customer_phone, $customer_address, $total_amount, $discount_amount, $final_amount, $payment_method);
            $stmt->execute();
            $order_id = $stmt->insert_id; // Lấy ID đơn hàng vừa tạo
        
            // Insert chi tiết đơn hàng vào bảng `order_details`
            foreach ($cart_items as $product_id => $product) {
                $product_name = $product['name'];
                $product_price = $product['price'];
                $quantity = isset($product['quantity']) ? $product['quantity'] : 1;
                $attribute_value = isset($product['attribute_value']) ? $product['attribute_value'] : null;
                $subtotal = $product_price * $quantity;
        
                // Lưu vào bảng order_details
                $stmt = $conn->prepare("INSERT INTO order_details (order_id, user_id, product_id, product_name, product_price, quantity, attribute_value, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiisdiss", $order_id, $user_id, $product_id, $product_name, $product_price, $quantity, $attribute_value, $subtotal);
                $stmt->execute();
            }
        
            // Xóa giỏ hàng sau khi đặt hàng COD thành công
            unset($_SESSION['cart']); 
        
            // Hiển thị thông báo thành công
            echo '<p>Đơn hàng của bạn đã được ghi nhận thành công. Chúng tôi sẽ liên hệ sớm nhất! xem chi tiết <a href="./history.php">tại đây</a>.</p>';
        } elseif ($payment_method == "vnpay") {
            // Thông tin VNPAY
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "http://localhost/duan/oder/banking_payment.php?order_id=";
            $vnp_TmnCode = "A7KX5GQG";
            $vnp_HashSecret = "KEMC7FSFIB1OKEXFZ46VWKD1ZF3DLQJR";

            // Mã giao dịch
            $vnp_TxnRef = rand(1, 10000); // Giao dịch ngẫu nhiên
            $vnp_OrderInfo = 'Thanh toán đơn hàng'; // Thông tin đơn hàng
            $vnp_OrderType = 'billpayment'; // Loại thanh toán
            $vnp_Amount = $final_amount * 100; // Tổng tiền (VND) nhân 100
            $vnp_Locale = 'vn'; // Ngôn ngữ (Việt Nam)
            $vnp_BankCode = 'NCB'; // Mã ngân hàng (có thể thay đổi)
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // Lấy địa chỉ IP người dùng

            // Dữ liệu gửi đến VNPAY
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef
            );

            // Nếu có mã ngân hàng, thêm vào dữ liệu
            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            // Sắp xếp dữ liệu theo thứ tự từ a-z
            ksort($inputData);

            // Chuỗi để tạo mã băm
            $query = "";
            $hashdata = "";
            $i = 0;

            // Duyệt qua mảng inputData để tạo hashdata và query
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            // Tạo URL thanh toán VNPAY
            $vnp_Url = $vnp_Url . "?" . $query;

            // Tạo mã bảo mật và thêm vào URL
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }

            // Lưu thông tin đơn hàng vào bảng `orders`
            global $conn;
            $user_id = $_SESSION['user_id']; // Giả sử người dùng đã đăng nhập
            $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, discount_amount, final_amount, payment_method, order_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("issssdsds", $user_id, $customer_name, $customer_email, $customer_phone, $customer_address, $total_amount, $discount_amount, $final_amount, $payment_method);
            $stmt->execute();
            $order_id = $stmt->insert_id; // Lấy ID đơn hàng vừa tạo

            // Insert chi tiết đơn hàng vào bảng `order_details`
            foreach ($cart_items as $product_id => $product) {
                $product_name = $product['name'];
                $product_price = $product['price'];
                $quantity = isset($product['quantity']) ? $product['quantity'] : 1;
                $attribute_value = isset($product['attribute_value']) ? $product['attribute_value'] : null;
                $subtotal = $product_price * $quantity;

                // Lưu vào bảng order_details
                $stmt = $conn->prepare("INSERT INTO order_details (order_id, user_id, product_id, product_name, product_price, quantity, attribute_value, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iiisdiss", $order_id, $user_id, $product_id, $product_name, $product_price, $quantity, $attribute_value, $subtotal);
                $stmt->execute();
            }

            // Xóa giỏ hàng sau khi thanh toán
            unset($_SESSION['cart']); // Xóa giỏ hàng khỏi session

            // Chuyển hướng đến trang thanh toán của VNPAY
            header('Location: ' . $vnp_Url);
            exit();
        }
    } else {
        echo "Vui lòng chọn phương thức thanh toán.";
    }
}

// Gọi hàm xử lý thanh toán
online_checkout();
?>
