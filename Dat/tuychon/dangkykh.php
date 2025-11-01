<?php
session_start();

// Kết nối cơ sở dữ liệu
include ('../connect.php');

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: ../dangnhap_NV.php");
    exit;
}

// Lấy ID_NhanVien từ session
$ID_NhanVien = $_SESSION['ID_NhanVien'];

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$sql_user = "SELECT TenNhanVien FROM nhanvien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenNhanVien = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenNhanVien = "Không tìm thấy thông tin nhân viên";
}

$fullname = $phone = $address = $username1 = $password = $re_password = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {

    // Lấy dữ liệu từ form
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $username1 = $_POST['username1'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    // Kiểm tra mật khẩu nhập lại
    if ($password != $re_password) {
        $error = "Mật khẩu nhập lại không khớp!";
    } else {
        // Kiểm tra xem số điện thoại đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM khachhang WHERE SoDienThoai = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Số điện thoại đã tồn tại!";
        } else {
            $stmt->close();

            // Chèn dữ liệu vào bảng khachhang
            $stmt = $conn->prepare("INSERT INTO khachhang (Ten, SoDienThoai, DiaChi, Username, Password, nguoitao) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fullname, $phone, $address, $username1, $password, $tenNhanVien);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công!";
                echo "<script>alert('$success');</script>";
                header("refresh:0.5; url=../tuychon/dangkykh.php");
                exit();
            } else {
                $error = "Đăng ký thất bại, vui lòng thử lại!";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<?php include '../menu.php'; ?>
<link rel="stylesheet" href="../assets/css/dangkykh.css">
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-address-card"></i> Đăng Ký Khách Hàng</h2>
        </div>
        <div class="card-body p-5">
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
                <script>
                    setTimeout(function () {
                        document.querySelector('.alert').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-row">
                    <div class="form-group col-md-6 input-with-icon">
                        <label for="fullname">Họ tên</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
                        <i class="fas fa-user form-icon"></i>
                    </div>
                    <div class="form-group col-md-6 input-with-icon">
                        <label for="phone">Số điện thoại</label>

                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        <i class="fas fa-phone form-icon"></i>
                    </div>
                </div>
                <div class="form-group input-with-icon">
                    <label for="address">Địa chỉ</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                    <i class="fas fa-map-marker-alt form-icon"></i>
                </div>
                <div class="form-group input-with-icon">
                    <label for="username1">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username1" name="username1" value="<?php echo htmlspecialchars($username1); ?>" required>
                    <i class="fas fa-user-circle form-icon"></i>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6 input-with-icon">
                        <label for="password">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" value="<?php echo htmlspecialchars($password); ?>" name="password" required>
                        <!-- <i class="fas fa-lock form-icon"></i> -->
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                    </div>
                    <div class="form-group col-md-6 input-with-icon">
                        <label for="re_password">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" id="re_password" name="re_password" value="<?php echo htmlspecialchars($re_password); ?>" required>
                        <!-- <i class="fas fa-lock form-icon"></i> -->
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('re_password')"></i>
                    </div>
                </div>
                
                <div class="d-flex justify-content-center">
                    <button type="submit" name="register" class="btn btn-primary">Đăng Ký</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const eyeIcon = input.nextElementSibling;
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

<?php include '../footer.php'; ?>