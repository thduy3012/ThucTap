CREATE TABLE KhachHang (
    ID_KhachHang INT AUTO_INCREMENT PRIMARY KEY,
    Ten VARCHAR(100) NOT NULL,
    SoDienThoai VARCHAR(20) NOT NULL,
    DiaChi VARCHAR(255) NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE DichVu (
    ID_DichVu INT AUTO_INCREMENT PRIMARY KEY,
    TenDichVu VARCHAR(255) NOT NULL
);

CREATE TABLE GoiDichVu (
    ID_GoiDichVu INT AUTO_INCREMENT PRIMARY KEY,
    ID_DichVu INT,
    TenGoiDichVu VARCHAR(255) NOT NULL,
    TocDo VARCHAR(50),
    GiaTien DECIMAL(10, 2) NOT NULL,
    MoTa VARCHAR(255),
    FOREIGN KEY (ID_DichVu) REFERENCES DichVu(ID_DichVu)
);

CREATE TABLE NhanVien (
    ID_NhanVien INT AUTO_INCREMENT PRIMARY KEY,
    TenNhanVien VARCHAR(100) NOT NULL,
    SoDienThoai VARCHAR(20) NOT NULL,
    DiaChi VARCHAR(255) NOT NULL,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE TTNhanVienBanHang (
    ID_TTNVBH INT AUTO_INCREMENT PRIMARY KEY,
    TenNhanVien VARCHAR(100) NOT NULL,
    SoDienThoai VARCHAR(20) NOT NULL,
    DiaChi VARCHAR(255) NOT NULL,
    ID_NhanVien INT,
    FOREIGN KEY (ID_NhanVien) REFERENCES NhanVien(ID_NhanVien)
);

CREATE TABLE ThongTinBanHang (
    ID_ThongTinBanHang INT AUTO_INCREMENT PRIMARY KEY,
    ID_KhachHang INT,
    ID_GoiDichVu INT,
    ID_TTNVBH INT,
    NgayDangKy DATE NOT NULL,
    SoLuong INT,
    FOREIGN KEY (ID_KhachHang) REFERENCES KhachHang(ID_KhachHang),
    FOREIGN KEY (ID_GoiDichVu) REFERENCES GoiDichVu(ID_GoiDichVu),
    FOREIGN KEY (ID_TTNVBH) REFERENCES TTNhanVienBanHang(ID_TTNVBH)
);

CREATE TABLE DoanhThu (
    ID_DoanhThu INT AUTO_INCREMENT PRIMARY KEY,
    ID_ThongTinBanHang INT,
    ThoiGian DATE NOT NULL,
    SoTien DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (ID_ThongTinBanHang) REFERENCES ThongTinBanHang(ID_ThongTinBanHang)
);


INSERT INTO KhachHang (Ten, SoDienThoai, DiaChi, Username, Password)
VALUES 
('Nguyen Van A', '0123456789', 'Hanoi', 'nguyenvana', 'password1'),
('Tran Thi B', '0987654321', 'Saigon', 'tranthib', 'password2'),
('Le Van C', '0912345678', 'Danang', 'levanc', 'password3'),
('Pham Thi D', '0908765432', 'Hue', 'phamthid', 'password4'),
('Hoang Van E', '0934567890', 'Haiphong', 'hoangvane', 'password5');


INSERT INTO DichVu (TenDichVu)
VALUES 
('Internet'),
('Truyền Hình'),
('Di động'),
('Điện thoại cố định'),
('Dịch vụ Khác');


INSERT INTO GoiDichVu (ID_DichVu, TenGoiDichVu, TocDo, GiaTien, MoTa)
VALUES 
(1, 'Gói cước Internet Cơ bản', '50Mbps', 200000, 'Internet tốc độ cơ bản'),
(1, 'Gói cước Internet Cao cấp', '200Mbps', 500000, 'Internet tốc độ cao'),
(2, 'Gói cước Truyền Hình Cơ bản', NULL, 150000, 'Truyền hình cáp cơ bản'),
(2, 'Gói cước Truyền Hình Cao cấp', NULL, 300000, 'Truyền hình cáp cao cấp'),
(3, 'Gói cước Di động Cơ bản', NULL, 100000, 'Di động cơ bản');


INSERT INTO NhanVien (TenNhanVien, SoDienThoai, DiaChi, Username, Password)
VALUES 
('Nguyen Van F', '0123456781', 'Hanoi', 'nguyenvanf', 'password6'),
('Tran Thi G', '0987654322', 'Saigon', 'tranthig', 'password7'),
('Le Van H', '0912345679', 'Danang', 'levanh', 'password8'),
('Pham Thi I', '0908765433', 'Hue', 'phamthii', 'password9'),
('Hoang Van J', '0934567891', 'Haiphong', 'hoangvanj', 'password10');


INSERT INTO TTNhanVienBanHang (TenNhanVien, SoDienThoai, DiaChi, ID_NhanVien)
VALUES 
('Nguyen Van F', '0123456781', 'Hanoi', 1),
('Tran Thi G', '0987654322', 'Saigon', 2),
('Le Van H', '0912345679', 'Danang', 3),
('Pham Thi I', '0908765433', 'Hue', 4),
('Hoang Van J', '0934567891', 'Haiphong', 5);


INSERT INTO ThongTinBanHang (ID_KhachHang, ID_GoiDichVu, ID_TTNVBH, NgayDangKy, SoLuong)
VALUES 
(1, 1, 1, '2024-01-01', 1),
(2, 2, 2, '2024-01-02', 1),
(3, 3, 3, '2024-01-03', 1),
(4, 4, 4, '2024-01-04', 1),
(5, 5, 5, '2024-01-05', 1),
(1, 2, 1, '2024-01-06', 2),
(2, 3, 2, '2024-01-07', 1),
(3, 4, 3, '2024-01-08', 1),
(4, 5, 4, '2024-01-09', 1),
(5, 1, 5, '2024-01-10', 1);


INSERT INTO DoanhThu (ID_ThongTinBanHang, ThoiGian, SoTien)
VALUES 
(1, '2024-01-01', 200000),
(2, '2024-01-02', 500000),
(3, '2024-01-03', 150000),
(4, '2024-01-04', 300000),
(5, '2024-01-05', 100000),
(6, '2024-01-06', 1000000),
(7, '2024-01-07', 150000),
(8, '2024-01-08', 300000),
(9, '2024-01-09', 100000),
(10, '2024-01-10', 200000);
