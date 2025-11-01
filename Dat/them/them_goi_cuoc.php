<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: dangnhap_NV.php");
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['idDichVu'])) {
    header("Location: ../danhsach/danh_sach_thong_tin_dich_vu.php");
    exit();
}

$idDichVu = $_GET['idDichVu'];
$message = "";

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Xử lý khi nhấn nút "Lưu"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $TenGoiDichVu = $_POST['TenGoiDichVu'];
    $TocDo = $_POST['TocDo'];
    $GiaTien = str_replace('.', '', $_POST['GiaTien']); // Xóa dấu chấm trước khi lưu vào cơ sở dữ liệu
    $MoTa = $_POST['MoTa'];
    // xử lý hình ảnh
    $target_dir = "./image/";
    $target_file = $target_dir . basename($_FILES["ImgURL"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $anhDV = $target_file;

    // Thêm thông tin gói dịch vụ vào CSDL
    $sqlInsert = "INSERT INTO goidichvu (ID_DichVu, TenGoiDichVu, TocDo, GiaTien, MoTa, ImgURL) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("isisss", $idDichVu, $TenGoiDichVu, $TocDo, $GiaTien, $MoTa, $anhDV);

    if ($stmtInsert->execute()) {
        echo "<script>alert('Thêm thành công.');</script>";
        header("refresh:0.5; url=../chitiet/chi_tiet_dich_vu.php?id=$idDichVu");
        exit();
    } else {
        $message = "Lỗi: " . $stmtInsert->error;
    }

    $stmtInsert->close();
}

// Đóng kết nối
$conn->close();
?>

<?php include '../menu.php'; ?>
<div class="content container-fluid mt-0">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white text-center py-4">
            <h2 class="mb-0"><i class="fas fa-plus-circle"></i> Thêm Gói Cước Mới</h2>
        </div>
        <div class="card-body p-5">
            <form action="../them/them_goi_cuoc.php?idDichVu=<?php echo htmlspecialchars($idDichVu); ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="TenGoiDichVu" class="form-label">Tên Gói Cước</label>
                    <input type="text" class="form-control" id="TenGoiDichVu" name="TenGoiDichVu" required>
                </div>
                <div class="form-group">
                    <label for="ImgURL" class="form-label">Hình ảnh</label>
                    <input type="file" class="form-control" id="ImgURL" name="ImgURL" onchange="previewImage(this);">
                    <img id="preview" src="#" alt="Preview Image" style="display: none;" class="mt-3">
                </div>
                <div class="form-group">
                    <label for="TocDo" class="form-label">Tốc Độ</label>
                    <input type="number" class="form-control" id="TocDo" name="TocDo">
                </div>
                <div class="form-group">
                    <label for="GiaTien" class="form-label">Giá Tiền</label>
                    <input type="text" class="form-control" id="GiaTien" name="GiaTien" required oninput="formatCurrency(this)">
                </div>
                <div class="form-group">
                    <label for="MoTa" class="form-label">Mô Tả</label>
                    <textarea class="form-control" id="MoTa" name="MoTa" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary mr-2">
                    <i class="bi bi-floppy"></i> Lưu
                </button>
                <a href="../chitiet/chi_tiet_dich_vu.php?id=<?php echo htmlspecialchars($idDichVu); ?>" class="btn btn-secondary">
                    <i class="bi bi-backspace"></i> Quay Lại
                </a>
            </form>
            <?php if (!empty($message)): ?>
                <div class="mt-3 alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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

    function formatCurrency(input) {
        let value = input.value;
        value = value.replace(/\D/g, '');
        value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        input.value = value;
    }
</script>
</body>
</html>
