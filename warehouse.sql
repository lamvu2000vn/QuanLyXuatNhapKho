-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th3 24, 2021 lúc 08:20 AM
-- Phiên bản máy phục vụ: 10.4.10-MariaDB
-- Phiên bản PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `warehouse`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created` date NOT NULL,
  `modified` date NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`cat_id`, `name`, `status`, `created`, `modified`) VALUES
(1, 'Laptop', 1, '0000-00-00', '0000-00-00'),
(2, '1', 2, '0000-00-00', '0000-00-00'),
(3, '2', 2, '0000-00-00', '0000-00-00'),
(4, 'Vũ Hoàng Lâm', 2, '0000-00-00', '0000-00-00'),
(5, '1', 2, '0000-00-00', '0000-00-00'),
(6, '2', 2, '0000-00-00', '0000-00-00'),
(7, 'no', 2, '0000-00-00', '0000-00-00'),
(8, 'no', 2, '0000-00-00', '0000-00-00'),
(9, 'no', 2, '0000-00-00', '0000-00-00'),
(10, 'gg', 2, '0000-00-00', '0000-00-00'),
(11, 'Suzuki', 2, '2021-03-16', '2021-03-16'),
(12, 'Suzuki', 2, '2021-03-16', '2021-03-16'),
(13, 'Suzuki', 2, '2021-03-16', '2021-03-16'),
(14, 'Laptop', 2, '2021-03-16', '2021-03-16'),
(15, 'CPU', 2, '2021-03-16', '2021-03-16'),
(16, 'Room', 2, '2021-03-16', '2021-03-16'),
(17, 'Print', 2, '2021-03-16', '2021-03-16'),
(18, 'Honeywell', 2, '2021-03-19', '2021-03-19'),
(19, 'Honeywell', 2, '2021-03-19', '2021-03-19'),
(20, '123', 2, '2021-03-19', '2021-03-19'),
(21, '123', 2, '2021-03-19', '2021-03-19'),
(22, '369', 2, '2021-03-19', '2021-03-19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `image` varchar(100) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  `price` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `fk_cat_id` (`cat_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `item`
--

INSERT INTO `item` (`item_id`, `name`, `image`, `price`, `cat_id`, `qty`, `status`) VALUES
(1, 'Bbbbb1', '2021-03-19-05-16-28-863da407f11021d3197ce7583977c2a2.jpg', 11, 12, 10, 1),
(2, 'Nitro', '2021-03-18-03-44-50-tải xuống.jfif', 1000, 17, 0, 1),
(3, '1ads', '2021-03-21-09-58-30-tải xuống.jfif', 1, 1, 100, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `resource` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`id`, `name`, `resource`) VALUES
(1, 'admin', 'photo'),
(2, 'guest', 'login'),
(3, 'member', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('Female','Male','Other') COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `birthday`, `gender`, `photo`, `role_id`, `active`, `created`, `modified`) VALUES
(1, 'Tmt', 'tien@gmail.com', '12345678910', '2021-03-01', 'Female', 'ădawd', 1, 2, '2021-03-12 00:00:00', '2021-03-12 00:00:00'),
(2, 'Chaocaloc1200', 'a@gmail.com', '$2y$10$Idd3RbdSJEGTIscPX2SNH.HiKWWXRUsv/euSVHCyaAcCsxSkpFjFu', '2021-03-02', 'Male', 'adcar', 2, 2, '2021-03-12 08:34:13', '2021-03-12 08:34:13'),
(3, 'Tmt123', 's@gmail.com', '$2y$10$SyvK2d6Dzvqk95UDuS3/a.r5tfmikpGUfyRNxAgN5YEK/cGi.mpTm', NULL, 'Female', '1.jpg', 2, 1, '2021-03-12 08:56:58', '2021-03-12 08:56:58'),
(4, 'TranMinhTien', 'chao@gmail.com', '$2y$10$hYbychkbYq.Wo/jlBqoNZ.6bxEPGEm7BDR.KO.NGR52WoPjNaVadi', '2021-03-15', 'Female', '1.jpg', 2, 2, '2021-03-14 02:09:42', '2021-03-14 02:09:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `warehouse`
--

DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE IF NOT EXISTS `warehouse` (
  `ware_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `imp_qty` int(11) DEFAULT NULL,
  `qty_in_stock` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`ware_id`),
  KEY `item_id` (`item_id`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `warehouse`
--

INSERT INTO `warehouse` (`ware_id`, `item_id`, `date_time`, `id_user`, `imp_qty`, `qty_in_stock`, `type`) VALUES
(25, 1, '2021-03-02 14:53:00', 3, 5, 10, 1);

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_cat_id` FOREIGN KEY (`cat_id`) REFERENCES `category` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
