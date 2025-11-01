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
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Congtyvienthong";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
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
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
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
                ThongTinBanHang AS ttb
            JOIN 
                GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
            JOIN 
                DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
            JOIN 
                TTNhanVienBanHang AS nv ON ttb.ID_TTNVBH = nv.ID_TTNVBH
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
<div class="content container-fluid">
    <h1>Chi Tiết Doanh Thu</h1>
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th>Ngày Đăng Ký</th>
                <th>Tên Dịch Vụ</th>
                <th>Tên Gói Dịch Vụ</th>
                <th>Giá Tiền</th>
                <th>Số Lượng</th>
                <th>Tên Nhân Viên</th>
                <th>Tổng Tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['GiaTien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SoLuong']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TongTien']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <form action="../xuat/xuat_excel_doanh_thu.php" method="post">
        <input type="hidden" name="service" value="<?php echo htmlspecialchars($ID_DichVu); ?>">
        <input type="hidden" name="period" value="<?php echo htmlspecialchars($period); ?>">
        <button type="submit" class="btn btn-success bi bi-file-earmark-arrow-down"> Xuất Excel</button>
    </form>
</div>
<?php include '../footer.php'; ?>
<!-- </div>
</body>

</html> -->