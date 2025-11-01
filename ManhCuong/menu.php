<?php
// Kiểm tra nếu nhân viên đã đăng nhập
if (!isset($_SESSION['ID_NhanVien'])) {
    header("Location: ../dangnhap_NV.php"); // Redirect đến trang đăng nhập nếu chưa đăng nhập
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'Congtyvienthong');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID_NhanVien từ session
$ID_NhanVien = $_SESSION['ID_NhanVien'];

// Truy vấn SQL để lấy tên nhân viên dựa trên ID_NhanVien
$sql_user = "SELECT TenNhanVien FROM NhanVien WHERE ID_NhanVien='$ID_NhanVien'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $tenNhanVien = $row_user['TenNhanVien'];
} else {
    // Xử lý khi không tìm thấy thông tin nhân viên
    $tenNhanVien = "Không tìm thấy thông tin nhân viên";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Menu</title>
</head>
<style>
    #body-row {
        margin-left: 0;
        margin-right: 0;
    }

    #sidebar-container {
        min-height: 100vh;
        background-color: #333;
        padding: 0;
    }

    /* Sidebar sizes when expanded and expanded */
    .sidebar-expanded {
        width: 230px;
    }

    .sidebar-collapsed {
        width: 60px;
    }

    /* Menu item*/
    #sidebar-container .list-group a {
        height: 50px;
        color: white;
    }

    /* Submenu item*/
    #sidebar-container .list-group .sidebar-submenu a {
        height: 45px;
        padding-left: 30px;
    }

    .sidebar-submenu {
        font-size: 0.9rem;
    }

    /* Separators */
    .sidebar-separator-title {
        background-color: #333;
        height: 35px;
    }

    .sidebar-separator {
        background-color: #333;
        height: 25px;
    }

    .logo-separator {
        background-color: #333;
        height: 60px;
    }

    /* Closed submenu icon */
    #sidebar-container .list-group .list-group-item[aria-expanded="false"] .submenu-icon::after {
        content: " \f0d7";
        font-family: FontAwesome;
        display: inline;
        text-align: right;
        padding-left: 10px;
    }

    /* Opened submenu icon */
    #sidebar-container .list-group .list-group-item[aria-expanded="true"] .submenu-icon::after {
        content: " \f0da";
        font-family: FontAwesome;
        display: inline;
        text-align: right;
        padding-left: 10px;
    }

    /* Back to top */
    #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        border: none;
        outline: none;
        background-color: red;
        color: white;
        cursor: pointer;
        padding: 15px;
        border-radius: 35%;
    }

    #myBtn:hover {
        background-color: #555;
    }
    #myChart_nvbh_pie, #myChart_pie, #myChart_kh_dv_max_pie {
        margin: 0 auto; 
        max-width: 600px;
        max-height: 600px;
    }
</style>

<body id="top">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">
            <img src="https://v4-alpha.getbootstrap.com/assets/brand/bootstrap-solid.svg" width="30" height="30" class="d-inline-block align-top" alt="">
            <span class="menu-collapsed">Công Ty Viễn Thông</span>
        </a>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <!-- <a class="nav-link" href="#top">Home <span class="sr-only">(current)</span></a> -->
                </li>
                <li class="nav-item">
                    <!-- <a class="nav-link" href="#top">Features</a> -->
                </li>
                <li class="nav-item">
                    <!-- <a class="nav-link" href="#top">Pricing</a> -->
                </li>
                <!-- This menu is hidden in bigger devices with d-sm-none. 
           The sidebar isn't proper for smaller screens imo, so this dropdown menu can keep all the useful sidebar itens exclusively for smaller screens  -->
                <!-- dropdown Danh Sach -->
                <li class="nav-item dropdown d-sm-block d-md-none">
                    <a class="nav-link dropdown-toggle" href="#" id="smallerscreenmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Danh Sách </a>
                    <div class="dropdown-menu" aria-labelledby="smallerscreenmenu">
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_khach_hang.php">Thông Tin Khách Hàng </a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_dich_vu.php">Thông Tin Dịch Vụ</a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php">Thông Tin Nhân Viên</a>
                        <a class="dropdown-item" href="../danhsach/danh_sach_thong_tin_ban_hang.php">Thông Tin Bán Hàng</a>
                    </div>
                </li>
                <!-- dropdown Top 10 -->
                <li class="nav-item dropdown d-sm-block d-md-none">
                    <a class="nav-link dropdown-toggle" href="#" id="smallerscreenmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Top 10 </a>
                    <div class="dropdown-menu" aria-labelledby="smallerscreenmenu">
                        <a class="dropdown-item" href="../top10/top10_nvbh.php">Nhân Viên Bán Hàng Nhiều</a>
                        <a class="dropdown-item" href="../top10/top10kh_dvMax.php">Khách Hàng Dùng Nhiều Dịch Vụ</a>
                        <a class="dropdown-item" href="../top10/dich_vu_dang_ky_nhieu.php">Dịch Vụ Đăng Ký Nhiều</a>
                    </div>
                </li>
                <!-- dropdown Thong ke -->
                <li class="nav-item dropdown d-sm-block d-md-none">
                    <a class="nav-link dropdown-toggle" href="#" id="smallerscreenmenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Thống Kê </a>
                    <div class="dropdown-menu" aria-labelledby="smallerscreenmenu">
                        <a class="dropdown-item" href="../thongke/doanh_thu.php">Doanh Thu</a>
                    </div>
                </li><!-- Smaller devices menu END -->
            </ul>
        </div>
        <span class="navbar-text">
            Xin chào <?php echo $tenNhanVien; ?> | <a href="../dang_xuat_nv.php" class="btn btn-dark bi bi-box-arrow-left"> Đăng Xuất</a>
        </span>
    </nav><!-- NavBar END -->
    <!-- Bootstrap row -->
    <div class="row" id="body-row">
        <!-- Sidebar -->
        <div id="sidebar-container" class="sidebar-expanded d-none d-md-block">
            <!-- d-* hiddens the Sidebar in smaller devices. Its itens can be kept on the Navbar 'Menu' -->
            <!-- Bootstrap List Group -->
            <ul class="list-group">
                <a href="#" data-toggle="sidebar-colapse" class="bg-dark list-group-item list-group-item-action d-flex align-items-center">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span id="collapse-icon" class="fa mr-3"></span>
                        <span id="collapse-text" class="menu-collapsed">Menu</span>
                    </div>
                </a>
                <!-- Separator with title -->
                <li class="list-group-item sidebar-separator-title text-muted d-flex align-items-center menu-collapsed">
                    <small>MAIN MENU</small>
                </li>
                <!-- /END Separator -->
                <!-- Menu with submenu -->
                <a href="#submenu1" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa-solid fa-list-ul fa-fw mr-3"></span>
                        <span class="menu-collapsed">Danh Sách</span>
                        <span class="submenu-icon ml-auto"></span>
                    </div>
                </a>
                <!-- Submenu content -->
                <div id='submenu1' class="collapse sidebar-submenu">
                    <a href="../danhsach/danh_sach_thong_tin_khach_hang.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Thông Tin Khách Hàng</span>
                    </a>
                    <a href="../danhsach/danh_sach_thong_tin_dich_vu.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Thông Tin Dịch Vụ</span>
                    </a>
                    <a href="../danhsach/danh_sach_thong_tin_nhan_vien_ban_hang.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Thông Tin Nhân Viên</span>
                    </a>
                    <a href="../danhsach/danh_sach_thong_tin_ban_hang.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Thông Tin Bán Hàng</span>
                    </a>
                </div>

                <a href="#submenu2" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa-solid fa-ranking-star fa-fw mr-3"></span>
                        <span class="menu-collapsed">Top 10</span>
                        <span class="submenu-icon ml-auto"></span>
                    </div>
                </a>
                <!-- Submenu content -->
                <div id='submenu2' class="collapse sidebar-submenu">
                    <a href="../top10/top10_nvbh.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Nhân Viên Bán Hàng Nhiều</span>
                    </a>
                    <a href="../top10/top10kh_dvMax.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Khách Dụng Nhiều Dịch Vụ</span>
                    </a>
                    <a href="../top10/dich_vu_dang_ky_nhieu.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Dịch Vụ Đăng Ký Nhiều</span>
                    </a>
                </div>

                <a href="#submenu3" data-toggle="collapse" aria-expanded="false" class="bg-dark list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa-solid fa-hand-holding-dollar fa-fw mr-3"></span>
                        <span class="menu-collapsed">Thống Kê</span>
                        <span class="submenu-icon ml-auto"></span>
                    </div>
                </a>
                <!-- Submenu content -->
                <div id='submenu3' class="collapse sidebar-submenu">
                    <a href="../thongke/doanh_thu.php" class="list-group-item list-group-item-action bg-dark text-white">
                        <span class="menu-collapsed">Doanh Thu</span>
                    </a>
                </div>


                <!-- <a href="../danhsach/doanh_thu.php" class="bg-dark list-group-item list-group-item-action">
                    <div class="d-flex w-100 justify-content-start align-items-center">
                        <span class="fa-solid fa-hand-holding-dollar fa-fw mr-3"></span>
                        <span class="menu-collapsed">Doanh Thu</span>
                    </div>
                </a> -->
                <!-- Separator without title -->
                <li class="list-group-item sidebar-separator menu-collapsed"></li>
                <!-- /END Separator -->

            </ul><!-- List Group END-->
        </div>
        <div class="container mt-3 col p-4">