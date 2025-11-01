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
include('../connect.php');

// Truy vấn chi tiết nhân viên bán hàng
$sql_nhanvien = "SELECT TenNhanVien, SoDienThoai, DiaChi FROM ttnhanvienbanhang WHERE ID_TTNVBH = $ID_TTNVBH";
$result_nhanvien = $conn->query($sql_nhanvien);

if ($result_nhanvien->num_rows == 0) {
    die("Nhân viên không tồn tại.");
}

$nhanvien = $result_nhanvien->fetch_assoc();

// Pagination setup
$limit = 10; // Số bản ghi hiển thị trên mỗi trang.
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn chi tiết các dịch vụ bán được của nhân viên
$sql = "SELECT 
    ttb.ID_ThongTinBanHang,
    ttb.NgayDangKy,
    kh.Ten AS TenKhachHang,
    dv.TenDichVu,
    gdv.TenGoiDichVu,
    ttb.SoLuong,
    (gdv.GiaTien * ttb.SoLuong) AS TongTien
FROM 
    thongtinbanhang AS ttb
JOIN 
    ttnhanvienbanhang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
JOIN 
    khachhang AS kh ON ttb.ID_KhachHang = kh.ID_KhachHang
JOIN 
    goidichvu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
    dichvu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
WHERE 
    ttb.ID_TTNVBH = $ID_TTNVBH
ORDER BY 
    ttb.NgayDangKy DESC
LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Fetch total number of rows in the table
$sql_total = "SELECT COUNT(*) AS total FROM thongtinbanhang WHERE ID_TTNVBH = $ID_TTNVBH";
$total_result = $conn->query($sql_total);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];

// Tính tổng số trang
$total_pages = ceil($total_rows / $limit);

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
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-user-tie"></i> Chi Tiết Nhân Viên Bán Hàng</h2>
        </div>
        <div class="card-body p-5">
            <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="btn btn-secondary mr-2">
                <i class="bi bi-backspace"></i> Quay Lại
            </a>
            <div class="card mb-3 mt-3">
                <div class="card-header bg-info text-white">
                    <h4><i class="fas fa-user-tie"></i> <strong><?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?></strong></h4>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?></p>
                    <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($nhanvien['DiaChi']); ?></p>
                </div>
            </div>
            <h3 class="mt-5 bg-primary text-white text-center py-4">Các Dịch Vụ Bán Được:</h3>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th scope="col" class="text-center">STT</th>
                            <th scope="col" class="text-right">Ngày Đăng Ký</th>
                            <th scope="col" class="text-center">Tên Khách Hàng</th>
                            <th scope="col" class="text-center">Tên Dịch Vụ</th>
                            <th scope="col" class="text-center">Tên Gói Dịch Vụ</th>
                            <th scope="col" class="text-center">Số Lượng</th>
                            <th scope="col" class="text-right">Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = ($page - 1) * $limit + 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-center'>" . $counter++ . "</td>";
                                echo "<td class='text-right'>" . date("d/m/Y", strtotime($row['NgayDangKy'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenKhachHang']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['SoLuong']) . "</td>";
                                echo "<td class='text-right'>" . number_format($row['TongTien'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    // Define the range of pages to display
                    $range = 2;
                    $start = max(1, $page - $range);
                    $end = min($total_pages, $page + $range);

                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?id=$ID_TTNVBH&page=" . ($page - 1) . "'>Trước</a></li>";
                    }

                    if ($start > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?id=$ID_TTNVBH&page=1'>1</a></li>";
                        if ($start > 2) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='?id=$ID_TTNVBH&page=$i'>$i</a></li>";
                        }
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                        echo "<li class='page-item'><a class='page-link' href='?id=$ID_TTNVBH&page=$total_pages'>$total_pages</a></li>";
                    }

                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='?id=$ID_TTNVBH&page=" . ($page + 1) . "'>Tiếp</a></li>";
                    }
                    ?>
                </ul>
            </nav>


        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->