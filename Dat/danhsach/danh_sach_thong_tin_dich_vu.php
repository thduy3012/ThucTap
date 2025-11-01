<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Truy vấn thông tin dịch vụ
$sql = "SELECT * FROM dichvu";
$result = $conn->query($sql);

$conn->close();
?>

<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-wrench"></i> Danh Sách Thông Tin Dịch Vụ</h2>
        </div>
        <div class="card-body p-5">
            <div class="form-row mb-3">
                <div class="col">
                    <a href="../them/them_dich_vu.php" class="btn btn-primary btn-sm bi bi-plus-circle"> Thêm Dịch Vụ</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th scope="col" class="text-center">Tên Dịch Vụ</th>
                            <th scope="col" class="text-center">Tùy Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['TenDichVu']) . "</td>";
                                echo "<td class='text-center'>
                                    <a href='../chitiet/chi_tiet_dich_vu.php?id=" . $row['ID_DichVu'] . "' class='btn btn-info btn-sm'><i class='fas fa-info-circle'></i> Xem Chi Tiết</a>
                                    <a href='../sua/sua_ten_dich_vu.php?id=" . $row['ID_DichVu'] . "' class='btn btn-warning btn-sm ml-2 mr-2'><i class='fas fa-pencil-alt'></i> Sửa</a>
                                    <a href='#' onclick='confirmDelete_dv(" . $row['ID_DichVu'] . ")' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Xóa</a>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center'>Không có dữ liệu</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>
