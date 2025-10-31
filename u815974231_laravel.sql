-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2025 at 04:01 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u815974231_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `achats`
--

CREATE TABLE `achats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('Création','Validation','Refus','Livraison','Réception') NOT NULL DEFAULT 'Création',
  `id_Fournisseur` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `achats`
--

INSERT INTO `achats` (`id`, `total`, `status`, `id_Fournisseur`, `id_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3000.00, 'Validation', 1, 1, '2025-10-27 09:26:41', '2025-10-27 09:26:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) NOT NULL,
  `auditable_type` varchar(255) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1023) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audits`
--

INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 1, '[]', '{\"name\":\"TVA 1\",\"value\":0,\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(2, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 2, '[]', '{\"name\":\"TVA 2\",\"value\":7,\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(3, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 3, '[]', '{\"name\":\"TVA 3\",\"value\":8,\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(4, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 4, '[]', '{\"name\":\"TVA 4\",\"value\":9,\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(5, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 5, '[]', '{\"name\":\"TVA  5\",\"value\":10,\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(6, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 6, '[]', '{\"name\":\"TVA  6\",\"value\":14,\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(7, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 7, '[]', '{\"name\":\"TVA 7\",\"value\":18,\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(8, 'App\\Models\\User', 1, 'created', 'App\\Models\\Tva', 8, '[]', '{\"name\":\"TVA 8\",\"value\":20,\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importTva', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:23', '2025-10-27 07:55:23'),
(9, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 1, '[]', '{\"name\":\"Kg\",\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(10, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 2, '[]', '{\"name\":\"Gramme\",\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(11, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 3, '[]', '{\"name\":\"L\",\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(12, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 4, '[]', '{\"name\":\"T\",\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(13, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 5, '[]', '{\"name\":\"M\\u00e8tre\",\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(14, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 6, '[]', '{\"name\":\"Unit\\u00e9\",\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(15, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 7, '[]', '{\"name\":\"Paquet\",\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(16, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 8, '[]', '{\"name\":\"Boite\",\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(17, 'App\\Models\\User', 1, 'created', 'App\\Models\\Unite', 9, '[]', '{\"name\":\"Bouteille\",\"iduser\":1,\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importUnite', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:55:47', '2025-10-27 07:55:47'),
(18, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 1, '[]', '{\"name\":\"RESERVE FOUNITURE SCOLAIRE\",\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(19, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 2, '[]', '{\"name\":\"CHAMBRE FROIDE  NEGATIVE\",\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(20, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 3, '[]', '{\"name\":\"CHAMBRE FROIDE POSITIVE\",\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(21, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 4, '[]', '{\"name\":\"ECONOMAT\",\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(22, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 5, '[]', '{\"name\":\"RESERVE  MOBILIER DE BUREAU\",\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(23, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 6, '[]', '{\"name\":\"RESERVE FOUNITURE DE BUREAU\",\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(24, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 7, '[]', '{\"name\":\"RESERVE FOUNITURE ELECTRIQUE\",\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(25, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 8, '[]', '{\"name\":\"RESERVE FOUNITURE INFORMATIQUE\",\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(26, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 9, '[]', '{\"name\":\"RESERVE MOBILIER DE CUISINE & RESTAURATION\",\"iduser\":1,\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(27, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 10, '[]', '{\"name\":\"RESERVE MOBILIER SCOLAIRE\",\"iduser\":1,\"id\":10}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(28, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 11, '[]', '{\"name\":\"RESERVE PAPITERIE\",\"iduser\":1,\"id\":11}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(29, 'App\\Models\\User', 1, 'created', 'App\\Models\\Local', 12, '[]', '{\"name\":\"RESERVE PRODUITS DE NETTOYAGE\",\"iduser\":1,\"id\":12}', 'https://red-guanaco-935735.hostingersite.com/importLocal', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:17', '2025-10-27 07:56:17'),
(30, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 1, '[]', '{\"name\":\"A\",\"id_local\":2,\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(31, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 2, '[]', '{\"name\":\"B\",\"id_local\":2,\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(32, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 3, '[]', '{\"name\":\"C\",\"id_local\":2,\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(33, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 4, '[]', '{\"name\":\"D\",\"id_local\":2,\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(34, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 5, '[]', '{\"name\":\"A\",\"id_local\":3,\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(35, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 6, '[]', '{\"name\":\"B\",\"id_local\":3,\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(36, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 7, '[]', '{\"name\":\"C\",\"id_local\":3,\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(37, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 8, '[]', '{\"name\":\"D\",\"id_local\":3,\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(38, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 9, '[]', '{\"name\":\"E\",\"id_local\":3,\"iduser\":1,\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(39, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 10, '[]', '{\"name\":\"F\",\"id_local\":3,\"iduser\":1,\"id\":10}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(40, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 11, '[]', '{\"name\":\"A\",\"id_local\":4,\"iduser\":1,\"id\":11}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(41, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 12, '[]', '{\"name\":\"B\",\"id_local\":4,\"iduser\":1,\"id\":12}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(42, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 13, '[]', '{\"name\":\"C\",\"id_local\":4,\"iduser\":1,\"id\":13}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(43, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 14, '[]', '{\"name\":\"D\",\"id_local\":4,\"iduser\":1,\"id\":14}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(44, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 15, '[]', '{\"name\":\"E\",\"id_local\":4,\"iduser\":1,\"id\":15}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(45, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 16, '[]', '{\"name\":\"F\",\"id_local\":4,\"iduser\":1,\"id\":16}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(46, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 17, '[]', '{\"name\":\"G\",\"id_local\":4,\"iduser\":1,\"id\":17}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(47, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 18, '[]', '{\"name\":\"A\",\"id_local\":5,\"iduser\":1,\"id\":18}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(48, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 19, '[]', '{\"name\":\"B\",\"id_local\":5,\"iduser\":1,\"id\":19}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(49, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 20, '[]', '{\"name\":\"C\",\"id_local\":5,\"iduser\":1,\"id\":20}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(50, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 21, '[]', '{\"name\":\"D\",\"id_local\":5,\"iduser\":1,\"id\":21}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(51, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 22, '[]', '{\"name\":\"A1\",\"id_local\":6,\"iduser\":1,\"id\":22}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(52, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 23, '[]', '{\"name\":\"A2\",\"id_local\":6,\"iduser\":1,\"id\":23}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(53, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 24, '[]', '{\"name\":\"B1\",\"id_local\":6,\"iduser\":1,\"id\":24}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(54, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 25, '[]', '{\"name\":\"B2\",\"id_local\":6,\"iduser\":1,\"id\":25}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(55, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 26, '[]', '{\"name\":\"B3\",\"id_local\":6,\"iduser\":1,\"id\":26}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(56, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 27, '[]', '{\"name\":\"C\",\"id_local\":7,\"iduser\":1,\"id\":27}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(57, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 28, '[]', '{\"name\":\"A1\",\"id_local\":8,\"iduser\":1,\"id\":28}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(58, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 29, '[]', '{\"name\":\"A2\",\"id_local\":8,\"iduser\":1,\"id\":29}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(59, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 30, '[]', '{\"name\":\"A1\",\"id_local\":1,\"iduser\":1,\"id\":30}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(60, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 31, '[]', '{\"name\":\"A2\",\"id_local\":1,\"iduser\":1,\"id\":31}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(61, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 32, '[]', '{\"name\":\"B1\",\"id_local\":1,\"iduser\":1,\"id\":32}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(62, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 33, '[]', '{\"name\":\"B2\",\"id_local\":1,\"iduser\":1,\"id\":33}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(63, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 34, '[]', '{\"name\":\"B3\",\"id_local\":1,\"iduser\":1,\"id\":34}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(64, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 35, '[]', '{\"name\":\"A\",\"id_local\":9,\"iduser\":1,\"id\":35}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(65, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 36, '[]', '{\"name\":\"B\",\"id_local\":9,\"iduser\":1,\"id\":36}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(66, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 37, '[]', '{\"name\":\"D\",\"id_local\":10,\"iduser\":1,\"id\":37}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(67, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 38, '[]', '{\"name\":\"A1\",\"id_local\":11,\"iduser\":1,\"id\":38}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(68, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 39, '[]', '{\"name\":\"A2\",\"id_local\":11,\"iduser\":1,\"id\":39}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(69, 'App\\Models\\User', 1, 'created', 'App\\Models\\Rayon', 40, '[]', '{\"name\":\"A\",\"id_local\":12,\"iduser\":1,\"id\":40}', 'https://red-guanaco-935735.hostingersite.com/importRayon', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:29', '2025-10-27 07:56:29'),
(70, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 1, '[]', '{\"name\":\"MATERIEL\",\"classe\":\"NON ALIMENTAIRE\",\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(71, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 2, '[]', '{\"name\":\"OUTILLAGE\",\"classe\":\"NON ALIMENTAIRE\",\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(72, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 3, '[]', '{\"name\":\"MOBILIER\",\"classe\":\"NON ALIMENTAIRE\",\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(73, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 4, '[]', '{\"name\":\"FOURNITURE\",\"classe\":\"NON ALIMENTAIRE\",\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(74, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 5, '[]', '{\"name\":\"EPICERIE & PRODUITS LAITIERS\",\"classe\":\"DENREES ALIMENTAIRES\",\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(75, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 6, '[]', '{\"name\":\"LEGUMES & FRUITS\",\"classe\":\"DENREES ALIMENTAIRES\",\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(76, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 7, '[]', '{\"name\":\"POISSON FRAIS\",\"classe\":\"DENREES ALIMENTAIRES\",\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(77, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 8, '[]', '{\"name\":\"VIANDES ABATS\",\"classe\":\"DENREES ALIMENTAIRES\",\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(78, 'App\\Models\\User', 1, 'created', 'App\\Models\\Category', 9, '[]', '{\"name\":\"VOLAILLES ET \\u0152UFS\",\"classe\":\"DENREES ALIMENTAIRES\",\"iduser\":1,\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:40', '2025-10-27 07:56:40'),
(79, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 1, '[]', '{\"name\":\"CUISINE\",\"id_categorie\":1,\"iduser\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(80, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 2, '[]', '{\"name\":\"RESTAURATION\",\"id_categorie\":1,\"iduser\":1,\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(81, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 3, '[]', '{\"name\":\"SCOLAIRE\",\"id_categorie\":1,\"iduser\":1,\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(82, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 4, '[]', '{\"name\":\"BUREAU\",\"id_categorie\":1,\"iduser\":1,\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(83, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 5, '[]', '{\"name\":\"INFORMATIQUE\",\"id_categorie\":1,\"iduser\":1,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(84, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 6, '[]', '{\"name\":\"AUDIOVISUEL\",\"id_categorie\":1,\"iduser\":1,\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(85, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 7, '[]', '{\"name\":\"JARDINAGE\",\"id_categorie\":1,\"iduser\":1,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(86, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 8, '[]', '{\"name\":\"CUISINE\",\"id_categorie\":2,\"iduser\":1,\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(87, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 9, '[]', '{\"name\":\"RESTAURATION\",\"id_categorie\":2,\"iduser\":1,\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(88, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 10, '[]', '{\"name\":\"PLOMBERIE ET QUINCAILLERIE\",\"id_categorie\":2,\"iduser\":1,\"id\":10}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(89, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 11, '[]', '{\"name\":\"BUREAU\",\"id_categorie\":3,\"iduser\":1,\"id\":11}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(90, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 12, '[]', '{\"name\":\"METALLIQUE\",\"id_categorie\":3,\"iduser\":1,\"id\":12}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(91, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 13, '[]', '{\"name\":\"SCOLAIRE\",\"id_categorie\":3,\"iduser\":1,\"id\":13}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(92, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 14, '[]', '{\"name\":\"RESTAURATION\",\"id_categorie\":3,\"iduser\":1,\"id\":14}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(93, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 15, '[]', '{\"name\":\"SCOLAIRE\",\"id_categorie\":4,\"iduser\":1,\"id\":15}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(94, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 16, '[]', '{\"name\":\"BUREAU\",\"id_categorie\":4,\"iduser\":1,\"id\":16}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(95, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 17, '[]', '{\"name\":\"INFORMATIQUE\",\"id_categorie\":4,\"iduser\":1,\"id\":17}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(96, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 18, '[]', '{\"name\":\"ELECTRIQUE\",\"id_categorie\":4,\"iduser\":1,\"id\":18}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(97, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 19, '[]', '{\"name\":\"PAPITERIE\",\"id_categorie\":4,\"iduser\":1,\"id\":19}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(98, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 20, '[]', '{\"name\":\"NETTOYAGE\",\"id_categorie\":4,\"iduser\":1,\"id\":20}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(99, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 21, '[]', '{\"name\":\"BOULANGERIE\",\"id_categorie\":5,\"iduser\":1,\"id\":21}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(100, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 22, '[]', '{\"name\":\"CONSERVES\",\"id_categorie\":5,\"iduser\":1,\"id\":22}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(101, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 23, '[]', '{\"name\":\"LEGUMES SECS (LEGUMINEUSES)\",\"id_categorie\":5,\"iduser\":1,\"id\":23}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(102, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 24, '[]', '{\"name\":\"PATTE & FARINEUSE\",\"id_categorie\":5,\"iduser\":1,\"id\":24}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(103, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 25, '[]', '{\"name\":\"EPICES MAROCAINES\",\"id_categorie\":5,\"iduser\":1,\"id\":25}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(104, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 26, '[]', '{\"name\":\"CONDIMENTS\",\"id_categorie\":5,\"iduser\":1,\"id\":26}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(105, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 27, '[]', '{\"name\":\"HERBES AROMATIQUES\",\"id_categorie\":5,\"iduser\":1,\"id\":27}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(106, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 28, '[]', '{\"name\":\"PRODUITS LAITIERS\",\"id_categorie\":5,\"iduser\":1,\"id\":28}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(107, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 29, '[]', '{\"name\":\"BOISSONS\",\"id_categorie\":5,\"iduser\":1,\"id\":29}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(108, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 30, '[]', '{\"name\":\"CHOCOLATERIES\",\"id_categorie\":5,\"iduser\":1,\"id\":30}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(109, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 31, '[]', '{\"name\":\"FRUITS SECS\",\"id_categorie\":5,\"iduser\":1,\"id\":31}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(110, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 32, '[]', '{\"name\":\"GLUCIDES\",\"id_categorie\":5,\"iduser\":1,\"id\":32}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(111, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 33, '[]', '{\"name\":\"MOLECULES\",\"id_categorie\":5,\"iduser\":1,\"id\":33}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(112, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 34, '[]', '{\"name\":\"HUILES\",\"id_categorie\":5,\"iduser\":1,\"id\":34}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(113, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 35, '[]', '{\"name\":\"LEGUMES\",\"id_categorie\":6,\"iduser\":1,\"id\":35}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(114, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 36, '[]', '{\"name\":\"FRUITS\",\"id_categorie\":6,\"iduser\":1,\"id\":36}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(115, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 37, '[]', '{\"name\":\"POISSON FRAIS\",\"id_categorie\":7,\"iduser\":1,\"id\":37}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(116, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 38, '[]', '{\"name\":\"VIANDES\",\"id_categorie\":8,\"iduser\":1,\"id\":38}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(117, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 39, '[]', '{\"name\":\"ABATS\",\"id_categorie\":8,\"iduser\":1,\"id\":39}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(118, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 40, '[]', '{\"name\":\"VOLAILLES\",\"id_categorie\":9,\"iduser\":1,\"id\":40}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(119, 'App\\Models\\User', 1, 'created', 'App\\Models\\SubCategory', 41, '[]', '{\"name\":\"\\u0152UFS\",\"id_categorie\":9,\"iduser\":1,\"id\":41}', 'https://red-guanaco-935735.hostingersite.com/importSubCategory', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:56:57', '2025-10-27 07:56:57'),
(120, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 1, '[]', '{\"name\":\"Carnets Bon de Sortie Administration FT 20,5*20,5 cm\",\"code_article\":\"FOUELC039\",\"price_achat\":291.67,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC039\",\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(121, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 2, '[]', '{\"name\":\"Carnets Bon de Sortie de Cuisine FT 21*20,5 cm\",\"code_article\":\"FOUELC040\",\"price_achat\":2.4,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC040\",\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(122, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 3, '[]', '{\"name\":\"Carnets  de Rapport de Le\\u00e7ons   FT 25 *30 cm\",\"code_article\":\"FOUELC041\",\"price_achat\":250,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC041\",\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(123, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 4, '[]', '{\"name\":\"Carnets Bon de Sortie de Cuisine FT 21*30,5 cm\",\"code_article\":\"FOUELC042\",\"price_achat\":233.34,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC042\",\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(124, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 5, '[]', '{\"name\":\"Cardex Feuilles de Consommations FT 32*24,5 CM\",\"code_article\":\"FOUELC043\",\"price_achat\":66.67,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC043\",\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(125, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 6, '[]', '{\"name\":\"Carnets Bon de Sortie 03 Exemplaires\",\"code_article\":\"FOUELC044\",\"price_achat\":156,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC044\",\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(126, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 7, '[]', '{\"name\":\"Carnets Bon de Sortie de Restaurant  FT 21*20,5 cm\",\"code_article\":\"FOUELC045\",\"price_achat\":39.17,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC045\",\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16');
INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(127, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 8, '[]', '{\"name\":\"Carnets Bon de Sortie de R\\u00e9ception   FT 26,5 *20,5 cm\",\"code_article\":\"FOUELC046\",\"price_achat\":36.71,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC046\",\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(128, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 9, '[]', '{\"name\":\"Carnets Bon de Sortie de Commande   FT 19,5 *13 cm\",\"code_article\":\"FOUELC047\",\"price_achat\":17,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC047\",\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(129, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 10, '[]', '{\"name\":\"Carnets  de Rapport de Le\\u00e7ons   FT 21,5 *31,5 cm\",\"code_article\":\"FOUELC048\",\"price_achat\":103,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC048\",\"id\":10}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(130, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 11, '[]', '{\"name\":\"Carnets  de Stages   FT12 *19,5 cm\",\"code_article\":\"FOUELC049\",\"price_achat\":850,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":38,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A1 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC049\",\"id\":11}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(131, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 12, '[]', '{\"name\":\"Carnets Feuilles de Consommations FT 48*32 CM\",\"code_article\":\"FOUELC053\",\"price_achat\":112.5,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC053\",\"id\":12}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(132, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 13, '[]', '{\"name\":\"Fiches de Renseignement FT 21*30 CM\",\"code_article\":\"FOUELC055\",\"price_achat\":1100,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC055\",\"id\":13}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(133, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 14, '[]', '{\"name\":\"Fiches Evaluations trimestrielle\",\"code_article\":\"FOUELC056\",\"price_achat\":970,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC056\",\"id\":14}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(134, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 15, '[]', '{\"name\":\"Feuilles d\'examen FT 44*31,5 cm\",\"code_article\":\"FOUELC057\",\"price_achat\":208.33,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC057\",\"id\":15}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(135, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 16, '[]', '{\"name\":\"Feuilles de Concours d\'acc\\u00e9s  FT 44*31,5 cm\",\"code_article\":\"FOUELC058\",\"price_achat\":870,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC058\",\"id\":16}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(136, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 17, '[]', '{\"name\":\"Livres D\\\"apprentissage m\\u00e9tier : Boulangerie p\\u00e2tissier\",\"code_article\":\"FOUELC059\",\"price_achat\":1380,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC059\",\"id\":17}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(137, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 18, '[]', '{\"name\":\"Livres D\\\"apprentissage m\\u00e9tier : Restaurant\",\"code_article\":\"FOUELC060\",\"price_achat\":760,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC060\",\"id\":18}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(138, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 19, '[]', '{\"name\":\"Livres D\\\"apprentissage m\\u00e9tier : Cuisine\",\"code_article\":\"FOUELC061\",\"price_achat\":1100,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC061\",\"id\":19}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(139, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 20, '[]', '{\"name\":\"Livres D\'engagement\",\"code_article\":\"FOUELC063\",\"price_achat\":650,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC063\",\"id\":20}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(140, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 21, '[]', '{\"name\":\"Registre de D\\u00e9pense\",\"code_article\":\"FOUELC064\",\"price_achat\":600,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC064\",\"id\":21}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(141, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 22, '[]', '{\"name\":\"Registre Comptable\",\"code_article\":\"FOUELC065\",\"price_achat\":600,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC065\",\"id\":22}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(142, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 23, '[]', '{\"name\":\"Registre de D\\u00e9part\",\"code_article\":\"FOUELC066\",\"price_achat\":600,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC066\",\"id\":23}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(143, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 24, '[]', '{\"name\":\"Registre d\'arriv\\u00e9e\",\"code_article\":\"FOUELC067\",\"price_achat\":650,\"id_categorie\":4,\"id_subcategorie\":19,\"id_local\":11,\"id_rayon\":39,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":null,\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"RESERVE PAPITERIE \\/ A2 \\/ FOURNITURE \\/ PAPITERIE \\/ FOUELC067\",\"id\":24}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(144, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 25, '[]', '{\"name\":\"Farine de Bl\\u00e9 Tendre\",\"code_article\":\"EPLBOU001\",\"price_achat\":13,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":13,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2028-06-06 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU001\",\"id\":25}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(145, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 26, '[]', '{\"name\":\"Farine de Bl\\u00e9 Dur\",\"code_article\":\"EPLBOU002\",\"price_achat\":13,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":13,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-01-07 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU002\",\"id\":26}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(146, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 27, '[]', '{\"name\":\"Far- de Bl\\u00e9 Tendre  pour P\\u00e4tisserie\",\"code_article\":\"EPLBOU003\",\"price_achat\":13,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":13,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-07 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU003\",\"id\":27}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(147, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 28, '[]', '{\"name\":\"Farine de Bl\\u00e9 Dur Son\",\"code_article\":\"EPLBOU004\",\"price_achat\":13,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":13,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-07 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU004\",\"id\":28}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(148, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 29, '[]', '{\"name\":\"Farine D\'orge\",\"code_article\":\"EPLBOU005\",\"price_achat\":20,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":20,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-07 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU005\",\"id\":29}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(149, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 30, '[]', '{\"name\":\"Semoule Mais boulangerie\",\"code_article\":\"EPLBOU006\",\"price_achat\":14,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":14,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU006\",\"id\":30}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(150, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 31, '[]', '{\"name\":\"Avoine\",\"code_article\":\"EPLBOU007\",\"price_achat\":14,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":14,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-07 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU007\",\"id\":31}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(151, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 32, '[]', '{\"name\":\"Levure Boulang\\u00e9re de 125 grs\",\"code_article\":\"EPLBOU008\",\"price_achat\":7,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":7,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-07 00:00:00\",\"id_tva\":1,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU008\",\"id\":32}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(152, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 33, '[]', '{\"name\":\"Levure Chimique\",\"code_article\":\"EPLBOU009\",\"price_achat\":7,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":7,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-07 00:00:00\",\"id_tva\":1,\"id_unite\":7,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU009\",\"id\":33}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:16', '2025-10-27 07:57:16'),
(153, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 34, '[]', '{\"name\":\"PAIN DE MI\",\"code_article\":\"EPLBOU010\",\"price_achat\":7,\"id_categorie\":5,\"id_subcategorie\":21,\"id_local\":4,\"id_rayon\":11,\"seuil\":7,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-07 00:00:00\",\"id_tva\":1,\"id_unite\":7,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ BOULANGERIE \\/ EPLBOU010\",\"id\":34}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(154, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 35, '[]', '{\"name\":\"Cornichons 4\\/4\",\"code_article\":\"EPLCON001\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON001\",\"id\":35}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(155, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 36, '[]', '{\"name\":\"Cornichons de 500 grs\",\"code_article\":\"EPLCON002\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON002\",\"id\":36}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(156, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 37, '[]', '{\"name\":\"Confiture 4\\/4\",\"code_article\":\"EPLCON003\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON003\",\"id\":37}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(157, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 38, '[]', '{\"name\":\"Mais Doux 4\\/4\",\"code_article\":\"EPLCON004\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-05 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON004\",\"id\":38}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(158, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 39, '[]', '{\"name\":\"Mais Doux de 500 grs\",\"code_article\":\"EPLCON005\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON005\",\"id\":39}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(159, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 40, '[]', '{\"name\":\"Olive Noir 4\\/4\",\"code_article\":\"EPLCON006\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":11,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ A \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON006\",\"id\":40}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(160, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 41, '[]', '{\"name\":\"Thon \\u00e0 L\'huile de 400 grs\",\"code_article\":\"EPLCON007\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON007\",\"id\":41}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(161, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 42, '[]', '{\"name\":\"Tomate Concentr\\u00e9e 4\\/4\",\"code_article\":\"EPLCON008\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON008\",\"id\":42}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(162, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 43, '[]', '{\"name\":\"Tomate Concentr\\u00e9e 1\\/6\",\"code_article\":\"EPLCON009\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON009\",\"id\":43}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(163, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 44, '[]', '{\"name\":\"Tomate Concentr\\u00e9e 1\\/8\",\"code_article\":\"EPLCON010\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-07-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON010\",\"id\":44}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(164, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 45, '[]', '{\"name\":\"Miel Ruche de 01 kg\",\"code_article\":\"EPLCON011\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-08-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON011\",\"id\":45}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(165, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 46, '[]', '{\"name\":\"Miel Pur de 850 grs\",\"code_article\":\"EPLCON012\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-09-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON012\",\"id\":46}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(166, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 47, '[]', '{\"name\":\"Ananas Rondelles 4\\/4\",\"code_article\":\"EPLCON013\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-10-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON013\",\"id\":47}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(167, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 48, '[]', '{\"name\":\"Petits Pois 4\\/4\",\"code_article\":\"EPLCON014\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-11-07 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON014\",\"id\":48}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(168, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 49, '[]', '{\"name\":\"Demi Poire 4\\/4\",\"code_article\":\"EPLCON015\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":22,\"id_local\":4,\"id_rayon\":12,\"seuil\":8,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-05 00:00:00\",\"id_tva\":8,\"id_unite\":8,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ CONSERVES \\/ EPLCON015\",\"id\":49}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(169, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 50, '[]', '{\"name\":\"Lentilles\",\"code_article\":\"EPLLSE001\",\"price_achat\":18,\"id_categorie\":5,\"id_subcategorie\":23,\"id_local\":4,\"id_rayon\":12,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ LEGUMES SECS (LEGUMINEUSES) \\/ EPLLSE001\",\"id\":50}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(170, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 51, '[]', '{\"name\":\"F\\u00e9ves S\\u00e9ches\",\"code_article\":\"EPLLSE002\",\"price_achat\":12,\"id_categorie\":5,\"id_subcategorie\":23,\"id_local\":4,\"id_rayon\":12,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ LEGUMES SECS (LEGUMINEUSES) \\/ EPLLSE002\",\"id\":51}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(171, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 52, '[]', '{\"name\":\"Haricot Blanc Secs\",\"code_article\":\"EPLLSE003\",\"price_achat\":15,\"id_categorie\":5,\"id_subcategorie\":23,\"id_local\":4,\"id_rayon\":12,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-05-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ LEGUMES SECS (LEGUMINEUSES) \\/ EPLLSE003\",\"id\":52}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(172, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 53, '[]', '{\"name\":\"Petits Pois Concass\\u00e9es\",\"code_article\":\"EPLLSE004\",\"price_achat\":22,\"id_categorie\":5,\"id_subcategorie\":23,\"id_local\":4,\"id_rayon\":12,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ LEGUMES SECS (LEGUMINEUSES) \\/ EPLLSE004\",\"id\":53}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(173, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 54, '[]', '{\"name\":\"Poischiches\",\"code_article\":\"EPLLSE005\",\"price_achat\":25,\"id_categorie\":5,\"id_subcategorie\":23,\"id_local\":4,\"id_rayon\":12,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ LEGUMES SECS (LEGUMINEUSES) \\/ EPLLSE005\",\"id\":54}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(174, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 55, '[]', '{\"name\":\"Riz Long Glac\\u00e9\",\"code_article\":\"EPLPAF001\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":12,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF001\",\"id\":55}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(175, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 56, '[]', '{\"name\":\"Coquillettes\",\"code_article\":\"EPLPAF002\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":12,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ B \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF002\",\"id\":56}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(176, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 57, '[]', '{\"name\":\"Nouilles\",\"code_article\":\"EPLPAF003\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF003\",\"id\":57}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(177, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 58, '[]', '{\"name\":\"Spaghettis\",\"code_article\":\"EPLPAF004\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF004\",\"id\":58}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(178, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 59, '[]', '{\"name\":\"Semoule de Couscous\",\"code_article\":\"EPLPAF005\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF005\",\"id\":59}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(179, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 60, '[]', '{\"name\":\"Torsade\",\"code_article\":\"EPLPAF006\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-01-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF006\",\"id\":60}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(180, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 61, '[]', '{\"name\":\"Semoule Fine\",\"code_article\":\"EPLPAF007\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF007\",\"id\":61}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(181, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 62, '[]', '{\"name\":\"Semoule Grosse\",\"code_article\":\"EPLPAF008\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF008\",\"id\":62}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(182, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 63, '[]', '{\"name\":\"Semoule Fino\",\"code_article\":\"EPLPAF009\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF009\",\"id\":63}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(183, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 64, '[]', '{\"name\":\"Belboula GF\",\"code_article\":\"EPLPAF010\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF010\",\"id\":64}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(184, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 65, '[]', '{\"name\":\"Belboula MF\",\"code_article\":\"EPLPAF011\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-05 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF011\",\"id\":65}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(185, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 66, '[]', '{\"name\":\"Vermicelle de Chine\",\"code_article\":\"EPLPAF012\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-07-05 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF012\",\"id\":66}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(186, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 67, '[]', '{\"name\":\"Vermicelle Cheveux D\'enge\",\"code_article\":\"EPLPAF013\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-01-06 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF013\",\"id\":67}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(187, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 68, '[]', '{\"name\":\"Poudre de Riz\",\"code_article\":\"EPLPAF014\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-02-06 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF014\",\"id\":68}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(188, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 69, '[]', '{\"name\":\"Maizena de 180 grs\",\"code_article\":\"EPLPAF015\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-03-06 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF015\",\"id\":69}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(189, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 70, '[]', '{\"name\":\"Poudre Cr\\u00e8me\",\"code_article\":\"EPLPAF016\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-04-06 00:00:00\",\"id_tva\":1,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF016\",\"id\":70}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(190, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 71, '[]', '{\"name\":\"BLE EBLY DE 500 GR\",\"code_article\":\"EPLPAF017\",\"price_achat\":30,\"id_categorie\":5,\"id_subcategorie\":24,\"id_local\":4,\"id_rayon\":13,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-05-06 00:00:00\",\"id_tva\":5,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"ECONOMAT \\/ C \\/ EPICERIE & PRODUITS LAITIERS \\/ PATTE & FARINEUSE \\/ EPLPAF017\",\"id\":71}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(191, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 72, '[]', '{\"name\":\"Artichauts Frais\",\"code_article\":\"LEFLEG001\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-09-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG001\",\"id\":72}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(192, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 73, '[]', '{\"name\":\"Aubergines\",\"code_article\":\"LEFLEG002\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG002\",\"id\":73}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(193, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 74, '[]', '{\"name\":\"Betteraves\",\"code_article\":\"LEFLEG003\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-11-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG003\",\"id\":74}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(194, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 75, '[]', '{\"name\":\"Carottes\",\"code_article\":\"LEFLEG004\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-12-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG004\",\"id\":75}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(195, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 76, '[]', '{\"name\":\"Choux Verts\",\"code_article\":\"LEFLEG005\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-01-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG005\",\"id\":76}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17');
INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(196, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 77, '[]', '{\"name\":\"CHAMPIGNONS FRAIS\",\"code_article\":\"LEFLEG006\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG006\",\"id\":77}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(197, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 78, '[]', '{\"name\":\"Choux Fleurs\",\"code_article\":\"LEFLEG007\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG007\",\"id\":78}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(198, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 79, '[]', '{\"name\":\"Courgettes\",\"code_article\":\"LEFLEG008\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG008\",\"id\":79}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(199, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 80, '[]', '{\"name\":\"Citrons Frais\",\"code_article\":\"LEFLEG009\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-05-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG009\",\"id\":80}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(200, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 81, '[]', '{\"name\":\"Citrons Confits\",\"code_article\":\"LEFLEG010\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG010\",\"id\":81}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(201, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 82, '[]', '{\"name\":\"Coings\",\"code_article\":\"LEFLEG011\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG011\",\"id\":82}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(202, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 83, '[]', '{\"name\":\"Concombres\",\"code_article\":\"LEFLEG012\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG012\",\"id\":83}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(203, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 84, '[]', '{\"name\":\"Coriandre\",\"code_article\":\"LEFLEG013\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG013\",\"id\":84}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(204, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 85, '[]', '{\"name\":\"Courge Rouge\",\"code_article\":\"LEFLEG014\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG014\",\"id\":85}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(205, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 86, '[]', '{\"name\":\"Epinards\",\"code_article\":\"LEFLEG015\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG015\",\"id\":86}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(206, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 87, '[]', '{\"name\":\"ECHALOTTE\",\"code_article\":\"LEFLEG016\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG016\",\"id\":87}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(207, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 88, '[]', '{\"name\":\"MAUVE\",\"code_article\":\"LEFLEG017\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-01-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG017\",\"id\":88}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(208, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 89, '[]', '{\"name\":\"PETITE OIGNON\",\"code_article\":\"LEFLEG018\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":7,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ C \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG018\",\"id\":89}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(209, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 90, '[]', '{\"name\":\"POURPIER\",\"code_article\":\"LEFLEG019\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG019\",\"id\":90}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(210, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 91, '[]', '{\"name\":\"Fenouils\",\"code_article\":\"LEFLEG020\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG020\",\"id\":91}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(211, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 92, '[]', '{\"name\":\"F\\u00e9ves Fraiches\",\"code_article\":\"LEFLEG021\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG021\",\"id\":92}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(212, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 93, '[]', '{\"name\":\"Haricots Verts\",\"code_article\":\"LEFLEG022\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-09 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG022\",\"id\":93}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(213, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 94, '[]', '{\"name\":\"Laitues\",\"code_article\":\"LEFLEG023\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-01-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG023\",\"id\":94}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(214, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 95, '[]', '{\"name\":\"Laitues Fris\\u00e9es\",\"code_article\":\"LEFLEG024\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-02-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG024\",\"id\":95}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(215, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 96, '[]', '{\"name\":\"Navets Sas Branche\",\"code_article\":\"LEFLEG025\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-03-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG025\",\"id\":96}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(216, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 97, '[]', '{\"name\":\"Navets salsif\\u00e9\",\"code_article\":\"LEFLEG026\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-04-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG026\",\"id\":97}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(217, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 98, '[]', '{\"name\":\"Oignons Sans Branches\",\"code_article\":\"LEFLEG027\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-05-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG027\",\"id\":98}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(218, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 99, '[]', '{\"name\":\"Oignons avec Branches\",\"code_article\":\"LEFLEG028\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-06-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG028\",\"id\":99}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(219, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 100, '[]', '{\"name\":\"Olives Confites Verte\",\"code_article\":\"LEFLEG029\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-07-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG029\",\"id\":100}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(220, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 101, '[]', '{\"name\":\"Olives Meslalla\",\"code_article\":\"LEFLEG030\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-08-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG030\",\"id\":101}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(221, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 102, '[]', '{\"name\":\"Persil\",\"code_article\":\"LEFLEG031\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-09-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG031\",\"id\":102}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(222, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 103, '[]', '{\"name\":\"Petits Pois Frais\",\"code_article\":\"LEFLEG032\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG032\",\"id\":103}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(223, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 104, '[]', '{\"name\":\"Poireaux\",\"code_article\":\"LEFLEG033\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-11-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG033\",\"id\":104}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(224, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 105, '[]', '{\"name\":\"Poivrons Verts\",\"code_article\":\"LEFLEG034\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-12-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG034\",\"id\":105}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(225, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 106, '[]', '{\"name\":\"Poivrons Rouge\",\"code_article\":\"LEFLEG035\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-01-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG035\",\"id\":106}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(226, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 107, '[]', '{\"name\":\"Poivrons Jaune\",\"code_article\":\"LEFLEG036\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG036\",\"id\":107}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(227, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 108, '[]', '{\"name\":\"Pomme de Terre\",\"code_article\":\"LEFLEG037\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG037\",\"id\":108}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(228, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 109, '[]', '{\"name\":\"Radis\",\"code_article\":\"LEFLEG038\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG038\",\"id\":109}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(229, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 110, '[]', '{\"name\":\"Tomates Fraiches\",\"code_article\":\"LEFLEG039\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-05-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG039\",\"id\":110}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(230, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 111, '[]', '{\"name\":\"Tomates Cerises\",\"code_article\":\"LEFLEG040\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG040\",\"id\":111}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(231, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 112, '[]', '{\"name\":\"TOPINAMBOUR\",\"code_article\":\"LEFLEG041\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG041\",\"id\":112}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(232, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 113, '[]', '{\"name\":\"PATATE DOUCE\",\"code_article\":\"LEFLEG042\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":35,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ LEGUMES \\/ LEFLEG042\",\"id\":113}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(233, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 114, '[]', '{\"name\":\"Ananas Frais\",\"code_article\":\"LEFFRU001\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU001\",\"id\":114}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(234, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 115, '[]', '{\"name\":\"Bananes\",\"code_article\":\"LEFFRU002\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU002\",\"id\":115}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(235, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 116, '[]', '{\"name\":\"Cl\\u00e9mentines\",\"code_article\":\"LEFFRU003\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU003\",\"id\":116}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(236, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 117, '[]', '{\"name\":\"Fraises\",\"code_article\":\"LEFFRU004\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU004\",\"id\":117}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(237, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 118, '[]', '{\"name\":\"Grenadines\",\"code_article\":\"LEFFRU005\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-01-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU005\",\"id\":118}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(238, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 119, '[]', '{\"name\":\"Kiwi\",\"code_article\":\"LEFFRU006\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":9,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ E \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU006\",\"id\":119}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(239, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 120, '[]', '{\"name\":\"Oranges de Table\",\"code_article\":\"LEFFRU007\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU007\",\"id\":120}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(240, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 121, '[]', '{\"name\":\"Oranges \\u00e0 presser\",\"code_article\":\"LEFFRU008\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU008\",\"id\":121}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(241, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 122, '[]', '{\"name\":\"Poires\",\"code_article\":\"LEFFRU009\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU009\",\"id\":122}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(242, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 123, '[]', '{\"name\":\"Pomme Fruits\",\"code_article\":\"LEFFRU010\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU010\",\"id\":123}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(243, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 124, '[]', '{\"name\":\"Prunes\",\"code_article\":\"LEFFRU011\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-07-10 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU011\",\"id\":124}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(244, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 125, '[]', '{\"name\":\"Raisins Frais\",\"code_article\":\"LEFFRU012\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-01-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU012\",\"id\":125}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(245, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 126, '[]', '{\"name\":\"Abricots Frais\",\"code_article\":\"LEFFRU013\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-02-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU013\",\"id\":126}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(246, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 127, '[]', '{\"name\":\"Anones Frais\",\"code_article\":\"LEFFRU014\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-03-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU014\",\"id\":127}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(247, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 128, '[]', '{\"name\":\"past\\u00e9que\",\"code_article\":\"LEFFRU015\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-04-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU015\",\"id\":128}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(248, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 129, '[]', '{\"name\":\"melon\",\"code_article\":\"LEFFRU016\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-05-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU016\",\"id\":129}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(249, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 130, '[]', '{\"name\":\"cantaloupe\",\"code_article\":\"LEFFRU017\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-06-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU017\",\"id\":130}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(250, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 131, '[]', '{\"name\":\"Papaye\",\"code_article\":\"LEFFRU018\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-07-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU018\",\"id\":131}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(251, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 132, '[]', '{\"name\":\"Kaki\",\"code_article\":\"LEFFRU019\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-08-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU019\",\"id\":132}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(252, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 133, '[]', '{\"name\":\"Framboise\",\"code_article\":\"LEFFRU020\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-09-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU020\",\"id\":133}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(253, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 134, '[]', '{\"name\":\"Cerise\",\"code_article\":\"LEFFRU021\",\"price_achat\":15,\"id_categorie\":6,\"id_subcategorie\":36,\"id_local\":3,\"id_rayon\":10,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ F \\/ LEGUMES & FRUITS \\/ FRUITS \\/ LEFFRU021\",\"id\":134}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(254, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 135, '[]', '{\"name\":\"Calamar Frais\",\"code_article\":\"PAFPAF001\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-11-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF001\",\"id\":135}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(255, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 136, '[]', '{\"name\":\"Crevettes Frais\",\"code_article\":\"PAFPAF002\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-12-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF002\",\"id\":136}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(256, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 137, '[]', '{\"name\":\"Crevettes Royal\",\"code_article\":\"PAFPAF003\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-01-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF003\",\"id\":137}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(257, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 138, '[]', '{\"name\":\"Merlan Frais\",\"code_article\":\"PAFPAF004\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF004\",\"id\":138}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(258, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 139, '[]', '{\"name\":\"Sardines Frais\",\"code_article\":\"PAFPAF005\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF005\",\"id\":139}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(259, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 140, '[]', '{\"name\":\"Congres Frais\",\"code_article\":\"PAFPAF006\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF006\",\"id\":140}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(260, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 141, '[]', '{\"name\":\"Ombrines Frais\",\"code_article\":\"PAFPAF007\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-05-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF007\",\"id\":141}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(261, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 142, '[]', '{\"name\":\"Pageots Frais\",\"code_article\":\"PAFPAF008\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF008\",\"id\":142}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(262, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 143, '[]', '{\"name\":\"Soles Ration Frais\",\"code_article\":\"PAFPAF009\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF009\",\"id\":143}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(263, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 144, '[]', '{\"name\":\"Espadon Frais\",\"code_article\":\"PAFPAF010\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF010\",\"id\":144}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(264, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 145, '[]', '{\"name\":\"poulpe\",\"code_article\":\"PAFPAF011\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":1,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ A \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF011\",\"id\":145}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(265, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 146, '[]', '{\"name\":\"la seiche\",\"code_article\":\"PAFPAF012\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF012\",\"id\":146}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17');
INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(266, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 147, '[]', '{\"name\":\"la pieuvre\",\"code_article\":\"PAFPAF013\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF013\",\"id\":147}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(267, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 148, '[]', '{\"name\":\"la raie\",\"code_article\":\"PAFPAF014\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF014\",\"id\":148}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(268, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 149, '[]', '{\"name\":\"les homards\",\"code_article\":\"PAFPAF015\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-01-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF015\",\"id\":149}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(269, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 150, '[]', '{\"name\":\"Langouste\",\"code_article\":\"PAFPAF016\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF016\",\"id\":150}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(270, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 151, '[]', '{\"name\":\"laugoustine\",\"code_article\":\"PAFPAF017\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF017\",\"id\":151}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(271, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 152, '[]', '{\"name\":\"les palourdes\",\"code_article\":\"PAFPAF018\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF018\",\"id\":152}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(272, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 153, '[]', '{\"name\":\"saint jack\",\"code_article\":\"PAFPAF019\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF019\",\"id\":153}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(273, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 154, '[]', '{\"name\":\"les huitres\",\"code_article\":\"PAFPAF020\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-11 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF020\",\"id\":154}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(274, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 155, '[]', '{\"name\":\"les moules\",\"code_article\":\"PAFPAF021\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-01-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF021\",\"id\":155}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(275, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 156, '[]', '{\"name\":\"les crabs\",\"code_article\":\"PAFPAF022\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-02-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF022\",\"id\":156}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(276, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 157, '[]', '{\"name\":\"tourteau\",\"code_article\":\"PAFPAF023\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":2,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-03-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ B \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF023\",\"id\":157}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(277, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 158, '[]', '{\"name\":\"araign\\u00e9e de mer\",\"code_article\":\"PAFPAF024\",\"price_achat\":15,\"id_categorie\":7,\"id_subcategorie\":37,\"id_local\":2,\"id_rayon\":3,\"seuil\":10,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-04-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ POISSON FRAIS \\/ POISSON FRAIS \\/ PAFPAF024\",\"id\":158}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(278, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 159, '[]', '{\"name\":\"Morceaux de Viande avec OS\",\"code_article\":\"VABVIA001\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-05-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA001\",\"id\":159}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(279, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 160, '[]', '{\"name\":\"tripes de mouton\",\"code_article\":\"VABVIA002\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-06-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA002\",\"id\":160}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(280, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 161, '[]', '{\"name\":\"pied de veau\",\"code_article\":\"VABVIA003\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-07-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA003\",\"id\":161}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(281, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 162, '[]', '{\"name\":\"Epaule de Mouton\",\"code_article\":\"VABVIA004\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-08-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA004\",\"id\":162}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(282, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 163, '[]', '{\"name\":\"Filet de B\\u0153ufs\",\"code_article\":\"VABVIA005\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-09-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA005\",\"id\":163}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(283, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 164, '[]', '{\"name\":\"Viande Hach\\u00e9\",\"code_article\":\"VABVIA006\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA006\",\"id\":164}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(284, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 165, '[]', '{\"name\":\"les saucisses\",\"code_article\":\"VABVIA007\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-11-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA007\",\"id\":165}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(285, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 166, '[]', '{\"name\":\"Jarret d\'agneau\",\"code_article\":\"VABVIA008\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-12-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA008\",\"id\":166}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(286, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 167, '[]', '{\"name\":\"steack de B\\u0153ufs\",\"code_article\":\"VABVIA009\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-01-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA009\",\"id\":167}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(287, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 168, '[]', '{\"name\":\"rhumsteack\",\"code_article\":\"VABVIA010\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-02-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA010\",\"id\":168}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(288, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 169, '[]', '{\"name\":\"entrecote\",\"code_article\":\"VABVIA011\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":38,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-03-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ VIANDES \\/ VABVIA011\",\"id\":169}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(289, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 170, '[]', '{\"name\":\"Cervelles de Mouton\",\"code_article\":\"VABABA001\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":39,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-04-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ ABATS \\/ VABABA001\",\"id\":170}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(290, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 171, '[]', '{\"name\":\"Foie de Veau\",\"code_article\":\"VABABA002\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":39,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-05-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ ABATS \\/ VABABA002\",\"id\":171}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(291, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 172, '[]', '{\"name\":\"T\\u00eates de Moutons\",\"code_article\":\"VABABA003\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":39,\"id_local\":2,\"id_rayon\":3,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-06-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ C \\/ VIANDES ABATS \\/ ABATS \\/ VABABA003\",\"id\":172}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(292, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 173, '[]', '{\"name\":\"Langues de Veau\",\"code_article\":\"VABABA004\",\"price_achat\":15,\"id_categorie\":8,\"id_subcategorie\":39,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-07-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VIANDES ABATS \\/ ABATS \\/ VABABA004\",\"id\":173}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(293, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 174, '[]', '{\"name\":\"Poulet des Abatoirs vid\\u00e9 plum\\u00e9 et sans jabot\",\"code_article\":\"VOEVOL001\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-08-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL001\",\"id\":174}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(294, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 175, '[]', '{\"name\":\"Poulet des Abatoirs beldit\",\"code_article\":\"VOEVOL002\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-09-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL002\",\"id\":175}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(295, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 176, '[]', '{\"name\":\"Blanc de Poulet\",\"code_article\":\"VOEVOL003\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-10-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL003\",\"id\":176}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(296, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 177, '[]', '{\"name\":\"Cuisse de Poulet\",\"code_article\":\"VOEVOL004\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-11-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL004\",\"id\":177}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(297, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 178, '[]', '{\"name\":\"Coquelets\",\"code_article\":\"VOEVOL005\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2026-12-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL005\",\"id\":178}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(298, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 179, '[]', '{\"name\":\"Pigeons\",\"code_article\":\"VOEVOL006\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-01-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL006\",\"id\":179}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(299, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 180, '[]', '{\"name\":\"Lapins\",\"code_article\":\"VOEVOL007\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-02-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL007\",\"id\":180}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(300, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 181, '[]', '{\"name\":\"FILET DE DINDE\",\"code_article\":\"VOEVOL008\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-03-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL008\",\"id\":181}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(301, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 182, '[]', '{\"name\":\"JAMBON DE DINDE FUME\",\"code_article\":\"VOEVOL009\",\"price_achat\":15,\"id_categorie\":9,\"id_subcategorie\":40,\"id_local\":2,\"id_rayon\":4,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-04-12 00:00:00\",\"id_tva\":8,\"id_unite\":1,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE  NEGATIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ VOLAILLES \\/ VOEVOL009\",\"id\":182}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(302, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 183, '[]', '{\"name\":\"\\u0152ufs de 60 grs\",\"code_article\":\"VOEOEU001\",\"price_achat\":2,\"id_categorie\":9,\"id_subcategorie\":41,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-05-12 00:00:00\",\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ \\u0152UFS \\/ VOEOEU001\",\"id\":183}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(303, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 184, '[]', '{\"name\":\"\\u0152ufs beldi de 60 grs\",\"code_article\":\"VOEOEU002\",\"price_achat\":3,\"id_categorie\":9,\"id_subcategorie\":41,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-06-12 00:00:00\",\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ \\u0152UFS \\/ VOEOEU002\",\"id\":184}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(304, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 185, '[]', '{\"name\":\"\\u0152ufs de Caille\",\"code_article\":\"VOEOEU003\",\"price_achat\":6,\"id_categorie\":9,\"id_subcategorie\":41,\"id_local\":3,\"id_rayon\":8,\"seuil\":5,\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2027-07-12 00:00:00\",\"id_tva\":8,\"id_unite\":6,\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ D \\/ VOLAILLES ET \\u0152UFS \\/ \\u0152UFS \\/ VOEOEU003\",\"id\":185}', 'https://red-guanaco-935735.hostingersite.com/importProduct', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:57:17', '2025-10-27 07:57:17'),
(305, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 13, '{\"id\":13,\"matricule\":null,\"nom\":\"JID\",\"prenom\":\"Hicham\",\"email\":\"hjid@tourisme.gov.ma\",\"email_verified_at\":null,\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 07:59:28', '2025-10-27 07:59:28'),
(306, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 26, '{\"id\":26,\"matricule\":null,\"nom\":\"ABADA\",\"prenom\":\"Hajar\",\"email\":\"habada@tourisme.gov.ma\",\"email_verified_at\":null,\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 08:00:39', '2025-10-27 08:00:39'),
(307, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 1, '[]', '{\"total\":156,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":null,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 08:04:25', '2025-10-27 08:04:25'),
(308, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 1, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 08:04:32', '2025-10-27 08:04:32'),
(309, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 1, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Livraison\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 08:04:37', '2025-10-27 08:04:37'),
(310, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 1, '{\"status\":\"Livraison\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 08:04:42', '2025-10-27 08:04:42'),
(311, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 2, '[]', '{\"total\":150,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"20\",\"personnel\":\"10\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-10-30 00:00:00\",\"id\":2}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:34:26', '2025-10-27 08:34:26'),
(312, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 2, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:34:39', '2025-10-27 08:34:39'),
(313, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 2, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:34:46', '2025-10-27 08:34:46'),
(314, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 3, '[]', '{\"total\":150,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu d\'application\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"20\",\"personnel\":\"10\",\"invites\":\"10\",\"divers\":\"10\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-10-31 00:00:00\",\"id\":3}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:39:57', '2025-10-27 08:39:57'),
(315, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 3, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:40:05', '2025-10-27 08:40:05'),
(316, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 3, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:40:10', '2025-10-27 08:40:10'),
(317, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 4, '[]', '{\"total\":600,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu specials\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"10\",\"personnel\":\"10\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":\"SALADE\",\"plat_principal\":\"TAIJINE\",\"accompagnement\":\"RIZ\",\"dessert\":\"BANANE\",\"date_usage\":\"2025-11-01 00:00:00\",\"id\":4}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:43:52', '2025-10-27 08:43:52'),
(318, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 4, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:43:59', '2025-10-27 08:43:59'),
(319, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 4, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 08:44:09', '2025-10-27 08:44:09'),
(320, 'App\\Models\\User', 1, 'created', 'App\\Models\\Fournisseur', 1, '[]', '{\"entreprise\":\"SA\",\"Telephone\":\"06-61-55-58-74\",\"Email\":\"alanyoit19@gmail.com\",\"iduser\":1,\"ICE\":null,\"siege_social\":null,\"RC\":null,\"Patente\":null,\"IF\":null,\"CNSS\":null,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/addFournisseur', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 09:24:26', '2025-10-27 09:24:26'),
(321, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 186, '[]', '{\"name\":\"VINDE HA\",\"code_article\":\"legfru004\",\"price_achat\":\"150\",\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-11-09 00:00:00\",\"class\":\"DENREES ALIMENTAIRES\",\"id_categorie\":\"6\",\"id_subcategorie\":\"36\",\"seuil\":\"10\",\"id_local\":\"3\",\"id_rayon\":\"5\",\"id_tva\":\"1\",\"id_unite\":\"1\",\"id_user\":1,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ A \\/ LEGUMES & FRUITS \\/ FRUITS \\/ legfru004\",\"id\":186}', 'https://red-guanaco-935735.hostingersite.com/addProduct', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 09:26:01', '2025-10-27 09:26:01'),
(322, 'App\\Models\\User', 1, 'created', 'App\\Models\\Achat', 1, '[]', '{\"total\":3000,\"status\":\"Cr\\u00e9ation\",\"id_Fournisseur\":\"1\",\"id_user\":1,\"id\":1}', 'https://red-guanaco-935735.hostingersite.com/StoreAchat', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 09:26:41', '2025-10-27 09:26:41'),
(323, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Achat', 1, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusAchat', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 09:26:51', '2025-10-27 09:26:51'),
(324, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 5, '[]', '{\"total\":850,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":null,\"id\":5}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:33:58', '2025-10-27 09:33:58'),
(325, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 5, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:34:03', '2025-10-27 09:34:03'),
(326, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 5, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:34:07', '2025-10-27 09:34:07'),
(327, 'App\\Models\\User', 1, 'created', 'App\\Models\\StockTransfer', 1, '[]', '{\"id_user\":1,\"status\":\"Cr\\u00e9ation\",\"from\":null,\"to\":\"1\",\"refusal_reason\":null,\"id\":1,\"status_label\":\"Creation\"}', 'https://red-guanaco-935735.hostingersite.com/StoreRouter', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,retour,création', '2025-10-27 09:37:53', '2025-10-27 09:37:53'),
(328, 'App\\Models\\User', 1, 'updated', 'App\\Models\\StockTransfer', 1, '{\"status\":\"Cr\\u00e9ation\",\"status_label\":\"Creation\"}', '{\"status\":\"Validation\",\"status_label\":\"Validated\"}', 'https://red-guanaco-935735.hostingersite.com/router/update-status', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,retour,validation', '2025-10-27 09:37:57', '2025-10-27 09:37:57'),
(329, 'App\\Models\\User', 1, 'created', 'App\\Models\\StockTransfer', 2, '[]', '{\"id_user\":1,\"status\":\"Cr\\u00e9ation\",\"from\":\"1\",\"to\":\"3\",\"refusal_reason\":null,\"id\":2,\"status_label\":\"Creation\"}', 'https://red-guanaco-935735.hostingersite.com/StoreTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,transfer,création', '2025-10-27 09:41:34', '2025-10-27 09:41:34'),
(330, 'App\\Models\\User', 1, 'updated', 'App\\Models\\StockTransfer', 2, '{\"status\":\"Cr\\u00e9ation\",\"refusal_reason\":null,\"status_label\":\"Creation\"}', '{\"status\":\"Refus\",\"refusal_reason\":\"test\",\"status_label\":\"Refused\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,transfer,refus', '2025-10-27 09:42:25', '2025-10-27 09:42:25'),
(331, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\StockTransfer', 2, '{\"id\":2,\"id_user\":1,\"status\":\"Refus\",\"from\":1,\"refusal_reason\":\"test\",\"to\":3,\"status_label\":\"Refused\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/transfer/delete', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,transfer,refus', '2025-10-27 09:42:33', '2025-10-27 09:42:33'),
(332, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 34, '[]', '{\"matricule\":\"app\",\"nom\":\"app\",\"prenom\":\"app\",\"email\":\"cham@gmail.com\",\"telephone\":\"1234567\",\"fonction\":\"aiki\",\"id\":34}', 'https://red-guanaco-935735.hostingersite.com/users', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:46:23', '2025-10-27 09:46:23'),
(333, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 34, '{\"id\":34,\"matricule\":\"app\",\"nom\":\"app\",\"prenom\":\"app\",\"email\":\"cham@gmail.com\",\"email_verified_at\":null,\"telephone\":\"1234567\",\"fonction\":\"aiki\",\"signature\":\"images\\/signatures\\/signature_34_1761558383.png\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:46:46', '2025-10-27 09:46:46'),
(334, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 35, '[]', '{\"matricule\":\"appt\",\"nom\":\"khachane\",\"prenom\":\"chaimae\",\"email\":\"chaimae@gmail.com\",\"telephone\":\"123456\",\"fonction\":\"asdf\",\"id\":35}', 'https://red-guanaco-935735.hostingersite.com/users', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:47:34', '2025-10-27 09:47:34'),
(335, 'App\\Models\\User', 35, 'created', 'App\\Models\\Vente', 6, '[]', '{\"total\":850,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"35\",\"id_user\":35,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-11-01 00:00:00\",\"id\":6}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:48:24', '2025-10-27 09:48:24'),
(336, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 36, '[]', '{\"matricule\":null,\"nom\":\"onahi\",\"prenom\":\"fatima\",\"email\":\"fatima@gmail.com\",\"telephone\":\"123456\",\"fonction\":null,\"id\":36}', 'https://red-guanaco-935735.hostingersite.com/users', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:50:21', '2025-10-27 09:50:21'),
(337, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 37, '[]', '{\"matricule\":null,\"nom\":\"merabte\",\"prenom\":\"said\",\"email\":\"said@gmail.com\",\"telephone\":null,\"fonction\":null,\"id\":37}', 'https://red-guanaco-935735.hostingersite.com/users', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 09:51:45', '2025-10-27 09:51:45'),
(338, 'App\\Models\\User', 1, 'created', 'App\\Models\\StockTransfer', 3, '[]', '{\"id_user\":1,\"status\":\"Cr\\u00e9ation\",\"from\":null,\"to\":\"1\",\"refusal_reason\":null,\"id\":3,\"status_label\":\"Creation\"}', 'https://red-guanaco-935735.hostingersite.com/StoreRouter', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,retour,création', '2025-10-27 09:57:59', '2025-10-27 09:57:59'),
(339, 'App\\Models\\User', 1, 'updated', 'App\\Models\\StockTransfer', 3, '{\"status\":\"Cr\\u00e9ation\",\"status_label\":\"Creation\"}', '{\"status\":\"Validation\",\"status_label\":\"Validated\"}', 'https://red-guanaco-935735.hostingersite.com/router/update-status', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,retour,validation', '2025-10-27 09:58:20', '2025-10-27 09:58:20'),
(340, 'App\\Models\\User', 1, 'created', 'App\\Models\\StockTransfer', 4, '[]', '{\"id_user\":1,\"status\":\"Cr\\u00e9ation\",\"from\":\"1\",\"to\":\"2\",\"refusal_reason\":null,\"id\":4,\"status_label\":\"Creation\"}', 'https://red-guanaco-935735.hostingersite.com/StoreTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,transfer,création', '2025-10-27 10:02:21', '2025-10-27 10:02:21'),
(341, 'App\\Models\\User', 1, 'updated', 'App\\Models\\StockTransfer', 4, '{\"status\":\"Cr\\u00e9ation\",\"status_label\":\"Creation\"}', '{\"status\":\"Validation\",\"status_label\":\"Validated\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'stock-transfer,transfer,validation', '2025-10-27 10:02:30', '2025-10-27 10:02:30'),
(342, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 7, '[]', '{\"id_user\":2,\"id_client\":null,\"id_formateur\":2,\"status\":\"Validation\",\"total\":0,\"id\":7}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 10:02:30', '2025-10-27 10:02:30'),
(343, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 7, '{\"total\":0}', '{\"total\":15}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 10:02:30', '2025-10-27 10:02:30'),
(344, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 38, '[]', '{\"matricule\":null,\"nom\":\"asdfg\",\"prenom\":\"sdfgh\",\"email\":\"app@gmail.com\",\"telephone\":null,\"fonction\":\"zxcvb\",\"id\":38}', 'https://red-guanaco-935735.hostingersite.com/users', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 10:10:24', '2025-10-27 10:10:24'),
(345, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 38, '{\"id\":38,\"matricule\":null,\"nom\":\"asdfg\",\"prenom\":\"sdfgh\",\"email\":\"app@gmail.com\",\"email_verified_at\":null,\"telephone\":null,\"fonction\":\"zxcvb\",\"signature\":\"images\\/signatures\\/signature_38_1761559824.png\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '160.178.212.81', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 10:10:29', '2025-10-27 10:10:29'),
(346, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 35, '{\"id\":35,\"matricule\":\"appt\",\"nom\":\"khachane\",\"prenom\":\"chaimae\",\"email\":\"chaimae@gmail.com\",\"email_verified_at\":null,\"telephone\":\"123456\",\"fonction\":\"asdf\",\"signature\":\"images\\/signatures\\/signature_35_1761558454.png\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:35:56', '2025-10-27 10:35:56'),
(347, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 29, '{\"id\":29,\"matricule\":null,\"nom\":\"Oberbrunner\",\"prenom\":\"Sandra\",\"email\":\"abbott.simeon@example.net\",\"email_verified_at\":\"2025-10-27 07:54:32\",\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:06', '2025-10-27 10:36:06'),
(348, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 37, '{\"id\":37,\"matricule\":null,\"nom\":\"merabte\",\"prenom\":\"said\",\"email\":\"said@gmail.com\",\"email_verified_at\":null,\"telephone\":null,\"fonction\":null,\"signature\":\"images\\/signatures\\/signature_37_1761558705.png\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:09', '2025-10-27 10:36:09');
INSERT INTO `audits` (`id`, `user_type`, `user_id`, `event`, `auditable_type`, `auditable_id`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `tags`, `created_at`, `updated_at`) VALUES
(349, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 36, '{\"id\":36,\"matricule\":null,\"nom\":\"onahi\",\"prenom\":\"fatima\",\"email\":\"fatima@gmail.com\",\"email_verified_at\":null,\"telephone\":\"123456\",\"fonction\":null,\"signature\":\"images\\/signatures\\/signature_36_1761558621.png\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:13', '2025-10-27 10:36:13'),
(350, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 33, '{\"id\":33,\"matricule\":null,\"nom\":\"Dicki\",\"prenom\":\"Olga\",\"email\":\"birdie.reilly@example.com\",\"email_verified_at\":\"2025-10-27 07:54:33\",\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:17', '2025-10-27 10:36:17'),
(351, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 32, '{\"id\":32,\"matricule\":null,\"nom\":\"Collier\",\"prenom\":\"Lonnie\",\"email\":\"qrowe@example.com\",\"email_verified_at\":\"2025-10-27 07:54:33\",\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:21', '2025-10-27 10:36:21'),
(352, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 31, '{\"id\":31,\"matricule\":null,\"nom\":\"O\'Conner\",\"prenom\":\"Meda\",\"email\":\"labadie.name@example.org\",\"email_verified_at\":\"2025-10-27 07:54:33\",\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:28', '2025-10-27 10:36:28'),
(353, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\User', 30, '{\"id\":30,\"matricule\":null,\"nom\":\"Fisher\",\"prenom\":\"Alivia\",\"email\":\"johns.tobin@example.org\",\"email_verified_at\":\"2025-10-27 07:54:33\",\"telephone\":null,\"fonction\":null,\"signature\":null}', '[]', 'https://red-guanaco-935735.hostingersite.com/DeleteUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:36:34', '2025-10-27 10:36:34'),
(354, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 2, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:38:15', '2025-10-27 10:38:15'),
(355, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 10, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:39:52', '2025-10-27 10:39:52'),
(356, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 3, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '41.142.185.40', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 10:41:11', '2025-10-27 10:41:11'),
(357, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 8, '[]', '{\"total\":30,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"50\",\"personnel\":\"6\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":\"salade\",\"plat_principal\":\"tajine\",\"accompagnement\":\"pain\",\"dessert\":\"jawhara\",\"date_usage\":\"2025-10-29 00:00:00\",\"id\":8}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '154.144.252.90', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-10-27 11:05:09', '2025-10-27 11:05:09'),
(358, 'App\\Models\\User', 23, 'created', 'App\\Models\\Vente', 9, '[]', '{\"total\":865,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"23\",\"id_user\":23,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-10-31 00:00:00\",\"id\":9}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 11:28:58', '2025-10-27 11:28:58'),
(359, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 9, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 11:29:27', '2025-10-27 11:29:27'),
(360, 'App\\Models\\User', 18, 'created', 'App\\Models\\Vente', 10, '[]', '{\"total\":250,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"18\",\"id_user\":18,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":null,\"id\":10}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 11:48:17', '2025-10-27 11:48:17'),
(361, 'App\\Models\\User', 18, 'updated', 'App\\Models\\Vente', 10, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Vis\\u00e9\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 11:49:57', '2025-10-27 11:49:57'),
(362, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 11, '[]', '{\"total\":15,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"45\",\"personnel\":\"5\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":\"salade\",\"plat_principal\":\"tajine\",\"accompagnement\":\"pain\",\"dessert\":\"jawhara\",\"date_usage\":\"2025-10-30 00:00:00\",\"id\":11}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.89.55.190', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', NULL, '2025-10-27 12:04:44', '2025-10-27 12:04:44'),
(363, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 21, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:14:23', '2025-10-27 13:14:23'),
(364, 'App\\Models\\User', 21, 'created', 'App\\Models\\Vente', 12, '[]', '{\"total\":3000,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"21\",\"id_user\":21,\"eleves\":\"20\",\"personnel\":\"5\",\"invites\":\"5\",\"divers\":\"5\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-11-01 00:00:00\",\"id\":12}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:16:05', '2025-10-27 13:16:05'),
(365, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 12, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:16:44', '2025-10-27 13:16:44'),
(366, 'App\\Models\\User', 10, 'updated', 'App\\Models\\Vente', 12, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Livraison\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:17:28', '2025-10-27 13:17:28'),
(367, 'App\\Models\\User', 21, 'updated', 'App\\Models\\Vente', 12, '{\"status\":\"Livraison\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:18:01', '2025-10-27 13:18:01'),
(368, 'App\\Models\\User', 21, 'created', 'App\\Models\\StockTransfer', 5, '[]', '{\"id_user\":21,\"status\":\"Cr\\u00e9ation\",\"from\":\"21\",\"to\":\"10\",\"refusal_reason\":null,\"id\":5,\"status_label\":\"Creation\"}', 'https://red-guanaco-935735.hostingersite.com/StoreTransfer', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'stock-transfer,transfer,création', '2025-10-27 13:23:29', '2025-10-27 13:23:29'),
(369, 'App\\Models\\User', 1, 'updated', 'App\\Models\\StockTransfer', 5, '{\"status\":\"Cr\\u00e9ation\",\"status_label\":\"Creation\"}', '{\"status\":\"Validation\",\"status_label\":\"Validated\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'stock-transfer,transfer,validation', '2025-10-27 13:24:09', '2025-10-27 13:24:09'),
(370, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 13, '[]', '{\"id_user\":10,\"id_client\":null,\"id_formateur\":10,\"status\":\"Validation\",\"total\":0,\"id\":13}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:24:09', '2025-10-27 13:24:09'),
(371, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 13, '{\"total\":0}', '{\"total\":450}', 'https://red-guanaco-935735.hostingersite.com/UpdateTransfer', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 13:24:09', '2025-10-27 13:24:09'),
(372, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 5, '{\"telephone\":null,\"fonction\":null}', '{\"telephone\":\"0666494924\",\"fonction\":\"Charg\\u00e9e des stages\"}', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:20:00', '2025-10-27 15:20:00'),
(373, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 7, '{\"fonction\":null}', '{\"fonction\":\"surveillate g\\u00e9nerale\"}', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:22:03', '2025-10-27 15:22:03'),
(374, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 16, '{\"telephone\":null,\"fonction\":null}', '{\"telephone\":\"0654572527\",\"fonction\":\"Formatrice\"}', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:24:11', '2025-10-27 15:24:11'),
(375, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 17, '{\"telephone\":null,\"fonction\":null}', '{\"telephone\":\"0666494935\",\"fonction\":\"Formatrice\"}', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:26:20', '2025-10-27 15:26:20'),
(376, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 18, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:30:03', '2025-10-27 15:30:03'),
(377, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 22, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:34:06', '2025-10-27 15:34:06'),
(378, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 19, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:35:14', '2025-10-27 15:35:14'),
(379, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 20, '{\"telephone\":null,\"fonction\":null}', '{\"telephone\":\"0666494975\",\"fonction\":\"Formatrice\"}', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 15:43:47', '2025-10-27 15:43:47'),
(380, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 14, '[]', '{\"total\":41650,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Non Alimentaire\",\"type_menu\":null,\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":0,\"personnel\":0,\"invites\":0,\"divers\":0,\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":null,\"id\":14}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 15:47:00', '2025-10-27 15:47:00'),
(381, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 14, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '105.156.231.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-27 15:47:08', '2025-10-27 15:47:08'),
(382, 'App\\Models\\User', 1, 'created', 'App\\Models\\User', 39, '[]', '{\"matricule\":null,\"nom\":\"BELLAMLIH\",\"prenom\":\"ABDELAZIZ\",\"email\":\"abdelaziz.bellemlih@outlook.com\",\"telephone\":\"661461671\",\"fonction\":\"Formateur\",\"id\":39}', 'https://red-guanaco-935735.hostingersite.com/importUsers', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 16:03:38', '2025-10-27 16:03:38'),
(383, 'App\\Models\\User', 1, 'updated', 'App\\Models\\User', 39, '[]', '[]', 'https://red-guanaco-935735.hostingersite.com/updateUser', '105.158.137.65', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-27 16:04:26', '2025-10-27 16:04:26'),
(384, 'App\\Models\\User', 2, 'created', 'App\\Models\\Vente', 15, '[]', '{\"total\":43,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu specials\",\"id_formateur\":\"2\",\"id_user\":2,\"eleves\":\"10\",\"personnel\":\"10\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":\"salade cesar\",\"plat_principal\":\"gigot d\'agneaupain\",\"accompagnement\":null,\"dessert\":\"banane\",\"date_usage\":\"2025-10-31 00:00:00\",\"id\":15}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.178.210.251', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', NULL, '2025-10-27 21:21:16', '2025-10-27 21:21:16'),
(385, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 11, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '105.158.123.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-28 13:20:30', '2025-10-28 13:20:30'),
(386, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 11, '{\"status\":\"Refus\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '105.158.123.66', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-28 13:21:55', '2025-10-28 13:21:55'),
(387, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 6, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/Command', '160.178.62.203', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-29 10:39:24', '2025-10-29 10:39:24'),
(388, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Product', 186, '{\"name\":\"VINDE HA\",\"code_article\":\"legfru004\",\"id_categorie\":6,\"id_subcategorie\":36,\"id_tva\":1,\"class\":\"DENREES ALIMENTAIRES\"}', '{\"name\":\"VINDE HAhh\",\"code_article\":\"mataud004\",\"id_categorie\":\"1\",\"id_subcategorie\":\"6\",\"id_tva\":\"2\",\"class\":\"NON ALIMENTAIRE\"}', 'https://red-guanaco-935735.hostingersite.com/updateProduct', '160.178.62.203', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-29 10:40:47', '2025-10-29 10:40:47'),
(389, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Product', 186, '{\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ A \\/ LEGUMES & FRUITS \\/ FRUITS \\/ legfru004\"}', '{\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ A \\/ MATERIEL \\/ AUDIOVISUEL \\/ mataud004\"}', 'https://red-guanaco-935735.hostingersite.com/updateProduct', '160.178.62.203', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-29 10:40:47', '2025-10-29 10:40:47'),
(390, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\Product', 186, '{\"id\":186,\"name\":\"VINDE HAhh\",\"code_article\":\"mataud004\",\"price_achat\":\"150.00\",\"code_barre\":null,\"emplacement\":\"CHAMBRE FROIDE POSITIVE \\/ A \\/ MATERIEL \\/ AUDIOVISUEL \\/ mataud004\",\"seuil\":10,\"date_expiration\":\"2025-11-09\",\"id_categorie\":1,\"id_subcategorie\":6,\"id_local\":3,\"id_rayon\":5,\"id_tva\":2,\"id_unite\":1,\"id_user\":1,\"class\":\"NON ALIMENTAIRE\",\"photo\":null,\"price_vente\":\"1.00\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/deleteProduct', '160.178.62.203', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-29 10:40:52', '2025-10-29 10:40:52'),
(391, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 8, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/Command', '105.74.2.113', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-29 14:45:01', '2025-10-29 14:45:01'),
(392, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 187, '[]', '{\"name\":\"test\",\"code_article\":\"mataud004\",\"price_achat\":\"20\",\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-30 00:00:00\",\"class\":\"NON ALIMENTAIRE\",\"id_categorie\":\"1\",\"id_subcategorie\":\"6\",\"seuil\":\"5\",\"id_local\":\"1\",\"id_rayon\":\"31\",\"id_tva\":\"2\",\"id_unite\":\"1\",\"id_user\":1,\"emplacement\":\"RESERVE FOUNITURE SCOLAIRE \\/ A2 \\/ MATERIEL \\/ AUDIOVISUEL \\/ mataud004\",\"id\":187}', 'https://red-guanaco-935735.hostingersite.com/addProduct', '105.157.116.76', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-29 15:39:46', '2025-10-29 15:39:46'),
(393, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 15, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/Command', '105.157.118.58', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 07:46:50', '2025-10-30 07:46:50'),
(394, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 16, '[]', '{\"total\":300,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"20\",\"personnel\":\"0\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-11-04 00:00:00\",\"id\":16}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 09:48:58', '2025-10-30 09:48:58'),
(395, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 16, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 09:49:30', '2025-10-30 09:49:30'),
(396, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 16, '{\"status\":\"Refus\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 10:45:51', '2025-10-30 10:45:51'),
(397, 'App\\Models\\User', 10, 'updated', 'App\\Models\\Vente', 16, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Livraison\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 10:48:46', '2025-10-30 10:48:46'),
(398, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 16, '{\"status\":\"Livraison\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', NULL, '2025-10-30 10:49:28', '2025-10-30 10:49:28'),
(399, 'App\\Models\\User', 21, 'created', 'App\\Models\\Vente', 17, '[]', '{\"total\":645,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"21\",\"id_user\":21,\"eleves\":\"20\",\"personnel\":\"0\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":\"2025-11-08 00:00:00\",\"id\":17}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 11:03:31', '2025-10-30 11:03:31'),
(400, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 17, '{\"status\":\"Cr\\u00e9ation\"}', '{\"status\":\"Refus\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 11:05:18', '2025-10-30 11:05:18'),
(401, 'App\\Models\\User', 1, 'updated', 'App\\Models\\Vente', 17, '{\"status\":\"Refus\"}', '{\"status\":\"R\\u00e9ception\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 11:05:32', '2025-10-30 11:05:32'),
(402, 'App\\Models\\User', 10, 'updated', 'App\\Models\\Vente', 17, '{\"status\":\"R\\u00e9ception\"}', '{\"status\":\"Livraison\"}', 'https://red-guanaco-935735.hostingersite.com/UpdateVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 11:06:41', '2025-10-30 11:06:41'),
(403, 'App\\Models\\User', 21, 'updated', 'App\\Models\\Vente', 17, '{\"status\":\"Livraison\"}', '{\"status\":\"Validation\"}', 'https://red-guanaco-935735.hostingersite.com/ChangeStatusVente', '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 11:07:45', '2025-10-30 11:07:45'),
(404, 'App\\Models\\User', 1, 'created', 'App\\Models\\Product', 188, '[]', '{\"name\":\"at\",\"code_article\":\"matbur005\",\"price_achat\":\"20\",\"code_barre\":null,\"photo\":null,\"date_expiration\":\"2025-10-31 00:00:00\",\"class\":\"NON ALIMENTAIRE\",\"id_categorie\":\"1\",\"id_subcategorie\":\"4\",\"seuil\":\"6\",\"id_local\":\"1\",\"id_rayon\":\"31\",\"id_tva\":\"2\",\"id_unite\":\"1\",\"id_user\":1,\"emplacement\":\"RESERVE FOUNITURE SCOLAIRE \\/ A2 \\/ MATERIEL \\/ BUREAU \\/ matbur005\",\"id\":188}', 'https://red-guanaco-935735.hostingersite.com/addProduct', '160.179.167.231', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 13:56:04', '2025-10-30 13:56:04'),
(405, 'App\\Models\\User', 1, 'deleted', 'App\\Models\\Product', 188, '{\"id\":188,\"name\":\"at\",\"code_article\":\"matbur005\",\"price_achat\":\"20.00\",\"code_barre\":null,\"emplacement\":\"RESERVE FOUNITURE SCOLAIRE \\/ A2 \\/ MATERIEL \\/ BUREAU \\/ matbur005\",\"seuil\":6,\"date_expiration\":\"2025-10-31\",\"id_categorie\":1,\"id_subcategorie\":4,\"id_local\":1,\"id_rayon\":31,\"id_tva\":2,\"id_unite\":1,\"id_user\":1,\"class\":\"NON ALIMENTAIRE\",\"photo\":null,\"price_vente\":\"1.00\"}', '[]', 'https://red-guanaco-935735.hostingersite.com/deleteProduct', '160.179.167.231', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-30 13:56:10', '2025-10-30 13:56:10'),
(406, 'App\\Models\\User', 1, 'created', 'App\\Models\\Vente', 18, '[]', '{\"total\":75,\"status\":\"Cr\\u00e9ation\",\"type_commande\":\"Alimentaire\",\"type_menu\":\"Menu eleves\",\"id_formateur\":\"1\",\"id_user\":1,\"eleves\":\"0\",\"personnel\":\"0\",\"invites\":\"0\",\"divers\":\"0\",\"entree\":null,\"plat_principal\":null,\"accompagnement\":null,\"dessert\":null,\"date_usage\":null,\"id\":18}', 'https://red-guanaco-935735.hostingersite.com/StoreVente', '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:66:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:8:\"Products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"Products-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:17:\"Products-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:18:\"Products-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:5:\"Taxes\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"Taxes-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"Taxes-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"Taxes-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:12:\"Fournisseurs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:19:\"Fournisseurs-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:21:\"Fournisseurs-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:22:\"Fournisseurs-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"Categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:17:\"Categories-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:19:\"Categories-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:20:\"Categories-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:5:\"Local\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:12:\"Local-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:14:\"Local-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:15:\"Local-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:5:\"Rayon\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"Rayon-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:14:\"Rayon-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:15:\"Rayon-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:7:\"Famille\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:6;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"Famille-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:16:\"Famille-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:17:\"Famille-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:5:\"Achat\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:12:\"Achat-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:14:\"Achat-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:15:\"Achat-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:8:\"Commande\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:15:\"Commande-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:17:\"Commande-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"Commande-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:10:\"Historique\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:17:\"Historique-Export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:18:\"Historique-montrer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:6:\"Unité\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:13:\"Unité-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"Unité-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:16:\"Unité-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:11:\"utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:18:\"utilisateur-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:20:\"utilisateur-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:21:\"utilisateur-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:6:\"rôles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"rôles-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:11:\"rôles-voir\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:15:\"rôles-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:16:\"rôles-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:15:\"Transfer-ajoute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:17:\"Transfer-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:18:\"Transfer-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:8:\"Transfer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:6;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:5:\"Stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:6:\"retour\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:14:\"retour-ajouter\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:15:\"retour-modifier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:16:\"retour-supprimer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:10:\"Inventaire\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:17:\"Voir-Consommation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:27:\"Voir-Consommation-Complète\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:33:\"Voir-Rapport-Mensuel-Consommation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:20:\"Voir-Stock-Demandeur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}}s:5:\"roles\";a:6:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"Administrateur\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:8:\"Économe\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"Magasinier\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:9:\"Formateur\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"Utilisateur\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"Gestionnaire\";s:1:\"c\";s:3:\"web\";}}}', 1761948504);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `classe` varchar(255) DEFAULT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `classe`, `iduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MATERIEL', 'NON ALIMENTAIRE', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(2, 'OUTILLAGE', 'NON ALIMENTAIRE', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(3, 'MOBILIER', 'NON ALIMENTAIRE', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(4, 'FOURNITURE', 'NON ALIMENTAIRE', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(5, 'EPICERIE & PRODUITS LAITIERS', 'DENREES ALIMENTAIRES', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(6, 'LEGUMES & FRUITS', 'DENREES ALIMENTAIRES', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(7, 'POISSON FRAIS', 'DENREES ALIMENTAIRES', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(8, 'VIANDES ABATS', 'DENREES ALIMENTAIRES', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL),
(9, 'VOLAILLES ET ŒUFS', 'DENREES ALIMENTAIRES', 1, '2025-10-27 07:56:40', '2025-10-27 07:56:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `Telephone` varchar(255) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Matricule` varchar(255) NOT NULL,
  `Fonction` enum('Directrice','Econome','Cadre Administratif','Assistante de Direction','Formateur','Administrateur') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consumption_product_details`
--

CREATE TABLE `consumption_product_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `consumption_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ligne_vente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ligne_achat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `tva_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tva_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consumption_product_details`
--

INSERT INTO `consumption_product_details` (`id`, `consumption_id`, `product_id`, `ligne_vente_id`, `ligne_achat_id`, `quantity`, `unit_price`, `tva_rate`, `tva_amount`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, NULL, 1.00, 156.00, 20.00, 31.20, 187.20, '2025-10-27 08:27:57', '2025-10-27 08:27:57'),
(2, 2, 159, 2, NULL, 10.00, 15.00, 20.00, 30.00, 180.00, '2025-10-27 08:34:57', '2025-10-27 08:34:57'),
(3, 3, 174, 3, NULL, 10.00, 15.00, 20.00, 30.00, 180.00, '2025-10-27 08:40:20', '2025-10-27 08:40:20'),
(4, 4, 136, 4, NULL, 40.00, 15.00, 20.00, 120.00, 720.00, '2025-10-27 08:44:27', '2025-10-27 08:44:27'),
(5, 5, 186, NULL, 1, 20.00, 150.00, 0.00, 0.00, 3000.00, '2025-10-27 09:27:15', '2025-10-27 09:27:15'),
(6, 6, 11, 5, NULL, 1.00, 850.00, 20.00, 170.00, 1020.00, '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(7, 7, 159, 7, NULL, 1.00, 15.00, 20.00, 3.00, 18.00, '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(8, 8, 186, 14, NULL, 20.00, 150.00, 0.00, 0.00, 3000.00, '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(9, 9, 186, 15, NULL, 3.00, 150.00, 0.00, 0.00, 450.00, '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(10, 10, 11, 16, NULL, 49.00, 850.00, 20.00, 8330.00, 49980.00, '2025-10-27 19:33:48', '2025-10-27 19:33:48'),
(11, 11, 176, 20, NULL, 20.00, 15.00, 20.00, 60.00, 360.00, '2025-10-30 13:53:29', '2025-10-30 13:53:29'),
(12, 12, 126, 21, NULL, 15.00, 15.00, 20.00, 45.00, 270.00, '2025-10-30 13:53:29', '2025-10-30 13:53:29'),
(13, 12, 114, 22, NULL, 10.00, 15.00, 20.00, 30.00, 180.00, '2025-10-30 13:53:29', '2025-10-30 13:53:29'),
(14, 12, 176, 23, NULL, 18.00, 15.00, 20.00, 54.00, 324.00, '2025-10-30 13:53:29', '2025-10-30 13:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `daily_consumption`
--

CREATE TABLE `daily_consumption` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `consumption_date` date NOT NULL,
  `vente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `achat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type_commande` varchar(255) DEFAULT NULL,
  `type_menu` varchar(255) DEFAULT NULL,
  `total_people` int(11) NOT NULL DEFAULT 0,
  `total_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_tva` decimal(10,2) NOT NULL DEFAULT 0.00,
  `average_cost_per_person` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category_costs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`category_costs`)),
  `eleves` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `personnel` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `invites` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `divers` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` enum('entree','sortie') NOT NULL DEFAULT 'sortie',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_consumption`
--

INSERT INTO `daily_consumption` (`id`, `consumption_date`, `vente_id`, `achat_id`, `type_commande`, `type_menu`, `total_people`, `total_cost`, `total_tva`, `average_cost_per_person`, `category_costs`, `eleves`, `personnel`, `invites`, `divers`, `type`, `created_at`, `updated_at`) VALUES
(1, '2025-10-27', 1, NULL, 'Non Alimentaire', NULL, 0, 187.20, 31.20, 0.00, '{\"4\":{\"id\":4,\"name\":\"FOURNITURE\",\"total_cost\":187.2,\"total_tva\":31.200000000000003}}', 0, 0, 0, 0, 'sortie', '2025-10-27 08:27:57', '2025-10-27 08:27:57'),
(2, '2025-10-27', 2, NULL, 'Alimentaire', 'Menu eleves', 30, 180.00, 30.00, 6.00, '{\"8\":{\"id\":8,\"name\":\"VIANDES ABATS\",\"total_cost\":180,\"total_tva\":30}}', 20, 10, 0, 0, 'sortie', '2025-10-27 08:34:57', '2025-10-27 08:34:57'),
(3, '2025-10-27', 3, NULL, 'Alimentaire', 'Menu d\'application', 50, 180.00, 30.00, 3.60, '{\"9\":{\"id\":9,\"name\":\"VOLAILLES ET \\u0152UFS\",\"total_cost\":180,\"total_tva\":30}}', 20, 10, 10, 10, 'sortie', '2025-10-27 08:40:20', '2025-10-27 08:40:20'),
(4, '2025-10-27', 4, NULL, 'Alimentaire', 'Menu specials', 20, 720.00, 120.00, 36.00, '{\"7\":{\"id\":7,\"name\":\"POISSON FRAIS\",\"total_cost\":720,\"total_tva\":120}}', 10, 10, 0, 0, 'sortie', '2025-10-27 08:44:27', '2025-10-27 08:44:27'),
(5, '2025-10-27', NULL, 1, NULL, NULL, 0, 0.00, 0.00, 0.00, '[]', 0, 0, 0, 0, 'entree', '2025-10-27 09:27:15', '2025-10-29 15:37:30'),
(6, '2025-10-27', 5, NULL, 'Non Alimentaire', NULL, 0, 1020.00, 170.00, 0.00, '{\"4\":{\"id\":4,\"name\":\"FOURNITURE\",\"total_cost\":1020,\"total_tva\":170}}', 0, 0, 0, 0, 'sortie', '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(7, '2025-10-27', 7, NULL, 'Alimentaire', NULL, 0, 18.00, 3.00, 0.00, '{\"8\":{\"id\":8,\"name\":\"VIANDES ABATS\",\"total_cost\":18,\"total_tva\":3}}', 0, 0, 0, 0, 'sortie', '2025-10-27 15:14:13', '2025-10-27 15:14:13'),
(8, '2025-10-27', 12, NULL, 'Alimentaire', 'Menu eleves', 35, 0.00, 0.00, 0.00, '[]', 20, 5, 5, 5, 'sortie', '2025-10-27 15:14:13', '2025-10-29 15:37:30'),
(9, '2025-10-27', 13, NULL, 'Alimentaire', NULL, 0, 0.00, 0.00, 0.00, '[]', 0, 0, 0, 0, 'sortie', '2025-10-27 15:14:13', '2025-10-29 15:37:30'),
(10, '2025-10-27', 14, NULL, 'Non Alimentaire', NULL, 0, 49980.00, 8330.00, 0.00, '{\"4\":{\"id\":4,\"name\":\"FOURNITURE\",\"total_cost\":49980,\"total_tva\":8330}}', 0, 0, 0, 0, 'sortie', '2025-10-27 19:33:48', '2025-10-27 19:33:48'),
(11, '2025-10-30', 16, NULL, 'Alimentaire', 'Menu eleves', 20, 360.00, 60.00, 18.00, '{\"9\":{\"id\":9,\"name\":\"VOLAILLES ET \\u0152UFS\",\"total_cost\":360,\"total_tva\":60}}', 20, 0, 0, 0, 'sortie', '2025-10-30 13:53:29', '2025-10-30 13:53:29'),
(12, '2025-10-30', 17, NULL, 'Alimentaire', 'Menu eleves', 20, 774.00, 129.00, 38.70, '{\"6\":{\"id\":6,\"name\":\"LEGUMES & FRUITS\",\"total_cost\":450,\"total_tva\":75},\"9\":{\"id\":9,\"name\":\"VOLAILLES ET \\u0152UFS\",\"total_cost\":324,\"total_tva\":54}}', 20, 0, 0, 0, 'sortie', '2025-10-30 13:53:29', '2025-10-30 13:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entreprise` varchar(255) NOT NULL,
  `Telephone` varchar(255) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `Email` varchar(255) NOT NULL,
  `ICE` varchar(255) DEFAULT NULL,
  `siege_social` varchar(255) DEFAULT NULL,
  `RC` varchar(255) DEFAULT NULL,
  `Patente` varchar(255) DEFAULT NULL,
  `IF` varchar(255) DEFAULT NULL,
  `CNSS` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `entreprise`, `Telephone`, `iduser`, `Email`, `ICE`, `siege_social`, `RC`, `Patente`, `IF`, `CNSS`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SA', '06-61-55-58-74', 1, 'alanyoit19@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-27 09:24:26', '2025-10-27 09:24:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hostorique_sig`
--

CREATE TABLE `hostorique_sig` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `signature` text DEFAULT NULL,
  `iduser` bigint(20) UNSIGNED DEFAULT NULL,
  `idvente` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hostorique_sig`
--

INSERT INTO `hostorique_sig` (`id`, `signature`, `iduser`, `idvente`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 1, 'Création', '2025-10-27 08:04:25', '2025-10-27 08:04:25'),
(2, NULL, 1, 1, 'Réception', '2025-10-27 08:04:32', '2025-10-27 08:04:32'),
(3, NULL, 1, 1, 'Livraison', '2025-10-27 08:04:37', '2025-10-27 08:04:37'),
(4, NULL, 1, 1, 'Validation', '2025-10-27 08:04:42', '2025-10-27 08:04:42'),
(5, NULL, 1, 2, 'Création', '2025-10-27 08:34:26', '2025-10-27 08:34:26'),
(6, NULL, 1, 2, 'Réception', '2025-10-27 08:34:39', '2025-10-27 08:34:39'),
(7, NULL, 1, 2, 'Validation', '2025-10-27 08:34:46', '2025-10-27 08:34:46'),
(8, NULL, 1, 3, 'Création', '2025-10-27 08:39:57', '2025-10-27 08:39:57'),
(9, NULL, 1, 3, 'Réception', '2025-10-27 08:40:05', '2025-10-27 08:40:05'),
(10, NULL, 1, 3, 'Validation', '2025-10-27 08:40:10', '2025-10-27 08:40:10'),
(11, NULL, 1, 4, 'Création', '2025-10-27 08:43:52', '2025-10-27 08:43:52'),
(12, NULL, 1, 4, 'Réception', '2025-10-27 08:43:59', '2025-10-27 08:43:59'),
(13, NULL, 1, 4, 'Validation', '2025-10-27 08:44:09', '2025-10-27 08:44:09'),
(14, NULL, 1, 5, 'Création', '2025-10-27 09:33:58', '2025-10-27 09:33:58'),
(15, NULL, 1, 5, 'Réception', '2025-10-27 09:34:03', '2025-10-27 09:34:03'),
(16, NULL, 1, 5, 'Validation', '2025-10-27 09:34:07', '2025-10-27 09:34:07'),
(17, 'images/signatures/signature_35_1761558454.png', 35, 6, 'Création', '2025-10-27 09:48:24', '2025-10-27 09:48:24'),
(18, NULL, 1, 8, 'Création', '2025-10-27 11:05:09', '2025-10-27 11:05:09'),
(19, NULL, 23, 9, 'Création', '2025-10-27 11:28:58', '2025-10-27 11:28:58'),
(20, NULL, 1, 9, 'Réception', '2025-10-27 11:29:27', '2025-10-27 11:29:27'),
(21, NULL, 18, 10, 'Création', '2025-10-27 11:48:17', '2025-10-27 11:48:17'),
(22, NULL, 1, 11, 'Création', '2025-10-27 12:04:44', '2025-10-27 12:04:44'),
(23, NULL, 21, 12, 'Création', '2025-10-27 13:16:05', '2025-10-27 13:16:05'),
(24, NULL, 1, 12, 'Réception', '2025-10-27 13:16:44', '2025-10-27 13:16:44'),
(25, NULL, 10, 12, 'Livraison', '2025-10-27 13:17:28', '2025-10-27 13:17:28'),
(26, NULL, 21, 12, 'Validation', '2025-10-27 13:18:01', '2025-10-27 13:18:01'),
(27, NULL, 1, 14, 'Création', '2025-10-27 15:47:00', '2025-10-27 15:47:00'),
(28, NULL, 1, 14, 'Validation', '2025-10-27 15:47:08', '2025-10-27 15:47:08'),
(29, NULL, 2, 15, 'Création', '2025-10-27 21:21:16', '2025-10-27 21:21:16'),
(30, NULL, 1, 11, 'Réception', '2025-10-28 13:21:55', '2025-10-28 13:21:55'),
(31, NULL, 1, 16, 'Création', '2025-10-30 09:48:58', '2025-10-30 09:48:58'),
(32, NULL, 1, 16, 'Réception', '2025-10-30 10:45:51', '2025-10-30 10:45:51'),
(33, NULL, 10, 16, 'Livraison', '2025-10-30 10:48:46', '2025-10-30 10:48:46'),
(34, NULL, 1, 16, 'Validation', '2025-10-30 10:49:28', '2025-10-30 10:49:28'),
(35, NULL, 21, 17, 'Création', '2025-10-30 11:03:31', '2025-10-30 11:03:31'),
(36, NULL, 1, 17, 'Réception', '2025-10-30 11:05:32', '2025-10-30 11:05:32'),
(37, NULL, 10, 17, 'Livraison', '2025-10-30 11:06:41', '2025-10-30 11:06:41'),
(38, NULL, 21, 17, 'Validation', '2025-10-30 11:07:45', '2025-10-30 11:07:45'),
(39, NULL, 1, 18, 'Création', '2025-10-31 15:08:06', '2025-10-31 15:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `entree` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sortie` decimal(10,2) NOT NULL DEFAULT 0.00,
  `reste` decimal(10,2) NOT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `id_achat` bigint(20) UNSIGNED DEFAULT NULL,
  `id_vente` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `date`, `entree`, `sortie`, `reste`, `prix_unitaire`, `id_achat`, `id_vente`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 6, '2025-10-27', 0.00, 1.00, 49.00, 156.00, NULL, 1, 1, '2025-10-27 08:04:42', '2025-10-27 08:04:42', NULL),
(2, 159, '2025-10-27', 0.00, 10.00, 40.00, 15.00, NULL, 2, 1, '2025-10-27 08:34:46', '2025-10-27 08:34:46', NULL),
(3, 174, '2025-10-27', 0.00, 10.00, 40.00, 15.00, NULL, 3, 1, '2025-10-27 08:40:10', '2025-10-27 08:40:10', NULL),
(4, 136, '2025-10-27', 0.00, 40.00, 10.00, 15.00, NULL, 4, 1, '2025-10-27 08:44:09', '2025-10-27 08:44:09', NULL),
(5, 186, '2025-10-27', 20.00, 0.00, 120.00, 150.00, 1, NULL, 1, '2025-10-27 09:26:51', '2025-10-27 09:26:51', NULL),
(6, 11, '2025-10-27', 0.00, 1.00, 49.00, 850.00, NULL, 5, 1, '2025-10-27 09:34:07', '2025-10-27 09:34:07', NULL),
(7, 186, '2025-10-27', 0.00, 20.00, 100.00, 150.00, NULL, 12, 21, '2025-10-27 13:18:01', '2025-10-27 13:18:01', NULL),
(8, 11, '2025-10-27', 0.00, 49.00, 0.00, 850.00, NULL, 14, 1, '2025-10-27 15:47:08', '2025-10-27 15:47:08', NULL),
(9, 176, '2025-10-30', 0.00, 20.00, 30.00, 15.00, NULL, 16, 1, '2025-10-30 10:49:28', '2025-10-30 10:49:28', NULL),
(10, 126, '2025-10-30', 0.00, 15.00, 35.00, 15.00, NULL, 17, 21, '2025-10-30 11:07:45', '2025-10-30 11:07:45', NULL),
(11, 114, '2025-10-30', 0.00, 10.00, 40.00, 15.00, NULL, 17, 21, '2025-10-30 11:07:45', '2025-10-30 11:07:45', NULL),
(12, 176, '2025-10-30', 0.00, 18.00, 12.00, 15.00, NULL, 17, 21, '2025-10-30 11:07:45', '2025-10-30 11:07:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_monthly_summaries`
--

CREATE TABLE `inventory_monthly_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `total_entrees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_sorties` decimal(10,2) NOT NULL DEFAULT 0.00,
  `end_stock` decimal(10,2) NOT NULL,
  `average_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_monthly_summaries`
--

INSERT INTO `inventory_monthly_summaries` (`id`, `product_id`, `year`, `month`, `total_entrees`, `total_sorties`, `end_stock`, `average_price`, `created_at`, `updated_at`) VALUES
(1, 6, 2025, 10, 0.00, 1.00, 49.00, 156.00, '2025-10-27 08:04:42', '2025-10-27 08:04:42'),
(2, 159, 2025, 10, 0.00, 10.00, 40.00, 15.00, '2025-10-27 08:34:46', '2025-10-27 08:34:46'),
(3, 174, 2025, 10, 0.00, 10.00, 40.00, 15.00, '2025-10-27 08:40:10', '2025-10-27 08:40:10'),
(4, 136, 2025, 10, 0.00, 40.00, 10.00, 15.00, '2025-10-27 08:44:09', '2025-10-27 08:44:09'),
(5, 186, 2025, 10, 20.00, 20.00, 100.00, 150.00, '2025-10-27 09:26:51', '2025-10-27 13:18:01'),
(6, 11, 2025, 10, 0.00, 50.00, 0.00, 850.00, '2025-10-27 09:34:07', '2025-10-27 15:47:08'),
(7, 176, 2025, 10, 0.00, 38.00, 12.00, 15.00, '2025-10-30 10:49:28', '2025-10-30 11:07:45'),
(8, 126, 2025, 10, 0.00, 15.00, 35.00, 15.00, '2025-10-30 11:07:45', '2025-10-30 11:07:45'),
(9, 114, 2025, 10, 0.00, 10.00, 40.00, 15.00, '2025-10-30 11:07:45', '2025-10-30 11:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_yearly_summaries`
--

CREATE TABLE `inventory_yearly_summaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `year` int(11) NOT NULL,
  `total_entrees` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_sorties` decimal(10,2) NOT NULL DEFAULT 0.00,
  `end_stock` decimal(10,2) NOT NULL,
  `average_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_yearly_summaries`
--

INSERT INTO `inventory_yearly_summaries` (`id`, `product_id`, `year`, `total_entrees`, `total_sorties`, `end_stock`, `average_price`, `created_at`, `updated_at`) VALUES
(1, 6, 2025, 0.00, 1.00, 49.00, 156.00, '2025-10-27 08:04:42', '2025-10-27 08:04:42'),
(2, 159, 2025, 0.00, 10.00, 40.00, 15.00, '2025-10-27 08:34:46', '2025-10-27 08:34:46'),
(3, 174, 2025, 0.00, 10.00, 40.00, 15.00, '2025-10-27 08:40:10', '2025-10-27 08:40:10'),
(4, 136, 2025, 0.00, 40.00, 10.00, 15.00, '2025-10-27 08:44:09', '2025-10-27 08:44:09'),
(5, 186, 2025, 20.00, 20.00, 100.00, 150.00, '2025-10-27 09:26:51', '2025-10-27 13:18:01'),
(6, 11, 2025, 0.00, 50.00, 0.00, 850.00, '2025-10-27 09:34:07', '2025-10-27 15:47:08'),
(7, 176, 2025, 0.00, 38.00, 12.00, 15.00, '2025-10-30 10:49:28', '2025-10-30 11:07:45'),
(8, 126, 2025, 0.00, 15.00, 35.00, 15.00, '2025-10-30 11:07:45', '2025-10-30 11:07:45'),
(9, 114, 2025, 0.00, 10.00, 40.00, 15.00, '2025-10-30 11:07:45', '2025-10-30 11:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ligne_achat`
--

CREATE TABLE `ligne_achat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `idachat` bigint(20) UNSIGNED NOT NULL,
  `idproduit` bigint(20) UNSIGNED NOT NULL,
  `qte` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ligne_achat`
--

INSERT INTO `ligne_achat` (`id`, `id_user`, `idachat`, `idproduit`, `qte`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 186, 20, '2025-10-27 09:26:41', '2025-10-27 09:26:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ligne_vente`
--

CREATE TABLE `ligne_vente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `idvente` bigint(20) UNSIGNED NOT NULL,
  `idproduit` bigint(20) UNSIGNED NOT NULL,
  `qte` int(11) NOT NULL,
  `contente_transfert` text DEFAULT NULL,
  `contete_formateur` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ligne_vente`
--

INSERT INTO `ligne_vente` (`id`, `id_user`, `idvente`, `idproduit`, `qte`, `contente_transfert`, `contete_formateur`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 6, 1, NULL, '0', '2025-10-27 08:04:25', '2025-10-27 09:37:57', NULL),
(2, 1, 2, 159, 10, '1', '8', '2025-10-27 08:34:26', '2025-10-27 10:02:30', NULL),
(3, 1, 3, 174, 10, NULL, '10', '2025-10-27 08:39:57', '2025-10-27 08:40:10', NULL),
(4, 1, 4, 136, 40, NULL, '40', '2025-10-27 08:43:52', '2025-10-27 08:44:09', NULL),
(5, 1, 5, 11, 1, NULL, '1', '2025-10-27 09:33:58', '2025-10-27 09:34:07', NULL),
(6, 35, 6, 11, 1, NULL, NULL, '2025-10-27 09:48:24', '2025-10-27 09:48:24', NULL),
(7, 2, 7, 159, 1, NULL, '1', '2025-10-27 10:02:30', '2025-10-27 10:02:30', NULL),
(8, 1, 8, 164, 1, NULL, NULL, '2025-10-27 11:05:09', '2025-10-27 11:05:09', NULL),
(9, 1, 8, 159, 1, NULL, NULL, '2025-10-27 11:05:09', '2025-10-27 11:05:09', NULL),
(10, 23, 9, 126, 1, NULL, NULL, '2025-10-27 11:28:58', '2025-10-27 11:28:58', NULL),
(11, 23, 9, 11, 1, NULL, NULL, '2025-10-27 11:28:58', '2025-10-27 11:28:58', NULL),
(12, 18, 10, 3, 1, NULL, NULL, '2025-10-27 11:48:17', '2025-10-27 11:48:17', NULL),
(13, 1, 11, 159, 1, NULL, NULL, '2025-10-27 12:04:44', '2025-10-27 12:04:44', NULL),
(14, 21, 12, 186, 20, '3', '17', '2025-10-27 13:16:05', '2025-10-27 13:24:09', NULL),
(15, 10, 13, 186, 3, NULL, '3', '2025-10-27 13:24:09', '2025-10-27 13:24:09', NULL),
(16, 1, 14, 11, 49, NULL, '49', '2025-10-27 15:47:00', '2025-10-27 15:47:08', NULL),
(17, 2, 15, 136, 1, NULL, NULL, '2025-10-27 21:21:16', '2025-10-27 21:21:16', NULL),
(18, 2, 15, 25, 1, NULL, NULL, '2025-10-27 21:21:16', '2025-10-27 21:21:16', NULL),
(19, 2, 15, 115, 1, NULL, NULL, '2025-10-27 21:21:16', '2025-10-27 21:21:16', NULL),
(20, 1, 16, 176, 20, NULL, '20', '2025-10-30 09:48:58', '2025-10-30 10:49:28', NULL),
(21, 21, 17, 126, 15, NULL, '15', '2025-10-30 11:03:31', '2025-10-30 11:07:45', NULL),
(22, 21, 17, 114, 10, NULL, '10', '2025-10-30 11:03:31', '2025-10-30 11:07:45', NULL),
(23, 21, 17, 176, 18, NULL, '18', '2025-10-30 11:03:31', '2025-10-30 11:07:45', NULL),
(24, 1, 18, 159, 2, NULL, NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06', NULL),
(25, 1, 18, 158, 1, NULL, NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06', NULL),
(26, 1, 18, 47, 1, NULL, NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06', NULL),
(27, 1, 18, 126, 1, NULL, NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `line_transfer`
--

CREATE TABLE `line_transfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `id_tva` bigint(20) UNSIGNED DEFAULT NULL,
  `id_unite` bigint(20) UNSIGNED DEFAULT NULL,
  `idcommande` bigint(20) UNSIGNED NOT NULL,
  `id_stocktransfer` bigint(20) UNSIGNED NOT NULL,
  `quantite` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `line_transfer`
--

INSERT INTO `line_transfer` (`id`, `id_user`, `id_product`, `id_tva`, `id_unite`, `idcommande`, `id_stocktransfer`, `quantite`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 8, 6, 1, 1, 1, '2025-10-27 09:37:53', '2025-10-27 09:37:53'),
(3, 1, 159, 8, 1, 2, 3, 1, '2025-10-27 09:57:59', '2025-10-27 09:57:59'),
(4, 1, 159, 8, 1, 2, 4, 1, '2025-10-27 10:02:21', '2025-10-27 10:02:21'),
(5, 21, 186, 1, 1, 12, 5, 3, '2025-10-27 13:23:29', '2025-10-27 13:23:29');

-- --------------------------------------------------------

--
-- Table structure for table `locals`
--

CREATE TABLE `locals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locals`
--

INSERT INTO `locals` (`id`, `name`, `iduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RESERVE FOUNITURE SCOLAIRE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(2, 'CHAMBRE FROIDE  NEGATIVE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(3, 'CHAMBRE FROIDE POSITIVE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(4, 'ECONOMAT', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(5, 'RESERVE  MOBILIER DE BUREAU', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(6, 'RESERVE FOUNITURE DE BUREAU', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(7, 'RESERVE FOUNITURE ELECTRIQUE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(8, 'RESERVE FOUNITURE INFORMATIQUE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(9, 'RESERVE MOBILIER DE CUISINE & RESTAURATION', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(10, 'RESERVE MOBILIER SCOLAIRE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(11, 'RESERVE PAPITERIE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL),
(12, 'RESERVE PRODUITS DE NETTOYAGE', 1, '2025-10-27 07:56:17', '2025-10-27 07:56:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_07_102755_create_categories_table', 1),
(5, '2025_03_07_103252_create_sub_categories_table', 1),
(6, '2025_03_08_103654_create_permission_tables', 1),
(7, '2025_03_11_215241_create_local_table', 1),
(8, '2025_03_11_220748_create__rayon_table', 1),
(9, '2025_03_12_212343_create_tvas_table', 1),
(10, '2025_03_12_215502_create_unite_table', 1),
(11, '2025_03_12_220635_create_products_table', 1),
(12, '2025_03_12_220744_create_stock_table', 1),
(13, '2025_03_14_114155_create_fournisseurs_table', 1),
(14, '2025_03_14_232314_create_achats_table', 1),
(15, '2025_03_15_110158_create_temp_achat_table', 1),
(16, '2025_03_15_124318_create_ligne_achat_table', 1),
(17, '2025_03_18_224934_create_clients_table', 1),
(18, '2025_03_18_230744_create_ventes_table', 1),
(19, '2025_03_18_230958_create_temp__vente_table', 1),
(20, '2025_03_18_231140_create_ligne__vente_table', 1),
(21, '2025_03_21_133307_create_audits_table', 1),
(22, '2025_04_12_120123_create_notifications_table', 1),
(23, '2025_04_18_153700_create_inventories_table', 1),
(24, '2025_04_18_153753_create_inventory_monthly_summaries_table', 1),
(25, '2025_04_18_153828_create_inventory_yearly_summaries_table', 1),
(26, '2025_04_18_185229_create_stocktransfer_table', 1),
(27, '2025_04_22_172855_create_tmpstocktransfer_table', 1),
(28, '2025_04_22_191703_create_line_transfer_table', 1),
(29, '2025_05_04_210657_create_daily_consumption_table', 1),
(30, '2025_10_24_161430_add_signature_to_users_table', 1),
(31, '2025_10_24_163126_create_hostorique_sig', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(5, 'App\\Models\\User', 8),
(5, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(5, 'App\\Models\\User', 11),
(6, 'App\\Models\\User', 12),
(5, 'App\\Models\\User', 13),
(5, 'App\\Models\\User', 14),
(5, 'App\\Models\\User', 15),
(4, 'App\\Models\\User', 16),
(4, 'App\\Models\\User', 17),
(4, 'App\\Models\\User', 18),
(4, 'App\\Models\\User', 19),
(4, 'App\\Models\\User', 20),
(4, 'App\\Models\\User', 21),
(4, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 23),
(5, 'App\\Models\\User', 24),
(5, 'App\\Models\\User', 25),
(5, 'App\\Models\\User', 26),
(5, 'App\\Models\\User', 27),
(5, 'App\\Models\\User', 28),
(1, 'App\\Models\\User', 34),
(1, 'App\\Models\\User', 35),
(4, 'App\\Models\\User', 36),
(3, 'App\\Models\\User', 37),
(1, 'App\\Models\\User', 38),
(4, 'App\\Models\\User', 39);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('05f4515d-f3c3-4393-bd9b-1d6bee539dab', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/2\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/2\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/k5\"}', '2025-10-27 09:29:15', '2025-10-27 08:34:26', '2025-10-27 09:29:15'),
('0d7c4056-9c14-4cb9-a7e8-f9ab2931fc74', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 35, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par chaimae khachane\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/6\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/6\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/oj\"}', NULL, '2025-10-27 09:48:24', '2025-10-27 09:48:24'),
('1a8d5782-3a50-4b72-af32-d2fc383b9c3c', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/zY\"}', '2025-10-28 11:39:15', '2025-10-27 15:47:08', '2025-10-28 11:39:15'),
('1b55dcfb-4884-4213-afbc-84af375b6e9d', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 21, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/Dx\"}', NULL, '2025-10-30 11:07:45', '2025-10-30 11:07:45'),
('2a763573-136f-4286-8b56-7ea5f1777982', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par chaimae khachane\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/6\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/6\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/oj\"}', '2025-10-27 11:05:17', '2025-10-27 09:48:24', '2025-10-27 11:05:17'),
('2c598409-4583-426e-acee-3b1ea37431c0', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/14\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/14\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/zY\"}', '2025-10-28 11:39:15', '2025-10-27 15:47:00', '2025-10-28 11:39:15'),
('33ad5257-b885-4ee7-a5f6-cd4bc80d9b77', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Fatima EL AZMI\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/9\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/9\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/rE\"}', '2025-10-27 12:04:52', '2025-10-27 11:28:58', '2025-10-27 12:04:52'),
('462cab0d-62c7-4008-91e1-da690c0601e1', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/16\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/16\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/BX\"}', '2025-10-30 09:50:33', '2025-10-30 09:48:58', '2025-10-30 09:50:33'),
('4f199c56-7bd9-468b-bc6f-b1b53d790de6', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre achat a \\u00e9t\\u00e9 approuv\\u00e9\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonReception\\/jR\"}', '2025-10-27 09:29:15', '2025-10-27 09:26:51', '2025-10-27 09:29:15'),
('4fa1e4c0-33a8-4836-8b61-785d69527ddf', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/3\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/3\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/l5\"}', '2025-10-27 09:29:15', '2025-10-27 08:39:57', '2025-10-27 09:29:15'),
('62142215-2b0b-4047-ae4b-edf5def80548', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Chaimae EMRAN\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/15\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/15\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/AO\"}', '2025-10-28 11:39:15', '2025-10-27 21:21:16', '2025-10-28 11:39:15'),
('6cc11a32-bc6a-4335-bd76-49c42b5dd007', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/k5\"}', '2025-10-27 09:29:15', '2025-10-27 08:34:46', '2025-10-27 09:29:15'),
('7e85f73a-366e-499d-b829-5ba7d96dc315', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Soufian ZIANI\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/12\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/12\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/x9\"}', '2025-10-27 13:16:26', '2025-10-27 13:16:05', '2025-10-27 13:16:26'),
('7fe8ffb5-c5c1-4b27-a77b-331a35725f26', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/1\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/1\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/jR\"}', '2025-10-27 09:29:15', '2025-10-27 08:04:25', '2025-10-27 09:29:15'),
('84fecdeb-2d97-4c7f-85b3-eddcaae9d994', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvel achat cr\\u00e9\\u00e9 par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/achat\\/1\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/achat\\/1\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonReception\\/jR\"}', '2025-10-27 09:29:15', '2025-10-27 09:26:41', '2025-10-27 09:29:15'),
('8df12f44-6938-4512-bb20-531852b04c47', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Soufian ZIANI\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/17\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/17\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/Dx\"}', '2025-10-30 11:04:28', '2025-10-30 11:03:31', '2025-10-30 11:04:28'),
('8e60c3d1-0d80-4d86-b3d7-4e0c742ae4f9', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/jR\"}', '2025-10-27 09:29:15', '2025-10-27 08:04:42', '2025-10-27 09:29:15'),
('985c615e-989f-4cb5-ad6b-fcaf917a3ad2', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/8\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/8\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/q2\"}', '2025-10-27 11:05:17', '2025-10-27 11:05:09', '2025-10-27 11:05:17'),
('9a291ba1-4b08-49d3-b287-b9a9588c7783', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/nR\"}', '2025-10-27 11:05:17', '2025-10-27 09:34:07', '2025-10-27 11:05:17'),
('a9a2a922-2584-43be-881c-ac5e6dc80dea', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/18\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/18\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/Ev\"}', NULL, '2025-10-31 15:08:06', '2025-10-31 15:08:06'),
('ae775d77-71a4-424c-a20f-63049a4f8128', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/l5\"}', '2025-10-27 09:29:15', '2025-10-27 08:40:10', '2025-10-27 09:29:15'),
('b331ae34-aa7c-4683-a164-543ce0aa4b54', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/mO\"}', '2025-10-27 09:29:15', '2025-10-27 08:44:09', '2025-10-27 09:29:15'),
('cb2e9bc0-b00d-4f4b-9e9e-567d3804f64b', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 18, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 vis\\u00e9e par l\'\\u00e9conome\",\"status\":\"Vis\\u00e9\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/vm\"}', NULL, '2025-10-27 11:49:57', '2025-10-27 11:49:57'),
('d21a34e9-bcc6-488e-87f6-b6a210c00fb3', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 21, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/x9\"}', '2025-10-30 11:03:36', '2025-10-27 13:18:01', '2025-10-30 11:03:36'),
('d3a45d04-a5d1-41b9-95db-43c58e3e7a24', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/5\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/5\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/nR\"}', '2025-10-27 11:05:17', '2025-10-27 09:33:58', '2025-10-27 11:05:17'),
('d68bf043-9fd9-4b35-a0fa-a1bbf8c97315', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/4\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/4\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/mO\"}', '2025-10-27 09:29:15', '2025-10-27 08:43:52', '2025-10-27 09:29:15'),
('d953d262-4435-402b-b42e-471c35515530', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Karima ANIA\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/11\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/11\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/wR\"}', '2025-10-27 12:04:52', '2025-10-27 12:04:44', '2025-10-27 12:04:52'),
('dc48c3ea-e7d7-4c4a-9ded-2058cd50a7d0', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Stock faible: Crevettes Frais - Quantit\\u00e9: 10, Seuil: 10\",\"status\":\"Stock Bas\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/stock\"}', '2025-10-27 09:29:15', '2025-10-27 08:44:09', '2025-10-27 09:29:15'),
('ef615618-c2bb-42b7-b873-4f60c20664e4', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Votre commande a \\u00e9t\\u00e9 approuv\\u00e9e\",\"status\":\"Validation\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/BX\"}', '2025-10-30 11:04:28', '2025-10-30 10:49:28', '2025-10-30 11:04:28'),
('f0404a9f-c75a-476d-b891-cc6e29091de6', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Nouvelle commande cr\\u00e9\\u00e9e par Bassma SOUHADI\",\"status\":\"Cr\\u00e9ation\",\"approve_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/10\\/approve\",\"reject_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/admin\\/vente\\/10\\/reject\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/ShowBonVente\\/vm\"}', '2025-10-27 12:04:52', '2025-10-27 11:48:17', '2025-10-27 12:04:52'),
('f7f6c174-6dc0-41f8-859c-87605555843c', 'App\\Notifications\\SystemNotification', 'App\\Models\\User', 1, '{\"message\":\"Stock faible: Carnets  de Stages   FT12 *19,5 cm - Quantit\\u00e9: 0, Seuil: 10\",\"status\":\"Stock Bas\",\"view_url\":\"https:\\/\\/red-guanaco-935735.hostingersite.com\\/stock\"}', '2025-10-28 11:39:15', '2025-10-27 15:47:08', '2025-10-28 11:39:15');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Products', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(2, 'Products-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(3, 'Products-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(4, 'Products-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(5, 'Taxes', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(6, 'Taxes-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(7, 'Taxes-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(8, 'Taxes-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(9, 'Fournisseurs', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(10, 'Fournisseurs-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(11, 'Fournisseurs-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(12, 'Fournisseurs-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(13, 'Categories', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(14, 'Categories-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(15, 'Categories-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(16, 'Categories-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(17, 'Local', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(18, 'Local-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(19, 'Local-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(20, 'Local-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(21, 'Rayon', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(22, 'Rayon-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(23, 'Rayon-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(24, 'Rayon-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(25, 'Famille', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(26, 'Famille-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(27, 'Famille-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(28, 'Famille-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(29, 'Achat', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(30, 'Achat-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(31, 'Achat-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(32, 'Achat-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(33, 'Commande', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(34, 'Commande-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(35, 'Commande-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(36, 'Commande-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(37, 'Historique', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(38, 'Historique-Export', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(39, 'Historique-montrer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(40, 'Unité', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(41, 'Unité-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(42, 'Unité-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(43, 'Unité-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(44, 'utilisateur', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(45, 'utilisateur-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(46, 'utilisateur-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(47, 'utilisateur-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(48, 'rôles', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(49, 'rôles-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(50, 'rôles-voir', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(51, 'rôles-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(52, 'rôles-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(53, 'Transfer-ajoute', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(54, 'Transfer-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(55, 'Transfer-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(56, 'Transfer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(57, 'Stock', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(58, 'retour', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(59, 'retour-ajouter', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(60, 'retour-modifier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(61, 'retour-supprimer', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(62, 'Inventaire', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(63, 'Voir-Consommation', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(64, 'Voir-Consommation-Complète', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(65, 'Voir-Rapport-Mensuel-Consommation', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL),
(66, 'Voir-Stock-Demandeur', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code_article` varchar(255) NOT NULL,
  `price_achat` decimal(10,2) NOT NULL,
  `code_barre` varchar(255) DEFAULT NULL,
  `emplacement` varchar(255) DEFAULT NULL,
  `seuil` int(11) NOT NULL DEFAULT 0,
  `date_expiration` date DEFAULT NULL,
  `id_categorie` bigint(20) UNSIGNED NOT NULL,
  `id_subcategorie` bigint(20) UNSIGNED NOT NULL,
  `id_local` bigint(20) UNSIGNED NOT NULL,
  `id_rayon` bigint(20) UNSIGNED NOT NULL,
  `id_tva` bigint(20) UNSIGNED DEFAULT NULL,
  `id_unite` bigint(20) UNSIGNED DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `class` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `price_vente` decimal(10,2) NOT NULL DEFAULT 1.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code_article`, `price_achat`, `code_barre`, `emplacement`, `seuil`, `date_expiration`, `id_categorie`, `id_subcategorie`, `id_local`, `id_rayon`, `id_tva`, `id_unite`, `id_user`, `class`, `photo`, `price_vente`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Carnets Bon de Sortie Administration FT 20,5*20,5 cm', 'FOUELC039', 291.67, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC039', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(2, 'Carnets Bon de Sortie de Cuisine FT 21*20,5 cm', 'FOUELC040', 2.40, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC040', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(3, 'Carnets  de Rapport de Leçons   FT 25 *30 cm', 'FOUELC041', 250.00, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC041', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(4, 'Carnets Bon de Sortie de Cuisine FT 21*30,5 cm', 'FOUELC042', 233.34, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC042', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(5, 'Cardex Feuilles de Consommations FT 32*24,5 CM', 'FOUELC043', 66.67, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC043', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(6, 'Carnets Bon de Sortie 03 Exemplaires', 'FOUELC044', 156.00, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC044', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(7, 'Carnets Bon de Sortie de Restaurant  FT 21*20,5 cm', 'FOUELC045', 39.17, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC045', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(8, 'Carnets Bon de Sortie de Réception   FT 26,5 *20,5 cm', 'FOUELC046', 36.71, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC046', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(9, 'Carnets Bon de Sortie de Commande   FT 19,5 *13 cm', 'FOUELC047', 17.00, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC047', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(10, 'Carnets  de Rapport de Leçons   FT 21,5 *31,5 cm', 'FOUELC048', 103.00, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC048', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(11, 'Carnets  de Stages   FT12 *19,5 cm', 'FOUELC049', 850.00, NULL, 'RESERVE PAPITERIE / A1 / FOURNITURE / PAPITERIE / FOUELC049', 10, NULL, 4, 19, 11, 38, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(12, 'Carnets Feuilles de Consommations FT 48*32 CM', 'FOUELC053', 112.50, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC053', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(13, 'Fiches de Renseignement FT 21*30 CM', 'FOUELC055', 1100.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC055', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(14, 'Fiches Evaluations trimestrielle', 'FOUELC056', 970.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC056', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(15, 'Feuilles d\'examen FT 44*31,5 cm', 'FOUELC057', 208.33, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC057', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(16, 'Feuilles de Concours d\'accés  FT 44*31,5 cm', 'FOUELC058', 870.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC058', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(17, 'Livres D\"apprentissage métier : Boulangerie pâtissier', 'FOUELC059', 1380.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC059', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(18, 'Livres D\"apprentissage métier : Restaurant', 'FOUELC060', 760.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC060', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(19, 'Livres D\"apprentissage métier : Cuisine', 'FOUELC061', 1100.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC061', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(20, 'Livres D\'engagement', 'FOUELC063', 650.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC063', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(21, 'Registre de Dépense', 'FOUELC064', 600.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC064', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(22, 'Registre Comptable', 'FOUELC065', 600.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC065', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(23, 'Registre de Départ', 'FOUELC066', 600.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC066', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(24, 'Registre d\'arrivée', 'FOUELC067', 650.00, NULL, 'RESERVE PAPITERIE / A2 / FOURNITURE / PAPITERIE / FOUELC067', 10, NULL, 4, 19, 11, 39, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(25, 'Farine de Blé Tendre', 'EPLBOU001', 13.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU001', 13, '2028-06-06', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(26, 'Farine de Blé Dur', 'EPLBOU002', 13.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU002', 13, '2026-01-07', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(27, 'Far- de Blé Tendre  pour Pätisserie', 'EPLBOU003', 13.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU003', 13, '2026-02-07', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(28, 'Farine de Blé Dur Son', 'EPLBOU004', 13.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU004', 13, '2026-03-07', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(29, 'Farine D\'orge', 'EPLBOU005', 20.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU005', 20, '2026-04-07', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(30, 'Semoule Mais boulangerie', 'EPLBOU006', 14.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU006', 14, '2025-10-05', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(31, 'Avoine', 'EPLBOU007', 14.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU007', 14, '2026-06-07', 5, 21, 4, 11, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(32, 'Levure Boulangére de 125 grs', 'EPLBOU008', 7.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU008', 7, '2026-07-07', 5, 21, 4, 11, 1, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(33, 'Levure Chimique', 'EPLBOU009', 7.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU009', 7, '2026-08-07', 5, 21, 4, 11, 1, 7, 1, NULL, NULL, 1.00, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(34, 'PAIN DE MI', 'EPLBOU010', 7.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / BOULANGERIE / EPLBOU010', 7, '2026-09-07', 5, 21, 4, 11, 1, 7, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(35, 'Cornichons 4/4', 'EPLCON001', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON001', 8, '2026-10-07', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(36, 'Cornichons de 500 grs', 'EPLCON002', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON002', 8, '2026-11-07', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(37, 'Confiture 4/4', 'EPLCON003', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON003', 8, '2026-12-07', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(38, 'Mais Doux 4/4', 'EPLCON004', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON004', 8, '2026-08-05', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(39, 'Mais Doux de 500 grs', 'EPLCON005', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON005', 8, '2027-02-07', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(40, 'Olive Noir 4/4', 'EPLCON006', 15.00, NULL, 'ECONOMAT / A / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON006', 8, '2027-03-07', 5, 22, 4, 11, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(41, 'Thon à L\'huile de 400 grs', 'EPLCON007', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON007', 8, '2027-04-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(42, 'Tomate Concentrée 4/4', 'EPLCON008', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON008', 8, '2027-05-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(43, 'Tomate Concentrée 1/6', 'EPLCON009', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON009', 8, '2027-06-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(44, 'Tomate Concentrée 1/8', 'EPLCON010', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON010', 8, '2027-07-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(45, 'Miel Ruche de 01 kg', 'EPLCON011', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON011', 8, '2027-08-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(46, 'Miel Pur de 850 grs', 'EPLCON012', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON012', 8, '2027-09-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(47, 'Ananas Rondelles 4/4', 'EPLCON013', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON013', 8, '2027-10-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(48, 'Petits Pois 4/4', 'EPLCON014', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON014', 8, '2027-11-07', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(49, 'Demi Poire 4/4', 'EPLCON015', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / CONSERVES / EPLCON015', 8, '2026-02-05', 5, 22, 4, 12, 8, 8, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(50, 'Lentilles', 'EPLLSE001', 18.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / LEGUMES SECS (LEGUMINEUSES) / EPLLSE001', 5, '2026-03-05', 5, 23, 4, 12, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(51, 'Féves Séches', 'EPLLSE002', 12.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / LEGUMES SECS (LEGUMINEUSES) / EPLLSE002', 5, '2026-04-05', 5, 23, 4, 12, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(52, 'Haricot Blanc Secs', 'EPLLSE003', 15.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / LEGUMES SECS (LEGUMINEUSES) / EPLLSE003', 5, '2026-05-05', 5, 23, 4, 12, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(53, 'Petits Pois Concassées', 'EPLLSE004', 22.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / LEGUMES SECS (LEGUMINEUSES) / EPLLSE004', 5, '2026-06-05', 5, 23, 4, 12, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(54, 'Poischiches', 'EPLLSE005', 25.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / LEGUMES SECS (LEGUMINEUSES) / EPLLSE005', 5, '2026-07-05', 5, 23, 4, 12, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(55, 'Riz Long Glacé', 'EPLPAF001', 30.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF001', 10, '2026-08-05', 5, 24, 4, 12, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(56, 'Coquillettes', 'EPLPAF002', 30.00, NULL, 'ECONOMAT / B / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF002', 10, '2026-09-05', 5, 24, 4, 12, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(57, 'Nouilles', 'EPLPAF003', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF003', 10, '2026-10-05', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(58, 'Spaghettis', 'EPLPAF004', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF004', 10, '2026-11-05', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(59, 'Semoule de Couscous', 'EPLPAF005', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF005', 10, '2026-12-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(60, 'Torsade', 'EPLPAF006', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF006', 10, '2027-01-05', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(61, 'Semoule Fine', 'EPLPAF007', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF007', 10, '2027-02-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(62, 'Semoule Grosse', 'EPLPAF008', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF008', 10, '2027-03-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(63, 'Semoule Fino', 'EPLPAF009', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF009', 10, '2027-04-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(64, 'Belboula GF', 'EPLPAF010', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF010', 10, '2027-05-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(65, 'Belboula MF', 'EPLPAF011', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF011', 10, '2027-06-05', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(66, 'Vermicelle de Chine', 'EPLPAF012', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF012', 5, '2027-07-05', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(67, 'Vermicelle Cheveux D\'enge', 'EPLPAF013', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF013', 5, '2025-01-06', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(68, 'Poudre de Riz', 'EPLPAF014', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF014', 5, '2025-02-06', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(69, 'Maizena de 180 grs', 'EPLPAF015', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF015', 5, '2025-03-06', 5, 24, 4, 13, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(70, 'Poudre Crème', 'EPLPAF016', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF016', 5, '2025-04-06', 5, 24, 4, 13, 1, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(71, 'BLE EBLY DE 500 GR', 'EPLPAF017', 30.00, NULL, 'ECONOMAT / C / EPICERIE & PRODUITS LAITIERS / PATTE & FARINEUSE / EPLPAF017', 5, '2025-05-06', 5, 24, 4, 13, 5, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(72, 'Artichauts Frais', 'LEFLEG001', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG001', 5, '2025-09-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(73, 'Aubergines', 'LEFLEG002', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG002', 5, '2025-10-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(74, 'Betteraves', 'LEFLEG003', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG003', 5, '2025-11-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(75, 'Carottes', 'LEFLEG004', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG004', 5, '2025-12-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(76, 'Choux Verts', 'LEFLEG005', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG005', 5, '2026-01-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(77, 'CHAMPIGNONS FRAIS', 'LEFLEG006', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG006', 5, '2026-02-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(78, 'Choux Fleurs', 'LEFLEG007', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG007', 5, '2026-03-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(79, 'Courgettes', 'LEFLEG008', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG008', 5, '2026-04-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(80, 'Citrons Frais', 'LEFLEG009', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG009', 5, '2026-05-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(81, 'Citrons Confits', 'LEFLEG010', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG010', 5, '2026-06-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(82, 'Coings', 'LEFLEG011', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG011', 5, '2026-07-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(83, 'Concombres', 'LEFLEG012', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG012', 5, '2026-08-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(84, 'Coriandre', 'LEFLEG013', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG013', 5, '2026-09-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(85, 'Courge Rouge', 'LEFLEG014', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG014', 5, '2026-10-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(86, 'Epinards', 'LEFLEG015', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG015', 5, '2026-11-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(87, 'ECHALOTTE', 'LEFLEG016', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG016', 5, '2026-12-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(88, 'MAUVE', 'LEFLEG017', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG017', 5, '2027-01-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(89, 'PETITE OIGNON', 'LEFLEG018', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / C / LEGUMES & FRUITS / LEGUMES / LEFLEG018', 5, '2027-02-09', 6, 35, 3, 7, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(90, 'POURPIER', 'LEFLEG019', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG019', 5, '2027-03-09', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(91, 'Fenouils', 'LEFLEG020', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG020', 5, '2027-04-09', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(92, 'Féves Fraiches', 'LEFLEG021', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG021', 5, '2027-05-09', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(93, 'Haricots Verts', 'LEFLEG022', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG022', 5, '2027-06-09', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(94, 'Laitues', 'LEFLEG023', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG023', 5, '2025-01-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(95, 'Laitues Frisées', 'LEFLEG024', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG024', 5, '2025-02-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(96, 'Navets Sas Branche', 'LEFLEG025', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG025', 5, '2025-03-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(97, 'Navets salsifé', 'LEFLEG026', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG026', 5, '2025-04-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(98, 'Oignons Sans Branches', 'LEFLEG027', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG027', 5, '2025-05-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(99, 'Oignons avec Branches', 'LEFLEG028', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG028', 5, '2025-06-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(100, 'Olives Confites Verte', 'LEFLEG029', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG029', 5, '2025-07-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(101, 'Olives Meslalla', 'LEFLEG030', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG030', 5, '2025-08-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(102, 'Persil', 'LEFLEG031', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG031', 5, '2025-09-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(103, 'Petits Pois Frais', 'LEFLEG032', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG032', 5, '2025-10-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(104, 'Poireaux', 'LEFLEG033', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / LEGUMES & FRUITS / LEGUMES / LEFLEG033', 5, '2025-11-10', 6, 35, 3, 8, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(105, 'Poivrons Verts', 'LEFLEG034', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG034', 5, '2025-12-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(106, 'Poivrons Rouge', 'LEFLEG035', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG035', 5, '2026-01-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(107, 'Poivrons Jaune', 'LEFLEG036', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG036', 5, '2026-02-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(108, 'Pomme de Terre', 'LEFLEG037', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG037', 5, '2026-03-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(109, 'Radis', 'LEFLEG038', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG038', 5, '2026-04-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(110, 'Tomates Fraiches', 'LEFLEG039', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG039', 5, '2026-05-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(111, 'Tomates Cerises', 'LEFLEG040', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG040', 5, '2026-06-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(112, 'TOPINAMBOUR', 'LEFLEG041', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG041', 5, '2026-07-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(113, 'PATATE DOUCE', 'LEFLEG042', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / LEGUMES / LEFLEG042', 5, '2026-08-10', 6, 35, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(114, 'Ananas Frais', 'LEFFRU001', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU001', 5, '2026-09-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(115, 'Bananes', 'LEFFRU002', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU002', 5, '2026-10-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(116, 'Clémentines', 'LEFFRU003', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU003', 5, '2026-11-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(117, 'Fraises', 'LEFFRU004', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU004', 5, '2026-12-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(118, 'Grenadines', 'LEFFRU005', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU005', 5, '2027-01-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(119, 'Kiwi', 'LEFFRU006', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / E / LEGUMES & FRUITS / FRUITS / LEFFRU006', 5, '2027-02-10', 6, 36, 3, 9, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(120, 'Oranges de Table', 'LEFFRU007', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU007', 5, '2027-03-10', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(121, 'Oranges à presser', 'LEFFRU008', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU008', 5, '2027-04-10', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(122, 'Poires', 'LEFFRU009', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU009', 5, '2027-05-10', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(123, 'Pomme Fruits', 'LEFFRU010', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU010', 5, '2027-06-10', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(124, 'Prunes', 'LEFFRU011', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU011', 5, '2027-07-10', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(125, 'Raisins Frais', 'LEFFRU012', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU012', 5, '2025-01-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(126, 'Abricots Frais', 'LEFFRU013', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU013', 5, '2025-02-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(127, 'Anones Frais', 'LEFFRU014', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU014', 5, '2025-03-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(128, 'pastéque', 'LEFFRU015', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU015', 5, '2025-04-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(129, 'melon', 'LEFFRU016', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU016', 5, '2025-05-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(130, 'cantaloupe', 'LEFFRU017', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU017', 5, '2025-06-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(131, 'Papaye', 'LEFFRU018', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU018', 5, '2025-07-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(132, 'Kaki', 'LEFFRU019', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU019', 5, '2025-08-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(133, 'Framboise', 'LEFFRU020', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU020', 5, '2025-09-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(134, 'Cerise', 'LEFFRU021', 15.00, NULL, 'CHAMBRE FROIDE POSITIVE / F / LEGUMES & FRUITS / FRUITS / LEFFRU021', 5, '2025-10-11', 6, 36, 3, 10, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(135, 'Calamar Frais', 'PAFPAF001', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF001', 10, '2025-11-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(136, 'Crevettes Frais', 'PAFPAF002', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF002', 10, '2025-12-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(137, 'Crevettes Royal', 'PAFPAF003', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF003', 10, '2026-01-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(138, 'Merlan Frais', 'PAFPAF004', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF004', 10, '2026-02-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(139, 'Sardines Frais', 'PAFPAF005', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF005', 10, '2026-03-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(140, 'Congres Frais', 'PAFPAF006', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF006', 10, '2026-04-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(141, 'Ombrines Frais', 'PAFPAF007', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF007', 10, '2026-05-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(142, 'Pageots Frais', 'PAFPAF008', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF008', 10, '2026-06-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(143, 'Soles Ration Frais', 'PAFPAF009', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF009', 10, '2026-07-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(144, 'Espadon Frais', 'PAFPAF010', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF010', 10, '2026-08-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(145, 'poulpe', 'PAFPAF011', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / A / POISSON FRAIS / POISSON FRAIS / PAFPAF011', 10, '2026-09-11', 7, 37, 2, 1, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(146, 'la seiche', 'PAFPAF012', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF012', 10, '2026-10-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(147, 'la pieuvre', 'PAFPAF013', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF013', 10, '2026-11-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(148, 'la raie', 'PAFPAF014', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF014', 10, '2026-12-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(149, 'les homards', 'PAFPAF015', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF015', 10, '2027-01-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(150, 'Langouste', 'PAFPAF016', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF016', 10, '2027-02-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(151, 'laugoustine', 'PAFPAF017', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF017', 10, '2027-03-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(152, 'les palourdes', 'PAFPAF018', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF018', 10, '2027-04-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(153, 'saint jack', 'PAFPAF019', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF019', 10, '2027-05-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(154, 'les huitres', 'PAFPAF020', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF020', 10, '2027-06-11', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(155, 'les moules', 'PAFPAF021', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF021', 10, '2025-01-12', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(156, 'les crabs', 'PAFPAF022', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF022', 10, '2025-02-12', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(157, 'tourteau', 'PAFPAF023', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / B / POISSON FRAIS / POISSON FRAIS / PAFPAF023', 10, '2025-03-12', 7, 37, 2, 2, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(158, 'araignée de mer', 'PAFPAF024', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / POISSON FRAIS / POISSON FRAIS / PAFPAF024', 10, '2025-04-12', 7, 37, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(159, 'Morceaux de Viande avec OS', 'VABVIA001', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA001', 5, '2025-05-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(160, 'tripes de mouton', 'VABVIA002', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA002', 5, '2025-06-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(161, 'pied de veau', 'VABVIA003', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA003', 5, '2025-07-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(162, 'Epaule de Mouton', 'VABVIA004', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA004', 5, '2025-08-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(163, 'Filet de Bœufs', 'VABVIA005', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA005', 5, '2025-09-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(164, 'Viande Haché', 'VABVIA006', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA006', 5, '2025-10-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(165, 'les saucisses', 'VABVIA007', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA007', 5, '2025-11-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(166, 'Jarret d\'agneau', 'VABVIA008', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA008', 5, '2025-12-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(167, 'steack de Bœufs', 'VABVIA009', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA009', 5, '2026-01-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(168, 'rhumsteack', 'VABVIA010', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA010', 5, '2026-02-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(169, 'entrecote', 'VABVIA011', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / VIANDES / VABVIA011', 5, '2026-03-12', 8, 38, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(170, 'Cervelles de Mouton', 'VABABA001', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / ABATS / VABABA001', 5, '2026-04-12', 8, 39, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(171, 'Foie de Veau', 'VABABA002', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / ABATS / VABABA002', 5, '2026-05-12', 8, 39, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(172, 'Têtes de Moutons', 'VABABA003', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / C / VIANDES ABATS / ABATS / VABABA003', 5, '2026-06-12', 8, 39, 2, 3, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(173, 'Langues de Veau', 'VABABA004', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VIANDES ABATS / ABATS / VABABA004', 5, '2026-07-12', 8, 39, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(174, 'Poulet des Abatoirs vidé plumé et sans jabot', 'VOEVOL001', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL001', 5, '2026-08-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(175, 'Poulet des Abatoirs beldit', 'VOEVOL002', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL002', 5, '2026-09-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(176, 'Blanc de Poulet', 'VOEVOL003', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL003', 5, '2026-10-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(177, 'Cuisse de Poulet', 'VOEVOL004', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL004', 5, '2026-11-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(178, 'Coquelets', 'VOEVOL005', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL005', 5, '2026-12-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(179, 'Pigeons', 'VOEVOL006', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL006', 5, '2027-01-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(180, 'Lapins', 'VOEVOL007', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL007', 5, '2027-02-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(181, 'FILET DE DINDE', 'VOEVOL008', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL008', 5, '2027-03-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(182, 'JAMBON DE DINDE FUME', 'VOEVOL009', 15.00, NULL, 'CHAMBRE FROIDE  NEGATIVE / D / VOLAILLES ET ŒUFS / VOLAILLES / VOEVOL009', 5, '2027-04-12', 9, 40, 2, 4, 8, 1, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(183, 'Œufs de 60 grs', 'VOEOEU001', 2.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / VOLAILLES ET ŒUFS / ŒUFS / VOEOEU001', 5, '2027-05-12', 9, 41, 3, 8, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(184, 'Œufs beldi de 60 grs', 'VOEOEU002', 3.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / VOLAILLES ET ŒUFS / ŒUFS / VOEOEU002', 5, '2027-06-12', 9, 41, 3, 8, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(185, 'Œufs de Caille', 'VOEOEU003', 6.00, NULL, 'CHAMBRE FROIDE POSITIVE / D / VOLAILLES ET ŒUFS / ŒUFS / VOEOEU003', 5, '2027-07-12', 9, 41, 3, 8, 8, 6, 1, NULL, NULL, 1.00, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(186, 'VINDE HAhh', 'mataud004', 150.00, NULL, 'CHAMBRE FROIDE POSITIVE / A / MATERIEL / AUDIOVISUEL / mataud004', 10, '2025-11-09', 1, 6, 3, 5, 2, 1, 1, 'NON ALIMENTAIRE', NULL, 1.00, '2025-10-27 09:26:01', '2025-10-29 10:40:52', '2025-10-29 10:40:52'),
(187, 'test', 'mataud004', 20.00, NULL, 'RESERVE FOUNITURE SCOLAIRE / A2 / MATERIEL / AUDIOVISUEL / mataud004', 5, '2025-10-30', 1, 6, 1, 31, 2, 1, 1, 'NON ALIMENTAIRE', NULL, 1.00, '2025-10-29 15:39:46', '2025-10-29 15:39:46', NULL),
(188, 'at', 'matbur005', 20.00, NULL, 'RESERVE FOUNITURE SCOLAIRE / A2 / MATERIEL / BUREAU / matbur005', 6, '2025-10-31', 1, 4, 1, 31, 2, 1, 1, 'NON ALIMENTAIRE', NULL, 1.00, '2025-10-30 13:56:04', '2025-10-30 13:56:10', '2025-10-30 13:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `rayons`
--

CREATE TABLE `rayons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `id_local` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rayons`
--

INSERT INTO `rayons` (`id`, `name`, `iduser`, `id_local`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'A', 1, 2, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(2, 'B', 1, 2, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(3, 'C', 1, 2, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(4, 'D', 1, 2, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(5, 'A', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(6, 'B', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(7, 'C', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(8, 'D', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(9, 'E', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(10, 'F', 1, 3, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(11, 'A', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(12, 'B', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(13, 'C', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(14, 'D', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(15, 'E', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(16, 'F', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(17, 'G', 1, 4, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(18, 'A', 1, 5, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(19, 'B', 1, 5, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(20, 'C', 1, 5, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(21, 'D', 1, 5, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(22, 'A1', 1, 6, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(23, 'A2', 1, 6, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(24, 'B1', 1, 6, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(25, 'B2', 1, 6, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(26, 'B3', 1, 6, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(27, 'C', 1, 7, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(28, 'A1', 1, 8, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(29, 'A2', 1, 8, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(30, 'A1', 1, 1, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(31, 'A2', 1, 1, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(32, 'B1', 1, 1, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(33, 'B2', 1, 1, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(34, 'B3', 1, 1, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(35, 'A', 1, 9, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(36, 'B', 1, 9, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(37, 'D', 1, 10, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(38, 'A1', 1, 11, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(39, 'A2', 1, 11, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL),
(40, 'A', 1, 12, '2025-10-27 07:56:29', '2025-10-27 07:56:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27'),
(2, 'Économe', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27'),
(3, 'Magasinier', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27'),
(4, 'Formateur', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27'),
(5, 'Utilisateur', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27'),
(6, 'Gestionnaire', 'web', '2025-10-27 07:54:27', '2025-10-27 07:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(11, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(18, 2),
(19, 2),
(21, 2),
(22, 2),
(23, 2),
(25, 2),
(26, 2),
(27, 2),
(29, 2),
(30, 2),
(31, 2),
(33, 2),
(34, 2),
(35, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(58, 2),
(59, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(1, 3),
(9, 3),
(13, 3),
(17, 3),
(21, 3),
(25, 3),
(29, 3),
(33, 3),
(34, 3),
(35, 3),
(37, 3),
(39, 3),
(40, 3),
(1, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
(53, 4),
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
(59, 4),
(60, 4),
(61, 4),
(66, 4),
(1, 5),
(33, 5),
(34, 5),
(37, 5),
(39, 5),
(1, 6),
(13, 6),
(17, 6),
(21, 6),
(25, 6),
(33, 6),
(34, 6),
(37, 6),
(39, 6),
(56, 6);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0YsAAFd2g53vGXvqHWSBEmy3ql6SJgdkdkFQw88x', 1, '160.179.167.231', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibGJ1VG1YRWpNMlNKenZ5VHM2MnZma1hNWnBNQmkxRExSeUxMSW8yNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2NvbnN1bXB0aW9uIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjE4MzIyNDI7fX0=', 1761836796),
('1tgrJcm5y5k763yIfhzOn9hG4jHg7llumYPLgdlC', 1, '160.90.37.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSjRWMGxmbEd2NGVUU2VGcWVrVk1vYVFVMlcwTjExaFdKWFZQS0I2QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL3Byb2R1Y3RzIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjE4MjI1MDg7fX0=', 1761837583),
('5B0seP9FURyuJmq6AKbbdh0pHSPhOKDk0LtfdX6U', NULL, '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid3FYYkhOMlNqNjlHbUxxSWNGN2U5SlJsc20yYVg0ZzIwZ29OZG9idiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cHM6Ly9yZWQtZ3VhbmFjby05MzU3MzUuaG9zdGluZ2Vyc2l0ZS5jb20vUm91dGVyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761908065),
('afOT4UVp9xZxIUG0rs3gilDrCiw3Ut3wxLofqHFP', NULL, '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNHB5aGVvU2lEUW9Sb1FvVWdwVHBQNUpzOGJuTGk5czhhcDNTRlB4YSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9fQ==', 1761923353),
('aNeTb9eFQZBmru01ZvIf4vMLHWObCn1bDrISG4b6', NULL, '212.40.1.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicVhzbnZmNGkxN3lOU29sa3NSeUdCQTc5WDlkaWV4NGRDSlE2WEpRRCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0OToiaHR0cHM6Ly9yZWQtZ3VhbmFjby05MzU3MzUuaG9zdGluZ2Vyc2l0ZS5jb20vaG9tZSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUwOiJodHRwczovL3JlZC1ndWFuYWNvLTkzNTczNS5ob3N0aW5nZXJzaXRlLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761862864),
('arBz7vtsUe0f4rBq3IuzkMMWKhvGzLFKbB3PD3bB', 1, '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVU5RR3h4S0h3Y0NwRVJDWDl4b2F0N3p4VjFzcVIwYXBiNlAwMWtUUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL1Nob3dCb25WZW50ZS9EeCI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc2MTg2NjU2NTt9fQ==', 1761867699),
('BazjDIb9HJr9PwggB4vurqmuteJCfnf4lNuawLhw', NULL, '54.39.182.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidkJ2a3BMRzdZU2lNSWlkUE5oSk1qb3h4QXRHT292V0RxOFUzUTdLMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761925580),
('bzVsVUkyBpcIHbfoqkMyAovJeVc8C0pxuMtQeDOC', 1, '87.58.95.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV1pvQ2tuNmE1eTk0WWxoMHFXVVZOckh1MURJM20yd1hmQjNqTGl5biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTM6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL3Byb2R1Y3RzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NjE4NjI4NjA7fX0=', 1761863018),
('cxIWCwj4pMGBstQLHw2NNIKM9NPSAUmwqLphcQU6', NULL, '212.40.1.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkRUOGpsclRINnJKOE5kaTFHanpOMHRPOHFpUjVicVdQc1pwZnRuWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761862859),
('ftn6xQRxlgZMQB9IDJQ2vggMd0dt2qmTlHOU8Gl7', NULL, '212.40.1.6', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmtWcldDSVpPaVJsS29CNlpLNDlqR04zRmxSY1M0bEE5bFRNd0w4byI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761862858),
('GgvyDQV3sHZVrirFvRmU5llNv2uInP0OeiR8RBk8', 1, '105.74.6.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoia3E1eDV5SGFKSXJVVmZwczZCcGphTmV4ajVTbXdwa09pNEI1anZFNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2Zvcm1hdGV1ci1zdG9jayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzYxODY4MDg5O319', 1761868128),
('Otg5Y2CNjMGjrAKAiRhPjJQZ8X12oEXFvjhptofx', NULL, '54.39.190.134', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic2hveDJhc3k4TGlJSlBxSjRmNVhOSFJONmtyS3lwMXRBWTdIamgzcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761925575),
('qyZXniGcUsyCG06ZMmSWfprYMtOgsP7LCRBqMBle', 2, '154.144.252.90', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiR2thY29ZQjFVMk5YMHBETXVoNkYwc0drek9wSWRpYzZ0OGo2aUhZZyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUwOiJodHRwczovL3JlZC1ndWFuYWNvLTkzNTczNS5ob3N0aW5nZXJzaXRlLmNvbS9zdG9jayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czo0OiJhdXRoIjthOjE6e3M6MjE6InBhc3N3b3JkX2NvbmZpcm1lZF9hdCI7aToxNzYxODI5NDA5O319', 1761836065),
('WqYpTFLxEyiE7uxu2aCVxzTJkQUKCwjMESNnDDM9', 1, '2a09:bac3:4828:191::28:10a', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0.1 Mobile/15E148 Safari/604.1', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRWthMWJVSzNUSUVxNWtBNThjMTNYTWkwZ2piYm53eWVrUXpKUEIwRyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL0NvbW1hbmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTc2MTkxMTk5NDt9fQ==', 1761915715),
('x1SSBfUspUzVjZiLCBrioO5hwJ1SW1BNrUuc3sov', NULL, '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2dqeVF2c3p4QTNTYTNURmREWFJSanZydnhWNjQyZkZVSlowWVVGMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761847008),
('xRI0127sRCOMDp3bejderxtayweEje9lezYY8gE4', NULL, '41.143.204.177', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNUMwaThmMVgwWmhKR0FJeFBGcVRSdEdsMVhSU3prb2liQ3V6NkdLNiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1MToiaHR0cHM6Ly9yZWQtZ3VhbmFjby05MzU3MzUuaG9zdGluZ2Vyc2l0ZS5jb20vUm91dGVyIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTA6Imh0dHBzOi8vcmVkLWd1YW5hY28tOTM1NzM1Lmhvc3RpbmdlcnNpdGUuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1761852536);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `id_tva` bigint(20) UNSIGNED DEFAULT NULL,
  `id_unite` bigint(20) UNSIGNED DEFAULT NULL,
  `quantite` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `id_product`, `id_tva`, `id_unite`, `quantite`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(2, 2, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(3, 3, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(4, 4, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(5, 5, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(6, 6, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 09:37:57', NULL),
(7, 7, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(8, 8, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(9, 9, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(10, 10, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(11, 11, 8, 6, 0, '2025-10-27 07:57:16', '2025-10-27 15:47:08', NULL),
(12, 12, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(13, 13, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(14, 14, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(15, 15, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(16, 16, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(17, 17, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(18, 18, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(19, 19, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(20, 20, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(21, 21, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(22, 22, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(23, 23, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(24, 24, 8, 6, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(25, 25, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(26, 26, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(27, 27, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(28, 28, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(29, 29, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(30, 30, 1, 1, 100, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(31, 31, 1, 1, 50, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(32, 32, 1, 6, 20, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(33, 33, 1, 7, 20, '2025-10-27 07:57:16', '2025-10-27 07:57:16', NULL),
(34, 34, 1, 7, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(35, 35, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(36, 36, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(37, 37, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(38, 38, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(39, 39, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(40, 40, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(41, 41, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(42, 42, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(43, 43, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(44, 44, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(45, 45, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(46, 46, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(47, 47, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(48, 48, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(49, 49, 8, 8, 40, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(50, 50, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(51, 51, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(52, 52, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(53, 53, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(54, 54, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(55, 55, 5, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(56, 56, 5, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(57, 57, 5, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(58, 58, 5, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(59, 59, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(60, 60, 5, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(61, 61, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(62, 62, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(63, 63, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(64, 64, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(65, 65, 1, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(66, 66, 5, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(67, 67, 5, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(68, 68, 5, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(69, 69, 8, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(70, 70, 1, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(71, 71, 5, 1, 20, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(72, 72, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(73, 73, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(74, 74, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(75, 75, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(76, 76, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(77, 77, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(78, 78, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(79, 79, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(80, 80, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(81, 81, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(82, 82, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(83, 83, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(84, 84, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(85, 85, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(86, 86, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(87, 87, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(88, 88, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(89, 89, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(90, 90, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(91, 91, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(92, 92, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(93, 93, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(94, 94, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(95, 95, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(96, 96, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(97, 97, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(98, 98, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(99, 99, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(100, 100, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(101, 101, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(102, 102, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(103, 103, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(104, 104, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(105, 105, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(106, 106, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(107, 107, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(108, 108, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(109, 109, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(110, 110, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(111, 111, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(112, 112, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(113, 113, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(114, 114, 8, 1, 40, '2025-10-27 07:57:17', '2025-10-30 11:07:45', NULL),
(115, 115, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(116, 116, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(117, 117, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(118, 118, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(119, 119, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(120, 120, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(121, 121, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(122, 122, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(123, 123, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(124, 124, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(125, 125, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(126, 126, 8, 1, 35, '2025-10-27 07:57:17', '2025-10-30 11:07:45', NULL),
(127, 127, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(128, 128, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(129, 129, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(130, 130, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(131, 131, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(132, 132, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(133, 133, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(134, 134, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(135, 135, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(136, 136, 8, 1, 10, '2025-10-27 07:57:17', '2025-10-27 08:44:09', NULL),
(137, 137, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(138, 138, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(139, 139, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(140, 140, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(141, 141, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(142, 142, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(143, 143, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(144, 144, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(145, 145, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(146, 146, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(147, 147, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(148, 148, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(149, 149, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(150, 150, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(151, 151, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(152, 152, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(153, 153, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(154, 154, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(155, 155, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(156, 156, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(157, 157, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(158, 158, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(159, 159, 8, 1, 41, '2025-10-27 07:57:17', '2025-10-27 09:58:20', NULL),
(160, 160, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(161, 161, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(162, 162, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(163, 163, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(164, 164, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(165, 165, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(166, 166, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(167, 167, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(168, 168, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(169, 169, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(170, 170, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(171, 171, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(172, 172, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(173, 173, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(174, 174, 8, 1, 40, '2025-10-27 07:57:17', '2025-10-27 08:40:10', NULL),
(175, 175, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(176, 176, 8, 1, 12, '2025-10-27 07:57:17', '2025-10-30 11:07:45', NULL),
(177, 177, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(178, 178, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(179, 179, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(180, 180, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(181, 181, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(182, 182, 8, 1, 50, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(183, 183, 8, 6, 300, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(184, 184, 8, 6, 300, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(185, 185, 8, 6, 270, '2025-10-27 07:57:17', '2025-10-27 07:57:17', NULL),
(186, 186, 2, 1, 100, '2025-10-27 09:26:01', '2025-10-29 10:40:52', '2025-10-29 10:40:52'),
(187, 187, 2, 1, 20, '2025-10-29 15:39:46', '2025-10-29 15:39:46', NULL),
(188, 188, 2, 1, 10, '2025-10-30 13:56:04', '2025-10-30 13:56:10', '2025-10-30 13:56:10');

-- --------------------------------------------------------

--
-- Table structure for table `stocktransfer`
--

CREATE TABLE `stocktransfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `status` enum('Création','Validation','Refus') NOT NULL DEFAULT 'Création',
  `from` int(10) UNSIGNED DEFAULT NULL,
  `refusal_reason` text DEFAULT NULL,
  `to` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocktransfer`
--

INSERT INTO `stocktransfer` (`id`, `id_user`, `status`, `from`, `refusal_reason`, `to`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Validation', NULL, NULL, 1, '2025-10-27 09:37:53', '2025-10-27 09:37:57', NULL),
(2, 1, 'Refus', 1, 'test', 3, '2025-10-27 09:41:34', '2025-10-27 09:42:33', '2025-10-27 09:42:33'),
(3, 1, 'Validation', NULL, NULL, 1, '2025-10-27 09:57:59', '2025-10-27 09:58:20', NULL),
(4, 1, 'Validation', 1, NULL, 2, '2025-10-27 10:02:21', '2025-10-27 10:02:30', NULL),
(5, 21, 'Validation', 21, NULL, 10, '2025-10-27 13:23:29', '2025-10-27 13:24:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_categorie` bigint(20) UNSIGNED NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `name`, `id_categorie`, `iduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CUISINE', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(2, 'RESTAURATION', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(3, 'SCOLAIRE', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(4, 'BUREAU', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(5, 'INFORMATIQUE', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(6, 'AUDIOVISUEL', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(7, 'JARDINAGE', 1, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(8, 'CUISINE', 2, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(9, 'RESTAURATION', 2, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(10, 'PLOMBERIE ET QUINCAILLERIE', 2, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(11, 'BUREAU', 3, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(12, 'METALLIQUE', 3, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(13, 'SCOLAIRE', 3, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(14, 'RESTAURATION', 3, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(15, 'SCOLAIRE', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(16, 'BUREAU', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(17, 'INFORMATIQUE', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(18, 'ELECTRIQUE', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(19, 'PAPITERIE', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(20, 'NETTOYAGE', 4, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(21, 'BOULANGERIE', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(22, 'CONSERVES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(23, 'LEGUMES SECS (LEGUMINEUSES)', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(24, 'PATTE & FARINEUSE', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(25, 'EPICES MAROCAINES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(26, 'CONDIMENTS', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(27, 'HERBES AROMATIQUES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(28, 'PRODUITS LAITIERS', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(29, 'BOISSONS', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(30, 'CHOCOLATERIES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(31, 'FRUITS SECS', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(32, 'GLUCIDES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(33, 'MOLECULES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(34, 'HUILES', 5, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(35, 'LEGUMES', 6, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(36, 'FRUITS', 6, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(37, 'POISSON FRAIS', 7, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(38, 'VIANDES', 8, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(39, 'ABATS', 8, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(40, 'VOLAILLES', 9, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL),
(41, 'ŒUFS', 9, 1, '2025-10-27 07:56:57', '2025-10-27 07:56:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `temp_achat`
--

CREATE TABLE `temp_achat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `idproduit` bigint(20) UNSIGNED NOT NULL,
  `id_fournisseur` bigint(20) UNSIGNED DEFAULT NULL,
  `qte` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_vente`
--

CREATE TABLE `temp_vente` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `idproduit` bigint(20) UNSIGNED NOT NULL,
  `id_client` bigint(20) UNSIGNED DEFAULT NULL,
  `id_formateur` bigint(20) UNSIGNED DEFAULT NULL,
  `qte` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmpstocktransfer`
--

CREATE TABLE `tmpstocktransfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `quantite_stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `quantite_transfer` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `from` bigint(20) UNSIGNED NOT NULL,
  `to` bigint(20) UNSIGNED NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `idcommande` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tvas`
--

CREATE TABLE `tvas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` decimal(5,2) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tvas`
--

INSERT INTO `tvas` (`id`, `name`, `value`, `iduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'TVA 1', 0.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(2, 'TVA 2', 7.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(3, 'TVA 3', 8.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(4, 'TVA 4', 9.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(5, 'TVA  5', 10.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(6, 'TVA  6', 14.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(7, 'TVA 7', 18.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL),
(8, 'TVA 8', 20.00, 1, '2025-10-27 07:55:23', '2025-10-27 07:55:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `unite`
--

CREATE TABLE `unite` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `iduser` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unite`
--

INSERT INTO `unite` (`id`, `name`, `iduser`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kg', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(2, 'Gramme', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(3, 'L', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(4, 'T', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(5, 'Mètre', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(6, 'Unité', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(7, 'Paquet', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(8, 'Boite', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL),
(9, 'Bouteille', 1, '2025-10-27 07:55:47', '2025-10-27 07:55:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `matricule` varchar(255) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fonction` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `signature` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `matricule`, `nom`, `prenom`, `email`, `email_verified_at`, `password`, `telephone`, `fonction`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `signature`) VALUES
(1, NULL, 'ANIA', 'Karima', 'kania@tourisme.gov.ma', NULL, '$2y$12$I7nnp.HN.E8mg7D9UB10FupZq.OPrIxoDfd98NFbCiviE0E0j6SSW', NULL, NULL, NULL, '2025-10-27 07:54:27', '2025-10-27 07:54:27', NULL, NULL),
(2, NULL, 'EMRAN', 'Chaimae', 'cemran@tourisme.gov.ma', NULL, '$2y$12$/gFwcvWV8oHAMOuU/xhHbe0mDYnIMiBfaSI0JGB1PzXql4Zyo.Lka', NULL, NULL, NULL, '2025-10-27 07:54:27', '2025-10-27 10:38:15', NULL, NULL),
(3, NULL, 'BOUNIF', 'Jaouad', 'jbounif@tourisme.gov.ma', NULL, '$2y$12$fPus5Gufo6bteAYEXvAU.eMRPpK8QRKrgeDYVq2OP1niKdBFd35K6', NULL, NULL, NULL, '2025-10-27 07:54:27', '2025-10-27 10:41:11', NULL, NULL),
(4, NULL, 'SAIED', 'Leila', 'leilasajed17@gmail.com', NULL, '$2y$12$BPPdltBlmSYV6JYRyclxvOnfoeOMgErLebNEWgrqdnw7S2/DaPYqm', NULL, NULL, NULL, '2025-10-27 07:54:28', '2025-10-27 07:54:28', NULL, NULL),
(5, NULL, 'CABOUR', 'Fatima', 'fatimacabour@gmail.com', NULL, '$2y$12$p1FEnQAnwBwaD5Ph2nuSOeJKxDMXA5oY/iIlm7p3q9TYjoJb2WI7G', '0666494924', 'Chargée des stages', NULL, '2025-10-27 07:54:28', '2025-10-27 15:20:00', NULL, NULL),
(6, NULL, 'MRIDA', 'Chafia', 'cmrida@tourisme.gov.ma', NULL, '$2y$12$AG0AVRkizBTloA1OCiDLG..rjJl0qR5vZDWsy84svFcv6yhvdGObe', NULL, NULL, NULL, '2025-10-27 07:54:28', '2025-10-27 07:54:28', NULL, NULL),
(7, NULL, 'ATTI', 'Sellamia', 'satti@tourisme.gov.ma', NULL, '$2y$12$5daHppyWC2lzfZfN2GLvVemJevcWJ8paaQGEBDSEcJp8ry2VSByjy', NULL, 'surveillate génerale', NULL, '2025-10-27 07:54:28', '2025-10-27 15:22:03', NULL, NULL),
(8, NULL, 'NASRI', 'Souad', 'snasri@tourisme.gov.ma', NULL, '$2y$12$yJcHGA0bQGluIdMbCs9cWONYphI85xeUCy1a6qigqQvTgLWAsXXQK', NULL, NULL, NULL, '2025-10-27 07:54:28', '2025-10-27 07:54:28', NULL, NULL),
(9, NULL, 'AFRI', 'Fatima', 'fafri@tourisme.gov.ma', NULL, '$2y$12$c4n0LHBY3mfxZdtQFphD3.Mt0qM3QDXKrMHWfStRMMo6W8gO0Z9xe', NULL, NULL, NULL, '2025-10-27 07:54:29', '2025-10-27 07:54:29', NULL, NULL),
(10, NULL, 'LABAAJ', 'Mustapha', 'mlabaaj@tourisme.gov.ma', NULL, '$2y$12$NaRDAbQJPMpZI4Q2kkgJlu.X4sAtIoB.amSwo5QnPsnSZIr4DhJPW', NULL, NULL, NULL, '2025-10-27 07:54:29', '2025-10-27 10:39:52', NULL, NULL),
(11, NULL, 'JADDOUR', 'Hassan', 'hjaddour@tourisme.gov.ma', NULL, '$2y$12$CFb/cPdgJLBshEYFK8MuqeJRakvtGWDFAc7AzrIoQMbBscgUc3f3S', NULL, NULL, NULL, '2025-10-27 07:54:29', '2025-10-27 07:54:29', NULL, NULL),
(12, NULL, 'AIT BELLA', 'Mohammed', 'maitbella@tourisme.gov.ma', NULL, '$2y$12$dYlNdiaXOLRvsSvhD46YdubG3CvGNTOdtqx9BhIRdUN8kvp9nYnw.', NULL, NULL, NULL, '2025-10-27 07:54:29', '2025-10-27 07:54:29', NULL, NULL),
(13, NULL, 'JID', 'Hicham', 'hjid@tourisme.gov.ma', NULL, '$2y$12$YWDjtGWvwwfGdF/TaHTx0eDP0E0LJx62UuSsoDLXYdQtHMuq/UM4e', NULL, NULL, NULL, '2025-10-27 07:54:29', '2025-10-27 07:59:28', '2025-10-27 07:59:28', NULL),
(14, NULL, 'BELBACHA', 'Amina', 'abelbacha@tourisme.gov.ma', NULL, '$2y$12$/0A7LrinPe87Txb4UA9/UOVXAsTBw7o19a6uqeW8c8LDFEudmQKj.', NULL, NULL, NULL, '2025-10-27 07:54:30', '2025-10-27 07:54:30', NULL, NULL),
(15, NULL, 'OUAOQA', 'Malika', 'mouaoqa@tourisme.gov.ma', NULL, '$2y$12$kbQ7yExB.K/wwS0GIbRpRuLWvF0of8N1pM0p3NJKvjdqwctMEg8Sm', NULL, NULL, NULL, '2025-10-27 07:54:30', '2025-10-27 07:54:30', NULL, NULL),
(16, NULL, 'BAGHDI', 'Mina', 'mbeghdi70@gmail.com', NULL, '$2y$12$JMk3M4ejE2TTHdx7bb9u..4/ymNXpC6CZ74sbj49chs49uf2UAv0O', '0654572527', 'Formatrice', NULL, '2025-10-27 07:54:30', '2025-10-27 15:24:11', NULL, NULL),
(17, NULL, 'AISSAOUI', 'Khadija', 'aissaouikhadija18@gmail.com', NULL, '$2y$12$3cvrhlYt9lKG8ZKzMxXrsOdbtpj3FJMlOEHOHAxHFw/Jwc5gIsC7u', '0666494935', 'Formatrice', NULL, '2025-10-27 07:54:30', '2025-10-27 15:26:20', NULL, NULL),
(18, NULL, 'SOUHADI', 'Bassma', 'souhadibassmaa@gmail.com', NULL, '$2y$12$aZhd.eTyc/iRFKuzOPD0U.Nx7SMhlI8itzguClmHGPCeaTsRT7Gia', NULL, NULL, NULL, '2025-10-27 07:54:30', '2025-10-27 15:30:03', NULL, NULL),
(19, NULL, 'ATTIF', 'Ihssane', 'ihssaneattif@gmail.com', NULL, '$2y$12$BZF9ttJmuhnOSVa2aMQJ0.YmsaD5Kz.eEcWoi507LRdA5wRF1gqRS', NULL, NULL, NULL, '2025-10-27 07:54:31', '2025-10-27 15:35:14', NULL, NULL),
(20, NULL, 'HAIMOUDI', 'Fatiha', 'fatihahaimoudi@gmail.com', NULL, '$2y$12$XdW63.WFwFNQtwkmVnCrWuek0QnIHt0ymSQRooxAi9WAIlxzzwzp6', '0666494975', 'Formatrice', NULL, '2025-10-27 07:54:31', '2025-10-27 15:43:47', NULL, NULL),
(21, NULL, 'ZIANI', 'Soufian', 'sziani40@gmail.com', NULL, '$2y$12$g3PBEQrEcmVEYhVSVp16QuMxWzpoiM/b6vuhvQwJqKUtxP1n4gh8K', NULL, NULL, NULL, '2025-10-27 07:54:31', '2025-10-27 13:14:23', NULL, NULL),
(22, NULL, 'SEHOUL', 'Jamal', 'jamal.sehoul@gmail.com', NULL, '$2y$12$39GBJzmhEorh3uskdPPule28DNGPnfaJdrvjBLjKWaVrxRkom/vDG', NULL, NULL, NULL, '2025-10-27 07:54:31', '2025-10-27 15:34:06', NULL, NULL),
(23, NULL, 'EL AZMI', 'Fatima', 'felazmi@tourisme.gov.ma', NULL, '$2y$12$TuIU.dQimt8xi39URIFmv.6i6K9FjUleVIRhAWw99dPMeONhEA2vS', NULL, NULL, NULL, '2025-10-27 07:54:31', '2025-10-27 07:54:31', NULL, NULL),
(24, NULL, 'FOUGNAR', 'Zineb', 'zfougnar@tourisme.gov.ma', NULL, '$2y$12$3/MHgZjoUJPJgVelefsveOIhExXVdCqoIRfZoks4QjwQNqDFlRjJO', NULL, NULL, NULL, '2025-10-27 07:54:32', '2025-10-27 07:54:32', NULL, NULL),
(25, NULL, 'MOUFKI', 'Sara', 'smoufki@tourisme.gov.ma', NULL, '$2y$12$CJ2fRgcouIioSP4qI8hZ9.9wEsDI74YAu89d8QHSuT0LpluGy8bJO', NULL, NULL, NULL, '2025-10-27 07:54:32', '2025-10-27 07:54:32', NULL, NULL),
(26, NULL, 'ABADA', 'Hajar', 'habada@tourisme.gov.ma', NULL, '$2y$12$E0naI6A9bIqXGADjsY2UO./bMZcps5i7hX07OwX/3ZSlZZCf38PVC', NULL, NULL, NULL, '2025-10-27 07:54:32', '2025-10-27 08:00:39', '2025-10-27 08:00:39', NULL),
(27, NULL, 'OUMADDOUCH', 'El Houssaine', 'eoumaddouch@tourisme.gov.ma', NULL, '$2y$12$Lq.ISpaTyZzGu0uXzaQH1.pbgIkz8.eTEmNusBfBhmQnm0yT7MSii', NULL, NULL, NULL, '2025-10-27 07:54:32', '2025-10-27 07:54:32', NULL, NULL),
(28, NULL, 'DRIOUCH', 'Hassan', 'hdriouch@tourisme.gov.ma', NULL, '$2y$12$zSVDxo7HpKDOxuLs21hz0eRk82gZIxLdvVsERmyiF5z.uzvt9.rTW', NULL, NULL, NULL, '2025-10-27 07:54:32', '2025-10-27 07:54:32', NULL, NULL),
(29, NULL, 'Oberbrunner', 'Sandra', 'abbott.simeon@example.net', '2025-10-27 07:54:32', '$2y$12$HAlGBJ.IlgakfhPxbQtc5.SCwPzHoCLEOjXHbXAXghtVKspkWY/mq', NULL, NULL, 'ORBG1Onb8s', '2025-10-27 07:54:33', '2025-10-27 10:36:06', '2025-10-27 10:36:06', NULL),
(30, NULL, 'Fisher', 'Alivia', 'johns.tobin@example.org', '2025-10-27 07:54:33', '$2y$12$HAlGBJ.IlgakfhPxbQtc5.SCwPzHoCLEOjXHbXAXghtVKspkWY/mq', NULL, NULL, '9TlWWNKElZ', '2025-10-27 07:54:33', '2025-10-27 10:36:34', '2025-10-27 10:36:34', NULL),
(31, NULL, 'O\'Conner', 'Meda', 'labadie.name@example.org', '2025-10-27 07:54:33', '$2y$12$HAlGBJ.IlgakfhPxbQtc5.SCwPzHoCLEOjXHbXAXghtVKspkWY/mq', NULL, NULL, 'Bl4Wmm6L2Q', '2025-10-27 07:54:33', '2025-10-27 10:36:28', '2025-10-27 10:36:28', NULL),
(32, NULL, 'Collier', 'Lonnie', 'qrowe@example.com', '2025-10-27 07:54:33', '$2y$12$HAlGBJ.IlgakfhPxbQtc5.SCwPzHoCLEOjXHbXAXghtVKspkWY/mq', NULL, NULL, 'hb2r7cHaFP', '2025-10-27 07:54:33', '2025-10-27 10:36:21', '2025-10-27 10:36:21', NULL),
(33, NULL, 'Dicki', 'Olga', 'birdie.reilly@example.com', '2025-10-27 07:54:33', '$2y$12$HAlGBJ.IlgakfhPxbQtc5.SCwPzHoCLEOjXHbXAXghtVKspkWY/mq', NULL, NULL, 'PhqoZkFDGA', '2025-10-27 07:54:33', '2025-10-27 10:36:17', '2025-10-27 10:36:17', NULL),
(34, 'app', 'app', 'app', 'cham@gmail.com', NULL, '$2y$12$K65gGGmPdN7CylIcF8hTLuHQfmGrvn9hio2Kv1sScHVnFxDr6zn7K', '1234567', 'aiki', NULL, '2025-10-27 09:46:23', '2025-10-27 09:46:46', '2025-10-27 09:46:46', 'images/signatures/signature_34_1761558383.png'),
(35, 'appt', 'khachane', 'chaimae', 'chaimae@gmail.com', NULL, '$2y$12$E/EBHl7jz9t2kjeRQzhMIuJSSjRr5FvqLlotuETkmLk9MNfzMLv9W', '123456', 'asdf', NULL, '2025-10-27 09:47:34', '2025-10-27 10:35:56', '2025-10-27 10:35:56', 'images/signatures/signature_35_1761558454.png'),
(36, NULL, 'onahi', 'fatima', 'fatima@gmail.com', NULL, '$2y$12$Jinuy4J/UmTHLl1.UBvIaecJxVa0Jz5mOO00i0Qd2.sNOkyEi8TDW', '123456', NULL, NULL, '2025-10-27 09:50:21', '2025-10-27 10:36:13', '2025-10-27 10:36:13', 'images/signatures/signature_36_1761558621.png'),
(37, NULL, 'merabte', 'said', 'said@gmail.com', NULL, '$2y$12$6LQoCemyKh/UMbVK5g9e0eYQtfYVEwFXj5KLto8Qc2D1NHQ73mmka', NULL, NULL, NULL, '2025-10-27 09:51:45', '2025-10-27 10:36:09', '2025-10-27 10:36:09', 'images/signatures/signature_37_1761558705.png'),
(38, NULL, 'asdfg', 'sdfgh', 'app@gmail.com', NULL, '$2y$12$tZekxVuKH3h4T1wBsm3eX.I5vOGmw/Or4gouTlZcOrpZTeMjOPBAa', NULL, 'zxcvb', NULL, '2025-10-27 10:10:24', '2025-10-27 10:10:29', '2025-10-27 10:10:29', 'images/signatures/signature_38_1761559824.png'),
(39, NULL, 'BELLAMLIH', 'ABDELAZIZ', 'abdelaziz.bellemlih@outlook.com', NULL, '$2y$12$qh2q9biUI4jiqMpK8Sb24.3wNM71aZU8gClcgnjNaoY1NiOUPlCP6', '661461671', 'Formateur', NULL, '2025-10-27 16:03:38', '2025-10-27 16:04:26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ventes`
--

CREATE TABLE `ventes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('Création','Validation','Refus','Livraison','Réception','Visé') NOT NULL DEFAULT 'Création',
  `type_commande` enum('Alimentaire','Non Alimentaire','Fournitures et matériels') NOT NULL DEFAULT 'Alimentaire',
  `type_menu` enum('Menu eleves','Menu specials','Menu d''application') DEFAULT NULL,
  `id_client` bigint(20) UNSIGNED DEFAULT NULL,
  `id_formateur` bigint(20) UNSIGNED NOT NULL,
  `is_transfer` tinyint(1) NOT NULL DEFAULT 0,
  `eleves` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `personnel` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `invites` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `divers` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `entree` varchar(255) DEFAULT NULL,
  `plat_principal` varchar(255) DEFAULT NULL,
  `accompagnement` varchar(255) DEFAULT NULL,
  `dessert` varchar(255) DEFAULT NULL,
  `date_usage` date DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ventes`
--

INSERT INTO `ventes` (`id`, `total`, `status`, `type_commande`, `type_menu`, `id_client`, `id_formateur`, `is_transfer`, `eleves`, `personnel`, `invites`, `divers`, `entree`, `plat_principal`, `accompagnement`, `dessert`, `date_usage`, `id_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 156.00, 'Validation', 'Non Alimentaire', NULL, NULL, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-10-27 08:04:25', '2025-10-27 08:04:42', NULL),
(2, 150.00, 'Validation', 'Alimentaire', 'Menu eleves', NULL, 1, 0, 20, 10, 0, 0, NULL, NULL, NULL, NULL, '2025-10-30', 1, '2025-10-27 08:34:26', '2025-10-27 08:34:46', NULL),
(3, 150.00, 'Validation', 'Alimentaire', 'Menu d\'application', NULL, 1, 0, 20, 10, 10, 10, NULL, NULL, NULL, NULL, '2025-10-31', 1, '2025-10-27 08:39:57', '2025-10-27 08:40:10', NULL),
(4, 600.00, 'Validation', 'Alimentaire', 'Menu specials', NULL, 1, 0, 10, 10, 0, 0, 'SALADE', 'TAIJINE', 'RIZ', 'BANANE', '2025-11-01', 1, '2025-10-27 08:43:52', '2025-10-27 08:44:09', NULL),
(5, 850.00, 'Validation', 'Non Alimentaire', NULL, NULL, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-10-27 09:33:58', '2025-10-27 09:34:07', NULL),
(6, 850.00, 'Refus', 'Non Alimentaire', NULL, NULL, 35, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-11-01', 35, '2025-10-27 09:48:24', '2025-10-29 10:39:24', NULL),
(7, 15.00, 'Validation', 'Alimentaire', NULL, NULL, 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 2, '2025-10-27 10:02:30', '2025-10-27 10:02:30', NULL),
(8, 30.00, 'Refus', 'Alimentaire', 'Menu eleves', NULL, 1, 0, 50, 6, 0, 0, 'salade', 'tajine', 'pain', 'jawhara', '2025-10-29', 1, '2025-10-27 11:05:09', '2025-10-29 14:45:01', NULL),
(9, 865.00, 'Réception', 'Non Alimentaire', NULL, NULL, 23, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-10-31', 23, '2025-10-27 11:28:58', '2025-10-27 11:29:27', NULL),
(10, 250.00, 'Visé', 'Non Alimentaire', NULL, NULL, 18, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 18, '2025-10-27 11:48:17', '2025-10-27 11:49:57', NULL),
(11, 15.00, 'Réception', 'Alimentaire', 'Menu eleves', NULL, 1, 0, 45, 5, 0, 0, 'salade', 'tajine', 'pain', 'jawhara', '2025-10-30', 1, '2025-10-27 12:04:44', '2025-10-28 13:21:55', NULL),
(12, 3000.00, 'Validation', 'Alimentaire', 'Menu eleves', NULL, 21, 0, 20, 5, 5, 5, NULL, NULL, NULL, NULL, '2025-11-01', 21, '2025-10-27 13:16:05', '2025-10-27 13:18:01', NULL),
(13, 450.00, 'Validation', 'Alimentaire', NULL, NULL, 10, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 10, '2025-10-27 13:24:09', '2025-10-27 13:24:09', NULL),
(14, 41650.00, 'Validation', 'Non Alimentaire', NULL, NULL, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-10-27 15:47:00', '2025-10-27 15:47:08', NULL),
(15, 43.00, 'Refus', 'Alimentaire', 'Menu specials', NULL, 2, 0, 10, 10, 0, 0, 'salade cesar', 'gigot d\'agneaupain', NULL, 'banane', '2025-10-31', 2, '2025-10-27 21:21:16', '2025-10-30 07:46:50', NULL),
(16, 300.00, 'Validation', 'Alimentaire', 'Menu eleves', NULL, 1, 0, 20, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-11-04', 1, '2025-10-30 09:48:58', '2025-10-30 10:49:28', NULL),
(17, 645.00, 'Validation', 'Alimentaire', 'Menu eleves', NULL, 21, 0, 20, 0, 0, 0, NULL, NULL, NULL, NULL, '2025-11-08', 21, '2025-10-30 11:03:31', '2025-10-30 11:07:45', NULL),
(18, 75.00, 'Création', 'Alimentaire', 'Menu eleves', NULL, 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, '2025-10-31 15:08:06', '2025-10-31 15:08:06', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achats`
--
ALTER TABLE `achats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `achats_id_fournisseur_foreign` (`id_Fournisseur`),
  ADD KEY `achats_id_user_foreign` (`id_user`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_iduser_foreign` (`iduser`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_iduser_foreign` (`iduser`);

--
-- Indexes for table `consumption_product_details`
--
ALTER TABLE `consumption_product_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consumption_product_details_consumption_id_foreign` (`consumption_id`),
  ADD KEY `consumption_product_details_product_id_foreign` (`product_id`),
  ADD KEY `consumption_product_details_ligne_vente_id_foreign` (`ligne_vente_id`),
  ADD KEY `consumption_product_details_ligne_achat_id_foreign` (`ligne_achat_id`);

--
-- Indexes for table `daily_consumption`
--
ALTER TABLE `daily_consumption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `daily_consumption_vente_id_foreign` (`vente_id`),
  ADD KEY `daily_consumption_achat_id_foreign` (`achat_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fournisseurs_iduser_foreign` (`iduser`);

--
-- Indexes for table `hostorique_sig`
--
ALTER TABLE `hostorique_sig`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hostorique_sig_iduser_foreign` (`iduser`),
  ADD KEY `hostorique_sig_idvente_foreign` (`idvente`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventories_product_id_foreign` (`product_id`),
  ADD KEY `inventories_id_achat_foreign` (`id_achat`),
  ADD KEY `inventories_id_vente_foreign` (`id_vente`),
  ADD KEY `inventories_created_by_foreign` (`created_by`);

--
-- Indexes for table `inventory_monthly_summaries`
--
ALTER TABLE `inventory_monthly_summaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_monthly_summaries_product_id_year_month_unique` (`product_id`,`year`,`month`);

--
-- Indexes for table `inventory_yearly_summaries`
--
ALTER TABLE `inventory_yearly_summaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_yearly_summaries_product_id_year_unique` (`product_id`,`year`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ligne_achat`
--
ALTER TABLE `ligne_achat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ligne_achat_id_user_foreign` (`id_user`),
  ADD KEY `ligne_achat_idachat_foreign` (`idachat`),
  ADD KEY `ligne_achat_idproduit_foreign` (`idproduit`);

--
-- Indexes for table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ligne_vente_id_user_foreign` (`id_user`),
  ADD KEY `ligne_vente_idvente_foreign` (`idvente`),
  ADD KEY `ligne_vente_idproduit_foreign` (`idproduit`);

--
-- Indexes for table `line_transfer`
--
ALTER TABLE `line_transfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `line_transfer_id_user_foreign` (`id_user`),
  ADD KEY `line_transfer_id_product_foreign` (`id_product`),
  ADD KEY `line_transfer_id_tva_foreign` (`id_tva`),
  ADD KEY `line_transfer_id_unite_foreign` (`id_unite`),
  ADD KEY `line_transfer_idcommande_foreign` (`idcommande`),
  ADD KEY `line_transfer_id_stocktransfer_foreign` (`id_stocktransfer`);

--
-- Indexes for table `locals`
--
ALTER TABLE `locals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `locals_iduser_foreign` (`iduser`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_id_categorie_foreign` (`id_categorie`),
  ADD KEY `products_id_subcategorie_foreign` (`id_subcategorie`),
  ADD KEY `products_id_local_foreign` (`id_local`),
  ADD KEY `products_id_rayon_foreign` (`id_rayon`),
  ADD KEY `products_id_tva_foreign` (`id_tva`),
  ADD KEY `products_id_unite_foreign` (`id_unite`),
  ADD KEY `products_id_user_foreign` (`id_user`);

--
-- Indexes for table `rayons`
--
ALTER TABLE `rayons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rayons_iduser_foreign` (`iduser`),
  ADD KEY `rayons_id_local_foreign` (`id_local`);

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
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_id_product_foreign` (`id_product`),
  ADD KEY `stock_id_tva_foreign` (`id_tva`),
  ADD KEY `stock_id_unite_foreign` (`id_unite`);

--
-- Indexes for table `stocktransfer`
--
ALTER TABLE `stocktransfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocktransfer_id_user_foreign` (`id_user`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sub_categories_id_categorie_foreign` (`id_categorie`),
  ADD KEY `sub_categories_iduser_foreign` (`iduser`);

--
-- Indexes for table `temp_achat`
--
ALTER TABLE `temp_achat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temp_achat_id_user_foreign` (`id_user`),
  ADD KEY `temp_achat_idproduit_foreign` (`idproduit`),
  ADD KEY `temp_achat_id_fournisseur_foreign` (`id_fournisseur`);

--
-- Indexes for table `temp_vente`
--
ALTER TABLE `temp_vente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temp_vente_id_user_foreign` (`id_user`),
  ADD KEY `temp_vente_idproduit_foreign` (`idproduit`),
  ADD KEY `temp_vente_id_client_foreign` (`id_client`),
  ADD KEY `temp_vente_id_formateur_foreign` (`id_formateur`);

--
-- Indexes for table `tmpstocktransfer`
--
ALTER TABLE `tmpstocktransfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tmpstocktransfer_id_product_foreign` (`id_product`),
  ADD KEY `tmpstocktransfer_from_foreign` (`from`),
  ADD KEY `tmpstocktransfer_to_foreign` (`to`),
  ADD KEY `tmpstocktransfer_iduser_foreign` (`iduser`),
  ADD KEY `tmpstocktransfer_idcommande_foreign` (`idcommande`);

--
-- Indexes for table `tvas`
--
ALTER TABLE `tvas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tvas_iduser_foreign` (`iduser`);

--
-- Indexes for table `unite`
--
ALTER TABLE `unite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unite_iduser_foreign` (`iduser`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `ventes`
--
ALTER TABLE `ventes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventes_id_client_foreign` (`id_client`),
  ADD KEY `ventes_id_formateur_foreign` (`id_formateur`),
  ADD KEY `ventes_id_user_foreign` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achats`
--
ALTER TABLE `achats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=407;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consumption_product_details`
--
ALTER TABLE `consumption_product_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `daily_consumption`
--
ALTER TABLE `daily_consumption`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hostorique_sig`
--
ALTER TABLE `hostorique_sig`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `inventory_monthly_summaries`
--
ALTER TABLE `inventory_monthly_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `inventory_yearly_summaries`
--
ALTER TABLE `inventory_yearly_summaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ligne_achat`
--
ALTER TABLE `ligne_achat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `line_transfer`
--
ALTER TABLE `line_transfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `locals`
--
ALTER TABLE `locals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `rayons`
--
ALTER TABLE `rayons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `stocktransfer`
--
ALTER TABLE `stocktransfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `temp_achat`
--
ALTER TABLE `temp_achat`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp_vente`
--
ALTER TABLE `temp_vente`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tmpstocktransfer`
--
ALTER TABLE `tmpstocktransfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tvas`
--
ALTER TABLE `tvas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `unite`
--
ALTER TABLE `unite`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `ventes`
--
ALTER TABLE `ventes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `achats`
--
ALTER TABLE `achats`
  ADD CONSTRAINT `achats_id_fournisseur_foreign` FOREIGN KEY (`id_Fournisseur`) REFERENCES `fournisseurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `achats_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consumption_product_details`
--
ALTER TABLE `consumption_product_details`
  ADD CONSTRAINT `consumption_product_details_consumption_id_foreign` FOREIGN KEY (`consumption_id`) REFERENCES `daily_consumption` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consumption_product_details_ligne_achat_id_foreign` FOREIGN KEY (`ligne_achat_id`) REFERENCES `ligne_achat` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consumption_product_details_ligne_vente_id_foreign` FOREIGN KEY (`ligne_vente_id`) REFERENCES `ligne_vente` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consumption_product_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `daily_consumption`
--
ALTER TABLE `daily_consumption`
  ADD CONSTRAINT `daily_consumption_achat_id_foreign` FOREIGN KEY (`achat_id`) REFERENCES `achats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `daily_consumption_vente_id_foreign` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD CONSTRAINT `fournisseurs_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hostorique_sig`
--
ALTER TABLE `hostorique_sig`
  ADD CONSTRAINT `hostorique_sig_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hostorique_sig_idvente_foreign` FOREIGN KEY (`idvente`) REFERENCES `ventes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventories_id_achat_foreign` FOREIGN KEY (`id_achat`) REFERENCES `achats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventories_id_vente_foreign` FOREIGN KEY (`id_vente`) REFERENCES `ventes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_monthly_summaries`
--
ALTER TABLE `inventory_monthly_summaries`
  ADD CONSTRAINT `inventory_monthly_summaries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_yearly_summaries`
--
ALTER TABLE `inventory_yearly_summaries`
  ADD CONSTRAINT `inventory_yearly_summaries_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ligne_achat`
--
ALTER TABLE `ligne_achat`
  ADD CONSTRAINT `ligne_achat_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_achat_idachat_foreign` FOREIGN KEY (`idachat`) REFERENCES `achats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_achat_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ligne_vente`
--
ALTER TABLE `ligne_vente`
  ADD CONSTRAINT `ligne_vente_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_vente_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ligne_vente_idvente_foreign` FOREIGN KEY (`idvente`) REFERENCES `ventes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `line_transfer`
--
ALTER TABLE `line_transfer`
  ADD CONSTRAINT `line_transfer_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `line_transfer_id_stocktransfer_foreign` FOREIGN KEY (`id_stocktransfer`) REFERENCES `stocktransfer` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `line_transfer_id_tva_foreign` FOREIGN KEY (`id_tva`) REFERENCES `tvas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `line_transfer_id_unite_foreign` FOREIGN KEY (`id_unite`) REFERENCES `unite` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `line_transfer_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `line_transfer_idcommande_foreign` FOREIGN KEY (`idcommande`) REFERENCES `ventes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `locals`
--
ALTER TABLE `locals`
  ADD CONSTRAINT `locals_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_id_categorie_foreign` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_id_local_foreign` FOREIGN KEY (`id_local`) REFERENCES `locals` (`id`),
  ADD CONSTRAINT `products_id_rayon_foreign` FOREIGN KEY (`id_rayon`) REFERENCES `rayons` (`id`),
  ADD CONSTRAINT `products_id_subcategorie_foreign` FOREIGN KEY (`id_subcategorie`) REFERENCES `sub_categories` (`id`),
  ADD CONSTRAINT `products_id_tva_foreign` FOREIGN KEY (`id_tva`) REFERENCES `tvas` (`id`),
  ADD CONSTRAINT `products_id_unite_foreign` FOREIGN KEY (`id_unite`) REFERENCES `unite` (`id`),
  ADD CONSTRAINT `products_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `rayons`
--
ALTER TABLE `rayons`
  ADD CONSTRAINT `rayons_id_local_foreign` FOREIGN KEY (`id_local`) REFERENCES `locals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rayons_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_id_tva_foreign` FOREIGN KEY (`id_tva`) REFERENCES `tvas` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_id_unite_foreign` FOREIGN KEY (`id_unite`) REFERENCES `unite` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `stocktransfer`
--
ALTER TABLE `stocktransfer`
  ADD CONSTRAINT `stocktransfer_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD CONSTRAINT `sub_categories_id_categorie_foreign` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sub_categories_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temp_achat`
--
ALTER TABLE `temp_achat`
  ADD CONSTRAINT `temp_achat_id_fournisseur_foreign` FOREIGN KEY (`id_fournisseur`) REFERENCES `fournisseurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_achat_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_achat_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `temp_vente`
--
ALTER TABLE `temp_vente`
  ADD CONSTRAINT `temp_vente_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_vente_id_formateur_foreign` FOREIGN KEY (`id_formateur`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_vente_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_vente_idproduit_foreign` FOREIGN KEY (`idproduit`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tmpstocktransfer`
--
ALTER TABLE `tmpstocktransfer`
  ADD CONSTRAINT `tmpstocktransfer_from_foreign` FOREIGN KEY (`from`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tmpstocktransfer_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tmpstocktransfer_idcommande_foreign` FOREIGN KEY (`idcommande`) REFERENCES `ventes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tmpstocktransfer_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tmpstocktransfer_to_foreign` FOREIGN KEY (`to`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tvas`
--
ALTER TABLE `tvas`
  ADD CONSTRAINT `tvas_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `unite`
--
ALTER TABLE `unite`
  ADD CONSTRAINT `unite_iduser_foreign` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ventes`
--
ALTER TABLE `ventes`
  ADD CONSTRAINT `ventes_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventes_id_formateur_foreign` FOREIGN KEY (`id_formateur`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ventes_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
