-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2023 at 12:24 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_new_dash`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `real_password` varchar(255) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `status` enum('active','not-active') NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `real_password`, `group_name`, `status`, `image`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'main_admin', 'main_admin@yahoo.com', NULL, '$2y$10$ooL/GNe1upqO5b5uKBLUjOYK1rabgM1G8UBP1HC/RhWEBS3n1ZPlW', 'main_admin010', NULL, 'active', NULL, NULL, '2023-06-12 12:17:34', '2023-06-12 12:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
CREATE TABLE `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` text DEFAULT NULL,
  `num_id` int(11) DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `office_id` bigint(20) UNSIGNED NOT NULL,
  `type_unit_id` bigint(20) UNSIGNED NOT NULL,
  `emirate` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `address`, `num_id`, `partner_id`, `office_id`, `type_unit_id`, `emirate`, `city`, `district`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Owner/ Agent nots (not visible on front end)', 25, 1, 1, 11, 1, 2, 3, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `main_settings`
--

DROP TABLE IF EXISTS `main_settings`;
CREATE TABLE `main_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `type_setting` enum('furniture','category_unit','type_request','type_status','bank_name') DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `main_settings`
--

INSERT INTO `main_settings` (`id`, `name`, `type_setting`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '{\"en\":\"emaraty\",\"ar\":\"\\u0627\\u0645\\u0627\\u0631\\u062a\\u064a\"}', 'bank_name', NULL, '2023-05-26 17:49:33', '2023-05-26 17:49:33'),
(2, '{\"en\":\"avalible\",\"ar\":\"\\u0645\\u062a\\u0627\\u062d\\u0629\"}', 'type_status', NULL, '2023-06-05 18:17:14', '2023-06-05 18:17:14'),
(3, '{\"en\":\"busy\",\"ar\":\"\\u0645\\u0634\\u063a\\u0648\\u0644\\u0647\"}', 'type_status', NULL, '2023-06-05 18:17:32', '2023-06-05 18:17:32'),
(4, '{\"en\":\"renet\",\"ar\":\"\\u0627\\u0644\\u0627\\u064a\\u062c\\u0627\\u0631\"}', 'type_request', NULL, '2023-06-05 18:17:53', '2023-06-05 18:17:53'),
(5, '{\"en\":\"owend\",\"ar\":\"\\u0645\\u0644\\u0643\"}', 'type_request', NULL, '2023-06-05 18:18:08', '2023-06-05 18:18:08'),
(6, '{\"en\":\"Dining table\",\"ar\":\"\\u0637\\u0627\\u0648\\u0644\\u0629 \\u0637\\u0639\\u0627\\u0645\"}', 'furniture', NULL, '2023-06-05 18:18:36', '2023-06-05 18:18:36'),
(7, '{\"en\":\"Reception chair\",\"ar\":\"\\u0643\\u0631\\u0633\\u064a \\u0627\\u0633\\u062a\\u0642\\u0628\\u0627\\u0644\"}', 'furniture', NULL, '2023-06-05 18:19:12', '2023-06-05 18:19:12'),
(8, '{\"en\":\"tv\",\"ar\":\"\\u062a\\u0644\\u0641\\u0632\\u064a\\u0648\\u0646\"}', 'furniture', NULL, '2023-06-05 18:19:35', '2023-06-05 18:19:35'),
(9, '{\"en\":\"Unfurnished\",\"ar\":\"\\u063a\\u064a\\u0631 \\u0645\\u0641\\u0631\\u0648\\u0634\\u0629\"}', 'category_unit', NULL, '2023-06-05 18:20:13', '2023-06-05 18:29:55'),
(10, '{\"en\":\"furnished\",\"ar\":\"\\u0645\\u0641\\u0631\\u0648\\u0634\\u0629\"}', 'category_unit', NULL, '2023-06-05 18:20:30', '2023-06-05 18:30:14'),
(11, '{\"en\":\"Two floors apartment\",\"ar\":\"\\u0634\\u0642\\u0629 \\u062f\\u0648\\u0631\\u064a\\u0646\"}', 'category_unit', NULL, '2023-06-12 12:18:41', '2023-06-12 12:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_admins_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2023_03_10_223258_create_main_settings_table', 1),
(7, '2023_03_12_192428_create_type_units_table', 1),
(8, '2023_03_28_035835_create_poperty_statuses_table', 1),
(9, '2023_04_07_115405_create_partner_table', 1),
(10, '2023_04_07_115405_create_real_estate_offices_table', 1),
(11, '2023_04_10_044839_create_offices_table', 1),
(12, '2023_04_14_152716_create_permission_tables', 1),
(13, '2023_05_27_01_create_buildings_table', 1),
(14, '2023_05_27_02_create_unites_table', 1),
(15, '2023_05_27_03_create_unite_extra_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

DROP TABLE IF EXISTS `offices`;
CREATE TABLE `offices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `real_password` varchar(255) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `status` enum('active','not-active') NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text DEFAULT NULL,
  `num_id` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emirate` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `IBAN_num` varchar(255) DEFAULT NULL,
  `bank_id` bigint(20) UNSIGNED NOT NULL,
  `account_num` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `num_id`, `phone`, `email`, `address`, `emirate`, `city`, `district`, `deleted_at`, `IBAN_num`, `bank_id`, `account_num`, `created_at`, `updated_at`) VALUES
(1, '{\"en\":\"omnia\",\"ar\":\"omnia\"}', 5555, '102365478', 'omnia.ali36036@yahoo.com', '{\"en\":\"the address ( English)\",\"ar\":\"the address ( Arabic)\"}', 131, 14, 25, NULL, '5554', 1, '585858', '2023-06-12 13:11:17', '2023-06-12 13:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'show_role', 'admin', '2023-06-12 12:17:31', '2023-06-12 12:17:31'),
(2, 'add_role', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(3, 'edit_role', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(4, 'delete_role', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(5, 'show_user', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(6, 'add_user', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(7, 'edit_user', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(8, 'delete_user', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(9, 'show_typeUnits', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(10, 'add_typeUnits', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(11, 'edit_typeUnits', 'admin', '2023-06-12 12:17:32', '2023-06-12 12:17:32'),
(12, 'delete_typeUnits', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(13, 'show_poperty_statuses', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(14, 'add_poperty_statuses', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(15, 'edit_poperty_statuses', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(16, 'delete_poperty_statuses', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(17, 'show_main_settings', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(18, 'add_main_settings', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(19, 'edit_main_settings', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(20, 'delete_main_settings', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(21, 'real_office', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(22, 'partner', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(23, 'creat', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(24, 'show', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(25, 'delete', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(26, 'edite', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(27, 'create_account', 'admin', '2023-06-12 12:17:33', '2023-06-12 12:17:33'),
(28, 'print', 'admin', '2023-06-12 12:17:34', '2023-06-12 12:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `poperty_statuses`
--

DROP TABLE IF EXISTS `poperty_statuses`;
CREATE TABLE `poperty_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_offices`
--

DROP TABLE IF EXISTS `real_estate_offices`;
CREATE TABLE `real_estate_offices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text DEFAULT NULL,
  `office_num` int(11) DEFAULT NULL,
  `num_tax` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emirate` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `real_estate_offices`
--

INSERT INTO `real_estate_offices` (`id`, `name`, `office_num`, `num_tax`, `phone`, `email`, `address`, `emirate`, `city`, `district`, `created_at`, `updated_at`) VALUES
(1, '{\"en\":\"\\u0633\\u0644\\u0637\\u0627\\u0646 \\u0645\\u062d\\u0645\\u062f \\u0633\\u0644\\u064a\\u0645\\u0627\\u0646 \\u0627\\u0644\\u062c\\u0627\\u0633\\u0631\",\"ar\":\"\\u0633\\u0644\\u0637\\u0627\\u0646 \\u0645\\u062d\\u0645\\u062f \\u0633\\u0644\\u064a\\u0645\\u0627\\u0646 \\u0627\\u0644\\u062c\\u0627\\u0633\\u0631\"}', 11111111, 2225222, '12365478952', 'omnia.abdallah36036@gmail.com', '{\"en\":\"the address ( English)\",\"ar\":\"the address ( Arabic)\"}', 130, 15, 35, '2023-06-12 13:12:01', '2023-06-12 13:12:01');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', '2023-06-12 12:17:34', '2023-06-12 12:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_city`
--

DROP TABLE IF EXISTS `tbl_city`;
CREATE TABLE `tbl_city` (
  `id` int(11) NOT NULL,
  `city_ar` varchar(100) NOT NULL,
  `city_en` varchar(100) DEFAULT NULL,
  `emara_id_fk` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_city`
--

INSERT INTO `tbl_city` (`id`, `city_ar`, `city_en`, `emara_id_fk`, `is_deleted`) VALUES
(13, 'الشارقة', 'Sharjah', 134, 0),
(14, 'أبو ظبي', 'Abu Dhabi', 131, 0),
(15, 'دبي', 'Dubai', 130, 0),
(16, 'عجمان', 'Ajman', 135, 0),
(17, 'رأس الخيمة', 'Ras Al-Khaimah', 136, 0),
(18, 'أم القيوين', 'Umm Al Quwain', 137, 0),
(19, 'الفجيرة', 'Fujairah', 138, 0),
(20, 'مصدر', 'Masdar', 131, 0),
(21, 'اختبار المدينه', 'CityTest', 139, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_emaraa`
--

DROP TABLE IF EXISTS `tbl_emaraa`;
CREATE TABLE `tbl_emaraa` (
  `id` int(11) NOT NULL,
  `emaraa_ar` varchar(150) DEFAULT NULL,
  `emaraa_en` varchar(100) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_emaraa`
--

INSERT INTO `tbl_emaraa` (`id`, `emaraa_ar`, `emaraa_en`, `is_deleted`) VALUES
(130, 'دبي', 'Dubai', 0),
(131, 'أبو ظبي', 'Abu Dhabi', 0),
(133, 'سبيب', 'يبيسبيسب', 1),
(134, 'الشارقة', 'Sharjah', 0),
(135, 'عجمان', 'Ajman', 0),
(136, 'رأس الخيمة', 'Ras Al-Khaimah', 0),
(137, 'أم القيوين', 'Umm Al Quwain', 0),
(138, 'الفجيرة', 'Fujairah', 0),
(139, 'اختبار المدن', 'Emara Test', 0),
(140, 'العين ', 'Al Ain ', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_quarter`
--

DROP TABLE IF EXISTS `tbl_quarter`;
CREATE TABLE `tbl_quarter` (
  `id` int(11) NOT NULL,
  `quarter_ar` varchar(100) NOT NULL,
  `quarter_en` varchar(100) DEFAULT NULL,
  `emara_id_fk` int(11) NOT NULL,
  `city_id_fk` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_quarter`
--

INSERT INTO `tbl_quarter` (`id`, `quarter_ar`, `quarter_en`, `emara_id_fk`, `city_id_fk`, `is_deleted`) VALUES
(1, 'الراس', 'Al Raas', 137, 18, 0),
(2, 'ملاح', 'Mallah', 137, 18, 0),
(3, 'السلمة ', 'Al Salama ', 137, 18, 0),
(4, 'السلمة-السويحات ', 'Al Salama-Al Suwaihat ', 137, 18, 0),
(5, 'أم الثعوب ', 'Umm Al Thoub ', 137, 18, 0),
(6, 'ام القيوين القديمة ', 'Old Umm Al Quwain ', 137, 18, 0),
(7, 'المدر ', 'Al Madar ', 137, 18, 0),
(8, 'ام درع 1', 'Umm Deraa 1', 137, 18, 0),
(9, 'ام درع 2 ', 'Umm Deraa 2', 137, 18, 0),
(10, 'فلج المعلا ', 'Falaj Al Mualla ', 137, 18, 0),
(11, 'ام الدرع ', 'Umm Al Deraa ', 137, 18, 0),
(12, 'السلمة- مدينة الشهداء ', 'Madinath Al Shuhada ', 137, 18, 0),
(13, 'السرة- كابر ', 'Al Surah, Kaber ', 137, 18, 0),
(14, 'النيفة ', 'Al Neefa ', 137, 18, 0),
(15, 'مدينة خليفة 1', 'Khalifa City-1', 137, 18, 0),
(16, 'مدينة خليفة 2', 'Khalifa City-2', 137, 18, 0),
(17, 'محمد بن راشد ', 'Moh\'d Bin Rashid ', 137, 18, 0),
(18, 'محمد بن راشد-1 ', 'Moh\'d Bin Rashid-1', 137, 18, 0),
(19, 'محمد بن راشد-2', 'Moh\'d Bin Rashid-2', 137, 18, 0),
(20, 'الحديثة ', 'Al Haditha ', 137, 18, 0),
(21, 'الرقة ', 'Al Regah ', 137, 18, 0),
(22, 'الروضة-2', 'Al Rawdah-2 ', 137, 18, 0),
(23, 'الروضة-2', 'Al Rawdah-2 ', 137, 18, 1),
(24, 'المدر-3', 'Al Madar-3', 137, 18, 0),
(25, 'العين ', 'Al Ain ', 131, 14, 0),
(26, 'النخيلة ', 'Al Nekheila', 137, 18, 0),
(27, 'القراين-1', 'Al Qraun 1', 137, 18, 0),
(28, 'القراين-2', 'Al Qraun 2', 137, 18, 0),
(29, 'شنتير ', 'Shanteer', 137, 18, 0),
(30, 'الغافات ', 'Al Gavat', 137, 18, 0),
(31, 'الجزيره الحمرا ', 'Al Jazirah Al Hamrh  ', 136, 17, 0),
(32, 'الحزام الاخضر ', 'Green Belt ', 137, 18, 0),
(33, 'لعطين-1 ', 'Lateen 1', 137, 18, 0),
(34, 'لعطين 2', 'Lateen 2', 137, 18, 0),
(35, 'القصيص ', 'Al Qusais ', 130, 15, 0),
(36, 'المقطع ', 'Al Maqta\'a ', 137, 18, 0),
(37, 'المقطع  2', 'Al Maqta\'a 2', 137, 18, 0),
(38, 'المقطع 1 ', 'Al Maqta\'a  1 ', 137, 18, 0),
(39, 'الراس 1 ', 'Al Rass 1', 137, 18, 0),
(40, 'الراس 2 ', 'Al Rass 2', 137, 18, 0),
(41, 'البطين ', 'Al Butain ', 137, 18, 0),
(42, 'الراشدية 1 ', 'Al Rashidiya 1', 135, 16, 0),
(43, 'الهبوب 2', 'AL Hiboob 2', 137, 18, 0);

-- --------------------------------------------------------

--
-- Table structure for table `type_units`
--

DROP TABLE IF EXISTS `type_units`;
CREATE TABLE `type_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `description` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `type_units`
--

INSERT INTO `type_units` (`id`, `name`, `description`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, '{\"en\":\"room\",\"ar\":\"\\u0648\\u062d\\u062f\\u0629 \\u0648\\u0627\\u062d\\u062f\"}', '{\"en\":\"room\",\"ar\":\"room\"}', NULL, '2023-06-12 15:55:07', '2023-06-12 15:55:07');

-- --------------------------------------------------------

--
-- Table structure for table `unites`
--

DROP TABLE IF EXISTS `unites`;
CREATE TABLE `unites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num_id` int(11) DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `office_id` bigint(20) UNSIGNED NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `name` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price_day` double(8,2) NOT NULL DEFAULT 0.00,
  `price_month` double(8,2) NOT NULL DEFAULT 0.00,
  `price_year` double(8,2) NOT NULL DEFAULT 0.00,
  `size` double(8,2) NOT NULL DEFAULT 0.00,
  `bedrooms` int(11) NOT NULL DEFAULT 0,
  `rooms` int(11) NOT NULL DEFAULT 0,
  `year_built` int(11) NOT NULL DEFAULT 0,
  `floors_no` int(11) NOT NULL DEFAULT 0,
  `available_from` varchar(25) DEFAULT NULL,
  `extra_details` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emirate` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `district` int(11) DEFAULT NULL,
  `category_unit_id` bigint(20) UNSIGNED NOT NULL,
  `type_unit_id` bigint(20) UNSIGNED NOT NULL,
  `type_status_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `unites`
--

INSERT INTO `unites` (`id`, `num_id`, `partner_id`, `office_id`, `building_id`, `name`, `description`, `price_day`, `price_month`, `price_year`, `size`, `bedrooms`, `rooms`, `year_built`, `floors_no`, `available_from`, `extra_details`, `address`, `emirate`, `city`, `district`, `category_unit_id`, `type_unit_id`, `type_status_id`, `created_at`, `updated_at`) VALUES
(1, 5555, 1, 1, 1, '{\"en\":\"Duplex apartment2\",\"ar\":\"\\u0634\\u0642\\u0629 \\u062f\\u0648\\u0628\\u0644\\u0643\\u0633\"}', '{\"en\":\"Duplex apartment\",\"ar\":\"\\u0634\\u0642\\u0629 \\u062f\\u0648\\u0628\\u0644\\u0643\\u0633\"}', 20.00, 20.00, 20.00, 20.00, 2, 2, 2000, 2, '2023-06-01', 'Extra details', '{\"en\":\"\\u0634\\u0642\\u0629 \\u062f\\u0648\\u0628\\u0644\\u0643\\u0633\",\"ar\":\"\\u0634\\u0642\\u0629 \\u062f\\u0648\\u0628\\u0644\\u0643\\u0633\"}', 130, 15, 35, 9, 1, 2, '2023-06-14 14:21:46', '2023-06-23 23:02:07');

-- --------------------------------------------------------

--
-- Table structure for table `unite_extra`
--

DROP TABLE IF EXISTS `unite_extra`;
CREATE TABLE `unite_extra` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unite_id` int(11) DEFAULT NULL,
  `extra_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unite_extra`
--

INSERT INTO `unite_extra` (`id`, `unite_id`, `extra_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, NULL, NULL),
(2, 1, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `real_password` varchar(255) DEFAULT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buildings_num_id_unique` (`num_id`),
  ADD KEY `buildings_partner_id_foreign` (`partner_id`),
  ADD KEY `buildings_office_id_foreign` (`office_id`),
  ADD KEY `buildings_type_unit_id_foreign` (`type_unit_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `main_settings`
--
ALTER TABLE `main_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `offices_email_unique` (`email`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partners_num_id_unique` (`num_id`),
  ADD UNIQUE KEY `partners_phone_unique` (`phone`),
  ADD UNIQUE KEY `partners_email_unique` (`email`),
  ADD UNIQUE KEY `partners_iban_num_unique` (`IBAN_num`),
  ADD UNIQUE KEY `partners_account_num_unique` (`account_num`),
  ADD KEY `partners_bank_id_foreign` (`bank_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `poperty_statuses`
--
ALTER TABLE `poperty_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `real_estate_offices`
--
ALTER TABLE `real_estate_offices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `real_estate_offices_office_num_unique` (`office_num`),
  ADD UNIQUE KEY `real_estate_offices_num_tax_unique` (`num_tax`),
  ADD UNIQUE KEY `real_estate_offices_phone_unique` (`phone`),
  ADD UNIQUE KEY `real_estate_offices_email_unique` (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tbl_city`
--
ALTER TABLE `tbl_city`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_emaraa`
--
ALTER TABLE `tbl_emaraa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_quarter`
--
ALTER TABLE `tbl_quarter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_units`
--
ALTER TABLE `type_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unites`
--
ALTER TABLE `unites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unites_num_id_unique` (`num_id`),
  ADD UNIQUE KEY `unites_name_unique` (`name`) USING HASH,
  ADD KEY `unites_partner_id_foreign` (`partner_id`),
  ADD KEY `unites_office_id_foreign` (`office_id`),
  ADD KEY `unites_building_id_foreign` (`building_id`),
  ADD KEY `unites_category_unit_id_foreign` (`category_unit_id`),
  ADD KEY `unites_type_unit_id_foreign` (`type_unit_id`),
  ADD KEY `unites_type_status_id_foreign` (`type_status_id`);

--
-- Indexes for table `unite_extra`
--
ALTER TABLE `unite_extra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unite_extra_extra_id_foreign` (`extra_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `main_settings`
--
ALTER TABLE `main_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `poperty_statuses`
--
ALTER TABLE `poperty_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `real_estate_offices`
--
ALTER TABLE `real_estate_offices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_city`
--
ALTER TABLE `tbl_city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_emaraa`
--
ALTER TABLE `tbl_emaraa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `tbl_quarter`
--
ALTER TABLE `tbl_quarter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `type_units`
--
ALTER TABLE `type_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unites`
--
ALTER TABLE `unites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unite_extra`
--
ALTER TABLE `unite_extra`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices` (`id`),
  ADD CONSTRAINT `buildings_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`),
  ADD CONSTRAINT `buildings_type_unit_id_foreign` FOREIGN KEY (`type_unit_id`) REFERENCES `main_settings` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `main_settings` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unites`
--
ALTER TABLE `unites`
  ADD CONSTRAINT `unites_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`),
  ADD CONSTRAINT `unites_category_unit_id_foreign` FOREIGN KEY (`category_unit_id`) REFERENCES `main_settings` (`id`),
  ADD CONSTRAINT `unites_office_id_foreign` FOREIGN KEY (`office_id`) REFERENCES `real_estate_offices` (`id`),
  ADD CONSTRAINT `unites_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`),
  ADD CONSTRAINT `unites_type_status_id_foreign` FOREIGN KEY (`type_status_id`) REFERENCES `main_settings` (`id`),
  ADD CONSTRAINT `unites_type_unit_id_foreign` FOREIGN KEY (`type_unit_id`) REFERENCES `main_settings` (`id`);

--
-- Constraints for table `unite_extra`
--
ALTER TABLE `unite_extra`
  ADD CONSTRAINT `unite_extra_extra_id_foreign` FOREIGN KEY (`extra_id`) REFERENCES `main_settings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
