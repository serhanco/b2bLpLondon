-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 15 Eyl 2026, 17:19:42
-- Sunucu sürümü: 10.11.19-MariaDB
-- PHP Sürümü: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Veritabanı: `partneracibadem_asg_partnership_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `partnership_applications`
--

CREATE TABLE `partnership_applications` (
  `id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `volume` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `partnership_applications`
--

INSERT INTO `partnership_applications` (`id`, `role`, `name`, `email`, `phone`, `volume`, `message`, `url`, `created_at`) VALUES
(1, 'Entrepreneur', 'Serhan Demirel', 'webserhan@gmail.com', '+1 5322027361', '6–20', 'deneme mesajı', 'https://partner.acibademinternational.com/london/', '2026-08-24 21:04:46');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `partnership_applications`
--
ALTER TABLE `partnership_applications`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `partnership_applications`
--
ALTER TABLE `partnership_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
