-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 14, 2020 at 05:59 AM
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
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_facturation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_comptabilite` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_direction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_production` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delai_paiement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `annonceurs`
--

INSERT INTO `annonceurs` (`id`, `nom`, `url`, `adresse_facturation`, `email_comptabilite`, `email_direction`, `email_production`, `delai_paiement`, `created_at`, `updated_at`) VALUES
(1, 'GeryAnnonceur1', 'GeryAnnonceur1', 'GeryAnnonceur1', 'meligery93@gmail.com', 'meligery93@gmail.com', 'meligery93@gmail.com', '12/03/2020', '2020-03-10 21:19:06', '2020-03-10 21:19:06'),
(2, 'GeryAnnonceur2', 'GeryAnnonceur2', 'GeryAnnonceur2', 'meligery@yahoo.com', 'meligery@yahoo.com', 'meligery@yahoo.com', '19/03/2020', '2020-03-10 21:19:36', '2020-03-10 21:19:36'),
(3, 'GeryAnnonceur3', 'GeryAnnonceur3', 'GeryAnnonceur3', 'meligery@outlook.fr', 'meligery@outlook.fr', 'meligery@outlook.fr', '25/03/2020', '2020-03-10 21:20:04', '2020-03-10 21:20:04'),
(4, 'GeryAnnonceur4', 'GeryAnnonceur4', 'GeryAnnonceur4', 'toto@toto.com', 'toto@toto.com', 'toto@toto.com', '31/03/2020', '2020-03-10 23:11:46', '2020-03-10 23:11:46');

-- --------------------------------------------------------

--
-- Table structure for table `bases`
--

CREATE TABLE `bases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `routeur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `bases`
--

INSERT INTO `bases` (`id`, `nom`, `routeur_id`, `created_at`, `updated_at`) VALUES
(1, 'Base1', 3, '2020-03-10 23:12:59', '2020-03-10 23:12:59'),
(2, 'Base2', 1, '2020-03-10 23:13:21', '2020-03-10 23:13:21'),
(3, 'Base3', 1, '2020-03-11 06:39:28', '2020-03-11 06:39:28'),
(4, 'Base4', 2, '2020-03-11 07:45:47', '2020-03-11 07:45:47'),
(5, 'Base5', 4, '2020-03-11 07:47:10', '2020-03-11 07:47:10'),
(6, 'Base6', 4, '2020-03-11 07:49:09', '2020-03-11 07:49:09');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `campagnes`
--

INSERT INTO `campagnes` (`id`, `nom`, `type_remuneration`, `remuneration`, `annonceur_id`, `created_at`, `updated_at`) VALUES
(1, 'Campagne1', 'argent', 50.00, 4, '2020-03-10 23:21:37', '2020-03-10 23:21:37'),
(2, 'Campagne2', 'argent', 100.00, 2, '2020-03-11 07:59:19', '2020-03-11 07:59:19'),
(3, 'Campagne3', 'money', 150.00, 1, '2020-03-11 07:59:49', '2020-03-11 07:59:49'),
(4, 'Campagne4', 'nkap', 200.00, 3, '2020-03-11 08:00:05', '2020-03-11 08:00:05');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(25, '2014_10_12_000000_create_users_table', 1),
(26, '2019_08_19_000000_create_failed_jobs_table', 1),
(27, '2020_03_09_083652_create_annonceurs_table', 1),
(28, '2020_03_09_084446_create_bases_table', 1),
(29, '2020_03_09_084519_create_campagnes_table', 1),
(30, '2020_03_09_084620_create_plannings_table', 1),
(31, '2020_03_09_084637_create_routeurs_table', 1),
(35, '2020_03_13_222845_create_resultats_table', 2);

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
  `volume` double(8,2) NOT NULL,
  `date_envoi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `plannings`
--

INSERT INTO `plannings` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `date_envoi`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 4, 5, 12000.00, '12/03/2020', '2020-03-11 07:53:49', '2020-03-11 07:53:49'),
(2, 1, 3, 1, 3, 500.00, '16/03/2020', '2020-03-14 00:55:54', '2020-03-14 00:55:54');

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
  `volume` double(8,2) NOT NULL,
  `date_envoi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resultat` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `resultats`
--

INSERT INTO `resultats` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `date_envoi`, `resultat`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 4, 5, 1200.00, '12/03/2020', 5, '2020-03-11 06:53:49', '2020-03-14 04:54:41'),
(2, 1, 3, 1, 3, 500.00, '16/03/2020', 3, '2020-03-14 00:55:54', '2020-03-14 04:49:41');

-- --------------------------------------------------------

--
-- Table structure for table `routeurs`
--

CREATE TABLE `routeurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `routeurs`
--

INSERT INTO `routeurs` (`id`, `nom`, `prix`, `created_at`, `updated_at`) VALUES
(1, 'GeryRouteur1', 50.00, '2020-03-10 21:17:55', '2020-03-10 21:17:55'),
(2, 'GeryRouteur2', 100.00, '2020-03-10 21:18:04', '2020-03-10 21:18:04'),
(3, 'GeryRouteur3', 150.00, '2020-03-10 21:18:12', '2020-03-10 21:18:12'),
(4, 'GeryRouteur4', 200.00, '2020-03-10 23:11:03', '2020-03-10 23:11:03');

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
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `bases`
--
ALTER TABLE `bases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `campagnes`
--
ALTER TABLE `campagnes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `plannings`
--
ALTER TABLE `plannings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resultats`
--
ALTER TABLE `resultats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routeurs`
--
ALTER TABLE `routeurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
