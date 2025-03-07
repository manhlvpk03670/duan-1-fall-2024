<?php 
// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'cookie');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Lấy danh sách danh mục chính
$sql = "SELECT * FROM categories";
$categories_result = $conn->query($sql);

?>

