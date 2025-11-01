<button onclick="topFunction()" id="myBtn" title="Go to top" class="bi bi-arrow-up-circle"></button>
</div> <!-- Đóng thẻ div của container -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<style>
    .hidden {
        display: none;
    }
</style>

<script>
    // Hide submenus
    $('#body-row .collapse').collapse('hide');

    // Collapse/Expand icon
    $('#collapse-icon').addClass('fa-angle-double-left');

    // Collapse click
    $('[data-toggle=sidebar-colapse]').click(function () {
        SidebarCollapse();
    });

    function SidebarCollapse() {
        $('.menu-collapsed').toggleClass('d-none');
        $('.sidebar-submenu').toggleClass('d-none');
        $('.submenu-icon').toggleClass('d-none');
        $('#sidebar-container').toggleClass('sidebar-expanded sidebar-collapsed');

        // Treating d-flex/d-none on separators with title
        var SeparatorTitle = $('.sidebar-separator-title');
        if (SeparatorTitle.hasClass('d-flex')) {
            SeparatorTitle.removeClass('d-flex');
        } else {
            SeparatorTitle.addClass('d-flex');
        }

        // Collapse/Expand icon
        $('#collapse-icon').toggleClass('fa-angle-double-left fa-angle-double-right');
    }
</script>

<script>
    // Get the button
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function (e) {
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

<script>
    function handleSuccess(message) {
        if (message) {
            let continueAdding = confirm(message + "\nBạn có muốn tiếp tục thêm thông tin mới không?");
            if (!continueAdding) {
                window.location.href = '../danhsach/danh_sach_thong_tin_khach_hang.php';
            }
        }
    }
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2();
    });
</script>
<!-- excel Doanh Thu sql Chitiet-->
<script>
    function exportTableToExcel() {
        var table = document.getElementById("dataTable");
        var rows = [];
        for (var i = 0, row; row = table.rows[i]; i++) {
            var cols = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                cols.push(col.innerText);
            }
            rows.push(cols);
        }
        var data = JSON.stringify(rows);

        var h2Content = document.getElementById("Header").innerText;

        var form = document.createElement("form");
        form.method = "POST";
        form.action = "../xuat/xuat_excel_doanh_thu.php";

        var inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = "data";
        inputData.value = data;

        var inputH2 = document.createElement("input");
        inputH2.type = "hidden";
        inputH2.name = "h2Content";
        inputH2.value = h2Content;

        form.appendChild(inputData);
        form.appendChild(inputH2);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<!-- excel Top 10 nvbh và sql Chitiet-->
<script>
    function exportTableToExcel1() {

        var table = document.getElementById("dataTable");
        var rows = [];
        for (var i = 0, row; row = table.rows[i]; i++) {
            var cols = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                if (j !== row.cells.length - 1) {
                    cols.push(col.innerText);
                }
            }
            rows.push(cols);
        }
        var data = JSON.stringify(rows);

        // var h2Content = document.getElementById("Header").innerText;

        var form = document.createElement("form");
        form.method = "POST";
        form.action = "../xuat/xuat_excel_top10_nvbh_nhieu.php";

        var inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = "data";
        inputData.value = data;

        // var inputH2 = document.createElement("input");
        // inputH2.type = "hidden";
        // inputH2.name = "h2Content";
        // inputH2.value = h2Content;

        form.appendChild(inputData);
        // form.appendChild(inputH2);
        document.body.appendChild(form);
        form.submit();
    }

    //sql chitiet
    function exportQueryToFile1(id) {
        const sqlChitiet = <?= json_encode($sqlChitiet) ?>;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../chitiet/chi_tiet_nvbh.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sqlChitiet';
        input.value = sqlChitiet;

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;

        form.appendChild(input);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<!-- excel Top 10 kh và sql Chitiet-->
<script>
    function exportTableToExcel2() {

        var table = document.getElementById("dataTable");
        var rows = [];
        for (var i = 0, row; row = table.rows[i]; i++) {
            var cols = [];
            for (var j = 0, col; col = row.cells[j]; j++) {
                if (j !== row.cells.length - 1) {
                    cols.push(col.innerText);
                }
            }
            rows.push(cols);
        }
        var data = JSON.stringify(rows);

        // var h2Content = document.getElementById("Header").innerText;

        var form = document.createElement("form");
        form.method = "POST";
        form.action = "../xuat/xuat_excel_top10_khach_hang_dung_nhieu_dv.php";

        var inputData = document.createElement("input");
        inputData.type = "hidden";
        inputData.name = "data";
        inputData.value = data;

        // var inputH2 = document.createElement("input");
        // inputH2.type = "hidden";
        // inputH2.name = "h2Content";
        // inputH2.value = h2Content;

        form.appendChild(inputData);
        // form.appendChild(inputH2);
        document.body.appendChild(form);
        form.submit();
    }

    //sql chitiet
    function exportQueryToFile2(id) {
        const sqlChitiet = <?= json_encode($sqlChitiet) ?>;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../chitiet/chi_tiet.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sqlChitiet';
        input.value = sqlChitiet;

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;

        form.appendChild(input);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
</script>

<!-- Top 10 dịch vụ max với sql Chitiet-->
<script>
    //sql chitiet
    function exportQueryToFile3(id) {
        const sqlChitiet = <?= json_encode($sqlChitiet) ?>;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../chitiet/chi_tiet_dich_vu_dang_ky_nhieu.php';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'sqlChitiet';
        input.value = sqlChitiet;

        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id';
        idInput.value = id;

        form.appendChild(input);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const yearForm = document.getElementById('yearForm');
        const quarterForm = document.getElementById('quarterForm');
        const monthForm = document.getElementById('monthForm');
        const weekForm = document.getElementById('weekForm');

        document.getElementsByName('time').forEach((radio) => {
            radio.addEventListener('change', function () {
                yearForm.classList.add('hidden');
                quarterForm.classList.add('hidden');
                monthForm.classList.add('hidden');
                weekForm.classList.add('hidden');

                if (this.value === 'year') {
                    yearForm.classList.remove('hidden');
                } else if (this.value === 'quarter') {
                    yearForm.classList.remove('hidden');
                    quarterForm.classList.remove('hidden');
                } else if (this.value === 'month') {
                    yearForm.classList.remove('hidden');
                    monthForm.classList.remove('hidden');
                } else if (this.value === 'week') {
                    weekForm.classList.remove('hidden');
                }
            });
        });
    });
</script>

<!-- kiểm tra thống kê form -->
<script>
    // function capNhatHienThiForm() {
    //     const namDuocChon = document.getElementById('year').checked;
    //     const quyDuocChon = document.getElementById('quarter').checked;
    //     const thangDuocChon = document.getElementById('month').checked;
    //     const tuanDuocChon = document.getElementById('week').checked;


    //     document.getElementById('yearForm').style.display = (namDuocChon || quyDuocChon || thangDuocChon) ? 'block' : 'none';
    //     document.getElementById('quaterForm').style.display = quyDuocChon ? 'block' : 'none';
    //     document.getElementById('monthForm').style.display = thangDuocChon ? 'block' : 'none';
    //     document.getElementById('weekForm').style.display = tuanDuocChon ? 'block' : 'none';
    // }

    function kiemTraForm() {
        const namDuocChon = document.getElementById('year').checked;
        const quyDuocChon = document.getElementById('quarter').checked;
        const thangDuocChon = document.getElementById('month').checked;
        const tuanDuocChon = document.getElementById('week').checked;

        if (namDuocChon && document.getElementById('yearSelect').value === '') {
            alert('Vui lòng chọn năm');
            return false;
        }

        if (quyDuocChon) {
            if (document.getElementById('yearSelect').value === '') {
                alert('Vui lòng chọn năm');
                return false;
            }
            if (document.getElementById('quarterSelect').value === '') {
                alert('Vui lòng chọn quý');
                return false;
            }
        }

        if (thangDuocChon) {
            if (document.getElementById('yearSelect').value === '') {
                alert('Vui lòng chọn năm');
                return false;
            }
            if (document.getElementById('monthSelect').value === '') {
                alert('Vui lòng chọn tháng');
                return false;
            }
        }
        if (tuanDuocChon) {
            if (document.getElementById('weekStartSelect').value === '') {
                alert('Vui lòng chọn ngày bắt đầu');
                return false;
            }
            if (document.getElementById('weekEndSelect').value === '') {
                alert('Vui lòng chọn ngày kết thúc');
                return false;
            }
            if (document.getElementById('weekStartSelect').value !== '' && document.getElementById('weekEndSelect').value !== '') {
                var startDate = new Date(document.getElementById('weekStartSelect').value);
                var endDate = new Date(document.getElementById('weekEndSelect').value);

                if (endDate <= startDate) {
                    alert('Ngày kết thúc phải là sau ngày bắt đầu');
                    return false;
                }
            }
        }
        return true;
    }

    document.getElementById('year').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('quarter').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('month').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    document.getElementById('week').addEventListener('change', function () {
        document.getElementById('quarterSelect').selectedIndex = 0;
        document.getElementById('monthSelect').selectedIndex = 0;
        document.getElementById('yearSelect').selectedIndex = 0;
        capNhatHienThiForm();
    });

    // Đính kèm kiemTraForm vào sự kiện submit của biểu mẫu
    document.querySelector('form').addEventListener('submit', function (e) {
        if (!kiemTraForm()) {
            e.preventDefault(); // Ngăn biểu mẫu gửi đi
        }
    });

    // Gọi ban đầu để đảm bảo các form bị ẩn nếu không có checkbox nào được chọn
    capNhatHienThiForm();

</script>


<script>
    function confirmDelete_ttnv(id) {
        var confirmed = confirm("Bạn có chắc chắn muốn xóa nhân viên này?");
        if (confirmed) {
            window.location.href = '../xoa/xoa_thong_tin_nhan_vien.php?id=' + id;
        }
    }

    function confirmDelete_dv(id) {
        var confirmed = confirm("Bạn có chắc chắn muốn xóa dịch vụ này?");
        if (confirmed) {
            window.location.href = '../xoa/xoa_dich_vu.php?id=' + id;
        }
    }
</script>

<script>
    <?php if (isset($labels) && isset($data)) { ?>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Số lượng dịch vụ sử dụng',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(141, 182, 205, 0.6)', // Thay đổi màu nền
                    borderColor: 'rgba(92, 147, 180, 1)', // Thay đổi màu viền
                    borderWidth: 1,
                    borderRadius: 5, // Thêm bo góc cho thanh
                    hoverBackgroundColor: 'rgba(92, 147, 180, 0.8)' // Thay đổi màu khi di chuột qua
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            fontSize: 14 // Thay đổi kích thước chữ trục y
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 14 // Thay đổi kích thước chữ trục x
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        color: 'white', // Thay đổi màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function (value, context) {
                            return value.toLocaleString();
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 80
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });

        // Biểu đồ hình tròn
        var ctx_pie = document.getElementById('myChart_pie').getContext('2d');
        var myChart_pie = new Chart(ctx_pie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Số lượng dịch vụ sử dụng',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: 'white', // Màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function (value, context) {
                            return value;
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 20
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });
    <?php } ?>
</script>

<script>
    <?php if (isset($tenNhanVien) && isset($soLuongDichVu)) { ?>
        var ctx_nvbh = document.getElementById('myChart_nvbh').getContext('2d');
        var myChart_nvbh = new Chart(ctx_nvbh, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tenNhanVien); ?>,
                datasets: [{
                    label: 'Tổng số dịch vụ bán được',
                    data: <?php echo json_encode($soLuongDichVu); ?>,
                    backgroundColor: 'rgba(141, 182, 205, 0.6)', // Thay đổi màu nền
                    borderColor: 'rgba(92, 147, 180, 1)', // Thay đổi màu viền
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(92, 147, 180, 0.8)' // Thay đổi màu khi di chuột qua
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        anchor: 'center',
                        align: 'top',
                        color: 'white', // Màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function (value, context) {
                            return value;
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            fontSize: 14
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 14
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 80
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });

        // Biểu đồ hình tròn
        var ctx_nvbh_pie = document.getElementById('myChart_nvbh_pie').getContext('2d');
        var myChart_nvbh_pie = new Chart(ctx_nvbh_pie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($tenNhanVien); ?>,
                datasets: [{
                    label: 'Tổng số dịch vụ bán được',
                    data: <?php echo json_encode($soLuongDichVu); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: 'white', // Màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function (value, context) {
                            return value;
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 20
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });
    <?php } ?>
</script>

<!-- Bieu do -->
<script>
    <?php if (isset($tenKhachHang) && isset($soLuongDichVu)) { ?>
        var ctx = document.getElementById('myChart_kh_dv_max').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($tenKhachHang); ?>,
                datasets: [{
                    label: 'Số lượng dịch vụ sử dụng',
                    data: <?php echo json_encode($soLuongDichVu); ?>,
                    backgroundColor: 'rgba(205, 160, 141, 0.6)', // Thay đổi màu nền
                    borderColor: 'rgba(180, 119, 92, 1)', // Thay đổi màu viền
                    borderWidth: 1,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(180, 119, 92, 0.8)' // Thay đổi màu khi di chuột qua
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            fontSize: 14
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 14
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 80
                    }
                },
                plugins: {
                    datalabels: {
                        anchor: 'center',
                        align: 'top',
                        formatter: function (value, context) {
                            return value;
                        },
                        color: 'white', // Màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });

        // Biểu đồ tròn
        var ctx_pie = document.getElementById('myChart_kh_dv_max_pie').getContext('2d');
        var myChart_pie = new Chart(ctx_pie, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($tenKhachHang); ?>,
                datasets: [{
                    label: 'Số lượng dịch vụ sử dụng',
                    data: <?php echo json_encode($soLuongDichVu); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: 'white', // Màu chữ
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function (value, context) {
                            return value;
                        }
                    },
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        bottom: 20
                    }
                }
            },
            plugins: [
                ChartDataLabels
            ]
        });
    <?php } ?>
</script>
</body>

</html>