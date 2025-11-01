<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php");
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Kiểm tra nếu có ID_TTBH
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_ban_hang.php");
    exit();
}

$id = $_GET['id'];

// Truy vấn thông tin bán hàng theo ID
$sql = "SELECT ThongTinBanHang.*, NhanVien.TenNhanVien AS TenNhanVien, KhachHang.Ten AS TenKhachHang, GoiDichVu.TenGoiDichVu AS TenGoiDichVu
        FROM thongtinbanhang
        LEFT JOIN nhanvien ON thongtinbanhang.ID_TTNVBH = nhanvien.ID_NhanVien
        LEFT JOIN khachhang ON thongtinbanhang.ID_KhachHang = khachhang.ID_KhachHang
        LEFT JOIN goidichvu ON thongtinbanhang.ID_GoiDichVu = goidichvu.ID_GoiDichVu
        WHERE thongtinbanhang.ID_ThongTinBanHang = ?";
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
$sqlNhanVien = "SELECT ID_NhanVien, TenNhanVien FROM nhanvien";
$resultNhanVien = $conn->query($sqlNhanVien);

// Lấy danh sách khách hàng từ cơ sở dữ liệu
$sqlKhachHang = "SELECT ID_KhachHang, Ten FROM khachhang";
$resultKhachHang = $conn->query($sqlKhachHang);

// Lấy danh sách gói dịch vụ từ cơ sở dữ liệu
$sqlGoiDichVu = "SELECT ID_GoiDichVu, TenGoiDichVu FROM goidichvu";
$resultGoiDichVu = $conn->query($sqlGoiDichVu);

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$ID_NhanVien = $_SESSION['ID_NhanVien']; // Lấy ID_NhanVien từ session
$sql_user = "SELECT TenNhanVien FROM nhanvien WHERE ID_NhanVien='$ID_NhanVien'";
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
    $sqlUpdate = "UPDATE thongtinbanhang SET ID_TTNVBH = ?, ID_KhachHang = ?, ID_GoiDichVu = ?, NgayDangKy = ?, nguoisua = ?, ngaysua = ? WHERE ID_ThongTinBanHang = ?";
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
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-edit"></i> Sửa Thông Tin Bán Hàng</h2>
        </div>
        <div class="card-body p-5">
            <form action="../sua/sua_thong_tin_ban_hang.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
                <div class="form-group">
                    <label for="ID_TTNVBH">Nhân viên bán hàng</label>
                    <select class="form-control select2" id="ID_TTNVBH" name="ID_TTNVBH" required>
                        <?php while ($row = $resultNhanVien->fetch_assoc()): ?>
                            <option value="<?php echo $row['ID_NhanVien']; ?>" <?php if ($row['ID_NhanVien'] == $thongTinBanHang['ID_TTNVBH']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['TenNhanVien']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ID_KhachHang">Khách hàng</label>
                    <select class="form-control select2" id="ID_KhachHang" name="ID_KhachHang" required>
                        <?php while ($row = $resultKhachHang->fetch_assoc()): ?>
                            <option value="<?php echo $row['ID_KhachHang']; ?>" <?php if ($row['ID_KhachHang'] == $thongTinBanHang['ID_KhachHang']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['Ten']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="ID_GoiDichVu">Gói dịch vụ</label>
                    <select class="form-control" id="ID_GoiDichVu" name="ID_GoiDichVu" required>
                        <?php while ($row = $resultGoiDichVu->fetch_assoc()): ?>
                            <option value="<?php echo $row['ID_GoiDichVu']; ?>" <?php if ($row['ID_GoiDichVu'] == $thongTinBanHang['ID_GoiDichVu']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['TenGoiDichVu']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="NgayBan">Ngày Bán</label>
                    <input type="date" class="form-control" id="NgayBan" name="NgayBan" value="<?php echo htmlspecialchars($thongTinBanHang['NgayDangKy']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                <a href="../danhsach/danh_sach_thong_tin_ban_hang.php" class="btn btn-secondary ml-2"><i class="fas fa-arrow-left"></i> Quay Lại</a>
            </form>
        </div>
    </div>
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
