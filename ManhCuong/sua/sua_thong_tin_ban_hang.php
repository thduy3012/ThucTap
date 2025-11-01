<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu có ID_TTBH
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_ban_hang.php");
    exit();
}

$id = $_GET['id'];

// Truy vấn thông tin bán hàng theo ID
$sql = "SELECT ThongTinBanHang.*, NhanVien.TenNhanVien AS TenNhanVien, KhachHang.Ten AS TenKhachHang, GoiDichVu.TenGoiDichVu AS TenGoiDichVu
        FROM ThongTinBanHang
        LEFT JOIN NhanVien ON ThongTinBanHang.ID_TTNVBH = NhanVien.ID_NhanVien
        LEFT JOIN KhachHang ON ThongTinBanHang.ID_KhachHang = KhachHang.ID_KhachHang
        LEFT JOIN GoiDichVu ON ThongTinBanHang.ID_GoiDichVu = GoiDichVu.ID_GoiDichVu
        WHERE ThongTinBanHang.ID_ThongTinBanHang = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$thongTinBanHang = $result->fetch_assoc();

if (!$thongTinBanHang) {
    header("Location: ../danhsach/danh_sach_ban_hang.php");
    exit();
}

// Lấy danh sách nhân viên từ cơ sở dữ liệu
$sqlNhanVien = "SELECT ID_NhanVien, TenNhanVien FROM NhanVien";
$resultNhanVien = $conn->query($sqlNhanVien);

// Lấy danh sách khách hàng từ cơ sở dữ liệu
$sqlKhachHang = "SELECT ID_KhachHang, Ten FROM KhachHang";
$resultKhachHang = $conn->query($sqlKhachHang);

// Lấy danh sách gói dịch vụ từ cơ sở dữ liệu
$sqlGoiDichVu = "SELECT ID_GoiDichVu, TenGoiDichVu FROM GoiDichVu";
$resultGoiDichVu = $conn->query($sqlGoiDichVu);

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

// Cập nhật thông tin bán hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ID_TTNVBH = $_POST['ID_TTNVBH'];
    $ID_KhachHang = $_POST['ID_KhachHang'];
    $ID_GoiDichVu = $_POST['ID_GoiDichVu'];
    $NgayBan = $_POST['NgayBan'];

    // Đặt múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    // Lấy ngày hiện tại
    $ngaySua = date("Y-m-d H:i:s");

    // Cập nhật thông tin bán hàng trong cơ sở dữ liệu
    $sqlUpdate = "UPDATE ThongTinBanHang SET ID_TTNVBH = ?, ID_KhachHang = ?, ID_GoiDichVu = ?, NgayDangKy = ?, nguoisua = ?, ngaysua = ? WHERE ID_ThongTinBanHang = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("iiisssi", $ID_TTNVBH, $ID_KhachHang, $ID_GoiDichVu, $NgayBan, $tenNhanVien, $ngaySua, $id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Cập nhật thành công.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_ban_hang.php");
        exit();
    } else {
        echo "<script>alert('Cập nhật thất bại.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Thông Tin Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-5">Sửa Thông Tin Bán Hàng</h2>
    <form action="../sua/sua_thong_tin_ban_hang.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
        <div class="form-group">
            <label for="ID_TTNVBH">Nhân viên bán hàng</label>
            <select class="form-control select2" id="ID_TTNVBH" name="ID_TTNVBH" required>
                <?php while ($row = $resultNhanVien->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_NhanVien']; ?>" <?php if ($row['ID_NhanVien'] == $thongTinBanHang['ID_TTNVBH'])
                               echo 'selected'; ?>>
                            <?php echo $row['TenNhanVien']; ?>
                        </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ID_KhachHang">Khách hàng</label>
            <select class="form-control select2" id="ID_KhachHang" name="ID_KhachHang" required>
                <?php while ($row = $resultKhachHang->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_KhachHang']; ?>" <?php if ($row['ID_KhachHang'] == $thongTinBanHang['ID_KhachHang'])
                               echo 'selected'; ?>>
                            <?php echo $row['Ten']; ?>
                        </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="ID_GoiDichVu">Gói dịch vụ</label>
            <select class="form-control" id="ID_GoiDichVu" name="ID_GoiDichVu" required>
                <?php while ($row = $resultGoiDichVu->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_GoiDichVu']; ?>" <?php if ($row['ID_GoiDichVu'] == $thongTinBanHang['ID_GoiDichVu'])
                               echo 'selected'; ?>>
                            <?php echo $row['TenGoiDichVu']; ?>
                        </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="NgayBan">Ngày Bán</label>
            <input type="date" class="form-control" id="NgayBan" name="NgayBan" value="<?php echo htmlspecialchars($thongTinBanHang['NgayDangKy']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary bi bi-floppy mr-2"> Lưu</button>
        <a href="../danhsach/danh_sach_thong_tin_ban_hang.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
    </form>
</div>
<?php include '../footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
</body>

</html>
