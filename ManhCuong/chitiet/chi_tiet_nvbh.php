<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra và nhận giá trị sqlChitiet
if (isset($_POST['sqlChitiet']) && isset($_POST['id'])) {
    $sqlChitiet = $_POST['sqlChitiet'];
    $ID_TTNVBH = $_POST['id'];
    $noiString = "AND ttb.ID_TTNVBH = $ID_TTNVBH";
    // echo "Giá trị sqlChitiet nhận được: " . htmlspecialchars($sqlChitiet);
    // echo "Giá trị id nhân viên nhận được: " . htmlspecialchars($ID_TTNVBH);
    // Thực hiện truy vấn với giá trị sqlChitiet ở đây
    $sqlChitietFull = $sqlChitiet . $noiString;
    $result = $conn->query($sqlChitietFull);
    // Truy vấn chi tiết nhân viên bán hàng
    $sql_nhanvien = "SELECT TenNhanVien, SoDienThoai, DiaChi FROM TTNhanVienBanHang WHERE ID_TTNVBH = $ID_TTNVBH";
    $result_nhanvien = $conn->query($sql_nhanvien);

    if ($result_nhanvien->num_rows == 0) {
        die("Nhân viên không tồn tại.");
    }
    $nhanvien = $result_nhanvien->fetch_assoc();



    $conn->close();
} else {
    echo "Không nhận được giá trị sqlChitiet";
}
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
<div class="content container-fluid">
    <h2 class="mt-5">Chi Tiết Nhân Viên Bán Hàng </h2> <br>
    <div class="card mb-3">
            <div class="card-header">
                <h4><i class="fa-solid fa-user-tie"></i> <strong><?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?></strong></h4>
            </div>
            <div class="card-body">
                <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?></p>
                <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($nhanvien['DiaChi']); ?></p>
            </div>
    </div>

    <h3 class="mt-5">Các Dịch Vụ Bán Được</h3>
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
            <?php $count=1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $count++ ."</td>";
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
    <a href="../top10/top10_nvbh.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->