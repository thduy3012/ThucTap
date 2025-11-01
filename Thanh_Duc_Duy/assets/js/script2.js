document.getElementById('login-form').addEventListener('submit', function(event) {
    var yourName = document.getElementById('your_name').value;
    var yourPass = document.getElementById('your_pass').value;

    if (!yourName || !yourPass) {
        alert('Vui lòng điền đầy đủ tên đăng nhập và mật khẩu.');
        event.preventDefault();
    }
});