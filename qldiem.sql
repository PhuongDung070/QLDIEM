-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 22, 2024 at 10:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qldiem`
--

-- --------------------------------------------------------

--
-- Table structure for table `danhsachhocphan`
--

CREATE TABLE `danhsachhocphan` (
  `MaDS` varchar(10) NOT NULL,
  `MaLHP` varchar(10) DEFAULT NULL,
  `MaKhoa` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danhsachhocphan`
--

INSERT INTO `danhsachhocphan` (`MaDS`, `MaLHP`, `MaKhoa`) VALUES
('DS001', 'HP001', 'KH01'),
('DS011', 'HP001', 'KH02'),
('DS002', 'HP002', 'KH01'),
('DS012', 'HP002', 'KH03'),
('DS003', 'HP003', 'KH03'),
('DS013', 'HP003', 'KH04'),
('DS004', 'HP004', 'KH04'),
('DS014', 'HP004', 'KH05'),
('DS005', 'HP005', 'KH01'),
('DS015', 'HP005', 'KH06'),
('DS006', 'HP006', 'KH06'),
('DS007', 'HP007', 'KH07'),
('DS008', 'HP008', 'KH01'),
('DS009', 'HP009', 'KH09'),
('DS010', 'HP010', 'KH10');

-- --------------------------------------------------------

--
-- Table structure for table `giangvien`
--

CREATE TABLE `giangvien` (
  `MaID` char(10) NOT NULL,
  `MaKhoa` varchar(10) NOT NULL,
  `HocVi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giangvien`
--

INSERT INTO `giangvien` (`MaID`, `MaKhoa`, `HocVi`) VALUES
('NV01', 'KH01', 'Tiến sĩ'),
('NV02', 'KH02', 'Thạc sĩ'),
('NV03', 'KH01', 'Giáo sư'),
('NV04', 'KH03', 'Phó giáo sư'),
('NV05', 'KH02', 'Thạc sĩ'),
('NV06', 'KH04', 'Tiến sĩ'),
('NV07', 'KH03', 'Giáo sư'),
('NV08', 'KH05', 'Thạc sĩ'),
('NV09', 'KH01', 'Tiến sĩ'),
('NV10', 'KH04', 'Phó giáo sư');

-- --------------------------------------------------------

--
-- Table structure for table `giangvien_hocphan`
--

CREATE TABLE `giangvien_hocphan` (
  `MaID` char(10) NOT NULL,
  `MaLHP` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giangvien_hocphan`
--

INSERT INTO `giangvien_hocphan` (`MaID`, `MaLHP`) VALUES
('NV01', 'HP001'),
('NV01', 'HP002'),
('NV02', 'HP003'),
('NV02', 'HP004'),
('NV03', 'HP005'),
('NV03', 'HP006'),
('NV04', 'HP007'),
('NV04', 'HP008'),
('NV05', 'HP009'),
('NV05', 'HP010'),
('NV06', 'HP001'),
('NV06', 'HP003'),
('NV07', 'HP005'),
('NV07', 'HP007'),
('NV08', 'HP009'),
('NV09', 'HP002'),
('NV09', 'HP004'),
('NV10', 'HP006'),
('NV10', 'HP008');

-- --------------------------------------------------------

--
-- Table structure for table `hocphan`
--

CREATE TABLE `hocphan` (
  `MaLHP` char(10) NOT NULL,
  `TenHP` varchar(100) NOT NULL,
  `SoTinChi` int(11) NOT NULL,
  `KhoaPhuTrach` varchar(100) NOT NULL,
  `Hocphantruoc` varchar(100) NOT NULL,
  `SoTiet` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hocphan`
--

INSERT INTO `hocphan` (`MaLHP`, `TenHP`, `SoTinChi`, `KhoaPhuTrach`, `Hocphantruoc`, `SoTiet`) VALUES
('HP001', 'Toán Cao Cấp', 3, 'KH01', '', 45),
('HP002', 'Lập Trình Cơ Bản', 4, 'KH02', '', 60),
('HP003', 'Hệ Điều Hành', 3, 'KH03', 'HP002', 45),
('HP004', 'Quản Trị Mạng', 3, 'KH04', 'HP003', 45),
('HP005', 'Phân Tích Dữ Liệu', 3, 'KH05', 'HP002', 45),
('HP006', 'Thiết Kế Web', 3, 'KH06', 'HP002', 45),
('HP007', 'Kinh Tế Vi Mô', 2, 'KH07', '', 30),
('HP008', 'Kỹ Năng Mềm', 2, 'KH08', '', 30),
('HP009', 'Thương Mại Điện Tử', 3, 'KH09', 'HP006', 45),
('HP010', 'Trí Tuệ Nhân Tạo', 4, 'KH10', 'HP003', 60);

-- --------------------------------------------------------

--
-- Table structure for table `ketquasv`
--

CREATE TABLE `ketquasv` (
  `MaID` char(10) NOT NULL,
  `MaLop` char(10) NOT NULL,
  `DiemCC` float DEFAULT NULL,
  `DiemKT1` float DEFAULT NULL,
  `DiemKT2` float DEFAULT NULL,
  `DiemTL` float DEFAULT NULL,
  `DiemKTHP` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ketquasv`
--

INSERT INTO `ketquasv` (`MaID`, `MaLop`, `DiemCC`, `DiemKT1`, `DiemKT2`, `DiemTL`, `DiemKTHP`) VALUES
('SV001', 'LHP001', 9, 10, 7, 9, 8.8),
('SV001', 'LHP002', 7.5, NULL, 9, 8.5, 8.3),
('SV001', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV002', 'LHP001', 9, 1, 8.5, 9, 8.8),
('SV002', 'LHP003', 6, 7, 7, 7.5, 7.1),
('SV002', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV003', 'LHP002', 9, 8, 8.5, 9, 8.8),
('SV003', 'LHP003', 8, 7.5, 7, 8, 7.8),
('SV003', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV004', 'LHP001', 9, 1, 8.5, 9, 8.8),
('SV004', 'LHP002', 8, 7.5, 7.5, 8.5, 8),
('SV004', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV005', 'LHP001', 9, 1, 8.5, 9, 8.8),
('SV005', 'LHP003', 7, 6.5, 7, 7.5, 7.3),
('SV005', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV006', 'LHP002', 7.5, 6, 7, 8, 7.4),
('SV006', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV007', 'LHP002', 8, 7.5, 8, 8.5, 8.2),
('SV007', 'LHP003', 7.5, 6.5, 7.5, 8, 7.6),
('SV007', 'LHP005', NULL, NULL, NULL, NULL, NULL),
('SV008', 'LHP003', 7.5, 7, 7.5, 8, 7.8),
('SV009', 'LHP001', 9, 1, 8.5, 9, 8.8),
('SV010', 'LHP002', 6.5, 6.5, 6, 7, 6.5);

-- --------------------------------------------------------

--
-- Table structure for table `khoa`
--

CREATE TABLE `khoa` (
  `MaKhoa` varchar(10) NOT NULL,
  `TenKhoa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khoa`
--

INSERT INTO `khoa` (`MaKhoa`, `TenKhoa`) VALUES
('KH01', 'Khoa Công nghệ thông tin'),
('KH02', 'Khoa Quản trị kinh doanh'),
('KH03', 'Khoa Kinh tế'),
('KH04', 'Khoa Kỹ thuật cơ khí'),
('KH05', 'Khoa Kỹ thuật điện'),
('KH06', 'Khoa Hóa học'),
('KH07', 'Khoa Vật lý'),
('KH08', 'Khoa Sinh học'),
('KH09', 'Khoa Y học cơ sở'),
('KH10', 'Khoa Giáo dục thể chất');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `MaID` char(10) NOT NULL,
  `TenNV` varchar(100) NOT NULL,
  `NgaySinh` date NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `SDT` char(15) NOT NULL,
  `Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhanvien`
--

INSERT INTO `nhanvien` (`MaID`, `TenNV`, `NgaySinh`, `DiaChi`, `GioiTinh`, `SDT`, `Email`) VALUES
('NV01', 'Tran Thi B', '1985-07-20', '456 Nguyen Trai, Ha Noi', 'Nu', '0987654321', 'tranthib@example.com'),
('NV02', 'Tran Thi B', '1982-07-15', '456 Nguyen Trai, Ha Noi', 'Nu', '0987654322', 'tranthib@example.com'),
('NV03', 'Le Van C', '1985-09-10', '789 Le Loi, Ha Noi', 'Nam', '0987654323', 'levanc@example.com'),
('NV04', 'Pham Thi D', '1987-11-25', '12 Hai Ba Trung, Ha Noi', 'Nu', '0987654324', 'phamthid@example.com'),
('NV05', 'Hoang Van E', '1990-01-30', '34 Nguyen Du, Ha Noi', 'Nam', '0987654325', 'hoangvane@example.com'),
('NV06', 'Do Thi F', '1988-03-18', '56 Hang Bong, Ha Noi', 'Nu', '0987654326', 'dothif@example.com'),
('NV07', 'Nguyen Van G', '1979-12-22', '78 Hang Bai, Ha Noi', 'Nam', '0987654327', 'nguyenvang@example.com'),
('NV08', 'Tran Thi H', '1983-06-05', '90 Giang Vo, Ha Noi', 'Nu', '0987654328', 'tranthih@example.com'),
('NV09', 'Le Van I', '1986-08-12', '123 Hoang Hoa Tham, Ha Noi', 'Nam', '0987654329', 'levani@example.com'),
('NV10', 'Pham Thi J', '1989-10-19', '45 Le Thanh Nghi, Ha Noi', 'Nu', '0987654330', 'phamthij@example.com'),
('QL01', 'Tran Van C', '1990-12-05', '789 Tran Phu, Ha Noi', 'Nam', '0971112233', 'tranc@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvienquanly`
--

CREATE TABLE `nhanvienquanly` (
  `MaID` char(10) NOT NULL,
  `PhongBan` varchar(100) NOT NULL,
  `ChucVu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhanvienquanly`
--

INSERT INTO `nhanvienquanly` (`MaID`, `PhongBan`, `ChucVu`) VALUES
('QL01', 'Phòng Đào Tạo', 'Trưởng phòng');

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `MaID` char(10) NOT NULL,
  `TenSV` varchar(100) NOT NULL,
  `DiaChi` varchar(255) NOT NULL,
  `NgaySinh` date NOT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `SDT` char(15) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `LopHC` char(10) DEFAULT NULL,
  `KhoaHoc` char(10) DEFAULT NULL,
  `HinhThucDT` varchar(50) DEFAULT NULL,
  `MaKhoa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`MaID`, `TenSV`, `DiaChi`, `NgaySinh`, `GioiTinh`, `SDT`, `Email`, `LopHC`, `KhoaHoc`, `HinhThucDT`, `MaKhoa`) VALUES
('SV001', 'Nguyễn Văn A', 'Hà Nội', '2003-05-15', 'Nam', '0987654321', 'nguyenvana@example.com', 'KTPM01', 'K199', 'Chính quy', 'KH01'),
('SV002', 'Trần Thị B', 'Hải Phòng', '2002-11-23', 'Nữ', '0987654322', 'tranthib@example.com', 'CNTT01', 'K18', 'Chính quy', 'KH02'),
('SV003', 'Lê Văn C', 'Đà Nẵng', '2004-02-14', 'Nam', '0987654323', 'levanc@example.com', 'KTPM02', 'K20', 'Chính quy', 'KH03'),
('SV004', 'Phạm Thị D', 'Hồ Chí Minh', '2003-07-19', 'Nữ', '0987654324', 'phamthid@example.com', 'QTKD01', 'K19', 'Chính quy', 'KH04'),
('SV005', 'Vũ Văn E', 'Huế', '2001-09-25', 'Nam', '0987654325', 'vuvane@example.com', 'KTPM03', 'K17', 'Liên thông', 'KH05'),
('SV006', 'Đinh Thị F', 'Cần Thơ', '2004-04-10', 'Nữ', '0987654326', 'dinhthif@example.com', 'CNTT02', 'K20', 'Chính quy', 'KH06'),
('SV007', 'Hoàng Văn G', 'Quảng Ninh', '2003-03-30', 'Nam', '0987654327', 'hoangvang@example.com', 'QTKD02', 'K19', 'Chính quy', 'KH07'),
('SV008', 'Ngô Thị H', 'Nam Định', '2002-06-15', 'Nữ', '0987654328', 'ngothih@example.com', 'CNTT03', 'K18', 'Chính quy', 'KH08'),
('SV009', 'Bùi Văn I', 'Thanh Hóa', '2004-08-21', 'Nam', '0987654329', 'buivani@example.com', 'KTPM04', 'K20', 'Chính quy', 'KH09'),
('SV010', 'Phan Thị K', 'Nghệ An', '2003-12-12', 'Nữ', '0987654330', 'phanthik@example.com', 'QTKD03', 'K19', 'Chính quy', 'KH10'),
('SV4799', 'eqwe', 'eqwe', '2000-12-12', 'Nam', '21312', '2@gmail.com', '32', '23', 'dsda', 'KH02');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaID` char(10) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(50) NOT NULL,
  `Quyen` char(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`MaID`, `TenDangNhap`, `MatKhau`, `Quyen`) VALUES
('NV01', 'testgv', '123456', 'GiangVien'),
('NV02', 'NV0202', 'password02', 'GiangVien'),
('NV03', 'NV0303', 'password03', 'GiangVien'),
('NV04', 'NV0404', 'password04', 'GiangVien'),
('NV05', 'NV0505', 'password05', 'GiangVien'),
('NV06', 'NV0606', 'password06', 'GiangVien'),
('NV07', 'NV0707', 'password07', 'GiangVien'),
('NV08', 'NV0808', 'password08', 'GiangVien'),
('NV09', 'NV0909', 'password09', 'GiangVien'),
('NV10', 'NV1010', 'password10', 'GiangVien'),
('QL01', 'testnv', '123456', 'QuanLy'),
('SV001', 'testsv', '123456', 'SinhVien'),
('SV002', 'tranb', 'password123', 'SinhVien'),
('SV003', 'nguyenc', 'password123', 'SinhVien'),
('SV004', 'led', 'password123', 'SinhVien'),
('SV005', 'phame', 'password123', 'SinhVien'),
('SV006', 'doanf', 'password123', 'SinhVien'),
('SV007', 'hoangg', 'password123', 'SinhVien'),
('SV008', 'vuh', 'password123', 'SinhVien'),
('SV009', 'buii', 'password123', 'SinhVien'),
('SV010', 'lej', 'password123', 'SinhVien'),
('SV4799', 'SV47998683', 'a0i8DKwh', 'SinhVien'),
('SV4962', 'SV49629363', 'hXhGOKOx', 'SinhVien'),
('SV6140', 'SV61403635', '83SleOym', 'SinhVien'),
('SV8284', 'SV82842003', 'ozpaML9v', 'SinhVien');

-- --------------------------------------------------------

--
-- Table structure for table `thoikhoabieu`
--

CREATE TABLE `thoikhoabieu` (
  `MaLop` varchar(10) NOT NULL,
  `MaLHP` varchar(10) NOT NULL,
  `PhongHoc` varchar(11) NOT NULL,
  `ThoiGianHoc` varchar(11) NOT NULL,
  `GiangVien` varchar(100) NOT NULL,
  `Ngày học` varchar(10) DEFAULT NULL,
  `Thời gian bắt đầu` date DEFAULT NULL,
  `Thời gian kết thúc` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thoikhoabieu`
--

INSERT INTO `thoikhoabieu` (`MaLop`, `MaLHP`, `PhongHoc`, `ThoiGianHoc`, `GiangVien`, `Ngày học`, `Thời gian bắt đầu`, `Thời gian kết thúc`) VALUES
('LHP001', 'HP001', 'A101', 'Thứ 2 08:00', 'NV01', NULL, NULL, NULL),
('LHP002', 'HP002', 'A102', 'Thứ 2 10:00', 'NV01', NULL, NULL, NULL),
('LHP003', 'HP003', 'A103', 'Thứ 2 12:00', 'NV03', NULL, NULL, NULL),
('LHP004', 'HP004', 'B201', 'Thứ 3 08:00', 'NV04', NULL, NULL, NULL),
('LHP005', 'HP005', 'B202', 'Thứ 3 10:00', 'NV01', NULL, NULL, NULL),
('LHP006', 'HP006', 'C301', 'Thứ 4 08:00', 'NV06', NULL, NULL, NULL),
('LHP007', 'HP007', 'C302', 'Thứ 4 10:00', 'NV07', NULL, NULL, NULL),
('LHP008', 'HP008', 'D401', 'Thứ 5 08:00', 'NV08', NULL, NULL, NULL),
('LHP009', 'HP009', 'D402', 'Thứ 5 10:00', 'NV09', NULL, NULL, NULL),
('LHP010', 'HP010', 'E501', 'Thứ 6 08:00', 'NV10', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thongkebaocao`
--

CREATE TABLE `thongkebaocao` (
  `MaTK` char(10) NOT NULL,
  `MaID` char(10) NOT NULL,
  `TenTK` varchar(100) NOT NULL,
  `NoiDung` varchar(255) NOT NULL,
  `NgayNhap` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `danhsachhocphan`
--
ALTER TABLE `danhsachhocphan`
  ADD PRIMARY KEY (`MaDS`),
  ADD UNIQUE KEY `unique_mahocphan_makhoa` (`MaLHP`,`MaKhoa`),
  ADD KEY `MaKhoa` (`MaKhoa`);

--
-- Indexes for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD PRIMARY KEY (`MaID`),
  ADD KEY `giangvien_ibfk_2` (`MaKhoa`);

--
-- Indexes for table `giangvien_hocphan`
--
ALTER TABLE `giangvien_hocphan`
  ADD PRIMARY KEY (`MaID`,`MaLHP`),
  ADD KEY `MaLHP` (`MaLHP`);

--
-- Indexes for table `hocphan`
--
ALTER TABLE `hocphan`
  ADD PRIMARY KEY (`MaLHP`),
  ADD KEY `fk_hocphan_1` (`KhoaPhuTrach`);

--
-- Indexes for table `ketquasv`
--
ALTER TABLE `ketquasv`
  ADD PRIMARY KEY (`MaID`,`MaLop`) USING BTREE,
  ADD KEY `ketquasv_ibfk_2` (`MaLop`);

--
-- Indexes for table `khoa`
--
ALTER TABLE `khoa`
  ADD PRIMARY KEY (`MaKhoa`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`MaID`);

--
-- Indexes for table `nhanvienquanly`
--
ALTER TABLE `nhanvienquanly`
  ADD PRIMARY KEY (`MaID`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`MaID`),
  ADD KEY `sinhvien_ibfk_2` (`MaKhoa`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaID`);

--
-- Indexes for table `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD PRIMARY KEY (`MaLop`),
  ADD KEY `thoikhoabieu_ibfk_2` (`GiangVien`),
  ADD KEY `thoikhoabieu_ibfk_3` (`MaLHP`);

--
-- Indexes for table `thongkebaocao`
--
ALTER TABLE `thongkebaocao`
  ADD PRIMARY KEY (`MaTK`),
  ADD KEY `thongkebaocao_ibfk_1` (`MaID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danhsachhocphan`
--
ALTER TABLE `danhsachhocphan`
  ADD CONSTRAINT `danhsachhocphan_ibfk_1` FOREIGN KEY (`MaLHP`) REFERENCES `hocphan` (`MaLHP`),
  ADD CONSTRAINT `danhsachhocphan_ibfk_2` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`);

--
-- Constraints for table `giangvien`
--
ALTER TABLE `giangvien`
  ADD CONSTRAINT `giangvien_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `nhanvien` (`MaID`),
  ADD CONSTRAINT `giangvien_ibfk_2` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`);

--
-- Constraints for table `giangvien_hocphan`
--
ALTER TABLE `giangvien_hocphan`
  ADD CONSTRAINT `giangvien_hocphan_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `giangvien` (`MaID`) ON DELETE CASCADE,
  ADD CONSTRAINT `giangvien_hocphan_ibfk_2` FOREIGN KEY (`MaLHP`) REFERENCES `hocphan` (`MaLHP`) ON DELETE CASCADE;

--
-- Constraints for table `hocphan`
--
ALTER TABLE `hocphan`
  ADD CONSTRAINT `fk_hocphan_1` FOREIGN KEY (`KhoaPhuTrach`) REFERENCES `khoa` (`MaKhoa`);

--
-- Constraints for table `ketquasv`
--
ALTER TABLE `ketquasv`
  ADD CONSTRAINT `ketquasv_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `sinhvien` (`MaID`),
  ADD CONSTRAINT `ketquasv_ibfk_2` FOREIGN KEY (`MaLop`) REFERENCES `thoikhoabieu` (`MaLop`);

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `taikhoan` (`MaID`);

--
-- Constraints for table `nhanvienquanly`
--
ALTER TABLE `nhanvienquanly`
  ADD CONSTRAINT `nhanvienquanly_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `nhanvien` (`MaID`);

--
-- Constraints for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD CONSTRAINT `sinhvien_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `taikhoan` (`MaID`),
  ADD CONSTRAINT `sinhvien_ibfk_2` FOREIGN KEY (`MaKhoa`) REFERENCES `khoa` (`MaKhoa`);

--
-- Constraints for table `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD CONSTRAINT `thoikhoabieu_ibfk_1` FOREIGN KEY (`MaLHP`) REFERENCES `hocphan` (`MaLHP`),
  ADD CONSTRAINT `thoikhoabieu_ibfk_2` FOREIGN KEY (`GiangVien`) REFERENCES `giangvien` (`MaID`),
  ADD CONSTRAINT `thoikhoabieu_ibfk_3` FOREIGN KEY (`MaLHP`) REFERENCES `hocphan` (`MaLHP`);

--
-- Constraints for table `thongkebaocao`
--
ALTER TABLE `thongkebaocao`
  ADD CONSTRAINT `thongkebaocao_ibfk_1` FOREIGN KEY (`MaID`) REFERENCES `nhanvienquanly` (`MaID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
