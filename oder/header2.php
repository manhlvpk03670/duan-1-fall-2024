<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Khởi tạo session nếu chưa có
    }

    $cart_quantity = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product) {
            $cart_quantity += $product['quantity'];
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Cookie2 SHOP</title>

        <!-- Thêm Google Fonts với Lobster và Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/header.css">
        <style>
            .account-container {
                position: relative;
            }

            .welcome-text {
                font-size: 0.8rem;
                white-space: nowrap;
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .account-container:hover .welcome-text {
                text-decoration: underline;
            }

            /* Base styles */
            body {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                padding: 0;
                transition: padding-top 0.3s ease;
            }

            /* Top banner */
            .top-banner {
                font-size: 14px;
                padding: 8px;
                text-align: center;
            }

            /* Navbar styles */
            .navbar {
                padding: 1rem;
                transition: all 0.3s ease;
            }

            .navbar-brand {
                font-family: 'Lobster', cursive;
                color: #ffc107 !important;
                font-weight: bold;
                font-size: 1.5rem;
                margin-left: 15px;
            }

            .navbar.fixed-top {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 1030;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            /* Navigation links */
            .nav-link {
                color: #f8f9fa !important;
                transition: color 0.3s;
                padding: 0.5rem 1rem;
            }

            .nav-link:hover {
                color: #ffc107 !important;
            }

            /* Search form */
            .form-inline {
                margin: 0.5rem 0;
            }

            .form-inline .form-control {
                border-radius: 20px;
                width: 300px;
            }

            /* Icons */
            .nav-icon {
                color: #f8f9fa;
                margin: 0 10px;
                position: relative;
            }

            .nav-icon:hover {
                color: #ffc107;
            }

            /* Cart icon and dropdown */
            .cart-icon {
                position: relative;
                cursor: pointer;
            }

            .cart-count {
                position: absolute;
                top: -8px;
                right: -8px;
                background-color: #e74c3c;
                color: white;
                font-size: 12px;
                border-radius: 50%;
                padding: 2px 6px;
                min-width: 18px;
                text-align: center;
            }

            .cart-dropdown {
                width: 300px;
                max-height: 400px;
                overflow-y: auto;
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                padding: 1rem;
            }

            /* Account container */
            .account-container {
                margin: 0 10px;
            }

            .welcome-text {
                font-size: 0.75rem;
                max-width: 120px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Separator */
            .separator {
                color: rgba(255, 255, 255, 0.5);
                margin: 0 8px;
                display: inline-block;
            }

            /* Cookie icon animation */
            .rotate-icon {
                animation: rotateAndMove 2s ease-in-out infinite;
            }

            @keyframes rotateAndMove {
                0% {
                    transform: rotate(0deg);
                }

                100% {
                    transform: rotate(360deg);
                }
            }

            /* Mobile responsiveness */
            @media (max-width: 991px) {
                .navbar-brand {
                    margin-left: 0;
                    font-size: 1.25rem;
                }

                .navbar-toggler {
                    margin-right: 0;
                }

                .navbar-collapse {
                    background: #343a40;
                    padding: 1rem;
                    border-radius: 8px;
                    margin-top: 0.5rem;
                }

                .form-inline {
                    width: 100%;
                    justify-content: center;
                }

                .form-inline .form-control {
                    width: 100%;
                    margin-bottom: 0.5rem;
                }

                .separator {
                    display: none;
                }

                .nav-icon {
                    margin: 0.5rem 0;
                }

                .cart-dropdown {
                    width: 100%;
                    position: fixed;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border-radius: 12px 12px 0 0;
                }

                .account-container {
                    width: 100%;
                    text-align: center;
                    margin: 0.5rem 0;
                }

                .welcome-text {
                    max-width: none;
                }
            }

            /* Small mobile devices */
            @media (max-width: 576px) {
                .navbar {
                    padding: 0.5rem;
                }

                .navbar-brand {
                    font-size: 1.1rem;
                }

                .cart-count {
                    font-size: 10px;
                    padding: 1px 4px;
                    min-width: 16px;
                }

                .top-banner {
                    font-size: 12px;
                    padding: 5px;
                }
            }

            body {
                background-color: var(--background-light);
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            }
        </style>
        <!-- Favicon cho các trình duyệt hiện đại -->
        <link rel="icon" type="image/png" sizes="32x32" href="/duan/img/cc5.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/duan/img/cc5.png">

        <!-- Favicon cho iOS -->
        <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">

        <!-- Favicon dự phòng cơ bản -->
        <link rel="shortcut icon" href="favicon.ico">
    </head>

    <body>

        <!-- Thanh thông báo freeship -->
        <div class="top-banner bg-warning text-center py-2">
            FREESHIP mọi đơn hàng từ 500k trở lên. Nhập mã "FREESHIP"
        </div>

        <!-- Thanh điều hướng chính -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand d-flex align-items-center " href="../index.php">COOKIE2 </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style=" color: #f39c12; margin-right: 150px"> </span>
            </button>
            <i class="bi bi-cookie rotate-icon" style="font-size: 30px; color: #f39c12; margin: 0px"></i>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a href="../index.php" class="nav-link">Trang chủ</a></li>
                    <span class="separator">|</span> <!-- Dấu phân cách -->
                    <li class="nav-item"><a href="../product.php" class="nav-link">Sản phẩm</a></li>
                    <span class="separator">|</span> <!-- Dấu phân cách -->
                    <li class="nav-item"><a href="../contact.php" class="nav-link">Liên hệ</a></li>
                    <span class="separator">|</span> <!-- Dấu phân cách -->
                    <li class="nav-item"><a href="../about.php" class="nav-link">Giới thiệu</a></li>

                </ul>
                <!-- Form tìm kiếm -->

                <form class="form-inline mx-3" method="GET" action="../search.php">
                    <input class="form-control mr-sm-2" type="search" placeholder="Tìm sản phẩm..." aria-label="Search" name="search">
                </form>


                <!-- Icon tài khoản với welcome message bên dưới -->
                <div class="account-container text-center me-3">
                    <a href="../profile.php" class="text-white text-decoration-none d-flex flex-column align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person mb-1" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                        </svg>
                        <?php if (isset($_SESSION['username'])): ?>
                            <span class="welcome-text small">
                                hi, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </span>
                        <?php else: ?>
                            <span class="welcome-text small">
                                Đăng nhập ngay
                            <?php endif; ?>
                    </a>
                </div>

                <span class="separator text-white">|</span>
                <!-- Icon Thông báo -->
                <a href="../contacthistory.php" class="notification-icon ms-3 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
                    </svg>
                </a>
                <span class="separator">|</span> <!-- Dấu phân cách -->
                <!-- Icon Giỏ hàng -->
                <a href="../cart.php" class="cart-icon nav-icon ml-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-bag-check-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0m-.646 5.354a.5.5 0 0 0-.708-.708L7.5 10.793 6.354 9.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0z" />
                    </svg>
                    <span class="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>
                </a>


                <!-- Giỏ hàng mini -->
                <div class="cart-dropdown">
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <ul class="cart-items">
                            <?php foreach ($_SESSION['cart'] as $product_id => $product): ?>
                                <li class="cart-item">
                                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
                                    <div class="cart-item-details">
                                        <span class="cart-item-name"><?php echo $product['name']; ?></span>
                                        <span class="cart-item-quantity">Số lượng: <?php echo $product['quantity']; ?></span>
                                        <span class="cart-item-price"><?php echo number_format($product['price'], 0, ',', '.') . ' VND'; ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="cart-total">
                            <span>Tổng tiền:</span>
                            <span><?php echo number_format(array_sum(array_column($_SESSION['cart'], 'price')), 0, ',', '.') . ' VND'; ?></span>
                        </div>
                        <a href="../cart.php" class="btn btn-primary" style="width: 100%; margin: 5px; background-color: brown;">Vào trang giỏ hàng</a>
                    <?php else: ?>
                        <p>Giỏ hàng trống.</p>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

    </body>
    <script>
        // Lắng nghe sự kiện cuộn trang
        window.onscroll = function() {
            var navbar = document.querySelector('.navbar');
            if (window.pageYOffset > 100) { // Nếu cuộn trang xuống hơn 100px
                navbar.classList.add('fixed-top');
            } else {
                navbar.classList.remove('fixed-top');
            }
        };
    </script>

    </html>