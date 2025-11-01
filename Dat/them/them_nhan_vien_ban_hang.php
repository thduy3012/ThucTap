<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kết nối cơ sở dữ liệu
    include('../connect.php');

    // Lấy dữ liệu từ biểu mẫu
    $TenNhanVien = $conn->real_escape_string($_POST['TenNhanVien']);
    $SoDienThoai = $conn->real_escape_string($_POST['SoDienThoai']);
    $DiaChi = $conn->real_escape_string($_POST['DiaChi']);
    $ID_NhanVien = $_SESSION['ID_NhanVien']; // Sử dụng ID_NhanVien từ session

    // Kiểm tra số điện thoại đã tồn tại hay chưa
    $check_sql = "SELECT * FROM ttnhanvienbanhang WHERE SoDienThoai = '$SoDienThoai'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        // Nếu số điện thoại đã tồn tại, hiển thị thông báo lỗi
        echo "<div class='alert alert-danger' role='alert'>Số điện thoại đã tồn tại trong cơ sở dữ liệu!</div>";
    } else {
        // Nếu số điện thoại chưa tồn tại, tiến hành thêm nhân viên vào cơ sở dữ liệu
        $sql = "INSERT INTO ttnhanvienbanhang (TenNhanVien, SoDienThoai, DiaChi, ID_NhanVien) 
                VALUES ('$TenNhanVien', '$SoDienThoai', '$DiaChi', '$ID_NhanVien')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thêm thành công.');</script>";
            header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");// Redirect đến danh sách nhân viên bán hàng
            exit();
        } else {
            echo "Lỗi: " . $sql . "" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-user-plus"></i> Thêm Nhân Viên Bán Hàng Mới</h2>
        </div>
        <div class="card-body p-5">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="TenNhanVien" class="form-label">Tên Nhân Viên</label>
                    <input type="text" class="form-control" id="TenNhanVien" name="TenNhanVien" required>
                </div>
                <div class="form-group">
                    <label for="SoDienThoai" class="form-label">Số Điện Thoại</label>
                    <input type="number" class="form-control" id="SoDienThoai" name="SoDienThoai" required>
                </div>
                <div class="form-group">
                    <label for="DiaChi" class="form-label">Địa Chỉ</label>
                    <input type="text" class="form-control" id="DiaChi" name="DiaChi" required>
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="bi bi-floppy"></i> Lưu
                </button>
                <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="btn btn-secondary">
                    <i class="bi bi-backspace"></i> Quay Lại
                </a>
            </form>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->