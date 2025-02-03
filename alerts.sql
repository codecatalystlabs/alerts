-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2025 at 01:32 PM
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
-- Database: `alerts`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_units`
--

CREATE TABLE `admin_units` (
  `id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL,
  `sub_region` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_units`
--

INSERT INTO `admin_units` (`id`, `name`, `region`, `sub_region`) VALUES
(1, 'Abim', 'North', 'Karamoja'),
(2, 'Adjumani', 'North', 'West Nile'),
(3, 'Agago', 'North', 'Acholi'),
(4, 'Alebtong', 'North', 'Lango'),
(5, 'Amolatar', 'North', 'Lango'),
(6, 'Amudat', 'North', 'Karamoja'),
(7, 'Amuru', 'North', 'Acholi'),
(8, 'Apac', 'North', 'Lango'),
(9, 'Arua', 'North', 'West Nile'),
(10, 'Dokolo', 'North', 'Lango'),
(11, 'Gulu', 'North', 'Acholi'),
(12, 'Kaabong', 'North', 'Karamoja'),
(13, 'Kitgum', 'North', 'Acholi'),
(14, 'Koboko', 'North', 'West Nile'),
(15, 'Kole', 'North', 'Lango'),
(16, 'Kotido', 'North', 'Karamoja'),
(17, 'Lamwo', 'North', 'Acholi'),
(18, 'Lira', 'North', 'Lango'),
(19, 'Maracha', 'North', 'West Nile'),
(20, 'Moroto', 'North', 'Karamoja'),
(21, 'Moyo', 'North', 'West Nile'),
(22, 'Nakapiripirit', 'North', 'Karamoja'),
(23, 'Napak', 'North', 'Karamoja'),
(24, 'Nebbi', 'North', 'West Nile'),
(25, 'Nwoya', 'North', 'Acholi'),
(26, 'Otuke', 'North', 'Lango'),
(27, 'Oyam', 'North', 'Lango'),
(28, 'Pader', 'North', 'Acholi'),
(29, 'Yumbe', 'North', 'West Nile'),
(30, 'Zombo', 'North', 'West Nile'),
(31, 'Karenga', 'North', 'Karamoja'),
(32, 'Kwania', 'North', 'Lango'),
(33, 'Nabilatuk', 'North', 'Karamoja'),
(34, 'Obongi', 'North', 'West Nile'),
(35, 'Omoro', 'North', 'Acholi'),
(36, 'Pakwach', 'North', 'West Nile'),
(37, 'Terego', 'North', 'West Nile'),
(38, 'Madi-Okollo', 'North', 'West Nile'),
(39, 'Amuria', 'East', 'Teso'),
(40, 'Budaka', 'East', 'Bugisu'),
(41, 'Bududa', 'East', 'Bugisu'),
(42, 'Bugiri', 'East', 'Busoga'),
(43, 'Bukedea', 'East', 'Teso'),
(44, 'Bukwo', 'East', 'Bugisu'),
(45, 'Bulambuli', 'East', 'Bugisu'),
(46, 'Busia', 'East', 'Busoga'),
(47, 'Butaleja', 'East', 'Bugisu'),
(48, 'Buyende', 'East', 'Busoga'),
(49, 'Iganga', 'East', 'Busoga'),
(50, 'Butebo', 'East', 'Teso'),
(51, 'Jinja', 'East', 'Busoga'),
(52, 'Kaberamaido', 'East', 'Teso'),
(53, 'Kaliro', 'East', 'Busoga'),
(54, 'Kamuli', 'East', 'Busoga'),
(55, 'Kapchorwa', 'East', 'Bugisu'),
(56, 'Katakwi', 'East', 'Teso'),
(57, 'Kibuku', 'East', 'Bugisu'),
(58, 'Kumi', 'East', 'Teso'),
(59, 'Kween', 'East', 'Bugisu'),
(60, 'Luuka', 'East', 'Busoga'),
(61, 'Manafwa', 'East', 'Bugisu'),
(62, 'Namayingo', 'East', 'Busoga'),
(63, 'Namutumba', 'East', 'Busoga'),
(64, 'Ngora', 'East', 'Teso'),
(65, 'Pallisa', 'East', 'Teso'),
(66, 'Serere', 'East', 'Teso'),
(67, 'Sironko', 'East', 'Bugisu'),
(68, 'Soroti', 'East', 'Teso'),
(69, 'Kapelebyong', 'East', 'Teso'),
(70, 'Mayuge', 'East', 'Busoga'),
(71, 'Mbale', 'East', 'Bugisu'),
(72, 'Namisindwa', 'East', 'Bugisu'),
(73, 'Tororo', 'East', 'Teso'),
(74, 'Bugweri', 'East', 'Busoga'),
(75, 'Kalaki', 'East', 'Teso'),
(76, 'Buikwe', 'Central', 'Busoga'),
(77, 'Bukomansimbi', 'Central', 'Buganda'),
(78, 'Butambala', 'Central', 'Buganda'),
(79, 'Buvuma', 'Central', 'Busoga'),
(80, 'Gomba', 'Central', 'Buganda'),
(81, 'Kalangala', 'Central', 'Buganda'),
(82, 'Kalungu', 'Central', 'Buganda'),
(83, 'Kayunga', 'Central', 'Buganda'),
(84, 'Kiboga', 'Central', 'Buganda'),
(85, 'Kyankwanzi', 'Central', 'Buganda'),
(86, 'Luweero', 'Central', 'Buganda'),
(87, 'Lwengo', 'Central', 'Buganda'),
(88, 'Lyantonde', 'Central', 'Buganda'),
(89, 'Masaka', 'Central', 'Buganda'),
(90, 'Mityana', 'Central', 'Buganda'),
(91, 'Mpigi', 'Central', 'Buganda'),
(92, 'Mubende', 'Central', 'Buganda'),
(93, 'Mukono', 'Central', 'Buganda'),
(94, 'Nakaseke', 'Central', 'Buganda'),
(95, 'Nakasongola', 'Central', 'Buganda'),
(96, 'Rakai', 'Central', 'Buganda'),
(97, 'Sembabule', 'Central', 'Buganda'),
(98, 'Wakiso', 'Central', 'Buganda'),
(99, 'Kasanda', 'Central', 'Buganda'),
(100, 'Kyotera', 'Central', 'Buganda'),
(101, 'Kampala', 'Central', 'Buganda'),
(102, 'Buhweju', 'West', 'Ankole'),
(103, 'Buliisa', 'West', 'Bunyoro'),
(104, 'Bundibugyo', 'West', 'Rwenzori'),
(105, 'Bushenyi', 'West', 'Ankole'),
(106, 'Hoima', 'West', 'Bunyoro'),
(107, 'Ibanda', 'West', 'Ankole'),
(108, 'Isingiro', 'West', 'Ankole'),
(109, 'Kabale', 'West', 'Kigezi'),
(110, 'Kabarole', 'West', 'Rwenzori'),
(111, 'Kamwenge', 'West', 'Rwenzori'),
(112, 'Kanungu', 'West', 'Kigezi'),
(113, 'Kasese', 'West', 'Rwenzori'),
(114, 'Kibaale', 'West', 'Bunyoro'),
(115, 'Kiruhura', 'West', 'Ankole'),
(116, 'Kiryandongo', 'West', 'Bunyoro'),
(117, 'Kisoro', 'West', 'Kigezi'),
(118, 'Kyegegwa', 'West', 'Bunyoro'),
(119, 'Kyenjojo', 'West', 'Bunyoro'),
(120, 'Masindi', 'West', 'Bunyoro'),
(121, 'Mbarara', 'West', 'Ankole'),
(122, 'Mitooma', 'West', 'Ankole'),
(123, 'Ntoroko', 'West', 'Rwenzori'),
(124, 'Ntungamo', 'West', 'Ankole'),
(125, 'Rubirizi', 'West', 'Ankole'),
(126, 'Rukungiri', 'West', 'Kigezi'),
(127, 'Sheema', 'West', 'Ankole'),
(128, 'Kagadi', 'West', 'Bunyoro'),
(129, 'Kakumiro', 'West', 'Bunyoro'),
(130, 'Kazo', 'West', 'Ankole'),
(131, 'Kikuube', 'West', 'Bunyoro'),
(132, 'Kitagwenda', 'West', 'Bunyoro'),
(133, 'Rubanda', 'West', 'Kigezi'),
(134, 'Rukiga', 'West', 'Kigezi'),
(135, 'Rwampara', 'West', 'Ankole'),
(136, 'Bunyangabu', 'West', 'Rwenzori'),
(137, 'Kampala', 'Central', 'Buganda'),
(138, 'Fort Portal', 'Western', 'Rwenzori'),
(139, 'Hoima', 'Western', 'Bunyoro'),
(140, 'Arua', 'Northern', 'West Nile'),
(141, 'Mbarara', 'Western', 'Ankole'),
(142, 'Jinja', 'Eastern', 'Busoga'),
(143, 'Mbale', 'Eastern', 'Bugisu'),
(144, 'Gulu', 'Northern', 'Acholi'),
(145, 'Masaka', 'Central', 'Buganda'),
(146, 'Gulu', 'Northern', 'Acholi');

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `call_taker` varchar(255) NOT NULL,
  `cif_no` varchar(255) NOT NULL,
  `person_reporting` varchar(255) NOT NULL,
  `village` varchar(255) NOT NULL,
  `sub_county` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `source_of_alert` varchar(255) NOT NULL,
  `alert_case_name` varchar(255) NOT NULL,
  `alert_case_age` int(11) NOT NULL,
  `alert_case_sex` varchar(50) NOT NULL,
  `alert_case_pregnant_duration` int(11) DEFAULT NULL,
  `alert_case_village` varchar(255) NOT NULL,
  `alert_case_parish` varchar(255) NOT NULL,
  `alert_case_sub_county` varchar(255) NOT NULL,
  `alert_case_district` varchar(255) NOT NULL,
  `alert_case_nationality` varchar(255) NOT NULL,
  `point_of_contact_name` varchar(255) NOT NULL,
  `point_of_contact_relationship` varchar(255) NOT NULL,
  `point_of_contact_phone` varchar(255) NOT NULL,
  `history` text DEFAULT NULL,
  `health_facility_visit` varchar(255) NOT NULL,
  `traditional_healer_visit` varchar(255) NOT NULL,
  `symptoms` text DEFAULT NULL,
  `actions` text NOT NULL,
  `case_verification_desk` text DEFAULT NULL,
  `field_verification` text DEFAULT NULL,
  `field_verification_decision` text DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `lab_result` varchar(10) DEFAULT NULL,
  `lab_result_date` date DEFAULT NULL,
  `is_highlighted` tinyint(1) DEFAULT 0,
  `assigned_to` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`id`, `status`, `date`, `time`, `call_taker`, `cif_no`, `person_reporting`, `village`, `sub_county`, `contact_number`, `source_of_alert`, `alert_case_name`, `alert_case_age`, `alert_case_sex`, `alert_case_pregnant_duration`, `alert_case_village`, `alert_case_parish`, `alert_case_sub_county`, `alert_case_district`, `alert_case_nationality`, `point_of_contact_name`, `point_of_contact_relationship`, `point_of_contact_phone`, `history`, `health_facility_visit`, `traditional_healer_visit`, `symptoms`, `actions`, `case_verification_desk`, `field_verification`, `field_verification_decision`, `feedback`, `lab_result`, `lab_result_date`, `is_highlighted`, `assigned_to`) VALUES
(1, 'Alive', '2025-01-31', '08:31:00', 'Waiswa', 'H90H', 'Waiswa', 'Waiswa', 'Subcounty', '0783229900', 'Health Facility', 'Waiswa', 13, 'Female', 0, 'qwwww', 'wqqwq', 'ewew', 'Kamuli', 'Uganda', 'KJksj', ',m,m,sm', '0786553222', 'Other mass gathering, Contact of suspect/probable/confirmed case', 'ljklska', 'kljlkkkkk', 'Headache, General Weakness, Rash', 'kjlkajda', 'Validated for EMS Evacuation, Safe Dignified Burial Team', '', '', '', 'Positive', '2025-01-31', 1, NULL),
(2, 'Alive', '2025-02-01', '06:55:00', 'Philip', 'DSDSD', 'nmnmn', 'mnnbnm', 'nbmnbm', '0766555444', 'Community', 'sasddjh', 15, 'Female', 0, 'kalyango', 'an,m,', ',mn,mn,', 'm,nmn', 'Uganda', 'Phil', 'Pjsnn', '0766643212', 'Other mass gathering, Contact of suspect/probable/confirmed case, Contact of sudden/unexplained death', 'hkjk', 'kjhkj', 'Fever, General Weakness, Rash', 'nmbmnbm', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gname` varchar(25) NOT NULL,
  `surname` varchar(25) NOT NULL,
  `oname` varchar(25) DEFAULT NULL,
  `email` varchar(25) NOT NULL,
  `affiliation` varchar(50) NOT NULL,
  `user_type` varchar(20) NOT NULL,
  `level` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `gname`, `surname`, `oname`, `email`, `affiliation`, `user_type`, `level`) VALUES
(1, 'pwaiswa', '$2y$10$p6/IIlQDOjKeb1CvxWq1YeEcrhIYFroODMA.EQ1TYDZo.y2BGdyPC', 'Philip', 'Waiswa', 'Alex', 'philipwaiswa@gmail.com', 'MoH', '', 'Admin'),
(3, 'kamuli', '$2y$10$/NfW3o0LVFaK6yRhT/s9Le2ZQSaj.pVspivD80zvMT9S2F2DU6XNq', 'Kamuli', 'Kamuli', 'Kamuli', 'kamuli@ebola.net', 'Kamuli', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_units`
--
ALTER TABLE `admin_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_units`
--
ALTER TABLE `admin_units`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
