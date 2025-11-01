<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID dịch vụ đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_dich_vu.php"); // Nếu không, chuyển hướng đến trang danh sách dịch vụ
    exit();
}

$id = $_GET['id'];

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem có ràng buộc khóa ngoại nào đang sử dụng dịch vụ này không
$sqlCheck = "SELECT * FROM ThongTinBanHang WHERE ID_GoiDichVu = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo "<script>alert('Không thể xóa dịch vụ này vì có các gói dịch vụ đang được sử dụng.');</script>";
    header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_dich_vu.php");
    exit();
} else {
    // Nếu không có ràng buộc khóa ngoại, tiến hành xóa dịch vụ
    $sqlDelete = "DELETE FROM DichVu WHERE ID_DichVu = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        echo "<script>alert('Xóa dịch vụ thành công.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_dich_vu.php");
        exit();
    } else {
        echo "<script>alert('Xóa dịch vụ thất bại.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_dich_vu.php");
        exit();
    }
}

$conn->close();
