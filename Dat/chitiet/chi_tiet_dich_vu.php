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
include('../connect.php');

// Lấy ID dịch vụ từ URL
$id = $_GET['id'];

// Truy vấn thông tin dịch vụ dựa trên ID
$sqlDichVu = "SELECT * FROM dichvu WHERE ID_DichVu = ?";
$stmtDichVu = $conn->prepare($sqlDichVu);
$stmtDichVu->bind_param("i", $id);
$stmtDichVu->execute();
$resultDichVu = $stmtDichVu->get_result();
$dichVu = $resultDichVu->fetch_assoc();

// Truy vấn thông tin các gói dịch vụ thuộc dịch vụ tương ứng
$sqlGoiDichVu = "SELECT * FROM goidichvu WHERE ID_DichVu = ?";
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
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0">Dịch Vụ <?php echo htmlspecialchars($dichVu['TenDichVu']); ?> Hiện Có Các Gói Cước</h2>
        </div>
        <div class="card-body p-5">
            <a href="../danhsach/danh_sach_thong_tin_dich_vu.php" class="btn btn-secondary mb-4">
                <i class="bi bi-backspace"></i> Quay Lại
            </a>
            <a href="../them/them_goi_cuoc.php?idDichVu=<?php echo $id; ?>" class="btn btn-primary mb-4 ml-2">
                <i class="bi bi-plus-circle"></i> Thêm Gói Cước
            </a>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped mt-3 rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th scope="col" class="text-center">STT</th>
                            <th scope="col" class="text-center">Tên Gói Dịch Vụ</th>
                            <th scope="col" class="text-center">Tốc Độ</th>
                            <th scope="col" class="text-right">Giá</th>
                            <th scope="col" class="text-center">Mô Tả</th>
                            <th scope="col" class="text-center">Tùy Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1;
                        while ($row = $resultGoiDichVu->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>" . $count++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                            if (!empty($row['TocDo']) && $row['TocDo'] != 0) {
                                echo "<td>" . htmlspecialchars($row['TocDo'] . " Mbps") . "</td>";
                            } else {
                                echo "<td>0</td>";
                            }
                            echo "<td class='text-right'>" . number_format($row['GiaTien'], 0, ',', '.') . "</td>";
                            echo "<td>" . htmlspecialchars($row['MoTa']) . "</td>";
                            echo "<td class='text-center'>
                                <a href='../sua/sua_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-warning mb-1 mr-1 btn-sm'>
                                    <i class='bi bi-pencil'></i> Sửa
                                </a>
                                <a href='../xoa/xoa_goi_cuoc.php?id=" . $row['ID_GoiDichVu'] . "' class='btn btn-danger mb-1 btn-sm'>
                                    <i class='bi bi-trash'></i> Xóa
                                </a>
                            </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

    <!-- </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->