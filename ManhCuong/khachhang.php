<?php
session_start();

if (!isset($_SESSION['ID_KhachHang'])) {
    header("Location: dangnhap.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$ID_KhachHang = $_SESSION['ID_KhachHang'];

$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT 
dv.TenDichVu,
gdv.TenGoiDichVu,
gdv.TocDo,
gdv.GiaTien,
gdv.MoTa,
ttb.NgayDangKy,
ttb.SoLuong
FROM 
KhachHang AS kh
JOIN 
ThongTinBanHang AS ttb ON kh.ID_KhachHang = ttb.ID_KhachHang
JOIN 
GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
JOIN 
DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
WHERE 
kh.ID_KhachHang = $ID_KhachHang
ORDER BY 
ttb.NgayDangKy;
";
$result = $conn->query($sql);

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Khách Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container"> -->
<?php include 'menu_kh.php'; ?>
<div class="container">
    <h2 class="mt-3">Danh Sách Dịch Vụ Bạn Đăng Kí</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên Dịch Vụ</th>
                <th>Tên Gói Dịch Vụ</th>
                <th>Tốc Độ</th>
                <th>Giá Tiền</th>
                <th>Mô Tả</th>
                <th>Ngày Đăng Kí</th>
                <th>Số Lượng</th>

            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['TocDo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['GiaTien']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NgayDangKy']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['SoLuong']) . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>
<?php include 'footer.php'; ?>