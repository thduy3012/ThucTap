<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: ../dangnhap_NV.php");
    exit;
}

// Check if service and period are set
if (!isset($_GET['service']) || !isset($_GET['period'])) {
    die("Invalid request");
}

$ID_DichVu = $_GET['service'];
$period = $_GET['period'];

// Connect to the database
include('../connect.php');

// Tạo truy vấn SQL tương ứng với khoảng thời gian
switch ($period) {
    case 'week':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                thongtinbanhang AS ttb
            JOIN 
                goidichvu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                dichvu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                ttnhanvienbanhang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND ttb.NgayDangKy BETWEEN DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY) 
                AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 6 DAY);";
        break;
    case 'month':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                thongtinbanhang AS ttb
            JOIN 
                goidichvu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                dichvu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                ttnhanvienbanhang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND MONTH(ttb.NgayDangKy) = MONTH(CURDATE())
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE());";
        break;
    case 'year':
        $sql = "SELECT 
                ttb.NgayDangKy, 
                dv.TenDichVu, 
                gdv.TenGoiDichVu, 
                gdv.GiaTien, 
                ttb.SoLuong, 
                nv.TenNhanVien,
                (gdv.GiaTien * ttb.SoLuong) AS TongTien
            FROM 
                thongtinbanhang AS ttb
            JOIN 
                goidichvu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                dichvu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                ttnhanvienbanhang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
            WHERE 
                dv.ID_DichVu = $ID_DichVu
                AND YEAR(ttb.NgayDangKy) = YEAR(CURDATE());";
        break;
}

// Thực hiện truy vấn
$result = $conn->query($sql);
?>

<!-- <!DOCTYPE html>
<html>
<head>
    <title>Chi Tiết Doanh Thu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container"> -->
    <?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-chart-line"></i> Chi Tiết Doanh Thu</h2>
        </div>
        <div class="card-body p-5">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th scope="col" class="text-right">Ngày Đăng Ký</th>
                            <th scope="col" class="text-center">Tên Dịch Vụ</th>
                            <th scope="col" class="text-center">Tên Gói Dịch Vụ</th>
                            <th scope="col" class="text-right">Giá Tiền</th>
                            <th scope="col" class="text-center">Số Lượng</th>
                            <th scope="col" class="text-center">Tên Nhân Viên</th>
                            <th scope="col" class="text-right">Tổng Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-right'>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                                echo "<td class='text-right'>" . htmlspecialchars($row['GiaTien']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['SoLuong']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                                echo "<td class='text-right'>" . number_format($row['TongTien'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <form action="../xuat/xuat_excel_doanh_thu.php" method="post" class="mt-4">
                <input type="hidden" name="service" value="<?php echo htmlspecialchars($ID_DichVu); ?>">
                <input type="hidden" name="period" value="<?php echo htmlspecialchars($period); ?>">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </button>
            </form>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<!-- </div>
</body>

</html> -->