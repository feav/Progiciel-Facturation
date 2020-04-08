-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 08, 2020 at 02:41 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `statistiques_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `annonceurs`
--

CREATE TABLE `annonceurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `login` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_facturation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_comptabilite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_production` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delai_paiement` int(10) UNSIGNED NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `annonceurs`
--

INSERT INTO `annonceurs` (`id`, `nom`, `login`, `password`, `url`, `adresse_facturation`, `email_comptabilite`, `email_direction`, `email_production`, `delai_paiement`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'annonceur1', 'loginannonceur1', 'passwordannonceur1', 'www.annonceur1.com', 'adresse1', 'comptabilite@annonceur1.com', 'direction@annonceur1.com', 'production@annonceur1.com', 25, 1, 1, '2020-03-26 18:44:30', '2020-03-26 18:44:30'),
(2, 'annonceur2', 'loginannonceur2', 'passwordannonceur2', 'www.annonceur2.com', 'adresse2', 'comptabilite@annonceur2.com', 'direction@annonceur2.com', 'production@annonceur2.com', 50, 1, 1, '2020-03-26 20:17:26', '2020-03-26 20:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `bases`
--

CREATE TABLE `bases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `routeur_id` bigint(20) UNSIGNED NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bases`
--

INSERT INTO `bases` (`id`, `nom`, `routeur_id`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'base1', 1, 1, 1, '2020-03-26 07:54:36', '2020-03-26 18:24:31'),
(2, 'base2', 2, 1, 1, '2020-03-27 14:09:56', '2020-03-27 14:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `campagnes`
--

CREATE TABLE `campagnes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_remuneration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remuneration` double(8,2) NOT NULL,
  `annonceur_id` bigint(20) UNSIGNED NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `campagnes`
--

INSERT INTO `campagnes` (`id`, `nom`, `type_remuneration`, `remuneration`, `annonceur_id`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'campagne1', 'liquide', 125.70, 1, 1, 1, '2020-03-26 20:18:05', '2020-03-27 05:09:02'),
(2, 'campagne2', 'argent', 50.35, 2, 1, 1, '2020-03-27 07:42:51', '2020-03-27 07:42:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2020_03_09_083652_create_annonceurs_table', 1),
(4, '2020_03_09_084446_create_bases_table', 1),
(5, '2020_03_09_084519_create_campagnes_table', 1),
(6, '2020_03_09_084620_create_plannings_table', 1),
(7, '2020_03_09_084637_create_routeurs_table', 1),
(8, '2020_03_13_222845_create_resultats_table', 1),
(9, '2020_03_25_104744_create_roles_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `plannings`
--

CREATE TABLE `plannings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `annonceur_id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `routeur_id` bigint(20) UNSIGNED NOT NULL,
  `base_id` bigint(20) UNSIGNED NOT NULL,
  `volume` bigint(20) UNSIGNED NOT NULL,
  `remuneration` double(8,2) NOT NULL,
  `date_envoi` date NOT NULL,
  `heure_envoi` time NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `plannings`
--

INSERT INTO `plannings` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `remuneration`, `date_envoi`, `heure_envoi`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 25, 125.70, '2020-03-30', '15:30:30', 1, 1, '2020-03-26 20:35:42', '2020-03-26 20:35:42'),
(2, 2, 2, 2, 2, 50, 50.35, '2020-03-30', '15:00:00', 1, 1, '2020-03-27 20:11:16', '2020-03-27 20:11:16'),
(3, 1, 1, 1, 1, 250, 125.70, '2020-03-31', '15:00:00', 1, 1, '2020-03-31 11:07:18', '2020-03-31 11:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `resultats`
--

CREATE TABLE `resultats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `annonceur_id` bigint(20) UNSIGNED NOT NULL,
  `campagne_id` bigint(20) UNSIGNED NOT NULL,
  `routeur_id` bigint(20) UNSIGNED NOT NULL,
  `base_id` bigint(20) UNSIGNED NOT NULL,
  `volume` bigint(20) UNSIGNED NOT NULL,
  `remuneration` double(8,2) NOT NULL,
  `date_envoi` date NOT NULL,
  `heure_envoi` time NOT NULL,
  `resultat` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `resultats`
--

INSERT INTO `resultats` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `remuneration`, `date_envoi`, `heure_envoi`, `resultat`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 25, 125.70, '2020-03-30', '15:30:30', 25, 1, 1, '2020-03-26 20:35:42', '2020-04-07 23:17:05'),
(2, 2, 2, 2, 2, 50, 50.35, '2020-03-30', '15:00:00', 50, 1, 1, '2020-03-27 20:11:16', '2020-03-27 20:14:59'),
(3, 1, 1, 1, 1, 250, 125.70, '2020-03-31', '15:00:00', 100, 1, 1, '2020-03-31 11:07:19', '2020-04-07 23:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `intitule` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `intitule`, `cree_par`, `modifie_par`) VALUES
(1, 'Direction', 1, 1),
(2, 'Trafic Manager', 1, 1),
(3, 'Administration', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `routeurs`
--

CREATE TABLE `routeurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double(16,8) NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `routeurs`
--

INSERT INTO `routeurs` (`id`, `nom`, `prix`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'routeur1', 25.00000000, 1, 1, '2020-03-26 06:32:00', '2020-03-26 17:32:51'),
(2, 'routeur2', 50.00000000, 1, 1, '2020-03-26 06:54:24', '2020-03-26 06:54:24'),
(3, 'routeur3', 0.12345600, 1, 1, '2020-03-29 17:10:48', '2020-04-07 22:05:46'),
(4, 'routeur4', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(5, 'routeur5', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(6, 'routeur6', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(7, 'routeur7', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(8, 'routeur8', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(9, 'routeur9', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(10, 'routeur10', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(11, 'routeur11', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(12, 'routeur12', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(13, 'routeur13', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(14, 'routeur14', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(15, 'routeur15', 15.00000000, 1, 1, '2020-03-26 05:32:00', '2020-04-07 22:10:26'),
(16, 'routeur16', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(17, 'routeur17', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(18, 'routeur18', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(19, 'routeur19', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(20, 'routeur20', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(21, 'routeur21', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(22, 'routeur22', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(23, 'routeur23', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(24, 'routeur24', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(25, 'routeur25', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(26, 'routeur26', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(27, 'routeur27', 27.00000000, 1, 1, '2020-03-26 05:32:00', '2020-04-07 22:09:16'),
(28, 'routeur28', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(29, 'routeur29', 29.00000000, 1, 1, '2020-03-26 05:32:00', '2020-04-07 22:07:21'),
(30, 'routeur30', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(31, 'routeur31', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(32, 'routeur32', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(33, 'routeur33', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(34, 'routeur34', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(35, 'routeur35', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(36, 'routeur36', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(37, 'routeur37', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(38, 'routeur38', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(39, 'routeur39', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(40, 'routeur40', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(41, 'routeur41', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(42, 'routeur42', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(43, 'routeur43', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(44, 'routeur44', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(45, 'routeur45', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(46, 'routeur46', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(47, 'routeur47', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(48, 'routeur48', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(49, 'routeur49', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(50, 'routeur50', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(51, 'routeur51', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(52, 'routeur52', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(53, 'routeur53', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(54, 'routeur54', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(55, 'routeur55', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(56, 'routeur56', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(57, 'routeur57', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(58, 'routeur58', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(59, 'routeur59', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(60, 'routeur60', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(61, 'routeur61', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(62, 'routeur62', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(63, 'routeur63', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(64, 'routeur64', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(65, 'routeur65', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(66, 'routeur66', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(67, 'routeur67', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(68, 'routeur68', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(69, 'routeur69', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(70, 'routeur70', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(71, 'routeur71', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(72, 'routeur72', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(73, 'routeur73', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(74, 'routeur74', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(75, 'routeur75', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(76, 'routeur76', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(77, 'routeur77', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(78, 'routeur78', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24'),
(79, 'routeur79', 25.00000000, 1, 1, '2020-03-26 05:32:00', '2020-03-26 16:32:51'),
(80, 'routeur80', 50.00000000, 1, 1, '2020-03-26 05:54:24', '2020-03-26 05:54:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `cree_par`, `modifie_par`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Directeur Général', 'admin@admin.com', NULL, '$2y$12$xxR1Z2YSA/mhlqAHC.qQfuJ2Zo.RnMMzyGFikxf5cX7CBo75MLwLa', 1, 1, 1, 'p7u64RUNjKeXtEOsIfC3iYPe7oUu8RjA6kd2Y8DVTHTHMZq1hq6ffik4LlJO', '2020-03-22 01:01:29', '2020-03-27 07:27:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annonceurs`
--
ALTER TABLE `annonceurs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bases`
--
ALTER TABLE `bases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campagnes`
--
ALTER TABLE `campagnes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plannings`
--
ALTER TABLE `plannings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resultats`
--
ALTER TABLE `resultats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routeurs`
--
ALTER TABLE `routeurs`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `annonceurs`
--
ALTER TABLE `annonceurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bases`
--
ALTER TABLE `bases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `campagnes`
--
ALTER TABLE `campagnes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plannings`
--
ALTER TABLE `plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resultats`
--
ALTER TABLE `resultats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `routeurs`
--
ALTER TABLE `routeurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
