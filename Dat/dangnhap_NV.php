<?php
session_start();

// Kết nối cơ sở dữ liệu
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT ID_NhanVien, Password FROM nhanvien WHERE Username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        // if (password_verify($password, $row['Password'])) {
        if ($password == $row['Password']) {
            // Lưu thông tin nhân viên vào session
            $_SESSION['ID_NhanVien'] = $row['ID_NhanVien'];
            header("Location: ./danhsach/danh_sach_thong_tin_khach_hang.php");
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
            echo "<script>alert('$error');</script>";
            header("refresh:0.5; url=dangnhap_NV.php");
            exit();
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        echo "<script>alert('$error');</script>";
        header("refresh:0.5; url=dangnhap_NV.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng nhập</title>

    <link href="assets/img/vnpt.png" rel="icon">
    <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <!-- Font Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/dangnhapTest.css">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex justify-content-between">

        <div class="logo">
            <!-- Uncomment below if you prefer to use an text logo -->
            <!-- <h1><a href="index.html">NewBiz</a></h1> -->
            <a href="trangchu.php"><img src="assets/img/logovnpt.png" alt="" class="img-fluid"></a>
        </div>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="index.php">Trang Chủ</a></li>
                <li><a class="nav-link scrollto" href="#about">Về Chúng Tôi</a></li>
                <li><a class="nav-link scrollto" href="#services">Dịch Vụ</a></li>
                <li><a class="nav-link scrollto " href="#portfolio">Portfolio</a></li>
                <li><a class="nav-link scrollto" href="#team">Đội Ngũ</a></li>
                
                <li><a class="nav-link scrollto" href="#contact">Liên Hệ</a></li>
                    <li class="dropdown">
                        <a href="#"><span>Thành Viên</span> <i class="bi bi-chevron-down"></i></a>
                        <ul>
                            <li><a href="dangky.php">Đăng ký</a></li>
                            <li><a href="dangnhap.php">Đăng nhập</a></li>
                        </ul>
                    </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

        </div>
    </header><!-- #header -->
    <div class="main">

        <!-- Sign in Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="assets/img/hinh1.jpg" alt="sign up image"></figure>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Đăng nhập</h2>
                        <form method="POST" class="register-form" id="login-form">
                            <div class="form-group">
                                <div class="icon"><i class="zmdi zmdi-account"></i></div>
                                <input type="text" name="username" id="your_name" placeholder="Tên Đăng Nhập" required />
                            </div>

                            <div class="form-group">
                                <div class="icon"><i class="zmdi zmdi-lock"></i></div>
                                <input type="password" name="password" id="your_pass" placeholder="Mật Khẩu" required />
                            </div>

                            <div class="form-group form-button">
                                <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                            </div>
                        </form>
                        <div class="social-login">
                            <span class="social-label">Đăng Nhập Với:</span>
                            <a href="dangnhap.php" class="customer-account"><i class="fas fa-user"></i> Tài Khoản Khách Hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
        <div class="container">
            <div class="row">

            <div class="col-lg-4 col-md-6 footer-info">
                <h3>VNPT</h3>
                <p>Với nhiều năm hình thành, phát triển và gắn bó với thị trường viễn thông tại Việt Nam, Tập đoàn Bưu chính
                Viễn thông VNPT hiện là một trong những nhà cung cấp các dịch vụ internet uy tín hàng đầu.</p>
            </div>

            <div class="col-lg-2 col-md-6 footer-links">
                <h4>Liên Kết Hữu Ích</h4>
                <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Về chúng tôi</a></li>
                <li><a href="#">Dịch vụ</a></li>
                <li><a href="#">Điều khoản dịch vụ</a></li>
                <li><a href="#">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 footer-contact">
                <h4>Liên Hệ</h4>
                <p>
                11 Phan Đình Phùng, Tân An, Ninh Kiều<br>
                Cần Thơ 92000<br>
                Việt Nam <br>
                <strong>Điện thoại:</strong> 0913 737 475<br>
                <strong>Email:</strong> vnpt@gmail.com<br>
                </p>

                <div class="social-links">
                    <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                </div>

            </div>

            <div class="col-lg-3 col-md-6 footer-newsletter">
                <h4>Bản Tin Của Chúng Tôi</h4>
                <p>VNPT là nhà cung cấp mạng uy tín hàng đầu tại Việt Nam với hơn 60 năm kinh nghiệm phát triển, đi đầu về
                công nghệ, chất lượng dịch vụ và chăm sóc khách hàng. Cáp quang VNPT ứng dụng công nghệ mới nhất trên thế
                giới là Gpon/AON cho chất lượng đường truyền nhanh và ổn định.</p>
                <!-- <form action="" method="post">
                <input type="email" name="email"><input type="submit" value="Đăng Ký">
                </form> -->
            </div>

            </div>
        </div>
        </div>

        <div class="container">
        <div class="copyright">
            &copy; Copyright <strong>VNPT</strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=NewBiz
        -->
            <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
        </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="assets/vendor/aos/aos.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

</body>

</html>