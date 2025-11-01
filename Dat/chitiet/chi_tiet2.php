<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Lấy ID Khách Hàng từ URL
$ID_KhachHang = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Truy vấn thông tin chi tiết của khách hàng
$sql_khachhang = "SELECT * FROM khachhang WHERE ID_KhachHang = $ID_KhachHang";
$result_khachhang = $conn->query($sql_khachhang);
$khachhang = $result_khachhang->fetch_assoc();

// Truy vấn thông tin các dịch vụ mà khách hàng đã đăng ký
$sql_dichvu = "SELECT dichvu.TenDichVu, goidichvu.TenGoiDichVu, goidichvu.GiaTien, thongtinbanhang.SoLuong, thongtinbanhang.NgayDangKy, ttnhanvienbanhang.TenNhanVien
               FROM thongtinbanhang
               JOIN goidichvu ON thongtinbanhang.ID_GoiDichVu = goidichvu.ID_GoiDichVu
               JOIN dichvu ON goidichvu.ID_DichVu = dichvu.ID_DichVu
               JOIN ttnhanvienbanhang ON thongtinbanhang.ID_TTNVBH = ttnhanvienbanhang.ID_TTNVBH
               WHERE thongtinbanhang.ID_KhachHang = $ID_KhachHang";
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
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-user"></i> Thông Tin Chi Tiết Khách Hàng</h2>
        </div>
        <div class="card-body p-5">
            <a href="../danhsach/danh_sach_thong_tin_khach_hang.php" class="btn btn-secondary mb-4">
                <i class="bi bi-backspace"></i> Quay Lại
            </a>
            <?php if ($khachhang) : ?>
                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h4><i class="fa-regular fa-user"></i> <strong><?php echo htmlspecialchars($khachhang['Ten']); ?></strong></h4>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($khachhang['SoDienThoai']); ?></p>
                        <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($khachhang['DiaChi']); ?></p>
                    </div>
                </div>

                <h3 class="mt-5 bg-primary text-white text-center py-4">Dịch Vụ Đã Đăng Ký</h3>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                        <thead class="bg-primary text-white text-center rounded-top">
                            <tr>
                                <th>STT</th>
                                <th>Tên Dịch Vụ</th>
                                <th>Tên Gói Dịch Vụ</th>
                                <th class="text-right">Giá</th>
                                <th>Số Lượng</th>
                                <th class="text-right">Ngày Đăng Ký</th>
                                <th>Nhân Viên Bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_dichvu->num_rows > 0) : ?>
                                <?php $counter = 1;
                                while ($row = $result_dichvu->fetch_assoc()) : ?>
                                    <tr>
                                        <td class="text-center"><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($row['TenDichVu']); ?></td>
                                        <td><?php echo htmlspecialchars($row['TenGoiDichVu']); ?></td>
                                        <td class="text-right"><?php echo number_format($row['GiaTien'], 0, ',', '.'); ?></td>
                                        <td class="text-center"><?php echo htmlspecialchars($row['SoLuong']); ?></td>
                                        <td class="text-right"><?php echo date('d/m/Y', strtotime($row['NgayDangKy'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['TenNhanVien']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">Khách hàng chưa đăng ký dịch vụ nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <p class="text-center">Không tìm thấy thông tin khách hàng.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->