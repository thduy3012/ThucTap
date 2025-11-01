<?php
session_start();
// Kiểm tra nếu khách hàng đã đăng nhập
if (!isset($_SESSION['ID_KhachHang'])) {
    header("Location: dangnhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID_KhachHang từ session
$ID_KhachHang = $_SESSION['ID_KhachHang'];

// Truy vấn SQL để lấy thông tin khách hàng dựa trên ID_KhachHang
$sql_user = "SELECT * FROM KhachHang WHERE ID_KhachHang='$ID_KhachHang'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $khachhang = $result_user->fetch_assoc();
} else {
    // Xử lý khi không tìm thấy thông tin khách hàng
    $khachhang = null;
}

// Xử lý khi biểu mẫu được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten = $_POST['Ten'];
    $soDienThoai = $_POST['SoDienThoai'];
    $diaChi = $_POST['DiaChi'];

    // Kiểm tra số điện thoại có bị trùng hay không
    $sql_check_phone = "SELECT * FROM KhachHang WHERE SoDienThoai=? AND ID_KhachHang!=?";
    $stmt_check_phone = $conn->prepare($sql_check_phone);
    $stmt_check_phone->bind_param("si", $soDienThoai, $ID_KhachHang);
    $stmt_check_phone->execute();
    $result_check_phone = $stmt_check_phone->get_result();

    if ($result_check_phone->num_rows > 0) {
        // Số điện thoại đã tồn tại
        $_SESSION['error_message'] = "Số điện thoại đã tồn tại, vui lòng chọn số điện thoại khác!";
    } else {
        // Cập nhật thông tin khách hàng trong cơ sở dữ liệu
        $sql_update = "UPDATE KhachHang SET Ten=?, SoDienThoai=?, DiaChi=? WHERE ID_KhachHang=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssi", $ten, $soDienThoai, $diaChi, $ID_KhachHang);

        if ($stmt->execute()) {
            // Cập nhật thành công
            $_SESSION['success_message'] = "Cập nhật thông tin thành công!";
        } else {
            // Cập nhật thất bại
            $_SESSION['error_message'] = "Cập nhật thông tin thất bại, vui lòng thử lại!";
        }

        $stmt->close();
    }

    $stmt_check_phone->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'menu_kh.php'; ?>
    <div class="container">
        <h2 class="mt-5">Sửa Thông Tin Khách Hàng</h2>
        <?php if (isset($_SESSION['success_message'])) : ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
            <script>
                setTimeout(function() {
                    document.querySelector('.alert-success').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])) : ?>
            <div class="alert alert-danger">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
            <script>
                setTimeout(function() {
                    document.querySelector('.alert-danger').style.display = 'none';
                }, 3000);
            </script>
        <?php endif; ?>

        <?php if ($khachhang) : ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="Ten">Tên Khách Hàng</label>
                    <input type="text" class="form-control" id="Ten" name="Ten" value="<?php echo htmlspecialchars($khachhang['Ten']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="SoDienThoai">Số Điện Thoại</label>
                    <input type="text" class="form-control" id="SoDienThoai" name="SoDienThoai" value="<?php echo htmlspecialchars($khachhang['SoDienThoai']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="DiaChi">Địa Chỉ</label>
                    <input type="text" class="form-control" id="DiaChi" name="DiaChi" value="<?php echo htmlspecialchars($khachhang['DiaChi']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary bi bi-floppy mr-2"> Lưu</button>
            </form>
        <?php else : ?>
            <p class="text-center">Không tìm thấy thông tin khách hàng.</p>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>