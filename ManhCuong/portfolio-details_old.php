<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Chi Tiết Gói Cước</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between">

      <div class="logo">
        <a href="trangchu.php"><img src="assets/img/logovnpt.png" alt="" class="img-fluid"></a>
      </div>
    </div>
  </header><!-- End Header -->
  <main id="main">
    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details mt-5">
      <div class="container">

        <?php
        if (isset($_GET['id'])) {
          $id = $_GET['id'];

          // Connect to the database
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "Congtyvienthong";

          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          // Query the database to get the service package details
          $sql = "SELECT goidichvu.TenGoiDichVu, goidichvu.MoTa, goidichvu.TocDo, goidichvu.GiaTien, dichvu.TenDichVu
                  FROM goidichvu
                  JOIN dichvu ON goidichvu.ID_DichVu = dichvu.ID_DichVu
                  WHERE goidichvu.ID_GoiDichVu = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $id);
          $stmt->execute();
          $result = $stmt->get_result();

          // Check if there are any results
          if ($result->num_rows > 0) {
            // Output the service package details
            while ($row = $result->fetch_assoc()) {
              echo '
              <div class="row gy-4">

                <div class="col-lg-8">
                  <div class="portfolio-details-slider swiper">
                    <div class="swiper-wrapper align-items-center">

                      <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-details-1.jpg" alt="">
                      </div>

                      <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-details-2.jpg" alt="">
                      </div>

                      <div class="swiper-slide">
                        <img src="assets/img/portfolio/portfolio-details-3.jpg" alt="">
                      </div>

                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>

                <div class="col-lg-4">
                  <div class="portfolio-info">
                    <h3>Thông Tin Gói Cước</h3>
                    <ul>
                      <li><strong>Tên Dịch Vụ</strong>: ' . $row["TenDichVu"] . '</li>
                      <li><strong>Tên Gói Cước</strong>: ' . $row["TenGoiDichVu"] . '</li>
                      <li><strong>Mô Tả</strong>: ' . $row["MoTa"] . '</li>';
              if (!is_null($row["TocDo"])) {
                echo '<li><strong>Tốc Độ</strong>: ' . $row["TocDo"] . '</li>';
              }
              echo '
                      <li><strong>Giá Tiền</strong>: ' . $row["GiaTien"] . ' VND</li>
                    </ul>
                  </div>
                  <div class="portfolio-description">
                    <h2>Mô Tả Chi Tiết</h2>
                    <p>' . $row["MoTa"] . '</p>
                  </div>
                </div>

              </div>';
            }
          } else {
            echo "No details found.";
          }

          // Close the database connection
          $stmt->close();
          $conn->close();
        } else {
          echo "No package ID specified.";
        }
        ?>

      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>VNPT</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> -->
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
