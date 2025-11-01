<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php");
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$ID_NhanVien = $_SESSION['ID_NhanVien']; // Lấy ID_NhanVien từ session
$sql_user = "SELECT TenNhanVien FROM NhanVien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenNhanVien = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenNhanVien = "Không tìm thấy thông tin nhân viên";
}

// Kiểm tra xem ID gói dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_goi_dich_vu.php");
    exit();
}

$id = $_GET['id'];

// Lấy thông tin gói dịch vụ từ CSDL
$sql = "SELECT * FROM GoiDichVu WHERE ID_GoiDichVu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$goiDichVu = $result->fetch_assoc();

// Kiểm tra xem gói dịch vụ có tồn tại không
if (!$goiDichVu) {
    header("Location: ../danhsach/danh_sach_goi_dich_vu.php");
    exit();
}

$idDichVu = $goiDichVu['ID_DichVu'];
$message = "";

// Xử lý khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $TenGoiDichVu = $_POST['TenGoiDichVu'];
    $TocDo = $_POST['TocDo']. " Mbps";
    $GiaTien = $_POST['GiaTien'];
    $MoTa = $_POST['MoTa'];
    // Cập nhật ảnh
    $target_dir = "./image/";
    $target_file = $target_dir . basename($_FILES["ImgURL"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $anhCapNhap = $target_file;

    // Đặt múi giờ Việt Nam
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    // Lấy ngày hiện tại
    $ngaySua = date("Y-m-d H:i:s");

    // Cập nhật thông tin gói dịch vụ vào CSDL
    $sqlUpdate = "UPDATE GoiDichVu SET TenGoiDichVu = ?, TocDo = ?, GiaTien = ?, MoTa = ?, ImgURL = ?, nguoisua = ?, ngaysua = ? WHERE ID_GoiDichVu = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sisssssi", $TenGoiDichVu, $TocDo, $GiaTien, $MoTa, $anhCapNhap, $tenNhanVien, $ngaySua, $id);

    if ($stmtUpdate->execute()) {
        echo "<script>alert('Cập nhật thành công.');</script>";
        header("refresh:0.5; url=../chitiet/chi_tiet_dich_vu.php?id=$idDichVu");
        exit();
    } else {
        $message = "Lỗi: " . $stmtUpdate->error;
    }
}

// Đóng kết nối
$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Gói Cước</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body> -->
<?php include '../menu.php'; ?>
<div class="container">
    <h2 class="mt-5">Sửa Gói Cước</h2>
    <form action="../sua/sua_goi_cuoc.php?id=<?php echo htmlspecialchars($id); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="TenGoiDichVu">Tên Gói Cước</label>
            <input type="text" class="form-control" id="TenGoiDichVu" name="TenGoiDichVu" value="<?php echo htmlspecialchars($goiDichVu['TenGoiDichVu']); ?>" required>
        </div>
        <div class="form-group">
            <label for="ImgURL">Ảnh ban đầu</label> <br>
            <!-- nối chuỗi cho link ảnh -->
            <img src="<?php echo "." . htmlspecialchars($goiDichVu['ImgURL']); ?>" class="blurred" alt="Hình ảnh">
            <br>
            <label for="ImgURL" class="form-label">Chọn ảnh mới</label>
            <br>
            
            <input type="file" class="form-control" id="ImgURL" name="ImgURL" onchange="previewImage(this);">
            <img id="preview" src="#" alt="Preview Image" style="display: none;">
        </div>
        <div class="form-group">
            <label for="TocDo">Tốc Độ</label>
            <input type="number" class="form-control" id="TocDo" name="TocDo" value="<?php echo htmlspecialchars($goiDichVu['TocDo']); ?>">
        </div>
        <div class="form-group">
            <label for="GiaTien">Giá Tiền</label>
            <input type="number" class="form-control" id="GiaTien" name="GiaTien" value="<?php echo htmlspecialchars($goiDichVu['GiaTien']); ?>" required>
        </div>
        <div class="form-group">
            <label for="MoTa">Mô Tả</label>
            <textarea class="form-control" id="MoTa" name="MoTa" rows="3"><?php echo htmlspecialchars($goiDichVu['MoTa']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary bi bi-floppy mr-2"> Lưu</button>
        <a href="../chitiet/chi_tiet_dich_vu.php?id=<?php echo htmlspecialchars($idDichVu); ?>" class="btn btn-secondary bi bi-backspace"> Quay
            Lại</a>
    </form>
    <?php if (!empty($message)) : ?>
        <div class="mt-3 alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
</div>
<?php include '../footer.php'; ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';

        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
</script>

</html> -->