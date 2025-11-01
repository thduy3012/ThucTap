<?php
session_start();

// Check if the employee is logged in
if (!isset($_SESSION['ID_NhanVien'])) {
    // Redirect to the login page or display an error message
    header("Location: ../dangnhap_NV.php");
    exit;
}
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra và nhận giá trị sqlChitiet
if (isset($_POST['sqlChitiet']) && isset($_POST['id'])) {
    $sqlChitiet = $_POST['sqlChitiet'];

    if (strpos($sqlChitiet, 'WHERE') !== false) {
        $whereString = substr($sqlChitiet, strpos($sqlChitiet, 'WHERE'));
        // echo "Giá trị whereString nhận được: " . htmlspecialchars($whereString);
        // echo "<br>";
    } else {
        $whereString = '';
    }
    $ID_DichVu = $_POST['id'];
    $noiString = "AND gdv.ID_DichVu = $ID_DichVu
    GROUP BY 
    gdv.ID_GoiDichVu, gdv.TenGoiDichVu;
    ";
    // echo "Giá trị sqlChitiet nhận được: " . htmlspecialchars($sqlChitiet);
    // echo "Giá trị id khách hàng  nhận được: " . htmlspecialchars($ID_DichVu);
    // Thực hiện truy vấn với giá trị sqlChitiet ở đây
    $sqlChitietFull = $sqlChitiet . $noiString;
    $result1 = $conn->query($sqlChitietFull);

    // Truy vấn dịch vụ
    $sql = "SELECT 
    dv.TenDichVu,
    SUM(ttb.SoLuong) AS TongSoGoiDaBan
    FROM 
    ThongTinBanHang AS ttb
    JOIN 
    GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
    JOIN 
    DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu ";
    //NỐI STRING DV
    $noiStringDV = "AND dv.ID_DichVu = $ID_DichVu -- Thay 1 bằng ID của dịch vụ bạn muốn hiển thị thông tin
    GROUP BY 
    dv.TenDichVu;
    ";
    if ($whereString != '') {
        $sql .= $whereString;
        $sql .= $noiStringDV;
    }
    // $sql = "SELECT 
    // dv.TenDichVu,
    // SUM(ttb.SoLuong) AS TongSoGoiDaBan
    // FROM 
    // ThongTinBanHang AS ttb
    // JOIN 
    // GoiDichVu AS gdv ON ttb.ID_GoiDichVu = gdv.ID_GoiDichVu
    // JOIN 
    // DichVu AS dv ON gdv.ID_DichVu = dv.ID_DichVu
    // WHERE 
    // dv.ID_DichVu = $ID_DichVu -- Thay 1 bằng ID của dịch vụ bạn muốn hiển thị thông tin
    // GROUP BY 
    // dv.TenDichVu;
    // ";
    $result = $conn->query($sql);
    $conn->close();
} else {
    echo "Không nhận được giá trị sqlChitiet";
}
?>

<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container"> -->
<?php include '../menu.php'; ?>
<div class="content container-fluid">
    <?php
    // Fetch the result from the query
    $row = $result->fetch_assoc();
    $tenDichVu = $row['TenDichVu'];
    $TongSoGoiDaBan = $row['TongSoGoiDaBan'];
    ?>

    <h2 class="mt-5">Chi tiết của dịch vụ <?php echo $tenDichVu; ?></h2>
    <p>Tổng số lượng bán được của dịch vụ: <?php echo $TongSoGoiDaBan; ?></p>

    <table class="table table-hover">
        <thead class="thead-light">
            <tr>
                <th>STT</th>
                <th>Gói dịch vụ</th>
                <th>Tổng số lượng bán được</th>
            </tr>
        </thead>
        <tbody>
            <?php $count=1;
            if ($result1->num_rows > 0) {
                while ($row1 = $result1->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $count++ ."</td>";
                    echo "<td>" . htmlspecialchars($row1['TenGoiDichVu']) . "</td>";
                    echo "<td>" . htmlspecialchars($row1['TongSoLuongBanDuoc']) . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>Không có dữ liệu</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="../top10/dich_vu_dang_ky_nhieu.php" class="btn btn-secondary bi bi-backspace"> Quay Lại</a>
</div>
<?php include '../footer.php'; ?>
<!-- </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> -->