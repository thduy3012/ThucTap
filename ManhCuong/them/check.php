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

$message = "";

// Truy vấn khách hàng
$sqlKhachHang = "SELECT ID_KhachHang, Ten, SoDienThoai FROM KhachHang";
$resultKhachHang = $conn->query($sqlKhachHang);

// Truy vấn thông tin dịch vụ
$sqlDichVu = "SELECT ID_DichVu, TenDichVu FROM DichVu";
$resultDichVu = $conn->query($sqlDichVu);

// Lấy ID của dịch vụ "Internet"
$defaultServiceId = 1; // Giả định ID của dịch vụ Internet là 1

// Truy vấn gói dịch vụ ban đầu cho dịch vụ có ID 1 (mặc định là dịch vụ Internet)
$sqlGoiDV = "SELECT ID_GoiDichVu, TenGoiDichVu FROM GoiDichVu WHERE ID_DichVu = $defaultServiceId";
$resultGoiDV = $conn->query($sqlGoiDV);

// Truy vấn thông tin nhân viên bán hàng
$sqlTTnvbh = "SELECT ID_TTNVBH, TenNhanVien, SoDienThoai FROM TTNhanVienBanHang";
$resultTTnvbh = $conn->query($sqlTTnvbh);



// Xử lý form gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['ajax'])) {
    // Lấy dữ liệu từ biểu mẫu
    $ID_KhachHang = $conn->real_escape_string($_POST['ID_KhachHang']);
    $ID_GoiDichVu = $conn->real_escape_string($_POST['ID_GoiDichVu']);
    $ID_TTNVBH = $conn->real_escape_string($_POST['ID_TTNVBH']);
    $SoLuong = $conn->real_escape_string($_POST['SoLuong']);
    $NgayDangKy = $conn->real_escape_string($_POST['NgayDangKy']);

    // Lấy giá tiền từ bảng GoiDichVu
    $sqlGiaTien = "SELECT GiaTien FROM GoiDichVu WHERE ID_GoiDichVu = $ID_GoiDichVu";
    $resultGiaTien = $conn->query($sqlGiaTien);
    $rowGiaTien = $resultGiaTien->fetch_assoc();
    $GiaTien = $rowGiaTien['GiaTien'];

    // Tính tổng tiền
    $SoTien = $SoLuong * $GiaTien;

    // Chèn dữ liệu vào bảng ThongTinBanHang
    $sql = "INSERT INTO ThongTinBanHang (ID_KhachHang, ID_GoiDichVu, ID_TTNVBH, NgayDangKy, SoLuong) 
            VALUES ('$ID_KhachHang', '$ID_GoiDichVu', '$ID_TTNVBH', '$NgayDangKy', '$SoLuong')";

    if ($conn->query($sql) === TRUE) {
        // Lấy ID_ThongTinBanHang vừa thêm
        $ID_ThongTinBanHang = $conn->insert_id;

        // Thêm thông tin vào bảng DoanhThu
        $sqlInsertDoanhThu = "INSERT INTO DoanhThu (ID_ThongTinBanHang, ThoiGian, SoTien) 
                            VALUES ('$ID_ThongTinBanHang', '$NgayDangKy', '$SoTien')";
        if ($conn->query($sqlInsertDoanhThu) === TRUE) {
            $message = "Thêm thông tin bán hàng thành công.";
        } else {
            $message = "Lỗi khi thêm thông tin vào bảng DoanhThu: " . $conn->error;
        }
    } else {
        $message = "Lỗi khi thêm thông tin vào bảng ThongTinBanHang: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thông Tin Bán Hàng</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body onload="handleSuccess('<?php echo $message; ?>')">
    <?php include '../menu.php'; ?>
    <div class="content container-fluid mt-0">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-4">
                <h2 class="mb-0"><i class="fas fa-plus-circle"></i> Thêm Thông Tin Bán Hàng</h2>
            </div>
            <div class="card-body p-5">
                <form action="../them/them_thong_tin_ban_hang.php" method="post">
                    <div class="form-group">
                        <label for="khachHang">Khách Hàng</label>
                        <select class="form-control select2" id="khachHang" name="ID_KhachHang" required>
                            <?php
                            if ($resultKhachHang->num_rows > 0) {
                                while ($row = $resultKhachHang->fetch_assoc()) {
                                    echo "<option value='" . $row['ID_KhachHang'] . "'>" . htmlspecialchars($row['Ten']) . " - " . htmlspecialchars($row['SoDienThoai']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Không có khách hàng nào</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="service">Chọn dịch vụ:</label>
                        <div class="d-flex">
                            <select class="form-control" id="service" name="ID_DichVu">
                                <?php
                                if ($resultDichVu->num_rows > 0) {
                                    while ($row = $resultDichVu->fetch_assoc()) {
                                        $selected = $row['ID_DichVu'] == $defaultServiceId ? 'selected' : '';
                                        echo '<option value="' . $row['ID_DichVu'] . '" ' . $selected . '>' . $row['TenDichVu'] . '</option>';
                                    }
                                } else {
                                    echo "<option value=''>Không có dịch vụ nào</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- kiểm tra id dịch vụ đã chọn -->
                    <div id="selected-id"></div>

                    <div class="form-group">
                        <label for="goiDichVu">Gói Dịch Vụ</label>
                        <select class="form-control" id="goiDichVu" name="ID_GoiDichVu" required>
                            <option value="">Chọn gói dịch vụ</option>
                            <?php
                            if ($resultGoiDV->num_rows > 0) {
                                while ($row = $resultGoiDV->fetch_assoc()) {
                                    echo "<option value='" . $row['ID_GoiDichVu'] . "'>" . htmlspecialchars($row['TenGoiDichVu']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Không có gói dịch vụ nào</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="soLuong">Số Lượng</label>
                            <input type="number" class="form-control" id="soLuong" name="SoLuong" required min="1">
                        </div>
                        <div class="col-sm-6">
                            <label for="ngayDangKy">Ngày Đăng Ký</label>
                            <input type="date" class="form-control" id="ngayDangKy" name="NgayDangKy" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nhanVienBanHang">Nhân Viên Bán Hàng</label>
                        <select class="form-control select2" id="nhanVienBanHang" name="ID_TTNVBH" required>
                            <?php
                            if ($resultTTnvbh->num_rows > 0) {
                                while ($row = $resultTTnvbh->fetch_assoc()) {
                                    echo "<option value='" . $row['ID_TTNVBH'] . "'>" . htmlspecialchars($row['TenNhanVien']) . " - " . htmlspecialchars($row['SoDienThoai']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>Không có nhân viên bán hàng nào</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thông tin</button>
                    <a href="../danhsach/danh_sach_thong_tin_ban_hang.php" class="btn btn-secondary ml-2"><i
                            class="fas fa-arrow-left"></i> Quay Lại</a>
                </form>
            </div>
        </div>
    </div>
    <?php include '../footer.php'; ?>

    <!-- Bao gồm jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bao gồm Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Bao gồm Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // Khởi tạo Select2 cho các thẻ select
            $('.select2').select2();

            //kiểm tra id dịch vụ đã chọn
            $('#service').change(function () {
                var selectedID = $(this).val();
                $('#selected-id').text("ID dịch vụ đã chọn: " + selectedID);

            });

            // Mặc định tải gói dịch vụ của dịch vụ có ID 1
            loadPackages('<?php echo $defaultServiceId; ?>');

            $('#service').change(function () {
                let serviceId = $(this).val();
                loadPackages(serviceId); // Gọi hàm loadPackages khi giá trị thay đổi
            });
        });

        function handleSuccess(message) {
            if (message) {
                let continueAdding = confirm(message + "\nBạn có muốn tiếp tục thêm thông tin mới không?");
                if (!continueAdding) {
                    window.location.href = '../danhsach/danh_sach_thong_tin_ban_hang.php';
                }
            }
        }

        function loadPackages(serviceId) {
            $.ajax({
                url: 'get_goi_dich_vu.php', // URL của script PHP để lấy thông tin về các gói dịch vụ
                type: 'GET',
                data: { id_dich_vu: serviceId },
                dataType: 'json',
                success: function (data) {
                    var goiDichVuSelect = $('#goiDichVu');
                    goiDichVuSelect.empty(); // Xóa các option hiện tại

                    // Kiểm tra nếu không có gói dịch vụ nào
                    if (data.length === 0) {
                        alert('Không có gói dịch vụ nào cho dịch vụ đã chọn');
                        return;
                    }

                    goiDichVuSelect.append('<option value="">Chọn gói dịch vụ</option>'); // Thêm tùy chọn mặc định
                    data.forEach(function (goiDichVu) {
                        // Thêm một option mới cho mỗi gói dịch vụ
                        goiDichVuSelect.append('<option value="' + goiDichVu.id + '">' + goiDichVu.ten + '</option>');
                    });
                },
                error: function () {
                    alert('Lỗi khi lấy thông tin về các gói dịch vụ');
                }
            });
        }
    </script>
</body>

</html>