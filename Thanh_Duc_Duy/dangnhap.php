<?php
session_start();

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);

    // Truy vấn kiểm tra thông tin đăng nhập
    $sql = "SELECT ID_KhachHang, Password FROM khachhang WHERE Username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        // if (password_verify($password, $row['Password'])) {
        if ($password == $row['Password']) {
            // Lưu thông tin nhân viên vào session
            $_SESSION['ID_KhachHang'] = $row['ID_KhachHang'];
            header("Location: khachhang.php");
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
            echo "<script>alert('$error');</script>";
            header("refresh:0.5; url=dangnhap.php");
            exit();
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng.";
        echo "<script>alert('$error');</script>";
        header("refresh:0.5; url=dangnhap.php");
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

    <!-- Font Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/dangnhap.css">
</head>

<body>

    <div class="main">

        <!-- Sign in Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="assets/img/hinh3.jpg" alt="sign up image"></figure>
                        <a href="dangky.php" class="signup-image-link">Tạo tài khoản</a>
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
                            <a href="dangnhap_NV.php" class="customer-account"><i class="fas fa-user"></i> Tài Khoản Nhân Viên</a>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
</body>

</html>