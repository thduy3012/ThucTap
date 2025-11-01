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

// Truy vấn thông tin dịch vụ
$sql = "SELECT * FROM DichVu";
$result = $conn->query($sql);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid">
    <h2 class="mt-3">Danh Sách Thông Tin Dịch Vụ</h2><br>
    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th>Tên Dịch Vụ</th>
                <th>Tùy Chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                    echo "<td>
                        <a href='../chitiet/chi_tiet_dich_vu.php?id=" . $row['ID_DichVu'] . "' class='btn btn-info bi bi-info-circle'> Xem Chi Tiết</a>
                        <a href='../sua/sua_ten_dich_vu.php?id=" . $row['ID_DichVu'] . "' class='btn btn-warning bi bi-pencil ml-2 mr-2'> Sửa</a>
                        <a href='#' onclick='confirmDelete_dv(" . $row['ID_DichVu'] . ")' class='btn btn-danger bi bi-trash'> Xóa</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../them/them_dich_vu.php" class="btn btn-primary bi bi-plus-circle mb-3"> Thêm Dịch Vụ</a>
</div>
<?php include '../footer.php'; ?>
<!-- </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->