<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Pagination setup
$limit = 10; // Number of entries to show in a page.
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Prepare the search query if provided
$search_query = isset($_GET['search_query']) ? $conn->real_escape_string($_GET['search_query']) : '';
$sql_search = '';
if (!empty($search_query)) {
    $sql_search = " WHERE TenNhanVien LIKE '%$search_query%' OR SoDienThoai LIKE '%$search_query%' OR DiaChi LIKE '%$search_query%'";
}

// Fetch total number of rows in the table
$sql_total = "SELECT COUNT(*) AS total FROM ttnhanvienbanhang" . $sql_search;
$total_result = $conn->query($sql_total);
$total_row = $total_result->fetch_assoc();
$total_rows = $total_row['total'];

// Fetch the data for the current page
$sql = "SELECT * FROM ttnhanvienbanhang" . $sql_search . " ORDER BY TenNhanVien ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Calculate total pages
$total_pages = ceil($total_rows / $limit);

$conn->close();
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Thông Tin Nhân Viên Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-user-friends"></i> Danh Sách Thông Tin Nhân Viên Bán Hàng</h2>
        </div>
        <div class="card-body p-5">
            <form action="" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm..." name="search_query" value="<?php echo htmlspecialchars($search_query); ?>">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Tìm Kiếm
                        </button>
                        <a href="../them/them_nhan_vien_ban_hang.php" class="btn btn-primary ml-2" >
                            <i class="bi bi-person-plus"></i> Thêm Nhân Viên Bán Hàng Mới
                        </a>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-striped rounded shadow-sm">
                    <thead class="bg-primary text-white text-center rounded-top">
                        <tr>
                            <th scope="col" class="text-center">Tên Nhân Viên</th>
                            <th scope="col" class="text-right">Số Điện Thoại</th>
                            <th scope="col" class="text-center">Địa Chỉ</th>
                            <th scope="col" class="text-center">Lựa Chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['TenNhanVien']) . "</td>";
                                echo "<td class='text-right'>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['DiaChi']) . "</td>";
                                echo "<td class='text-center'>
                                        <a href='../chitiet/chi_tiet_nvbh2.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-info bi bi-info-circle btn-sm'> Xem Chi Tiết</a>
                                        <a href='../sua/sua_thong_tin_nhan_vien.php?id=" . $row['ID_TTNVBH'] . "' class='btn btn-warning bi bi-pencil ml-2 mr-2 btn-sm'> Sửa</a>
                                        <a href='#' onclick='confirmDelete_ttnv(" . $row['ID_TTNVBH'] . ")' class='btn btn-danger bi bi-trash btn-sm'> Xóa</a>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Không có dữ liệu</td></tr>";
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
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "&search_query=" . urlencode($search_query) . "'>Trước Đó</a></li>";
                    }

                    if ($start > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=1&search_query=" . urlencode($search_query) . "'>1</a></li>";
                        if ($start > 2) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                    }

                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='?page=$i&search_query=" . urlencode($search_query) . "'>$i</a></li>";
                        }
                    }

                    if ($end < $total_pages) {
                        if ($end < $total_pages - 1) {
                            echo "<li class='page-item'><span class='page-link'>...</span></li>";
                        }
                        echo "<li class='page-item'><a class='page-link' href='?page=$total_pages&search_query=" . urlencode($search_query) . "'>$total_pages</a></li>";
                    }

                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "&search_query=" . urlencode($search_query) . "'>Kế Tiếp</a></li>";
                    }
                    ?>
                </ul>
            </nav>
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