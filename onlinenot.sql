-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 26 May 2023, 15:58:30
-- Sunucu sürümü: 8.0.31
-- PHP Sürümü: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `onlinenot`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanici_tbl`
--

DROP TABLE IF EXISTS `kullanici_tbl`;
CREATE TABLE IF NOT EXISTS `kullanici_tbl` (
  `kullanici_id` int NOT NULL AUTO_INCREMENT,
  `ad_soyad` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `sifre` varchar(50) NOT NULL,
  PRIMARY KEY (`kullanici_id`),
  KEY `index_adi` (`kullanici_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `kullanici_tbl`
--

INSERT INTO `kullanici_tbl` (`kullanici_id`, `ad_soyad`, `mail`, `sifre`) VALUES
(7, 'a', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notlar`
--

DROP TABLE IF EXISTS `notlar`;
CREATE TABLE IF NOT EXISTS `notlar` (
  `not_id` int NOT NULL AUTO_INCREMENT,
  `kullanici_id` int NOT NULL,
  `not_baslik` text NOT NULL,
  `not_icerik` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`not_id`),
  KEY `index_adi` (`kullanici_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Tablo döküm verisi `notlar`
--

INSERT INTO `notlar` (`not_id`, `kullanici_id`, `not_baslik`, `not_icerik`) VALUES
(8, 7, 'ALERT', 'DENEME'),
(9, 7, 'aa', 'sss'),
(10, 7, 'aaaaa', 'ssssss'),
(11, 7, 'deneme 22', 'asdfggg');

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `notlar`
--
ALTER TABLE `notlar`
  ADD CONSTRAINT `notlar_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanici_tbl` (`kullanici_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
