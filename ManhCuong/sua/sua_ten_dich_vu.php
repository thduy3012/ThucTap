<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_dich_vu.php"); // Nếu không, chuyển hướng đến trang danh sách dịch vụ
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$ID_NhanVien = $_SESSION['ID_NhanVien']; // Lấy ID_NhanVien từ session
$sql_user = "SELECT TenNhanVien FROM NhanVien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenNhanVien = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenNhanVien = "Không tìm thấy thông tin nhân viên";
}

// Lấy ID dịch vụ từ URL
$id = $_GET['id'];

// Truy vấn thông tin dịch vụ dựa trên ID
$sqlDichVu = "SELECT * FROM DichVu WHERE ID_DichVu = ?";
$stmtDichVu = $conn->prepare($sqlDichVu);
$stmtDichVu->bind_param("i", $id);
$stmtDichVu->execute();
$resultDichVu = $stmtDichVu->get_result();
$dichVu = $resultDichVu->fetch_assoc();

// Xử lý khi người dùng submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenMoi = $_POST['tenMoi'];

    // Đặt múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    // Lấy ngày hiện tại
    $ngaySua = date("Y-m-d H:i:s");

    // Cập nhật tên dịch vụ trong cơ sở dữ liệu
    $sqlUpdate = "UPDATE DichVu SET TenDichVu = ?, nguoisua = ?, ngaysua = ? WHERE ID_DichVu = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssi", $tenMoi, $tenNhanVien, $ngaySua, $id);

    if ($stmtUpdate->execute()) {
        // Redirect đến trang chi tiết dịch vụ sau khi cập nhật thành công
        echo "<script>alert('Cập nhật thành công.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_dich_vu.php?id=$id");
        // header("Location: danh_sach_thong_tin_dich_vu.php?id=$id");
        exit();
    } else {
        echo "Cập nhật thất bại: " . $conn->error;
    }
}

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tên Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-5">Sửa Tên Dịch Vụ</h2>
    <form method="post">
        <div class="form-group">
            <label for="tenMoi">Tên Dịch Vụ Mới</label>
            <input type="text" class="form-control" id="tenMoi" name="tenMoi" value="<?php echo htmlspecialchars($dichVu['TenDichVu']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary bi bi-floppy mr-2"> Lưu</button>
        <a href="../danhsach/danh_sach_thong_tin_dich_vu.php?id=<?php echo $id; ?>" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
    </form>
</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->