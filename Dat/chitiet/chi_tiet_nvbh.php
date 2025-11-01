<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}
// kết nối csdl
include('../connect.php');

$records_per_page = 10; // Số lượng bản ghi trên mỗi trang

// Lấy trang hiện tại từ GET, nếu không có thì mặc định là trang 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// Kiểm tra và nhận giá trị sqlChitiet và ID_TTNVBH từ session hoặc form post
if (isset($_POST['sqlChitiet']) && isset($_POST['id'])) {
    $sqlChitiet = $_POST['sqlChitiet'];
    $ID_TTNVBH = $_POST['id'];
    $_SESSION['sqlChitiet'] = $sqlChitiet; // Lưu giá trị sqlChitiet vào session
    $_SESSION['ID_TTNVBH'] = $ID_TTNVBH; // Lưu giá trị ID_TTNVBH vào session
} elseif (isset($_SESSION['sqlChitiet']) && isset($_SESSION['ID_TTNVBH'])) {
    $sqlChitiet = $_SESSION['sqlChitiet'];
    $ID_TTNVBH = $_SESSION['ID_TTNVBH'];
} else {
    echo "Không nhận được giá trị sqlChitiet hoặc ID_TTNVBH";
    exit();
}

$noiString = "AND ttb.ID_TTNVBH = $ID_TTNVBH";

// Truy vấn để lấy tổng số bản ghi
$sqlCount = "SELECT COUNT(*) as total FROM (" . $sqlChitiet . " " . $noiString . ") as t";
$resultCount = $conn->query($sqlCount);
$total_records = $resultCount->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Truy vấn để lấy dữ liệu theo trang
$sqlChitietFull = $sqlChitiet . $noiString . " LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sqlChitietFull);

// Truy vấn chi tiết nhân viên bán hàng
$sql_nhanvien = "SELECT TenNhanVien, SoDienThoai, DiaChi FROM ttnhanvienbanhang WHERE ID_TTNVBH = $ID_TTNVBH";
$result_nhanvien = $conn->query($sql_nhanvien);

if ($result_nhanvien->num_rows == 0) {
    die("Nhân viên không tồn tại.");
}
$nhanvien = $result_nhanvien->fetch_assoc();

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
            <a href="../top10/top10_nvbh.php" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Quay Lại
            </a>
            <div class="card mb-3">
                <div class="card-header">
                    <h4><i class="fas fa-user-tie"></i> <strong><?php echo htmlspecialchars($nhanvien['TenNhanVien']); ?></strong></h4>
                </div>
                <div class="card-body">
                    <p class="card-text"><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($nhanvien['SoDienThoai']); ?></p>
                    <p class="card-text"><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($nhanvien['DiaChi']); ?></p>
                </div>
            </div>

            <h3 class="mt-5 bg-primary text-white text-center py-4">Các Dịch Vụ Bán Được</h3>
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
                        <?php $count = 1 + $offset;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='text-center'>" . $count++ . "</td>";
                                echo "<td class='text-right'>" . date("d/m/Y", strtotime($row['NgayDangKy'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenKhachHang']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['TenGoiDichVu']) . "</td>";
                                echo "<td class='text-center'>" . htmlspecialchars($row['SoLuong']) . "</td>";
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
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    // Define the range of pages to display
                    $range = 2;
                    $start = max(1, $current_page - $range);
                    $end = min($total_pages, $current_page + $range);

                    if ($current_page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($current_page - 1) . "'>Trước Đó</a></li>";
                    }

                    if ($start > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=1'>1</a></li>";
                        if ($start > 2) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $current_page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='?page=$i'>$i</a></li>";
                        }
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                        echo "<li class='page-item'><a class='page-link' href='?page=$total_pages'>$total_pages</a></li>";
                    }

                    if ($current_page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($current_page + 1) . "'>Kế Tiếp</a></li>";
                    }
                    ?>
                </ul>
            </nav>

        </div>
    </div>
</div>
<?php include '../footer.php'; ?>