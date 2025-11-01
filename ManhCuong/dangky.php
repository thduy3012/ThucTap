<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Ký</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/dangky.css">
</head>

<body>
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
                                <label for="agree-term" class="label-agree-term">
                                    <span class="custom-checkbox">
                                        <span class="checkbox-inner"></span>
                                    </span>
                                    Tôi đồng ý với tất cả <a href="dieukhoan.html" class="term-service">Điều khoản</a>
                                </label>
                            </div>

                            <div class="form-group form-button">
                                <button type="submit" name="register" id="signup" class="form-submit">Đăng Ký</button>
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

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="assets/js/script1.js"></script>
</body>

</html>