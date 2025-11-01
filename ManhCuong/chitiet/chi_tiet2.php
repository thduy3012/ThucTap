<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID Khách Hàng từ URL
$ID_KhachHang = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Truy vấn thông tin chi tiết của khách hàng
$sql_khachhang = "SELECT * FROM KhachHang WHERE ID_KhachHang = $ID_KhachHang";
$result_khachhang = $conn->query($sql_khachhang);
$khachhang = $result_khachhang->fetch_assoc();

// Truy vấn thông tin các dịch vụ mà khách hàng đã đăng ký
$sql_dichvu = "SELECT DichVu.TenDichVu, GoiDichVu.TenGoiDichVu, GoiDichVu.GiaTien, ThongTinBanHang.SoLuong, ThongTinBanHang.NgayDangKy, TTNhanVienBanHang.TenNhanVien
               FROM ThongTinBanHang
               JOIN GoiDichVu ON ThongTinBanHang.ID_GoiDichVu = GoiDichVu.ID_GoiDichVu
               JOIN DichVu ON GoiDichVu.ID_DichVu = DichVu.ID_DichVu
               JOIN TTNhanVienBanHang ON ThongTinBanHang.ID_TTNVBH = TTNhanVienBanHang.ID_TTNVBH
               WHERE ThongTinBanHang.ID_KhachHang = $ID_KhachHang";
$result_dichvu = $conn->query($sql_dichvu);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Chi Tiết Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body> -->
<?php include '../menu.php'; ?>
<div class="container-fluid">
    <h2 class="mt-5">Thông Tin Chi Tiết Khách Hàng</h2>
    <?php if ($khachhang) : ?>
        <div class="card mb-3">
            <div class="card-header">
                <h4><i class="fa-regular fa-user"></i> <strong><?php echo htmlspecialchars($khachhang['Ten']); ?></strong></h4>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($khachhang['SoDienThoai']); ?></p>
                <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($khachhang['DiaChi']); ?></p>
            </div>
        </div>

        <h3>Dịch Vụ Đã Đăng Ký</h3>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>STT</th>
                    <th>Tên Dịch Vụ</th>
                    <th>Tên Gói Dịch Vụ</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                    <th>Ngày Đăng Ký</th>
                    <th>Nhân Viên Bán</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_dichvu->num_rows > 0) : ?>
                    <?php $counter = 1;  
                        while ($row = $result_dichvu->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $counter++; ?></td>
                            <td><?php echo htmlspecialchars($row['TenDichVu']); ?></td>
                            <td><?php echo htmlspecialchars($row['TenGoiDichVu']); ?></td>
                            <td><?php echo htmlspecialchars($row['GiaTien']); ?></td>
                            <td><?php echo htmlspecialchars($row['SoLuong']); ?></td>
                            <td><?php echo htmlspecialchars($row['NgayDangKy']); ?></td>
                            <td><?php echo htmlspecialchars($row['TenNhanVien']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center">Khách hàng chưa đăng ký dịch vụ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-center">Không tìm thấy thông tin khách hàng.</p>
    <?php endif; ?>
    <a href="../danhsach/danh_sach_thong_tin_khach_hang.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->