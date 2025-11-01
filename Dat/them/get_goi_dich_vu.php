<?php
// Kết nối đến cơ sở dữ liệu
include('../connect.php');

// Lấy id_dich_vu từ yêu cầu
$id_dich_vu = $_GET['id_dich_vu'];

// Thực hiện truy vấn để lấy thông tin về các gói dịch vụ
$query = "SELECT ID_GoiDichVu, TenGoiDichVu FROM goidichvu WHERE ID_DichVu = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $id_dich_vu);
$stmt->execute();
$result = $stmt->get_result();

// Chuyển đổi kết quả truy vấn thành một mảng các gói dịch vụ
$goi_dich_vu = array();
while ($row = $result->fetch_assoc()) {
    $goi_dich_vu[] = array(
        'id' => $row['ID_GoiDichVu'],
        'ten' => $row['TenGoiDichVu']
    );
}

// Trả về mảng các gói dịch vụ dưới dạng JSON
echo json_encode($goi_dich_vu);

$db->close();
?>