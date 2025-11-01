<?php
session_start();

// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kiểm tra xem ID nhân viên đã được truyền qua URL hay không
if (!isset($_GET['id'])) {
    header("Location: ../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php"); // Nếu không, chuyển hướng đến trang danh sách nhân viên
    exit();
}

$id = $_GET['id'];

// Kết nối cơ sở dữ liệu
include('../connect.php');

// Kiểm tra xem có ràng buộc khóa ngoại nào đang sử dụng nhân viên này không
$sqlCheck = "SELECT * FROM thongtinbanhang WHERE ID_TTNVBH = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows > 0) {
    echo "<script>alert('Không thể xóa nhân viên này vì có thông tin bán hàng liên quan.');</script>";
    header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");
    exit();
} else {
    // Nếu không có ràng buộc khóa ngoại, tiến hành xóa nhân viên
    $sqlDelete = "DELETE FROM ttnhanvienbanhang WHERE ID_TTNVBH = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $id);

    if ($stmtDelete->execute()) {
        echo "<script>alert('Xóa nhân viên thành công.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");
        exit();
    } else {
        echo "<script>alert('Xóa nhân viên thất bại.');</script>";
        header("refresh:0.5; url=../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php");
        exit();
    }
}

$conn->close();
?>
