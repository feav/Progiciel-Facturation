-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 28, 2020 at 12:19 PM
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

INSERT INTO `annonceurs` (`id`, `nom`, `url`, `adresse_facturation`, `email_comptabilite`, `email_direction`, `email_production`, `delai_paiement`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'annonceur1', 'www.annonceur1.com', 'adresse1', 'comptabilite@annonceur1.com', 'direction@annonceur1.com', 'production@annonceur1.com', 25, 1, 1, '2020-03-26 19:44:30', '2020-03-26 19:44:30'),
(2, 'annonceur2', 'www.annonceur2.com', 'adresse2', 'comptabilite@annonceur2.com', 'direction@annonceur2.com', 'production@annonceur2.com', 50, 1, 1, '2020-03-26 21:17:26', '2020-03-26 21:17:26');

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
(1, 'base1', 1, 1, 1, '2020-03-26 08:54:36', '2020-03-26 19:24:31'),
(2, 'base2', 2, 1, 1, '2020-03-27 15:09:56', '2020-03-27 15:09:56');

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
(1, 'campagne1', 'liquide', 25.00, 1, 1, 1, '2020-03-26 21:18:05', '2020-03-27 06:09:02'),
(2, 'campagne2', 'argent', 50.35, 2, 1, 1, '2020-03-27 08:42:51', '2020-03-27 08:42:51');

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
  `volume` double(8,2) NOT NULL,
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

INSERT INTO `plannings` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `date_envoi`, `heure_envoi`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 25.00, '2020-03-30', '15:30:30', 1, 1, '2020-03-26 21:35:42', '2020-03-26 21:35:42'),
(2, 2, 2, 2, 2, 50.00, '2020-03-30', '15:00:00', 1, 1, '2020-03-27 21:11:16', '2020-03-27 21:11:16');

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
  `date_envoi` date NOT NULL,
  `heure_envoi` time NOT NULL,
  `resultat` bigint(20) UNSIGNED DEFAULT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `resultats`
--

INSERT INTO `resultats` (`id`, `annonceur_id`, `campagne_id`, `routeur_id`, `base_id`, `volume`, `date_envoi`, `heure_envoi`, `resultat`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 25.00, '2020-03-30', '15:30:30', 25, 1, 1, '2020-03-26 21:35:42', '2020-03-26 23:09:45'),
(2, 2, 2, 2, 2, 50.00, '2020-03-30', '15:00:00', 50, 1, 1, '2020-03-27 21:11:16', '2020-03-27 21:14:59');

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
  `prix` double(8,2) NOT NULL,
  `cree_par` bigint(20) UNSIGNED NOT NULL,
  `modifie_par` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `routeurs`
--

INSERT INTO `routeurs` (`id`, `nom`, `prix`, `cree_par`, `modifie_par`, `created_at`, `updated_at`) VALUES
(1, 'routeur1', 25.00, 1, 1, '2020-03-26 07:32:00', '2020-03-26 18:32:51'),
(2, 'routeur2', 50.00, 1, 1, '2020-03-26 07:54:24', '2020-03-26 07:54:24');

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
(1, 'Directeur Général', 'admin@admin.com', NULL, '$2y$12$xxR1Z2YSA/mhlqAHC.qQfuJ2Zo.RnMMzyGFikxf5cX7CBo75MLwLa', 1, 1, 1, 'AoghG2burXoBZdzPY0uVDfrQCNRNN2fIYX65aRzTySc0UhW7MsmladRmkvDK', '2020-03-22 02:01:29', '2020-03-27 08:27:30');

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resultats`
--
ALTER TABLE `resultats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `routeurs`
--
ALTER TABLE `routeurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
