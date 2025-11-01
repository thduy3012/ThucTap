<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php");
    exit();
}

// Kiểm tra nếu có ID_TTBH
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_ban_hang.php");
    exit();
}

$id = $_GET['id'];

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Bắt đầu một giao dịch
$conn->begin_transaction();

try {
    // Truy vấn xóa doanh thu tương ứng
    $sql_delete_doanhthu = "DELETE FROM DoanhThu WHERE ID_ThongTinBanHang = ?";
    $stmt_delete_doanhthu = $conn->prepare($sql_delete_doanhthu);
    $stmt_delete_doanhthu->bind_param("i", $id);
    $stmt_delete_doanhthu->execute();
    
    // Truy vấn xóa thông tin bán hàng
    $sql_delete_thongtinbanhang = "DELETE FROM ThongTinBanHang WHERE ID_ThongTinBanHang = ?";
    $stmt_delete_thongtinbanhang = $conn->prepare($sql_delete_thongtinbanhang);
    $stmt_delete_thongtinbanhang->bind_param("i", $id);
    $stmt_delete_thongtinbanhang->execute();
    
    // Commit các thay đổi vào cơ sở dữ liệu
    $conn->commit();
    echo "<script>alert('Xóa thành công.');</script>";
    header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_ban_hang.php");
    exit();
} catch (Exception $e) {
    // Nếu có lỗi, rollback các thay đổi và hiển thị thông báo lỗi
    $conn->rollback();
    echo "Xóa thất bại: " . $e->getMessage();
}

$conn->close();
?>
