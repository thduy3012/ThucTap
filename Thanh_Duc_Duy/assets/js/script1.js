document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('register-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Ngăn chặn gửi biểu mẫu mặc định

        // Lấy các giá trị của các trường đầu vào
        var fullname = document.getElementById('fullname').value;
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var phone = document.getElementById('phone').value;
        var address = document.getElementById('address').value;
        var pass = document.getElementById('pass').value;
        var rePass = document.getElementById('re_pass').value;
        var agreeTerm = document.getElementById('agree-term').checked;

        // Kiểm tra các trường thông tin và điều khoản
        if (!fullname || !name || !email || !phone || !address || !pass || !rePass || !agreeTerm) {
            alert('Vui lòng điền đầy đủ các trường thông tin và đồng ý điều khoản.');
            return; // Dừng quá trình nếu thông tin không hợp lệ
        } else if (pass !== rePass) {
            alert('Mật khẩu và Nhập Lại Mật Khẩu không khớp.');
            return; // Dừng quá trình nếu mật khẩu không khớp
        } else {
            // Giả lập quá trình lưu thông tin đăng ký (tùy chọn)
            alert('Đăng ký thành công!');
            // Chuyển hướng đến trang đăng nhập
            window.location.href = "Dangnhap.html"; // Chuyển hướng đến trang đăng nhập
        }
    });
});

    // Hiển thị mật khẩu
    document.querySelectorAll('.toggle-password').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                this.querySelector('i').classList.remove('fa-eye');
                this.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.querySelector('i').classList.remove('fa-eye-slash');
                this.querySelector('i').classList.add('fa-eye');
            }
        });
    });

