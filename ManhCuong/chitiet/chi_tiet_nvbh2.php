<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

if (!isset($_GET['id'])) {
    die("ID nhân viên không hợp lệ.");
}

$ID_TTNVBH = intval($_GET['id']);

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn chi tiết nhân viên bán hàng
$sql_nhanvien = "SELECT TenNhanVien, SoDienThoai, DiaChi FROM TTNhanVienBanHang WHERE ID_TTNVBH = $ID_TTNVBH";
$result_nhanvien = $conn->query($sql_nhanvien);

if ($result_nhanvien->num_rows == 0) {
    die("Nhân viên không tồn tại.");
}

$nhanvien = $result_nhanvien->fetch_assoc();

// Truy vấn chi tiết nhân viên bán hàng
$sql = "SELECT 
    ttb.ID_ThongTinBanHang,
    ttb.NgayDangKy,
    kh.Ten AS TenKhachHang,
    dv.TenDichVu,
    gdv.TenGoiDichVu,
    ttb.SoLuong,
    (gdv.GiaTien * ttb.SoLuong) AS TongTien
FROM 
    ThongTinBanHang AS ttb
JOIN 
    TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
JOIN 
    KhachHang AS kh ON ttb.ID_KhachHang = kh.ID_KhachHang
JOIN 
    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
WHERE 
    ttb.ID_TTNVBH = $ID_TTNVBH";

$result = $conn->query($sql);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body> -->
<?php include '../menu.php'; ?>
<div class="container-fluid">
    <h2 class="mt-5">Chi Tiết Nhân Viên Bán Hàng </h2>
    <br>
    <div class="card mb-3">
            <div class="card-header">
                <h4><i class="fa-solid fa-user-tie"></i> <strong><?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?></strong></h4>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?></p>
                <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($nhanvien['DiaChi']); ?></p>
            </div>
    </div>

    <!-- <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th>Tên Nhân Viên</th>
                <th>Số Điện Thoại</th>
                <th>Địa Chỉ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?></td>
                <td><?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?></td>
                <td><?php echo htmlspecialchars($nhanvien['DiaChi']); ?></td>
            </tr>
        </tbody>
    </table> -->
    <h3 class="mt-5">Các Dịch Vụ Bán Được:</h3>
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Ngày Đăng Ký</th>
                <th>Tên Khách Hàng</th>
                <th>Tên Dịch Vụ</th>
                <th>Tên Gói Dịch Vụ</th>
                <th>Số Lượng</th>
                <th>Tổng Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $counter++ . "</td>";
                    echo "<td>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenKhachHang']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SoLuong']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TongTien']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->