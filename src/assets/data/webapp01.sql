-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2017 at 12:33 PM
-- Server version: 5.7.9
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webapp01`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` tinyint(4) NOT NULL,
  `business_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `about_text` text COLLATE utf8_unicode_ci NOT NULL,
  `intro_lead_in` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `intro_heading` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phone_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `instagram` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `business_name`, `about_text`, `intro_lead_in`, `intro_heading`, `phone_no`, `email`, `instagram`, `facebook`, `logo_name`) VALUES
(1, 'Papa\'s Media Production', 'Papa\'s Media Systems is a company based in the Gambia that provides high quality printing service, sound and lighting equipment, DJ service, and video production for events. 	\r\n		\r\nWe have a strong reputation for exceptional DJ service for corporate and social events. We have state of the art sound and lighting equipment and well-known experienced DJs for your weddings, naming ceremonies, birthdays, graduations, corporate parties, etc.\r\n				\r\nWe provide video and photography services with high quality content for events. Our cameramen have the latest camera gear and lighting equipment at their disposal, and have years of experience.\r\n	\r\nWe offer in-house printing of ID Card and custom printed cards. Service ranges from plastic card printing to embossing metal tags.', 'Papa\'s Media Production', 'Sound System and More...', '+220 7153333', 'info@papasmedia.com', 'https://www.instagram.com/', 'https://www.facebook.com/Papas-Entertainment-1-173255886366345', 'img-56def433ca81b-20160308154803.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `gphotos`
--

CREATE TABLE `gphotos` (
  `id` int(11) NOT NULL,
  `img_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `img_descr` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gphotos`
--

INSERT INTO `gphotos` (`id`, `img_name`, `img_descr`) VALUES
(1, '1.jpg', 'From off a hill whose concave womb reworded'),
(2, '2.jpg', 'A plaintful story from a sistering vale'),
(3, '3.jpg', 'A plaintful story from a sistering vale'),
(4, '4.jpg', 'My spirits to attend this double voice accorded'),
(5, '5.jpg', 'And down I laid to list the sad-tuned tale'),
(6, 'img-56cdca40dae7d-20160224162032.jpg', 'A plaintful story from a sistering vale'),
(7, 'img-56cdca4cb8153-20160224162044.jpg', 'A plaintful story from a sistering vale'),
(8, 'img-56cdca56c2133-20160224162054.jpg', 'A plaintful story from a sistering vale'),
(9, 'img-56cdca63d9b4b-20160224162107.jpg', 'A plaintful story from a sistering vale'),
(10, 'img-56cdca79bf490-20160224162129.jpg', 'A plaintful story from a sistering vale'),
(17, 'img-56d6e271bf52c-20160302135409.jpg', 'Test Photo uploaded from admin page'),
(18, 'img-56d77705a823b-20160303002805.jpg', 'From off a hill whose concave womb reworded'),
(19, 'img-56d7770f47a8a-20160303002815.jpg', 'From off a hill whose concave womb reworded'),
(20, 'img-56d7772044101-20160303002832.jpg', 'From off a hill whose concave womb reworded'),
(21, 'img-56d7772a045a1-20160303002842.jpg', 'From off a hill whose concave womb reworded');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `svc_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `svc_descr` text COLLATE utf8_unicode_ci NOT NULL,
  `svc_img_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `svc_name`, `svc_descr`, `svc_img_name`) VALUES
(1, 'PA (Sound) System', 'Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here Description of PA (Sound) System here', 'svc-56ca415fe2b9e-20160221235943.jpg'),
(2, 'Lighting System', 'Description of lighting system here Description of lighting system here Description of lighting system here Description of lighting system here Description of lighting system here Description of lighting system here Description of lighting system here Description of lighting system here', 'svc-56ca1746e7ec1-20160221210006.jpg'),
(3, 'Video Coverage', 'Unlike a controlled studio environment, live event coverage requires an infinite number of carefully coordinated details. With PAPA’S MEDIA PRODUCTION, the public don’t need to worry in fact, they can relax. Our team specializes in the various nuances and complexities of a live event and we will work with them to carefully plan and schedule so even the most prepared moments will feel fresh and exciting. We can capture their once in a lifetime event so it will live forever. As a one-stop production house, PAPA’S MEDIA PRODUCTION can handle every aspect of preparation and production; from setting up cameras, designing lighting and managing equipment to shooting, editing and distributing the live event. By working with PAPA’S MEDIA PRODUCTION, they can relax and feel confident knowing that they have made the right choice. Our crew will work with the event organizers and planners ensuring that every department will understand our vision and will be able to execute it flawlessly during the event.', 'svc-56ca174fa5e3b-20160221210015.jpg'),
(11, 'Test Services', 'Test ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest ServiceTest Service', 'img-56eb45ce855b0-20160318000326.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password_hash`, `admin`) VALUES
(1, 'cjarju', '21232f297a57a5a743894a0e4a801fc3', 1),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 1),
(13, 'test', '098f6bcd4621d373cade4e832627b4f6', 1),
(15, 'test02', '098f6bcd4621d373cade4e832627b4f6', 1),
(21, 'test03', '098f6bcd4621d373cade4e832627b4f6', 1),
(24, 'test04', '098f6bcd4621d373cade4e832627b4f6', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gphotos`
--
ALTER TABLE `gphotos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `gphotos`
--
ALTER TABLE `gphotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
