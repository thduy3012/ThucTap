<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Kết nối cơ sở dữ liệu
    include('connect.php');

    // Lấy dữ liệu từ form
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username = $_POST['name'];
    $password = $_POST['pass'];
    $re_password = $_POST['re_pass'];

    // Kiểm tra mật khẩu nhập lại
    if ($password != $re_password) {
        $_SESSION['error_message'] = "Mật khẩu nhập lại không khớp!";
        header("Location: dangky.php");
        exit();
    }

    // Kiểm tra xem số điện thoại đã tồn tại chưa
    $stmt = $conn->prepare("SELECT * FROM khachhang WHERE SoDienThoai = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Số điện thoại đã tồn tại!";
        $stmt->close();
        $conn->close();
        header("Location: dangky.php");
        exit();
    }
    $stmt->close();

    // Chèn dữ liệu vào bảng khachhang
    $stmt = $conn->prepare("INSERT INTO khachhang (Ten, SoDienThoai, DiaChi, Username, Password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $phone, $address, $username, $password);

    if ($stmt->execute()) {
        // Đăng ký thành công, lưu thông tin vào session và chuyển hướng
        $_SESSION['ID_KhachHang'] = $conn->insert_id;
        $_SESSION['TenDangNhap'] = $username;
        header("Location: khachhang.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Đăng ký thất bại, vui lòng thử lại!";
        header("Location: dangky.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Đăng Ký</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/vnpt.png" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/test.css" rel="stylesheet">

  <!-- Font Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

    <!-- main -->
    <div class="main">
        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Đăng Ký</h2>

                        <?php
                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                            unset($_SESSION['error_message']);
                        }
                        ?>

                        <form method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="fullname"><i class="fas fa-user"></i></label>
                                <input type="text" name="fullname" id="fullname" placeholder="Họ Tên" required />
                            </div>
                            <div class="form-group">
                                <label for="phone"><i class="fas fa-phone"></i></label>
                                <input type="tel" name="phone" id="phone" placeholder="Số Điện Thoại" required />
                            </div>
                            <div class="form-group">
                                <label for="address"><i class="fas fa-home"></i></label>
                                <input type="text" name="address" id="address" placeholder="Địa Chỉ" required />
                            </div>
                            <div class="form-group">
                                <label for="name"><i class="fas fa-user-tag"></i></label>
                                <input type="text" name="name" id="name" placeholder="Tên Đăng Nhập" required />
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="fas fa-lock"></i></label>
                                <input type="password" name="pass" id="pass" placeholder="Mật Khẩu" required />
                                <span class="toggle-password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="fas fa-lock"></i></label>
                                <input type="password" name="re_pass" id="re_pass" placeholder="Nhập Lại Mật Khẩu" required />
                                <span class="toggle-password" onclick="togglePassword('re_pass')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>

                            <div class="form-group">
                                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required />
                                <label for="agree-term" class="label-agree-term mt-2">
                                    <span class="custom-checkbox">
                                        <span class="checkbox-inner"></span>
                                    </span>
                                    Tôi đồng ý với tất cả <a href="dieukhoan.html" class="term-service">Điều khoản</a>
                                </label>
                            </div>

                            <div class="form-group form-button">
                                <button type="submit" name="register" id="signup" class="form-submit mt-3">Đăng Ký</button>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="assets/img/hinh2.jpg" alt="sing up image"></figure>
                        <a href="dangnhap.php" class="signup-image-link">Tôi đã có tài khoản</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
        <!-- end main -->

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
    <script src="assets/js/script1.js"></script>

</body>

</html>