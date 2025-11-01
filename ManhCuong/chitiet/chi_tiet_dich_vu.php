<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: danh_sach_thong_tin_dich_vu.php"); // Nếu không, chuyển hướng đến trang danh sách dịch vụ
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID dịch vụ từ URL
$id = $_GET['id'];

// Truy vấn thông tin dịch vụ dựa trên ID
$sqlDichVu = "SELECT * FROM DichVu WHERE ID_DichVu = ?";
$stmtDichVu = $conn->prepare($sqlDichVu);
$stmtDichVu->bind_param("i", $id);
$stmtDichVu->execute();
$resultDichVu = $stmtDichVu->get_result();
$dichVu = $resultDichVu->fetch_assoc();

// Truy vấn thông tin các gói dịch vụ thuộc dịch vụ tương ứng
$sqlGoiDichVu = "SELECT * FROM GoiDichVu WHERE ID_DichVu = ?";
$stmtGoiDichVu = $conn->prepare($sqlGoiDichVu);
$stmtGoiDichVu->bind_param("i", $id);
$stmtGoiDichVu->execute();
$resultGoiDichVu = $stmtGoiDichVu->get_result();

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Dịch Vụ</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid">
    <h2 class="mt-5">Dịch Vụ <?php echo htmlspecialchars($dichVu['TenDichVu']); ?> Hiện Có Các Gói Cước:</h2> <br>
    <!-- <h4>Các Gói Cước Hiện Có:</h4> -->
    <table class="table table-hover">
        <thead class="thead-light"> 
            <tr>
                <th>STT</th>
                <th>Tên Gói Dịch Vụ</th>
                <th>Tốc Độ</th>
                <th>Giá Tiền</th>
                <th>Mô Tả</th>
                <th>Tùy Chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php $count=1;
            while ($row = $resultGoiDichVu->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $count++ ."</td>";
                echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                if (!empty($row['TocDo']) && $row['TocDo'] != 0) {
                    echo "<td>" . htmlspecialchars($row['TocDo']." Mbps") . "</td>";
                } else {
                    echo "<td>0</td>";
                }
                echo "<td>" . htmlspecialchars($row['GiaTien']) . "</td>";
                echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
                echo "<td>
                    <a href='../sua/sua_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-warning bi bi-pencil'> Sửa</a>
                    <a href='../xoa/xoa_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-danger bi bi-trash ml-2'> Xóa</a>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../them/them_goi_cuoc.php?idDichVu=<?php echo $id; ?>" class="btn btn-primary bi bi-plus-circle"> Thêm Gói Cước</a>
    <a href="../danhsach/danh_sach_thong_tin_dich_vu.php" class="btn btn-secondary bi bi-backspace ml-2"> Quay Lại</a>
</div>
<?php include '../footer.php'; ?>
<!-- </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->