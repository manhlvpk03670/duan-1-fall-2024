-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 03, 2024 lúc 04:25 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `cookie`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Set làm bánh'),
(2, 'Set làm socola'),
(3, 'Dụng cụ làm bánh'),
(4, 'Ưu đãi tháng này ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sent','seen','replied') NOT NULL DEFAULT 'sent',
  `admin_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `name`, `email`, `message`, `image`, `created_at`, `status`, `admin_reply`, `replied_at`) VALUES
(3, 3, 'le van manh', 'vanmanhh240325@gmail.com', 'ê c', '673c03f9a56cf.jpg', '2024-11-19 03:20:25', 'replied', 'giỡn quài mày sád', '2024-12-03 07:51:38'),
(4, 3, 'le van manh', 'vanmanhh240325@gmail.com', 'eeeee', '673c12dab65e8.jpg', '2024-11-19 04:23:54', 'replied', 'sao em', '2024-11-19 06:30:56'),
(5, 3, 'ee', 'vanmanhh240325@gmail.com', 'ádasdasdsad', '', '2024-11-19 07:08:18', 'sent', NULL, NULL),
(6, 3, 'ee', 'vanmanhh240325@gmail.com', 'ádasdasdas', '673c397ef366c.jpg', '2024-11-19 07:08:46', 'sent', NULL, NULL),
(7, 3, 'le van manh', 'vanmanhh240325@gmail.com', 'ádads', '673c39c8378ea.jpg', '2024-11-19 07:10:00', 'sent', NULL, NULL),
(8, 3, 'le van manh', 'vanmanhh240325@gmail.com', 'ádads', '673c39dc388f1.jpg', '2024-11-19 07:10:20', 'sent', NULL, NULL),
(9, 3, 'le van manh', 'vanmanhh240325@gmail.com', '111212', '673c40e35db60.jpg', '2024-11-19 07:40:19', 'sent', NULL, NULL),
(10, 8, 'le van long', 'vanmanhh240329@gmail.com', 'asdadasd', '674e595457352.png', '2024-12-03 01:05:24', 'replied', 'ok', '2024-12-03 01:05:37'),
(11, 8, 'le van manh2', 'admsssin@gmail.com', 'quan que', '674ed527daacd.png', '2024-12-03 09:53:43', 'sent', NULL, NULL),
(12, 8, 'le van manh2', 'admsssin@gmail.com', 'banh mi dep', '674ed5459934e.png', '2024-12-03 09:54:13', 'sent', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `expiration_date` date DEFAULT NULL,
  `usage_limit` int(11) DEFAULT 1,
  `times_used` int(11) DEFAULT 0,
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `status` enum('active','expired','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percentage`, `discount_amount`, `expiration_date`, `usage_limit`, `times_used`, `min_order_value`, `max_discount`, `status`) VALUES
(1, 'FREESHIP', 10.00, 0.00, '2020-12-31', 46, 0, 300000.00, 250000.00, 'active'),
(3, '3333', 10.00, 0.00, '2030-03-01', 96, 0, 300000.00, 250000.00, 'active'),
(4, 'banhbeo', 10.00, 10000.00, '2025-02-04', 30, 0, 100000.00, 50000.00, 'inactive');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `final_amount` decimal(10,2) NOT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `payment_method` enum('banking','cod','vnpay') NOT NULL,
  `order_status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `total_amount`, `discount_amount`, `final_amount`, `coupon_code`, `payment_method`, `order_status`, `created_at`, `updated_at`) VALUES
(56, 3, 'le van manh', 'vanmanhh240325@gmail.com', '0905266784', 'ở đây', 36000.00, 0.00, 36000.00, NULL, 'vnpay', 'delivered', '2024-11-19 06:24:11', '2024-11-19 06:25:04'),
(57, 3, 'le van manh', 'vanmanhh240325@gmail.com', '0905266784', 'ádasdasd', 36000.00, 0.00, 36000.00, NULL, 'cod', 'pending', '2024-11-19 06:50:50', '2024-11-19 06:50:50'),
(58, 3, 'le van manh', 'vanmanhh240325@gmail.com', '0905266584', 'sdc', 883000.00, 0.00, 883000.00, NULL, 'cod', 'pending', '2024-11-26 01:15:53', '2024-11-26 01:15:53'),
(59, 8, 'le van manh', 'vanmanhh240325@gmail.com', '0905266584', 'ádasd', 36000.00, 0.00, 36000.00, NULL, 'vnpay', 'delivered', '2024-11-27 15:50:43', '2024-12-03 01:51:32'),
(60, 3, 'le van manh', 'vanmanhh240325@gmail.com', '0540404554', 'ádasdasd', 1809808.00, 0.00, 1809808.00, NULL, 'vnpay', 'pending', '2024-11-29 16:51:36', '2024-11-29 16:51:36'),
(61, 8, 'le van manh', 'vanmanhh240325@gmail.com', '0540404554', 'vanmnh', 55000000.00, 0.00, 55000000.00, NULL, 'vnpay', 'shipped', '2024-12-03 02:23:20', '2024-12-03 07:12:52'),
(62, 17, 'le van manh', 'vanmanhh240325@gmail.com', '0540404554', 'asdasdasd', 550000.00, 0.00, 550000.00, NULL, 'vnpay', 'delivered', '2024-12-03 07:19:45', '2024-12-03 08:52:50'),
(63, 8, 'le van manh2', 'admsssin@gmail.com', '0540404554', 'ádasdasd', 550000.00, 0.00, 550000.00, NULL, 'vnpay', 'pending', '2024-12-03 09:40:06', '2024-12-03 09:40:06'),
(64, 8, 'le van manh2', 'admsssin@gmail.com', '0540404554', 'câcscsacas', 504000.00, 0.00, 504000.00, NULL, 'cod', 'pending', '2024-12-03 09:46:26', '2024-12-03 09:46:26'),
(65, 8, 'admin', 'admsssin@gmail.com', '0905266784', 'ádasdsa', 550000.00, 0.00, 550000.00, NULL, 'cod', 'cancelled', '2024-12-03 09:58:40', '2024-12-03 13:41:17'),
(66, 8, 'admin', 'admsssin@gmail.com', '0540404554', 'levanmanh', 5835252.00, 0.00, 5835252.00, NULL, 'cod', 'pending', '2024-12-03 14:17:28', '2024-12-03 14:17:28'),
(67, 8, 'admin', 'admsssin@gmail.com', '0540404554', 'dscccx', 550000.00, 0.00, 550000.00, NULL, 'vnpay', 'pending', '2024-12-03 14:17:57', '2024-12-03 14:17:57'),
(68, 8, 'admin', 'admsssin@gmail.com', '0540404554', ' cx c cx', 504000.00, 0.00, 504000.00, NULL, 'vnpay', 'pending', '2024-12-03 14:19:55', '2024-12-03 14:19:55'),
(69, 8, 'admin', 'admsssin@gmail.com', '0540404554', 'xvxvxcv', 5235252.00, 0.00, 5235252.00, NULL, 'vnpay', 'pending', '2024-12-03 14:21:00', '2024-12-03 14:21:00'),
(70, 8, 'admin', 'admsssin@gmail.com', '0540404554', 'fdgdvdf', 300000.00, 0.00, 300000.00, NULL, 'vnpay', 'cancelled', '2024-12-03 14:22:38', '2024-12-03 14:25:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `attribute_value` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `user_id`, `product_id`, `product_name`, `product_price`, `quantity`, `attribute_value`, `subtotal`, `created_at`) VALUES
(28, 56, 3, 1, 'Bộ Dụng Cụ Làm Bánh Quy', 36000.00, 1, NULL, 36000.00, '2024-11-19 06:24:11'),
(29, 57, 3, 1, 'Bộ Dụng Cụ Làm Bánh Quy', 36000.00, 1, '1KG', 36000.00, '2024-11-19 06:50:50'),
(30, 58, 3, 2, 'Bộ Dụng Cụ Làm matcha', 297500.00, 1, NULL, 297500.00, '2024-11-26 01:15:53'),
(31, 58, 3, 2, 'Bộ Dụng Cụ Làm matcha', 297500.00, 1, NULL, 297500.00, '2024-11-26 01:15:53'),
(32, 59, 8, 1, 'Bộ Dụng Cụ Làm Bánh Quy', 36000.00, 1, NULL, 36000.00, '2024-11-27 15:50:43'),
(33, 60, 3, 1, 'Bộ Dụng Cụ Làm Bánh Quy', 452452.00, 4, '1KG', 1809808.00, '2024-11-29 16:51:36'),
(34, 61, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 550000.00, 100, '1KG', 55000000.00, '2024-12-03 02:23:20'),
(35, 62, 17, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 550000.00, 1, '1KG', 550000.00, '2024-12-03 07:19:45'),
(36, 63, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 550000.00, 1, '1KG', 550000.00, '2024-12-03 09:40:06'),
(37, 64, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 504000.00, 1, NULL, 504000.00, '2024-12-03 09:46:26'),
(38, 65, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 550000.00, 1, '1KG', 550000.00, '2024-12-03 09:58:40'),
(39, 66, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 600000.00, 1, '1.5KG', 600000.00, '2024-12-03 14:17:28'),
(40, 66, 8, 2, 'Bánh kem matcha', 5235252.00, 1, 'Blue', 5235252.00, '2024-12-03 14:17:28'),
(41, 67, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 550000.00, 1, '1KG', 550000.00, '2024-12-03 14:17:57'),
(42, 68, 8, 1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 504000.00, 1, NULL, 504000.00, '2024-12-03 14:19:55'),
(43, 69, 8, 2, 'Bánh kem matcha', 5235252.00, 1, 'Blue', 5235252.00, '2024-12-03 14:21:00'),
(44, 70, 8, 3, 'Bánh kem vani', 150000.00, 2, NULL, 300000.00, '2024-12-03 14:22:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_reviews`
--

CREATE TABLE `order_reviews` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_reviews`
--

INSERT INTO `order_reviews` (`id`, `order_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(3, 39, 3, 4, 'ádasdadssadad', '2024-11-19 05:18:38'),
(5, 47, 3, 4, 'ádasd', '2024-11-19 06:17:11'),
(6, 44, 3, 3, '', '2024-11-19 06:18:58'),
(7, 56, 3, 5, 'đẹp', '2024-11-19 06:25:11'),
(8, 1, 3, 3, 'cc', '2024-11-29 16:52:33'),
(9, 48, 8, 3, 'dep', '2024-12-03 00:45:55'),
(10, 50, 8, 5, 'xịn đssyd \r\n', '2024-12-03 02:05:59'),
(11, 59, 8, 3, 'asdasd', '2024-12-03 13:06:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `subcategory_id` int(11) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT 0,
  `original_price` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `category_id`, `price`, `description`, `stock`, `image_url`, `created_at`, `updated_at`, `subcategory_id`, `discount_percent`, `original_price`) VALUES
(1, 'Bộ Bánh Quy Ăn Kèm Với Bánh Mì', 1, 560000.00, 'Shop cam kết giá rẻ nhất thị trường chuyên về baby food tại Việt Nam\r\nBánh quy Nomura Mire Biscuits Nhật Bản 120g (2loại)\r\n\r\nBánh quy Nomura Mire Biscuits Nhật Bản 120g (2loại) là món quà lưu niệm nổi tiếng từ Kochi - Japan với hương vị mộc mạc của ngũ cốc, bơ, dầu cọ pha một chút vị mặn của muối biển.\r\n\"Bạn nhìn vào gói bánh quy tròn mầu nâu vàng với thật nhiều chiếc bánh quy nhỏ xíu trên tay, lật trở qua lại và tự hỏi, điều gì đã khiến cho chiếc bánh tối giản này trở thành món quà lưu niệm mà ai cũng phải có mỗi lần đến với Kochi (Nhật Bản), thứ quà mà người dân nơi đây tự hào gọi với cái tên \"\"Majime na Okashi\"\" hay những chiếc bánh quy ngon nhất thế giới.\r\nBạn bóc một gói bánh và đưa chiếc bánh đầu tiên lên miệng. Hương vị ngọt và mặn được pha trộn một cách hoàn hảo cứ râm ran mãi trong vòm miệng khi miếng bánh dẫn tan ra trên đầu lưỡi. Dư âm giòn tan của bánh khiến bạn triền miên trong những miếng cắn cho đến khi giật mình nhìn lại... túi bánh sớm đã trống rỗng.\r\nĐối với những người dân tại Kochi, những chiếc bánh quy này không chỉ mang hương vị Nhật Bản đích thực mà còn gợi lại những kỷ niệm đẹp xưa cũ và những khoảnh khắc thân thương khi được chia sẻ những chiếc bánh với những người thân yêu của họ. Giờ đây, bạn cũng có thể có những trải nghiệm tuyệt với này mỗi khi thưởng thức Nomura, hoặc lan tỏa chúng đến với những người yêu thương của mình như những món quà chan chứa tình cảm.\"\r\n#Banhmannhatban #banhman #banhquyman #banhcaman', 100, 'https://th.bing.com/th/id/OIP.lMqmnByPwyUi35ISdfphgQHaEP?w=268&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7', '2024-11-07 02:29:55', '2024-12-03 01:31:10', 1, 10, 280000),
(2, 'Bánh kem matcha', 1, 350000.00, 'Bộ dụng cụ làm socola tuyệt vời cho những người yêu thích làm bánh', 50, 'https://th.bing.com/th?id=OIP._DQs0z1unzFKm-Of4-UPdAHaFg&w=290&h=215&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-07 02:29:55', '2024-11-27 00:46:10', 2, 15, 400000),
(3, 'Bánh kem vani', 1, 150000.00, 'Khuôn bánh chống dính chất lượng cao, giúp việc làm bánh dễ dàng hơn', 200, 'https://th.bing.com/th/id/OIP.lL15IkYsoM7fJatTPBqCWQHaHa?w=176&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7', '2024-11-07 02:29:55', '2024-11-27 00:47:42', 3, 0, 300000),
(7, 'Bộ Dụng Cụ Làm Bánh Quy', 1, 200000.00, 'Bộ dụng cụ làm bánh quy cơ bản.', 10, 'https://th.bing.com/th?id=OIP.3ZsIS41VrmvK0c02wOE33wHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-07 01:20:48', '2024-11-07 02:09:26', 1, 10, 250000),
(8, 'Bộ Dụng Cụ Làm Socola', 2, 250000.00, 'Bộ dụng cụ làm socola tại nhà.', 5, 'https://th.bing.com/th?id=OIP.3ZsIS41VrmvK0c02wOE33wHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-07 01:20:48', '2024-12-02 14:24:21', 4, 20, 350000),
(9, 'Khuôn Bánh Chống Dính', 1, 150000.00, 'Khuôn bánh chống dính chất lượng cao.', 20, 'https://th.bing.com/th?id=OIP.3ZsIS41VrmvK0c02wOE33wHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-07 01:20:48', '2024-11-11 05:33:25', 3, 0, 200000),
(10, 'Bình Lắc Pha Chế', 4, 200000.00, 'Bình lắc chuyên dụng cho pha chế.', 15, 'https://th.bing.com/th?id=OIP.3ZsIS41VrmvK0c02wOE33wHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-07 01:20:48', '2024-11-12 08:37:16', 9, 30, 250000),
(20, 'Đinh Long Vũ', 4, 500000.00, 'ádasdasdsad', 5000, 'https://th.bing.com/th?id=OIP.HFGT9RrZ58fM3XdmpfKREwHaEo&w=316&h=197&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-11 15:12:47', '2024-11-27 00:59:24', 9, 10, 100000),
(21, 'Bánh Mì Hà nội', 4, 500000.00, 'Ngon như hà nội', 5000, 'https://th.bing.com/th?id=OIP.HFGT9RrZ58fM3XdmpfKREwHaEo&w=316&h=197&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-12 08:38:19', '2024-11-12 08:38:19', 9, 50, 1000000),
(22, 'Bánh Quy Socola Mix Vị Hạnh Nhân Vinamilk', 2, 350000.00, 'Bánh Quy Socola ở đâu chả giống nhau, sao phải chọn  Ăn vặt Cùng Tũn?\r\n\r\n– Đội ngũ trẻ trung, năng động chính vì vậy Ăn vặt Cùng Tũn rất hiểu khách hàng. Bạn sẽ được tư vấn, chăm sóc nhiệt tình bởi đội ngũ tận tâm nhất.\r\n\r\n– An toàn, đảm bảo: Đồ ăn vặt ở Ăn vặt Cùng Tũn đều có giấy chứng nhận an toàn vệ sinh thưc phẩm. Bao bì đóng gói chắc chắn, đầy đủ ngày sản xuất và hạn sử dụng. Hàng được Ship đi bằng hộp Cát-tông cứng 3 lớp nên bạn yên tâm đồ ăn chắc chắn. Sẽ luôn còn mới và ngon lành cho đến khi bạn nhận được.\r\n\r\nCách bảo quản Bánh Quy Socola: nơi khô ráo, thoáng mát và tránh ánh nắng mặt trời.\r\n\r\nShop cam kết :\r\n\r\n– Về sản phẩm: khách nhận được hàng chất lượng, đúng hình và đúng như môt tả.\r\n\r\n– Về dịch vụ: tư vấn miễn phí, hướng dẫn và giải quyết tất cả những vấn đề của khách hàng trong quá trình mua hàng.\r\n\r\nXem thêm các sản phẩm ăn vặt tại đây.', 50, 'https://snackshophaiphong.top/wp-content/uploads/2024/02/anhsp176-600x600.jpg', '2024-12-03 14:04:17', '2024-12-03 14:04:17', 4, 20, 300000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `attribute_name` varchar(255) NOT NULL,
  `attribute_value` varchar(255) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `product_id`, `attribute_name`, `attribute_value`, `price`) VALUES
(20, 9, 'áo', 'Trắng', 300000.00),
(21, 9, 'áo', 'xanh', 2000000.00),
(22, 9, 'áo', 'đỏ', 505055.00),
(132, 21, 'Gói', '6 Cái', 4554324.00),
(149, 2, 'Color', '500g', 505050.00),
(150, 2, 'Color', 'Blue', 5235252.00),
(151, 2, 'Color', 'Steel', 525252.00),
(160, 20, 'áo', 'Trắng', 45045.00),
(181, 1, 'Gói', '500g', 500000.00),
(182, 1, 'Gói', '1KG', 550000.00),
(183, 1, 'Gói', '1.5KG', 600000.00),
(184, 1, 'Gói', '5KG', 650000.00),
(195, 22, 'Gói', '500G', 350000.00),
(196, 22, 'Gói', '1KG', 400000.00),
(197, 22, 'Gói', '1.5KG', 450000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 3, 'đẹp', '2024-11-10 10:10:22'),
(6, 1, 3, 4, 'vui vậy thôi chứ bố dặn con này', '2024-11-10 10:20:16'),
(8, 1, 3, 4, 'xấu vãi', '2024-11-10 10:21:24'),
(14, 1, 3, 5, 'gớm rứa mi', '2024-11-10 10:31:57'),
(16, 1, 3, 4, 'đẹp đó mi', '2024-11-10 10:34:33'),
(17, 2, 3, 1, 'được', '2024-11-10 10:35:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Bánh Quy '),
(2, 1, 'Bánh Ngọt'),
(3, 1, 'Bánh Kem'),
(4, 2, 'Socola đen'),
(5, 2, 'Socola sữa'),
(6, 2, 'Socola trắng'),
(7, 3, 'Khuôn bánh'),
(8, 3, 'Dụng cụ'),
(9, 4, 'Sale Today');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sub_images`
--

CREATE TABLE `sub_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sub_images`
--

INSERT INTO `sub_images` (`id`, `product_id`, `image_url`, `created_at`, `updated_at`) VALUES
(37, 1, 'https://th.bing.com/th?id=OIP.mEfUfIQbyRdCs_vXSbMtVAHaJO&w=223&h=279&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-08 12:49:38', '2024-11-08 14:14:30'),
(38, 1, 'https://th.bing.com/th?id=OIP.cnA3GVXLh-Puq2dG0U6ecgHaJ4&w=216&h=288&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-08 12:49:38', '2024-11-08 13:11:05'),
(41, 1, 'https://th.bing.com/th?id=OIP.0mV1QYtoDEmHOwruAAnbuAHaE8&w=305&h=204&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-08 13:13:12', '2024-11-08 13:13:12'),
(42, 1, 'https://th.bing.com/th?id=OIP.ImWlT4PFNtIW_jZphsIwWwHaHZ&w=250&h=249&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-08 13:13:12', '2024-11-08 13:13:12'),
(55, 9, 'uploads/1731303539_Screenshot 2024-04-03 215230.png', '2024-11-11 05:38:59', '2024-11-11 05:38:59'),
(86, 20, 'https://th.bing.com/th?id=OIP.HFGT9RrZ58fM3XdmpfKREwHaEo&w=316&h=197&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-11 15:12:47', '2024-11-11 15:12:47'),
(87, 21, 'https://th.bing.com/th?id=OIP.HFGT9RrZ58fM3XdmpfKREwHaEo&w=316&h=197&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-12 08:38:19', '2024-11-12 08:38:19'),
(88, 2, 'https://th.bing.com/th?id=OIP.ZuESn8NBV9XXYpLrisciXQHaHa&w=250&h=250&c=8&rs=1&qlt=90&o=6&dpr=1.3&pid=3.1&rm=2', '2024-11-27 00:45:14', '2024-11-27 00:45:14'),
(89, 2, 'https://th.bing.com/th/id/OIP.4Q33RwGhq3A1__Qa7VLImAHaHa?w=184&h=185&c=7&r=0&o=5&dpr=1.3&pid=1.7', '2024-11-27 00:45:29', '2024-11-27 00:45:29'),
(90, 2, 'https://th.bing.com/th/id/OIP.FX30V9YY2B8kdkHUG36eJgHaHI?w=157&h=181&c=7&r=0&o=5&dpr=1.3&pid=1.7', '2024-11-27 00:45:40', '2024-11-27 00:45:40'),
(91, 2, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAsJCQcJCQcJCQkJCwkJCQkJCQsJCwsMCwsLDA0QDBEODQ4MEhkSJRodJR0ZHxwpKRYlNzU2GioyPi0pMBk7IRP/2wBDAQcICAsJCxULCxUsHRkdLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCwsLCz/wAARCADrAMQDASIAAhEB', '2024-11-27 00:45:52', '2024-11-27 00:45:52'),
(92, 3, 'https://th.bing.com/th/id/OIP.Zo7Zxo1NWnhnLq8rCXy4TwHaHa?w=204&h=204&c=7&r=0&o=5&dpr=1.3&pid=1.7', '2024-11-27 00:47:03', '2024-11-27 00:47:03'),
(93, 22, 'https://th.bing.com/th/id/OIP.vEWn2PWs9tLmdqQSN1BLxgHaHa?pid=ImgDet&w=184&h=184&c=7&dpr=1.3', '2024-12-03 14:04:17', '2024-12-03 14:04:17'),
(94, 22, 'https://th.bing.com/th/id/OIP.6KxYD2tPjbqq_QLmiAmEGwHaHa?pid=ImgDet&w=184&h=184&c=7&dpr=1.3', '2024-12-03 14:05:14', '2024-12-03 14:05:14'),
(95, 22, 'https://th.bing.com/th/id/OIP.h6yDVOQc9U1EE_-1qmhIrAHaHa?pid=ImgDet&w=184&h=184&c=7&dpr=1.3', '2024-12-03 14:05:26', '2024-12-03 14:05:26'),
(96, 22, 'https://th.bing.com/th/id/OIP.M-CIYc9sEwZETLQzNf2xkgHaHa?pid=ImgDet&w=184&h=184&c=7&dpr=1.3', '2024-12-03 14:05:38', '2024-12-03 14:05:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `full_name`, `password`, `email`, `avatar_url`, `created_at`, `role`, `phone`, `address`) VALUES
(3, 'vanmanh3', 'le van manh', '$2y$10$wl6eJOBWuapfR4rRPE1Vee/VLGccxIspXlM7ANc9qGKgJk3zc8s5y', 'vanmanhh240325@gmail.com', './uploads/1684851456698-chanelbeautybookpng_800x1300.webp', '2024-11-09 15:14:28', 'user', '123545678', 'ádasd'),
(8, 'vanmanh1', 'admin', '$2y$10$Ppt5bYUdbtNc5DwjOPxA7.II6POSXydorD.Teh7siPMMWzZ/4v9Yq', 'admsssin@gmail.com', './uploads/Screenshot 2024-04-08 221808.png', '2024-11-09 15:55:31', 'admin', '33355757', 'ádasd'),
(9, 'vanmanh4', 'le van manh', '$2y$10$FUSE5nxm/PKn2OXhaVFkj.HL89PmVdoQqbwAm/w14dGNdWmHdGJ92', 'asdasdasd@gmail.com', NULL, '2024-11-11 02:41:42', 'admin', '123545678', 'ádasd'),
(15, 'anhlongdeptrai3', 'le van long', '$2y$10$3xiiWG29RruQkivzSI.E5exWb/LTMjcO0ust9YxEY41lrXqyIlMNa', 'vanmanhh240329@gmail.com', NULL, '2024-11-11 03:05:36', 'user', '0905266784', 'ádasd'),
(17, 'levanmanh', 'manhlvpk03670@gmail.com', '$2y$10$erXv5H4GA8T8jk6TZFIyZe/xLxqd7emG7IDvnxOWDMbgn6THDxu5y', 'manhlvpk03670@gmail.com', NULL, '2024-12-03 07:19:04', 'user', '0905266784', 'ádasd');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_code` (`coupon_code`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `order_reviews`
--
ALTER TABLE `order_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `sub_images`
--
ALTER TABLE `sub_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `order_reviews`
--
ALTER TABLE `order_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `sub_images`
--
ALTER TABLE `sub_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_code`) REFERENCES `coupons` (`code`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `order_details_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `order_reviews`
--
ALTER TABLE `order_reviews`
  ADD CONSTRAINT `order_reviews_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_reviews_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `sub_images`
--
ALTER TABLE `sub_images`
  ADD CONSTRAINT `sub_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
