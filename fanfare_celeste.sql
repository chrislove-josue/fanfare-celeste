-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 juin 2026 à 21:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fanfare_celeste`
--

-- --------------------------------------------------------

--
-- Structure de la table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE `albums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `category` enum('concerts','rehearsals','trophies','events','members','ceremonies','group') NOT NULL DEFAULT 'events',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `event_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('fanfare-celeste-la-trompette-de-dieu-de-latlantique-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:30:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:12:\"view members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:14:\"create members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"edit members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:6;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:14:\"delete members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:15:\"approve members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:14:\"manage members\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:13:\"view finances\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:19:\"create transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:20:\"approve transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:14:\"export reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:4;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"view events\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:8:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"create events\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:11:\"edit events\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:13:\"delete events\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:21:\"manage participations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:6;i:4;i:7;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:14:\"view committee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:16:\"manage committee\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"view documents\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:8;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:16:\"upload documents\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:16:\"delete documents\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:17:\"approve documents\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"manage pages\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"manage carousels\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:19:\"manage testimonials\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:11:\"manage news\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"manage gallery\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:15:\"manage partners\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:12:\"manage roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:9:\"view logs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:15:\"manage settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}}s:5:\"roles\";a:8:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"president\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:9:\"treasurer\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:9:\"secretary\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:9:\"conductor\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"section-leader\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:6:\"member\";s:1:\"c\";s:3:\"web\";}}}', 1782756480);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `carousels`
--

CREATE TABLE `carousels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `link_text` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `carousels`
--

INSERT INTO `carousels` (`id`, `title`, `subtitle`, `image`, `link`, `link_text`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fanfare Céleste', 'La Trompette de Dieu de l\'Atlantique', 'carousels/7mofTL5NU8wRI6IxX4txemWZMjB7sZRTV41qJIci.jpg', 'http://localhost:8000/about', 'Découvrir', 1, 1, '2026-06-28 16:37:00', '2026-06-28 16:58:27'),
(2, 'Rejoignez-nous', 'Une grande famille musicale vous attend', 'carousels/YiDRUAGjh54c9pj2BDvhsjPFMGIdp0uoNCUOnTRZ.jpg', 'http://localhost:8000/membership/apply', 'Adhérer maintenant', 2, 1, '2026-06-28 16:37:00', '2026-06-28 16:58:48'),
(3, 'Événements à venir', 'Venez vivre des moments uniques', 'carousels/iI59ieg3f3SfSy1UGqZyTfOjksP0BscP94DdrybV.jpg', 'http://localhost:8000/events', 'Voir les événements', 3, 1, '2026-06-28 16:37:00', '2026-06-28 16:59:17');

-- --------------------------------------------------------

--
-- Structure de la table `committee_members`
--

CREATE TABLE `committee_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `position` varchar(255) NOT NULL,
  `position_order` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT 1,
  `responsibilities` text DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `committee_members`
--

INSERT INTO `committee_members` (`id`, `member_id`, `position`, `position_order`, `start_date`, `end_date`, `is_current`, `responsibilities`, `achievements`, `created_at`, `updated_at`) VALUES
(1, 46, 'president', 1, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:09:33', '2026-06-28 17:09:33'),
(2, 47, 'vice_president', 2, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:09:59', '2026-06-28 17:09:59'),
(3, 2, 'deputy_conductor', 3, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:10:17', '2026-06-28 17:10:17'),
(4, 11, 'treasurer', 4, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:10:39', '2026-06-28 17:10:39'),
(5, 6, 'section_leader_percussion', 5, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:10:54', '2026-06-28 17:10:54'),
(6, 25, 'section_leader_trombone', 6, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:11:11', '2026-06-28 17:11:11'),
(7, 17, 'general_secretary', 7, '2026-06-28', NULL, 1, NULL, NULL, '2026-06-28 17:11:28', '2026-06-28 17:11:28');

-- --------------------------------------------------------

--
-- Structure de la table `contributions`
--

CREATE TABLE `contributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('monthly','special','donation') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `month` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `receipt_number` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contributions`
--

INSERT INTO `contributions` (`id`, `member_id`, `type`, `amount`, `month`, `year`, `payment_date`, `payment_method`, `transaction_id`, `receipt_number`, `created_at`, `updated_at`) VALUES
(1, 49, 'special', 60000.00, 6, 2026, '2026-06-29', 'cash', NULL, 'REC-2026-00001', '2026-06-29 07:43:50', '2026-06-29 07:43:50');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` enum('statutes','minutes','financial_report','activity_program','internal','contract','certificate') NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submission_type` enum('admin','member','applicant') NOT NULL DEFAULT 'admin',
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `title`, `category`, `description`, `file_path`, `file_name`, `file_size`, `file_type`, `uploaded_by`, `member_id`, `submission_type`, `status`, `rejection_reason`, `approved_at`, `document_date`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'test', 'statutes', NULL, 'documents/zIpFJqje4zKWjzWLi4YKPQ0UOSVpqmcqoEq6HVpu', 'project.docx', '0', 'application/x-empty', 1, NULL, 'admin', 'pending', NULL, NULL, '2026-06-29', 1, '2026-06-29 09:47:36', '2026-06-29 09:47:36');

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('concert','entertainment','official_outing','rehearsal','evangelical_campaign','special','wedding','funeral','festival') NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `location_url` varchar(255) DEFAULT NULL,
  `responsible_id` bigint(20) UNSIGNED NOT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `actual_cost` decimal(12,2) DEFAULT NULL,
  `status` enum('planned','upcoming','ongoing','completed','cancelled') NOT NULL DEFAULT 'planned',
  `notes` text DEFAULT NULL,
  `poster_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `type`, `start_date`, `end_date`, `location`, `location_url`, `responsible_id`, `budget`, `actual_cost`, `status`, `notes`, `poster_path`, `created_at`, `updated_at`) VALUES
(1, 'Concert de Gala', 'Grand concert de gala de la Fanfare Céleste', 'concert', '2026-07-13 17:36:00', '2026-07-13 20:36:00', 'Palais des Congrès, Cotonou', NULL, 6, 1500000.00, NULL, 'planned', NULL, 'events/posters/Beg3xdjpg0rrYIGx8rX4NbgCeGsUygFYQw07Bty8.jpg', '2026-06-28 16:36:59', '2026-06-28 17:17:49'),
(2, 'Animation Mariage Royal', 'Animation musicale pour un mariage prestigieux', 'entertainment', '2026-07-05 17:36:00', '2026-07-05 21:36:00', 'Hôtel de la Plage, Cotonou', NULL, 6, 500000.00, NULL, 'planned', NULL, 'events/posters/VtqgFbxnuTnHMQlVLOIOJoYsmZgF1lW26s9ep588.jpg', '2026-06-28 16:36:59', '2026-06-28 17:19:20'),
(3, 'Répétition Générale', 'Répétition pour le concert à venir', 'rehearsal', '2026-07-01 17:36:00', '2026-07-01 19:36:00', 'Salle de répétition, Église Céleste', NULL, 6, 0.00, NULL, 'planned', NULL, 'events/posters/HjHEzmFA2v2Urh1rqIuNThNZFXJ8RKNZdXtbu76k.webp', '2026-06-28 16:36:59', '2026-06-28 18:31:03'),
(4, 'Campagne Évangélique', 'Campagne d\'évangélisation avec musique', 'evangelical_campaign', '2026-07-28 17:36:00', '2026-07-30 22:36:00', 'Stade Municipal, Porto-Novo', NULL, 6, 800000.00, NULL, 'planned', NULL, 'events/posters/MPHYTju6SVzpTYWVngzimDRRodhFIUwsjU0YyTJv.jpg', '2026-06-28 16:36:59', '2026-06-28 17:16:52'),
(5, 'test', 'test', 'concert', '2026-06-03 20:26:00', '2026-07-12 20:26:00', 'Salle de répétition, Église Céleste', NULL, 1, NULL, NULL, 'planned', NULL, 'events/posters/kkaHHIsTvPsHijBeMsFaUG1zVBvJrsLHYR5jK5Eu.jpg', '2026-06-28 18:26:27', '2026-06-28 18:32:35');

-- --------------------------------------------------------

--
-- Structure de la table `event_participations`
--

CREATE TABLE `event_participations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_status` enum('confirmed','present','absent','late','excused') NOT NULL DEFAULT 'confirmed',
  `arrival_time` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `transport_provided` tinyint(1) NOT NULL DEFAULT 0,
  `meal_provided` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `event_public_registrations`
--

CREATE TABLE `event_public_registrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `whatsapp_number` varchar(30) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `financial_transactions`
--

CREATE TABLE `financial_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('income','expense') NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text NOT NULL,
  `transaction_date` date NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `financial_transactions`
--

INSERT INTO `financial_transactions` (`id`, `member_id`, `type`, `category`, `amount`, `description`, `transaction_date`, `status`, `approved_by`, `approved_at`, `receipt_path`, `reference`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 47, 'income', 'cotisation', 50000.00, 'test', '2026-06-29', 'approved', 1, '2026-06-29 07:17:54', NULL, 'TRX-6A422A1ADDED2', NULL, '2026-06-29 07:17:30', '2026-06-29 07:17:54'),
(2, NULL, 'expense', 'instrument', 100000.00, 'test', '2026-06-29', 'approved', 1, '2026-06-29 08:02:59', 'receipts/I50tnMEeNZSgzfBf3Ccqdkeo98ov2zVjXeFEmE8W.pdf', 'TRX-6A42349C9C416', NULL, '2026-06-29 08:02:20', '2026-06-29 08:02:59');

-- --------------------------------------------------------

--
-- Structure de la table `galleries`
--

CREATE TABLE `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `category` enum('concerts','rehearsals','trophies','events','members','ceremonies','group') NOT NULL DEFAULT 'events',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `event_date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `galleries`
--

INSERT INTO `galleries` (`id`, `title`, `slug`, `description`, `cover_image`, `category`, `is_published`, `event_date`, `location`, `order`, `views_count`, `likes_count`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '🏆 Nos Trophées et Distinctions', 'nos-trophees-et-distinctions', 'Collection des trophées et reconnaissances obtenues par la Fanfare Céleste au fil des années.', 'galleries/covers/Qd6ysZO2SAVCEN9iDXA3oD1kwG5rPDveEIvdw2hs.jpg', 'trophies', 1, '2024-06-15', 'Cotonou, Bénin', 1, 0, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:41:35', NULL),
(2, '🎵 Concert de Gala 2022', 'concert-de-gala-2024', 'Concert exceptionnel au Palais des Congrès de Cotonou', 'galleries/covers/2KOq8cKnXo0mdVsP5KCBhRadlIb9dDLnmLiFqu6N.jpg', 'concerts', 1, '2022-05-20', 'Palais des Congrès, Cotonou', 2, 4, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:44:58', NULL),
(3, '🎶 Festival International de Musique', 'festival-international-de-musique', 'Participation au festival international de Cotonou', 'galleries/covers/LxLy2lADn8bFXpV5X2bZBpFBfLv94roNN7i7Qiql.jpg', 'concerts', 1, '2024-03-10', 'Stade de l\'Amitié, Cotonou', 3, 2, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:43:58', NULL),
(4, '📅 Événements Marquants', 'evenements-marquants', 'Les événements qui ont marqué la vie de la fanfare.', 'galleries/covers/G3irE9lDB1c5hdga9SxwqsaTDPt1K5Jt303rk3h0.jpg', 'events', 1, '2024-04-25', 'Cotonou, Bénin', 4, 0, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:46:11', NULL),
(5, '👥 Vie des Membres', 'vie-des-membres', 'Les moments forts de la vie communautaire de notre fanfare.', 'galleries/covers/ODmMo7Ew8wFJKEjIcbqslII4g6LlK1dbxKEE1YGC.jpg', 'members', 1, NULL, NULL, 5, 2, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:54:38', NULL),
(6, '⛪ Cérémonies Religieuses', 'ceremonies-religieuses', 'Les cérémonies religieuses auxquelles participe la fanfare.', 'galleries/covers/EN0Bt4c2fJouITN3Eoz6tP5Gh2mauGCcanHxCkwk.jpg', 'ceremonies', 1, NULL, NULL, 6, 2, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:52:21', NULL),
(7, '📸 Photos de Famille', 'photos-de-famille', 'Les photos de groupe de la Fanfare Céleste à travers les années.', 'galleries/covers/mUJkLQEjn3a5v4Gm5BBZH2yFJukuylsDPvieGbTw.jpg', 'group', 1, NULL, NULL, 7, 0, 0, 1, '2026-06-28 16:36:59', '2026-06-28 16:47:28', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gallery_media`
--

CREATE TABLE `gallery_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('photo','video') NOT NULL DEFAULT 'photo',
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gallery_media`
--

INSERT INTO `gallery_media` (`id`, `gallery_id`, `title`, `description`, `type`, `file_path`, `file_name`, `file_size`, `mime_type`, `alt_text`, `order`, `is_cover`, `is_published`, `views_count`, `likes_count`, `metadata`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', NULL, 'photo', 'galleries/3/media/1782668437_xWtfuKzuKd.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', '117778', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(2, 3, 'WhatsApp Image 2026-06-22 at 23.23.12 (2).jpeg', NULL, 'photo', 'galleries/3/media/1782668437_4nzg90f2CY.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (2).jpeg', '94373', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(3, 3, 'WhatsApp Image 2026-06-22 at 23.23.12 (3).jpeg', NULL, 'photo', 'galleries/3/media/1782668437_aJ2dbPmdrX.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (3).jpeg', '86289', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(4, 3, 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', NULL, 'photo', 'galleries/3/media/1782668437_IG6jEGvkfv.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', '86671', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(5, 3, 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', NULL, 'photo', 'galleries/3/media/1782668437_XTrwAN2oiQ.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', '84656', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(6, 3, 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', NULL, 'photo', 'galleries/3/media/1782668437_wasc9GgVx4.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', '70351', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(7, 3, 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', NULL, 'photo', 'galleries/3/media/1782668437_wSRvwDvrc5.jpeg', 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', '90139', 'image/jpeg', NULL, 13, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(8, 3, 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', NULL, 'photo', 'galleries/3/media/1782668437_RKzbBgB6gc.jpeg', 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', '83971', 'image/jpeg', NULL, 15, 0, 1, 0, 0, NULL, '2026-06-28 16:40:37', '2026-06-28 16:40:37', NULL),
(9, 1, 'event1.jpeg', NULL, 'photo', 'galleries/1/media/1782668532_Sw1liFg2Xi.jpeg', 'event1.jpeg', '83244', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(10, 1, 'WhatsApp Image 2026-06-22 at 23.23.12 (3).jpeg', NULL, 'photo', 'galleries/1/media/1782668532_6za9qAYNEo.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (3).jpeg', '86289', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(11, 1, 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', NULL, 'photo', 'galleries/1/media/1782668532_jrDWVsMG3z.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', '86671', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(12, 1, 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', NULL, 'photo', 'galleries/1/media/1782668532_8wjvnBXqiZ.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', '84656', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(13, 1, 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', NULL, 'photo', 'galleries/1/media/1782668532_R1KaIxuZ5n.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', '70351', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(14, 1, 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', NULL, 'photo', 'galleries/1/media/1782668532_FGxsdU5u6M.jpeg', 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', '90139', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:42:12', '2026-06-28 16:42:12', NULL),
(15, 2, 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_oyZNVyG8Ww.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', '117778', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(16, 2, 'WhatsApp Image 2026-06-22 at 23.16.37.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_sfLkaYxvBa.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.37.jpeg', '154234', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(17, 2, 'WhatsApp Image 2026-06-22 at 23.16.40.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_1HjukRZi4x.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.40.jpeg', '137967', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(18, 2, 'WhatsApp Image 2026-06-22 at 23.23.12 (1).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_VJDK8Z3ZCD.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (1).jpeg', '117778', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(19, 2, 'WhatsApp Image 2026-06-22 at 23.23.12 (2).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_FBl39vLvrt.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (2).jpeg', '94373', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(20, 2, 'WhatsApp Image 2026-06-22 at 23.23.12 (4).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_plpdONGz9Z.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (4).jpeg', '102752', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(21, 2, 'WhatsApp Image 2026-06-22 at 23.23.13 (3).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_WsB89rSScL.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.13 (3).jpeg', '89935', 'image/jpeg', NULL, 13, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(22, 2, 'WhatsApp Image 2026-06-22 at 23.23.13.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_pjyZtNhgMu.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.13.jpeg', '127726', 'image/jpeg', NULL, 15, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(23, 2, 'WhatsApp Image 2026-06-22 at 23.23.14 (1).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_I7zr3RyMz3.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.14 (1).jpeg', '61244', 'image/jpeg', NULL, 17, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(24, 2, 'WhatsApp Image 2026-06-22 at 23.23.16 (1).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_coWwEq08bO.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (1).jpeg', '79142', 'image/jpeg', NULL, 19, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(25, 2, 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_adA4X6DKwv.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', '134345', 'image/jpeg', NULL, 21, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(26, 2, 'WhatsApp Image 2026-06-22 at 23.23.16.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_GJE7SzjkXF.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16.jpeg', '109196', 'image/jpeg', NULL, 23, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(27, 2, 'WhatsApp Image 2026-06-22 at 23.23.17 (1).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_jRJx0NUw7h.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.17 (1).jpeg', '103066', 'image/jpeg', NULL, 25, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(28, 2, 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', NULL, 'photo', 'galleries/2/media/1782668612_wcKjZz1POZ.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', '119013', 'image/jpeg', NULL, 27, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(29, 2, 'WhatsApp Image 2026-06-22 at 23.23.19 (1).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_0pFsJv2dJh.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (1).jpeg', '88711', 'image/jpeg', NULL, 29, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(30, 2, 'WhatsApp Image 2026-06-22 at 23.23.19 (2).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_WVoj8yDwG0.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (2).jpeg', '147484', 'image/jpeg', NULL, 31, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(31, 2, 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_sSRgtYDgCT.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', '158900', 'image/jpeg', NULL, 33, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(32, 2, 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', NULL, 'photo', 'galleries/2/media/1782668612_zJOnzatfz9.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', '113910', 'image/jpeg', NULL, 35, 0, 1, 0, 0, NULL, '2026-06-28 16:43:32', '2026-06-28 16:43:32', NULL),
(33, 4, 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', NULL, 'photo', 'galleries/4/media/1782668804_A1pQllaZ64.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', '119013', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:46:44', '2026-06-28 16:46:44', NULL),
(34, 4, 'WhatsApp Image 2026-06-22 at 23.23.18 (3).jpeg', NULL, 'photo', 'galleries/4/media/1782668804_xqg1KA0tHS.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.18 (3).jpeg', '84773', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(35, 4, 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', NULL, 'photo', 'galleries/4/media/1782668805_0fTN9eQRSk.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', '158900', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(36, 4, 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', NULL, 'photo', 'galleries/4/media/1782668805_jHpr5fEeSO.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', '86671', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(37, 4, 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', NULL, 'photo', 'galleries/4/media/1782668805_EFRUzUVPcI.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', '84656', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(38, 4, 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', NULL, 'photo', 'galleries/4/media/1782668805_gDgJIZCZBE.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', '70351', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(39, 4, 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', NULL, 'photo', 'galleries/4/media/1782668805_MRYhyMl8Ns.jpeg', 'WhatsApp Image 2026-06-22 at 23.27.00.jpeg', '90139', 'image/jpeg', NULL, 13, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(40, 4, 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', NULL, 'photo', 'galleries/4/media/1782668805_vt52QW8xyR.jpeg', 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', '83971', 'image/jpeg', NULL, 15, 0, 1, 0, 0, NULL, '2026-06-28 16:46:45', '2026-06-28 16:46:45', NULL),
(41, 7, 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', NULL, 'photo', 'galleries/7/media/1782668894_GyHvZ5Jxt5.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.29.jpeg', '117778', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(42, 7, 'WhatsApp Image 2026-06-22 at 23.16.32.jpeg', NULL, 'photo', 'galleries/7/media/1782668894_uVj29x3wdj.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.32.jpeg', '102752', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(43, 7, 'WhatsApp Image 2026-06-22 at 23.23.13 (3).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_krpwacUI6Y.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.13 (3).jpeg', '89935', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(44, 7, 'WhatsApp Image 2026-06-22 at 23.23.14 (2).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_LZSmNebK0K.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.14 (2).jpeg', '149950', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(45, 7, 'WhatsApp Image 2026-06-22 at 23.23.14.jpeg', NULL, 'photo', 'galleries/7/media/1782668894_oAhLfOeG1N.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.14.jpeg', '119847', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(46, 7, 'WhatsApp Image 2026-06-22 at 23.23.16 (1).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_1dY2AoYan6.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (1).jpeg', '79142', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(47, 7, 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_AkpTe3GyPK.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', '134345', 'image/jpeg', NULL, 13, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(48, 7, 'WhatsApp Image 2026-06-22 at 23.23.16.jpeg', NULL, 'photo', 'galleries/7/media/1782668894_0jz1zJDY1p.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16.jpeg', '109196', 'image/jpeg', NULL, 15, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(49, 7, 'WhatsApp Image 2026-06-22 at 23.23.17 (2).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_m4EexT0vCM.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.17 (2).jpeg', '139119', 'image/jpeg', NULL, 17, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(50, 7, 'WhatsApp Image 2026-06-22 at 23.23.18 (2).jpeg', NULL, 'photo', 'galleries/7/media/1782668894_cyGPMUyjz8.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.18 (2).jpeg', '88252', 'image/jpeg', NULL, 19, 0, 1, 0, 0, NULL, '2026-06-28 16:48:14', '2026-06-28 16:48:14', NULL),
(51, 6, 'WhatsApp Image 2026-06-22 at 23.16.40.jpeg', NULL, 'photo', 'galleries/6/media/1782668996_yO91x8MrCF.jpeg', 'WhatsApp Image 2026-06-22 at 23.16.40.jpeg', '137967', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(52, 6, 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_TilphOIWuK.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', '134345', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:51:28', '2026-06-28 16:51:28'),
(53, 6, 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', NULL, 'photo', 'galleries/6/media/1782668996_WXfiU2Znms.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.17.jpeg', '119013', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(54, 6, 'WhatsApp Image 2026-06-22 at 23.23.18.jpeg', NULL, 'photo', 'galleries/6/media/1782668996_XxkUHbPmuT.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.18.jpeg', '76428', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(55, 6, 'WhatsApp Image 2026-06-22 at 23.23.19 (1).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_1ZJMiQtl5e.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (1).jpeg', '88711', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(56, 6, 'WhatsApp Image 2026-06-22 at 23.23.19 (2).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_Z50sxcVPSc.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (2).jpeg', '147484', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(57, 6, 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_LthgI2oazQ.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (3).jpeg', '158900', 'image/jpeg', NULL, 13, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(58, 6, 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_qbeqcyveF6.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', '113910', 'image/jpeg', NULL, 15, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(59, 6, 'WhatsApp Image 2026-06-22 at 23.23.20 (4).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_v3DePzGysT.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.20 (4).jpeg', '88519', 'image/jpeg', NULL, 17, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(60, 6, 'WhatsApp Image 2026-06-22 at 23.23.21 (1).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_gqNZswBsdX.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.21 (1).jpeg', '120803', 'image/jpeg', NULL, 19, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(61, 6, 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_8hYnuSYYwZ.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (1).jpeg', '86671', 'image/jpeg', NULL, 21, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(62, 6, 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', NULL, 'photo', 'galleries/6/media/1782668996_QcT8QQC71x.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59 (3).jpeg', '84656', 'image/jpeg', NULL, 23, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(63, 6, 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', NULL, 'photo', 'galleries/6/media/1782668996_KOcIfn2hiS.jpeg', 'WhatsApp Image 2026-06-22 at 23.26.59.jpeg', '70351', 'image/jpeg', NULL, 25, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(64, 6, 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', NULL, 'photo', 'galleries/6/media/1782668996_mWYqt32div.jpeg', 'WhatsApp Image 2026-06-22 at 23.51.07.jpeg', '83971', 'image/jpeg', NULL, 27, 0, 1, 0, 0, NULL, '2026-06-28 16:49:56', '2026-06-28 16:49:56', NULL),
(65, 5, 'logo.jpg', NULL, 'photo', 'galleries/5/media/1782669275_Ty8JLB9X3M.jpg', 'logo.jpg', '38221', 'image/jpeg', NULL, 1, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL),
(66, 5, 'repetition.jpeg', NULL, 'photo', 'galleries/5/media/1782669275_z5rkMi21mb.jpeg', 'repetition.jpeg', '58038', 'image/jpeg', NULL, 3, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL),
(67, 5, 'WhatsApp Image 2026-06-22 at 23.23.12 (1).jpeg', NULL, 'photo', 'galleries/5/media/1782669275_ELBXYeLoIR.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.12 (1).jpeg', '117778', 'image/jpeg', NULL, 5, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL),
(68, 5, 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', NULL, 'photo', 'galleries/5/media/1782669275_fpAHMC6GCW.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.16 (2).jpeg', '134345', 'image/jpeg', NULL, 7, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL),
(69, 5, 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', NULL, 'photo', 'galleries/5/media/1782669275_0qS84RfESO.jpeg', 'WhatsApp Image 2026-06-22 at 23.23.19 (4).jpeg', '113910', 'image/jpeg', NULL, 9, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL),
(70, 5, 'WhatsApp Image 2026-06-22 at 23.50.13.jpeg', NULL, 'photo', 'galleries/5/media/1782669275_TddXuhTKBH.jpeg', 'WhatsApp Image 2026-06-22 at 23.50.13.jpeg', '74094', 'image/jpeg', NULL, 11, 0, 1, 0, 0, NULL, '2026-06-28 16:54:35', '2026-06-28 16:54:35', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `gallery_media_tags`
--

CREATE TABLE `gallery_media_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `media_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gallery_tags`
--

CREATE TABLE `gallery_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
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
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `album_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` enum('photo','video') NOT NULL DEFAULT 'photo',
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `likes_count` int(11) NOT NULL DEFAULT 0,
  `is_cover` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

CREATE TABLE `members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `member_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `instrument` varchar(255) DEFAULT NULL,
  `instrument_level` enum('debutant','intermediaire','avance','expert') DEFAULT NULL,
  `entry_date` date NOT NULL,
  `section` varchar(255) DEFAULT NULL,
  `section_role` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','suspended','withdrawn','deceased','training') NOT NULL DEFAULT 'pending',
  `id_document` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `user_id`, `member_number`, `first_name`, `last_name`, `email`, `phone`, `whatsapp`, `address`, `birth_date`, `birth_place`, `instrument`, `instrument_level`, `entry_date`, `section`, `section_role`, `photo`, `status`, `id_document`, `emergency_contact_name`, `emergency_contact_phone`, `medical_notes`, `skills`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, 'FC-2026-0001', 'Jean', 'Mendy', 'president@fanfareceleste.org', '+22997000001', NULL, 'Cotonou, Bénin', '1980-01-01', NULL, 'Trompette', NULL, '2010-01-01', 'Cuivres', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:38', '2026-06-28 16:36:38'),
(2, NULL, 'FC-2026-0002', 'Marie', 'Dossou', 'tresorier@fanfareceleste.org', '+22997000002', NULL, 'Cotonou, Bénin', '1980-01-01', NULL, 'Percussion', NULL, '2010-01-01', 'Percussions', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:38', '2026-06-28 16:36:38'),
(3, NULL, 'FC-2026-5713', 'Paul', 'Zinsou', 'secretaire@fanfareceleste.org', '+22997000003', NULL, 'Cotonou, Bénin', '1980-01-01', NULL, 'Saxophone', NULL, '2010-01-01', 'Cuivres', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:39', '2026-06-28 16:36:39'),
(4, NULL, 'FC-2026-5714', 'David', 'Agossou', 'chef@fanfareceleste.org', '+22997000004', NULL, 'Cotonou, Bénin', '1980-01-01', NULL, 'Trompette', NULL, '2010-01-01', 'Cuivres', NULL, NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:39', '2026-06-28 16:36:39'),
(5, NULL, 'FC-2026-4459', 'Marc', 'Rodriguez', 'marc.rodriguez@example.com', '+22993223460', NULL, 'Cotonou, Bénin', '1991-07-19', NULL, 'Trompette', 'avance', '2024-10-07', 'Saxophones', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22992148006', NULL, NULL, NULL, '2026-06-28 16:36:40', '2026-06-28 16:36:40'),
(6, NULL, 'FC-2026-4460', 'Marc', 'Miller', 'marc.miller@example.com', '+22991862085', NULL, 'Cotonou, Bénin', '1998-10-13', NULL, 'Trombone', 'intermediaire', '2019-12-29', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992256734', NULL, NULL, NULL, '2026-06-28 16:36:40', '2026-06-28 16:36:49'),
(7, NULL, 'FC-2026-2428', 'Marc', 'Johnson', 'marc.johnson@example.com', '+22999606616', NULL, 'Cotonou, Bénin', '1986-11-20', NULL, 'Trombone', 'expert', '2020-07-19', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992665399', NULL, NULL, NULL, '2026-06-28 16:36:40', '2026-06-28 16:36:50'),
(8, NULL, 'FC-2026-6828', 'Luc', 'Garcia', 'luc.garcia@example.com', '+22991766553', NULL, 'Cotonou, Bénin', '1985-12-16', NULL, 'Caisse Claire', 'expert', '2024-08-07', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22991228208', NULL, NULL, NULL, '2026-06-28 16:36:40', '2026-06-28 16:36:40'),
(9, NULL, 'FC-2026-1746', 'Marc', 'Davis', 'marc.davis@example.com', '+22997008542', NULL, 'Cotonou, Bénin', '1982-12-21', NULL, 'Caisse Claire', 'debutant', '2015-02-15', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22996114970', NULL, NULL, NULL, '2026-06-28 16:36:41', '2026-06-28 16:36:41'),
(10, NULL, 'FC-2026-1747', 'Luc', 'Davis', 'luc.davis@example.com', '+22994614198', NULL, 'Cotonou, Bénin', '1996-08-11', NULL, 'Batterie', 'expert', '2016-07-29', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993473373', NULL, NULL, NULL, '2026-06-28 16:36:41', '2026-06-28 16:36:41'),
(11, NULL, 'FC-2026-3244', 'Thomas', 'Garcia', 'thomas.garcia@example.com', '+22991896003', NULL, 'Cotonou, Bénin', '1997-03-11', NULL, 'Saxophone Alto', 'debutant', '2020-07-23', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993910847', NULL, NULL, NULL, '2026-06-28 16:36:42', '2026-06-28 16:36:58'),
(12, NULL, 'FC-2026-3245', 'André', 'Brown', 'andré.brown@example.com', '+22994106064', NULL, 'Cotonou, Bénin', '1983-06-29', NULL, 'Batterie', 'avance', '2015-03-11', 'Percussions', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22997358302', NULL, NULL, NULL, '2026-06-28 16:36:42', '2026-06-28 16:36:42'),
(13, NULL, 'FC-2026-2168', 'Michel', 'Martinez', 'michel.martinez@example.com', '+22999044965', NULL, 'Cotonou, Bénin', '1996-04-18', NULL, 'Trombone', 'debutant', '2020-10-01', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22996334431', NULL, NULL, NULL, '2026-06-28 16:36:42', '2026-06-28 16:36:42'),
(14, NULL, 'FC-2026-1787', 'Pierre', 'Rodriguez', 'pierre.rodriguez@example.com', '+22997446630', NULL, 'Cotonou, Bénin', '1998-07-09', NULL, 'Batterie', 'avance', '2022-01-25', 'Saxophones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993428801', NULL, NULL, NULL, '2026-06-28 16:36:43', '2026-06-28 16:36:43'),
(15, NULL, 'FC-2026-1788', 'Pierre', 'Williams', 'pierre.williams@example.com', '+22998404910', NULL, 'Cotonou, Bénin', '1984-11-15', NULL, 'Saxophone Alto', 'debutant', '2015-06-09', 'Trompettes', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22991417561', NULL, NULL, NULL, '2026-06-28 16:36:43', '2026-06-28 16:36:43'),
(16, NULL, 'FC-2026-9928', 'Pierre', 'Davis', 'pierre.davis@example.com', '+22992244539', NULL, 'Cotonou, Bénin', '1997-01-10', NULL, 'Batterie', 'intermediaire', '2020-01-03', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992402360', NULL, NULL, NULL, '2026-06-28 16:36:43', '2026-06-28 16:36:43'),
(17, NULL, 'FC-2026-8523', 'Nicolas', 'Jones', 'nicolas.jones@example.com', '+22994351630', NULL, 'Cotonou, Bénin', '1986-02-02', NULL, 'Saxophone Alto', 'avance', '2022-03-31', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993362668', NULL, NULL, NULL, '2026-06-28 16:36:44', '2026-06-28 16:36:44'),
(18, NULL, 'FC-2026-8524', 'Philippe', 'Garcia', 'philippe.garcia@example.com', '+22990607676', NULL, 'Cotonou, Bénin', '1988-01-30', NULL, 'Saxophone Alto', 'intermediaire', '2015-08-15', 'Trompettes', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22994395733', NULL, NULL, NULL, '2026-06-28 16:36:44', '2026-06-28 16:36:44'),
(19, NULL, 'FC-2026-9035', 'François', 'Rodriguez', 'françois.rodriguez@example.com', '+22991115577', NULL, 'Cotonou, Bénin', '1987-11-16', NULL, 'Caisse Claire', 'debutant', '2015-10-22', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22998928655', NULL, NULL, NULL, '2026-06-28 16:36:44', '2026-06-28 16:36:44'),
(20, NULL, 'FC-2026-3209', 'Thomas', 'Johnson', 'thomas.johnson@example.com', '+22999581249', NULL, 'Cotonou, Bénin', '1990-06-26', NULL, 'Saxophone Alto', 'expert', '2021-11-17', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993837991', NULL, NULL, NULL, '2026-06-28 16:36:45', '2026-06-28 16:36:45'),
(21, NULL, 'FC-2026-3210', 'Philippe', 'Johnson', 'philippe.johnson@example.com', '+22993240621', NULL, 'Cotonou, Bénin', '1985-06-10', NULL, 'Trombone', 'intermediaire', '2015-02-02', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993236292', NULL, NULL, NULL, '2026-06-28 16:36:45', '2026-06-28 16:36:51'),
(22, NULL, 'FC-2026-4075', 'Luc', 'Hernandez', 'luc.hernandez@example.com', '+22999607676', NULL, 'Cotonou, Bénin', '1982-04-12', NULL, 'Trompette', 'expert', '2017-11-16', 'Saxophones', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22999209662', NULL, NULL, NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(23, NULL, 'FC-2026-4076', 'André', 'Miller', 'andré.miller@example.com', '+22996791049', NULL, 'Cotonou, Bénin', '1985-11-24', NULL, 'Trompette', 'avance', '2019-08-17', 'Trombones', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22999978515', NULL, NULL, NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(24, NULL, 'FC-2026-7938', 'Jean', 'Hernandez', 'jean.hernandez@example.com', '+22997969450', NULL, 'Cotonou, Bénin', '1983-02-17', NULL, 'Batterie', 'expert', '2017-02-06', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992793843', NULL, NULL, NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(25, NULL, 'FC-2026-6593', 'Philippe', 'Hernandez', 'philippe.hernandez@example.com', '+22992570747', NULL, 'Cotonou, Bénin', '1988-03-26', NULL, 'Batterie', 'intermediaire', '2022-07-21', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22997836414', NULL, NULL, NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(26, NULL, 'FC-2026-8201', 'Pierre', 'Johnson', 'pierre.johnson@example.com', '+22999826297', NULL, 'Cotonou, Bénin', '1988-05-18', NULL, 'Batterie', 'expert', '2024-04-01', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993617200', NULL, NULL, NULL, '2026-06-28 16:36:47', '2026-06-28 16:36:56'),
(27, NULL, 'FC-2026-8202', 'Nicolas', 'Hernandez', 'nicolas.hernandez@example.com', '+22993570500', NULL, 'Cotonou, Bénin', '1991-12-12', NULL, 'Trompette', 'intermediaire', '2018-06-21', 'Trompettes', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22990103316', NULL, NULL, NULL, '2026-06-28 16:36:48', '2026-06-28 16:36:48'),
(28, NULL, 'FC-2026-8203', 'François', 'Miller', 'françois.miller@example.com', '+22993766219', NULL, 'Cotonou, Bénin', '1997-09-07', NULL, 'Trombone', 'avance', '2020-07-11', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22994519152', NULL, NULL, NULL, '2026-06-28 16:36:48', '2026-06-28 16:36:48'),
(29, NULL, 'FC-2026-3952', 'Luc', 'Martinez', 'luc.martinez@example.com', '+22994341150', NULL, 'Cotonou, Bénin', '1997-08-08', NULL, 'Trompette', 'avance', '2024-12-09', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22994039854', NULL, NULL, NULL, '2026-06-28 16:36:48', '2026-06-28 16:36:58'),
(30, NULL, 'FC-2026-8196', 'Philippe', 'Davis', 'philippe.davis@example.com', '+22993612591', NULL, 'Cotonou, Bénin', '1988-12-18', NULL, 'Saxophone Alto', 'expert', '2019-01-03', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22996952584', NULL, NULL, NULL, '2026-06-28 16:36:49', '2026-06-28 16:36:50'),
(31, NULL, 'FC-2026-8197', 'Nicolas', 'Johnson', 'nicolas.johnson@example.com', '+22993657565', NULL, 'Cotonou, Bénin', '1989-05-17', NULL, 'Batterie', 'debutant', '2016-03-17', 'Trompettes', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22997620933', NULL, NULL, NULL, '2026-06-28 16:36:50', '2026-06-28 16:36:50'),
(32, NULL, 'FC-2026-8198', 'Marc', 'Martinez', 'marc.martinez@example.com', '+22993087464', NULL, 'Cotonou, Bénin', '1990-09-03', NULL, 'Trombone', 'avance', '2019-10-27', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993922209', NULL, NULL, NULL, '2026-06-28 16:36:50', '2026-06-28 16:36:50'),
(33, NULL, 'FC-2026-5249', 'André', 'Hernandez', 'andré.hernandez@example.com', '+22999374342', NULL, 'Cotonou, Bénin', '1988-02-17', NULL, 'Trompette', 'expert', '2015-06-23', 'Saxophones', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22990383188', NULL, NULL, NULL, '2026-06-28 16:36:51', '2026-06-28 16:36:51'),
(34, NULL, 'FC-2026-5250', 'Thomas', 'Williams', 'thomas.williams@example.com', '+22998004128', NULL, 'Cotonou, Bénin', '1998-04-24', NULL, 'Saxophone Alto', 'intermediaire', '2015-05-01', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22993127887', NULL, NULL, NULL, '2026-06-28 16:36:52', '2026-06-28 16:36:52'),
(35, NULL, 'FC-2026-5251', 'François', 'Johnson', 'françois.johnson@example.com', '+22998034450', NULL, 'Cotonou, Bénin', '1985-03-03', NULL, 'Trombone', 'avance', '2018-07-21', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22997394076', NULL, NULL, NULL, '2026-06-28 16:36:52', '2026-06-28 16:36:52'),
(36, NULL, 'FC-2026-2952', 'Jean', 'Martinez', 'jean.martinez@example.com', '+22991594301', NULL, 'Cotonou, Bénin', '1988-08-15', NULL, 'Trombone', 'expert', '2015-11-15', 'Percussions', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22992240608', NULL, NULL, NULL, '2026-06-28 16:36:53', '2026-06-28 16:36:53'),
(37, NULL, 'FC-2026-2953', 'Luc', 'Miller', 'luc.miller@example.com', '+22993439358', NULL, 'Cotonou, Bénin', '1980-08-13', NULL, 'Batterie', 'expert', '2023-11-26', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22994155296', NULL, NULL, NULL, '2026-06-28 16:36:53', '2026-06-28 16:36:53'),
(38, NULL, 'FC-2026-0691', 'François', 'Martinez', 'françois.martinez@example.com', '+22990374195', NULL, 'Cotonou, Bénin', '1980-10-09', NULL, 'Saxophone Alto', 'intermediaire', '2022-07-27', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22994568832', NULL, NULL, NULL, '2026-06-28 16:36:53', '2026-06-28 16:36:53'),
(39, NULL, 'FC-2026-1677', 'Jean', 'Johnson', 'jean.johnson@example.com', '+22994884477', NULL, 'Cotonou, Bénin', '1994-08-30', NULL, 'Batterie', 'debutant', '2016-07-21', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992790892', NULL, NULL, NULL, '2026-06-28 16:36:54', '2026-06-28 16:36:54'),
(40, NULL, 'FC-2026-1678', 'Marc', 'Williams', 'marc.williams@example.com', '+22996717756', NULL, 'Cotonou, Bénin', '1984-08-07', NULL, 'Batterie', 'avance', '2019-12-17', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992816984', NULL, NULL, NULL, '2026-06-28 16:36:55', '2026-06-28 16:36:55'),
(41, NULL, 'FC-2026-1679', 'Marc', 'Jones', 'marc.jones@example.com', '+22994486973', NULL, 'Cotonou, Bénin', '1997-09-24', NULL, 'Saxophone Alto', 'avance', '2023-12-26', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22997918413', NULL, NULL, NULL, '2026-06-28 16:36:55', '2026-06-28 16:36:55'),
(42, NULL, 'FC-2026-2520', 'André', 'Jones', 'andré.jones@example.com', '+22990254057', NULL, 'Cotonou, Bénin', '1997-02-28', NULL, 'Saxophone Alto', 'intermediaire', '2020-01-21', 'Trompettes', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22991700848', NULL, NULL, NULL, '2026-06-28 16:36:56', '2026-06-28 16:36:56'),
(43, NULL, 'FC-2026-2521', 'André', 'Johnson', 'andré.johnson@example.com', '+22990506239', NULL, 'Cotonou, Bénin', '1983-10-19', NULL, 'Batterie', 'debutant', '2022-05-07', 'Percussions', NULL, NULL, 'training', NULL, 'Contact Urgence', '+22999516116', NULL, NULL, NULL, '2026-06-28 16:36:56', '2026-06-28 16:36:56'),
(44, NULL, 'FC-2026-2308', 'Nicolas', 'Brown', 'nicolas.brown@example.com', '+22996215468', NULL, 'Cotonou, Bénin', '1989-03-04', NULL, 'Batterie', 'avance', '2018-12-31', 'Trombones', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22997959242', NULL, NULL, NULL, '2026-06-28 16:36:57', '2026-06-28 16:36:57'),
(45, NULL, 'FC-2026-2309', 'Luc', 'Williams', 'luc.williams@example.com', '+22996693258', NULL, 'Cotonou, Bénin', '1991-10-28', NULL, 'Trombone', 'debutant', '2018-07-28', 'Percussions', NULL, NULL, 'active', NULL, 'Contact Urgence', '+22992367873', NULL, NULL, NULL, '2026-06-28 16:36:58', '2026-06-28 16:36:58'),
(46, NULL, 'FC-2026-9661', 'Super', 'Administrateur', 'admin@fanfareceleste.org', '+229 97 00 00 00', NULL, 'Non renseigné', '2000-01-01', 'Non renseigné', 'Saxophone Alto', 'expert', '2026-06-28', 'Saxophones', 'Membre', 'members/photos/WtBTQSA0m6LgSQpOFLBG1vzvmIjgOzk4j5wAmc0A.jpg', 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:59', '2026-06-28 17:12:09'),
(47, NULL, 'FC-2026-0499', 'Administrateur', 'Admin', 'admin@fanfare.com', '+229 97 00 00 00', NULL, 'Non renseigné', '2000-01-01', 'Non renseigné', 'Non renseigné', 'debutant', '2026-06-28', 'Non renseigné', 'Membre', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:59', '2026-06-28 16:36:59'),
(48, NULL, 'FC-2026-6224', 'Membre', 'Admin', 'membre@fanfareceleste.org', '+229 97 00 00 00', NULL, 'Non renseigné', '2000-01-01', 'Non renseigné', 'Non renseigné', 'debutant', '2026-06-28', 'Non renseigné', 'Membre', NULL, 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-28 16:36:59', '2026-06-28 16:36:59'),
(49, NULL, 'FC-2026-9662', 'nestor', 'atchougo', 'chrislovenestor@gmail.com', '+22998123353', NULL, 'cotonou', '2026-06-28', NULL, NULL, NULL, '2026-06-28', NULL, NULL, 'members/photos/XWezjnfzGWZk2GvB9BVSuYfQOXXkDoGv9m9DEZNG.webp', 'active', 'members/documents/aSG0mdZMVsOjAgyx2PSgHGfwHaT7o3WgUH4fj5A4.pdf', NULL, NULL, NULL, NULL, NULL, '2026-06-28 17:21:57', '2026-06-29 09:37:53'),
(50, NULL, 'FC-2026-9663', 'Illimath', 'SOULEYMANE', 'test@test.test', '40146182', NULL, 'cotonou', '2026-06-04', NULL, NULL, NULL, '2026-06-29', NULL, NULL, 'members/photos/lHMIsfPBmZIndCMBf2hWlOaDcEvXvLG2Jl1fkHmv.jpg', 'pending', 'members/documents/7hCaoY96iIbpuN6KNrqeer9idWnmsnpqxk11Gijk.pdf', NULL, NULL, NULL, NULL, NULL, '2026-06-29 08:44:09', '2026-06-29 08:44:09'),
(51, NULL, 'FC-2026-9664', 'Nestor', 'ATCHOUGO', 'sounesatchougo@gmail.com', '+22998123353', '98123353', 'cotonou', '2026-06-01', NULL, NULL, NULL, '2026-06-29', NULL, NULL, 'members/photos/d6WjRYqJokv1P4FP8TnKLDQtFJhNgCE7YlpKObtm.jpg', 'pending', 'members/documents/P4TN5kIjYopCOQrbLLIpNB5acJm4KoHkLBMbJBmS.pdf', NULL, NULL, NULL, NULL, 'ok', '2026-06-29 08:54:33', '2026-06-29 08:54:33'),
(53, NULL, 'FC-2026-9665', 'Nestor', 'ATCHOUGO', 'sounoesatchougo@gmail.com', '+22998123353', NULL, 'cotonou', '2026-06-04', NULL, NULL, NULL, '2026-06-29', NULL, NULL, 'members/photos/zGSMcBPhR94KToIqvEiZUODonQhifEDTgmJXWXTs.jpg', 'pending', 'members/documents/EljxuMKbkJtuWT9QN6fDZWMBS29L7rlyf5AY20p2.pdf', NULL, NULL, NULL, NULL, 'bv', '2026-06-29 09:14:55', '2026-06-29 09:37:16');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 38),
(5, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 6),
(8, 'App\\Models\\User', 7),
(8, 'App\\Models\\User', 38),
(8, 'App\\Models\\User', 39),
(8, 'App\\Models\\User', 40),
(8, 'App\\Models\\User', 42);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `cover_image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `template` varchar(255) NOT NULL DEFAULT 'default',
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `category` enum('official','sponsor','media','cultural','institutional') NOT NULL DEFAULT 'official',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `partners`
--

INSERT INTO `partners` (`id`, `name`, `logo`, `description`, `website`, `category`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Ministère de la Culture', 'partners/logos/1782668219_ministere-de-la-culture.jpg', 'Soutien aux activités culturelles et artistiques', 'https://www.culture.bj', 'institutional', 1, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(2, 'Église Évangélique du Bénin', 'partners/logos/1782668219_eglise-evangelique.jpg', 'Partenaire spirituel et institutionnel', NULL, 'official', 2, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(3, 'Radio Lumière', 'partners/logos/1782668219_radio-lumiere.jpg', 'Diffusion de nos concerts et événements', 'https://www.radiolumiere.bj', 'media', 3, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(4, 'ORCA Bénin', 'partners/logos/1782668219_orca-benin.jpg', 'Sponsor officiel de la fanfare', 'https://www.orca.bj', 'sponsor', 4, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(5, 'Institut Français du Bénin', 'partners/logos/1782668219_institut-francais.jpg', 'Partenariat culturel et échanges artistiques', 'https://www.institutfrancais-benin.com', 'cultural', 5, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(6, 'Ambassade de France', 'partners/logos/1782668219_ambassade-de-france.jpg', 'Soutien aux échanges culturels', 'https://www.ambafrance-bj.org', 'institutional', 6, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(7, 'Canal 3 Bénin', 'partners/logos/1782668219_canal-3-benin.jpg', 'Couverture médiatique des événements', 'https://www.canal3benin.com', 'media', 7, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00'),
(8, 'MTN Bénin', 'partners/logos/1782668220_mtn-benin.jpg', 'Sponsor principal de la fanfare', 'https://www.mtn.bj', 'sponsor', 8, 1, '2026-06-28 16:37:00', '2026-06-28 16:37:00');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(2, 'create members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(3, 'edit members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(4, 'delete members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(5, 'approve members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(6, 'manage members', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(7, 'view finances', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(8, 'create transactions', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(9, 'approve transactions', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(10, 'export reports', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(11, 'view events', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(12, 'create events', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(13, 'edit events', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(14, 'delete events', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(15, 'manage participations', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(16, 'view committee', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(17, 'manage committee', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(18, 'view documents', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(19, 'upload documents', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(20, 'delete documents', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(21, 'approve documents', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(22, 'manage pages', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(23, 'manage carousels', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(24, 'manage testimonials', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(25, 'manage news', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(26, 'manage gallery', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(27, 'manage partners', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(28, 'manage roles', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(29, 'view logs', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(30, 'manage settings', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34');

-- --------------------------------------------------------

--
-- Structure de la table `rehearsals`
--

CREATE TABLE `rehearsals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rehearsal_attendances`
--

CREATE TABLE `rehearsal_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(2, 'admin', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(3, 'president', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(4, 'treasurer', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(5, 'secretary', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(6, 'conductor', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(7, 'section-leader', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34'),
(8, 'member', 'web', '2026-06-28 16:36:34', '2026-06-28 16:36:34');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 1),
(2, 2),
(2, 5),
(3, 1),
(3, 2),
(3, 5),
(3, 6),
(4, 1),
(4, 2),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(6, 5),
(7, 1),
(7, 2),
(7, 3),
(7, 4),
(8, 1),
(8, 2),
(8, 4),
(9, 1),
(9, 2),
(9, 3),
(9, 4),
(10, 1),
(10, 2),
(10, 4),
(11, 1),
(11, 2),
(11, 3),
(11, 4),
(11, 5),
(11, 6),
(11, 7),
(11, 8),
(12, 1),
(12, 2),
(12, 3),
(12, 5),
(12, 6),
(13, 1),
(13, 2),
(13, 3),
(13, 5),
(13, 6),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(15, 5),
(15, 6),
(15, 7),
(16, 1),
(16, 2),
(16, 3),
(16, 5),
(17, 1),
(17, 2),
(17, 3),
(18, 1),
(18, 2),
(18, 3),
(18, 4),
(18, 5),
(18, 6),
(18, 8),
(19, 1),
(19, 2),
(19, 3),
(19, 5),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(21, 3),
(21, 5),
(22, 1),
(22, 2),
(22, 3),
(22, 5),
(23, 1),
(23, 2),
(23, 5),
(24, 1),
(24, 2),
(24, 5),
(25, 1),
(25, 2),
(25, 5),
(26, 1),
(26, 2),
(26, 5),
(27, 1),
(27, 2),
(27, 5),
(28, 1),
(29, 1),
(29, 2),
(29, 3),
(30, 1),
(30, 2),
(30, 5);

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
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
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AHhOT6fEDJOrlVczKWxrxmrcVKRhkqWQCgk96Nem', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJocHlTYnhLMG1tc1lIY0duWWVwVjBtVGtRanFWZWRoS2hpT2xvaGZyIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvbWVtYmVyc1wvZXhwb3J0XC9hbGwiLCJyb3V0ZSI6ImFkbWluLm1lbWJlcnMuZXhwb3J0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1782730851),
('GVPvaofyIUHeNRjbJIlqG9VJz5cZwCJdwE6cswhE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWU0sxbTZDTm1iSmNUT0wwSG5FemVxRVNnc2xQVFZ4OGlLdmxZYVZ4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC92aWRlb3NcL0VVQ3h1blI1YmVZIiwicm91dGUiOiJ2aWRlb3Muc2hvdyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1782761697),
('Y9uCpaIidCtGgnupqU8WZCKvXmbBpXGtvEmLUwpp', 38, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5SjUxT1ZPVGFPOGdNbTFZSXR6TnZqZzlQNjJFY3Z0VTFFQXN0ZzNlIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3ZpZGVvc1wvZzhaS3gyR2h6NTgiLCJyb3V0ZSI6InZpZGVvcy5zaG93In0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozOH0=', 1782735393),
('ZrYy8RDaQZL2s6xFe0k7fOKG8ofG8kSIhyjaB9OY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJzdVFrSEtSdGJOaGE2b1RCU2xwWU5pdFJCeDFWVG93Y2JIOVNEWElTIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1782761695);

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_role` varchar(255) DEFAULT NULL,
  `author_image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` int(11) DEFAULT NULL COMMENT 'De 1 à 5 étoiles',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `testimonials`
--

INSERT INTO `testimonials` (`id`, `author_name`, `author_role`, `author_image`, `content`, `rating`, `is_approved`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(1, 'jean', NULL, 'testimonials/roR9OCQ9NXdovgOmTjsL3uXvhkvDvgi7SFLvyXHz.jpg', 'test', 5, 0, 1, 0, '2026-06-28 17:00:39', '2026-06-28 17:00:39');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `member_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `member_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 46, 'Super Administrateur', 'admin@fanfareceleste.org', '2026-06-28 16:36:35', '$2y$12$UU2mlVaXULVcrV4JqV/54.cX.8PNaBRmwXgOp3dE8x8vjC1UbkDv.', '5HM3kYB4nZWlSzLa4SBGENYswL87IPPlFQ3XiCA2kgMu5Vs8lXTOkzCLnAEG', '2026-06-28 16:36:35', '2026-06-28 17:12:10'),
(2, 47, 'Administrateur', 'admin@fanfare.com', '2026-06-28 16:36:35', '$2y$12$ZalYWQF2D8W7fKBuOkUcSu49YRPsy1NE.QWzGPJjJ7naugielJfDS', NULL, '2026-06-28 16:36:35', '2026-06-28 16:36:59'),
(3, 1, 'Président', 'president@fanfareceleste.org', '2026-06-28 16:36:36', '$2y$12$VU3cATFhy/XrupkhNgY6.uqeVN.Vb/1XQKMVX8ISWqjTcDrNkc.iW', NULL, '2026-06-28 16:36:36', '2026-06-28 16:36:59'),
(4, 3, 'Secrétaire', 'secretaire@fanfareceleste.org', '2026-06-28 16:36:36', '$2y$12$BhepLIVtu8zz.hz/sMzErO9QnCA91Zx5hih8fWA1/FLcGmwoleTLS', NULL, '2026-06-28 16:36:36', '2026-06-28 16:36:59'),
(5, 2, 'Trésorier', 'tresorier@fanfareceleste.org', '2026-06-28 16:36:37', '$2y$12$Y5LQ/LOjZ.mmefGlp83TfOZL6n/KVnpfiH3BssPd4ONTWA18y5sDq', NULL, '2026-06-28 16:36:37', '2026-06-28 16:36:59'),
(6, 4, 'Chef de fanfare', 'chef@fanfareceleste.org', '2026-06-28 16:36:37', '$2y$12$sg59veND5mqTZ/YmAW.MuewuI.gvCYGhyWu5Ll3A6G4KQITFqmb/K', NULL, '2026-06-28 16:36:37', '2026-06-28 16:36:59'),
(7, 48, 'Membre', 'membre@fanfareceleste.org', '2026-06-28 16:36:38', '$2y$12$8HEFhojqolejStl0HRz5M.TdRFnRIVDe3X.3/wxnhpO.ZSqxdWBQ2', NULL, '2026-06-28 16:36:38', '2026-06-28 16:36:59'),
(8, 6, 'Marc Miller', 'marc.miller@example.com', NULL, '$2y$12$aOz2xEX18XGTz1XE9d0JwO1c9QIP2qyLJdP522YD7rjv/NtkZTlsq', NULL, '2026-06-28 16:36:40', '2026-06-28 16:36:40'),
(9, 8, 'Luc Garcia', 'luc.garcia@example.com', NULL, '$2y$12$8Ka/Jx0B7A7xVF5AWFDYwOSk81AZSiLefwjvwHHO3WVsT/dl0YiBW', NULL, '2026-06-28 16:36:41', '2026-06-28 16:36:41'),
(10, 9, 'Marc Davis', 'marc.davis@example.com', NULL, '$2y$12$LZkP1T75H/W0qZVrdnHpw.pfeOnv/8ZpItBNkGblm//7t4BlbK.N2', NULL, '2026-06-28 16:36:41', '2026-06-28 16:36:41'),
(11, 10, 'Luc Davis', 'luc.davis@example.com', NULL, '$2y$12$iOIjWCvOLGwUo/loS6fkbOweJ0MY/pE7HtmqozhZQJ3.nEEOVXwZ.', NULL, '2026-06-28 16:36:42', '2026-06-28 16:36:42'),
(12, 13, 'Michel Martinez', 'michel.martinez@example.com', NULL, '$2y$12$cQdNKeNLLBgHwAzP2lB7FeFG/U6bSmaTIBUsorh.wDuHagbKPnjPK', NULL, '2026-06-28 16:36:42', '2026-06-28 16:36:42'),
(13, 14, 'Pierre Rodriguez', 'pierre.rodriguez@example.com', NULL, '$2y$12$egin9BUkzSl9.Fxd6/U23O659psgjNVkJ13FOrprlgrdgfL77f7tO', NULL, '2026-06-28 16:36:43', '2026-06-28 16:36:43'),
(14, 16, 'Pierre Davis', 'pierre.davis@example.com', NULL, '$2y$12$fJb6itbB0Jjzfo2oEaSyt.cvoFB4FQpT53eYoXydeTNUyaoGIAlQ2', NULL, '2026-06-28 16:36:44', '2026-06-28 16:36:44'),
(15, 17, 'Nicolas Jones', 'nicolas.jones@example.com', NULL, '$2y$12$qrcfn42iLJSkroA8JYbfQuVLi6.RCvFpLrbjj8q7wDk3.P9WNw0l2', NULL, '2026-06-28 16:36:44', '2026-06-28 16:36:44'),
(16, 19, 'François Rodriguez', 'françois.rodriguez@example.com', NULL, '$2y$12$UXyVK.OqKDx3gjHkcvG/y../A9KJ4K11E5CBupuLC3oEvw4hGrIwq', NULL, '2026-06-28 16:36:45', '2026-06-28 16:36:45'),
(17, 20, 'Thomas Johnson', 'thomas.johnson@example.com', NULL, '$2y$12$x2/YGpcl7Jjc.syMsLXgieyGBupVuLVDhEYuAL/vMwu/aw7pUIYA2', NULL, '2026-06-28 16:36:45', '2026-06-28 16:36:45'),
(18, 21, 'Philippe Johnson', 'philippe.johnson@example.com', NULL, '$2y$12$tinz0ofLiKb2xJBmKO4EjOQkGsDNT.x67unXz.Gc6xtWflc5N/Ea6', NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(19, 24, 'Jean Hernandez', 'jean.hernandez@example.com', NULL, '$2y$12$pmsLesv4jM7tWcpL9SG5C.LrowbN6.VlaoP4pdq.U9Wo1KkchFY1e', NULL, '2026-06-28 16:36:46', '2026-06-28 16:36:46'),
(20, 25, 'Philippe Hernandez', 'philippe.hernandez@example.com', NULL, '$2y$12$jq6nVR6I9/agw77Vn6FkAOtTlcIJBp8j4wJOwyD5Kkh2gtqNLKWTy', NULL, '2026-06-28 16:36:47', '2026-06-28 16:36:47'),
(21, 26, 'Pierre Johnson', 'pierre.johnson@example.com', NULL, '$2y$12$8qG80xIf17H3cfItqfZwQ.h/eg6yKmrkG06T9URxIQSaCl/u95YNe', NULL, '2026-06-28 16:36:48', '2026-06-28 16:36:48'),
(22, 28, 'François Miller', 'françois.miller@example.com', NULL, '$2y$12$VKj80WiQTetWw3ZstEqfTuKvt.ONLJ8Xy0GbNq3WaD4W5gaGZUW.2', NULL, '2026-06-28 16:36:48', '2026-06-28 16:36:48'),
(23, 29, 'Luc Martinez', 'luc.martinez@example.com', NULL, '$2y$12$Itq7xS9iS2NlKUXM6cYoj.EvIHnfHr9vFoTrAdYIo71Hg6naLuK6S', NULL, '2026-06-28 16:36:49', '2026-06-28 16:36:49'),
(24, 30, 'Philippe Davis', 'philippe.davis@example.com', NULL, '$2y$12$wa7RCw6uKrPYT4xo285wCu6PftzuzL.1mCkrPhD8Fx4fcbRfOYVa.', NULL, '2026-06-28 16:36:49', '2026-06-28 16:36:49'),
(25, 32, 'Marc Martinez', 'marc.martinez@example.com', NULL, '$2y$12$QNqTaJaEgz3VJO28xtrMiOyoY4jv9Z7f6txHEnOeAMsuEbUzm2z2G', NULL, '2026-06-28 16:36:50', '2026-06-28 16:36:50'),
(26, 7, 'Marc Johnson', 'marc.johnson@example.com', NULL, '$2y$12$dFsyb/8nHJvaFfr3smSFBe66wR8t8JlPY333NPvRYWtJp4x8Ezzoq', NULL, '2026-06-28 16:36:50', '2026-06-28 16:36:50'),
(27, 34, 'Thomas Williams', 'thomas.williams@example.com', NULL, '$2y$12$MQsmTpMKWUluwy2so4p2LeMU7UgMnJyt843OqBZcbdy5tLFwHx.t6', NULL, '2026-06-28 16:36:52', '2026-06-28 16:36:52'),
(28, 35, 'François Johnson', 'françois.johnson@example.com', NULL, '$2y$12$J0Om8XirS/iAPwbTUx3syeclHGxr0ekYH1HBRszY28Czeeb3TyaHG', NULL, '2026-06-28 16:36:52', '2026-06-28 16:36:52'),
(29, 37, 'Luc Miller', 'luc.miller@example.com', NULL, '$2y$12$l5MsVFqVbXBcwKgyPOHoMOe2TdWnW9Et4FI51GZaQ75jtOYUjQTDa', NULL, '2026-06-28 16:36:53', '2026-06-28 16:36:53'),
(30, 38, 'François Martinez', 'françois.martinez@example.com', NULL, '$2y$12$fKKwMFKOrug27TWM2NNDg.xM/R7Ji0CXhGeVI8W.046NM.shiOf0y', NULL, '2026-06-28 16:36:54', '2026-06-28 16:36:54'),
(31, 39, 'Jean Johnson', 'jean.johnson@example.com', NULL, '$2y$12$DXyNuQ21iiLCn6yMASbNre.6tdTyjKg4BeCqcYiR.Bb0OjejERFei', NULL, '2026-06-28 16:36:54', '2026-06-28 16:36:54'),
(32, 40, 'Marc Williams', 'marc.williams@example.com', NULL, '$2y$12$cPcTsI0sm7bwZDOrcATWsuB6gDiLQegjPDTAq.3drRO1EX86hL5su', NULL, '2026-06-28 16:36:55', '2026-06-28 16:36:55'),
(33, 41, 'Marc Jones', 'marc.jones@example.com', NULL, '$2y$12$5e1JTd2FZDOjg8tApq7lt.zUyST8f3swoKdkN71Gk3zW0iY7M4U06', NULL, '2026-06-28 16:36:55', '2026-06-28 16:36:55'),
(34, 42, 'André Jones', 'andré.jones@example.com', NULL, '$2y$12$kG6Z0EVR78xpiTo7fuztuubQm46.Oy3G1OORgn8aXAT2A8tYmcsfu', NULL, '2026-06-28 16:36:56', '2026-06-28 16:36:56'),
(35, 44, 'Nicolas Brown', 'nicolas.brown@example.com', NULL, '$2y$12$3dssJHZkk3dv/uLvVqcMQ.nXRVvlaT1h5SFE4ZnKA8USYoEWXCZCW', NULL, '2026-06-28 16:36:58', '2026-06-28 16:36:58'),
(36, 11, 'Thomas Garcia', 'thomas.garcia@example.com', NULL, '$2y$12$loHM2dS46McvdnAEpEddxu515PrCfst.944bYPZl7wmFbgqo5FOzy', NULL, '2026-06-28 16:36:58', '2026-06-28 16:36:58'),
(37, 45, 'Luc Williams', 'luc.williams@example.com', NULL, '$2y$12$dgWzB34nXNLvtf9vjuqcpOm1CVkBnZFqmvMvJY3no/H78EvJxeBim', NULL, '2026-06-28 16:36:59', '2026-06-28 16:36:59'),
(38, 49, 'nestor atchougo', 'chrislovenestor@gmail.com', NULL, '$2y$12$8sklSba2WqICm56BoMxc3OzqdiKPQy08qlh8NKk53gai3fSRW1tsu', NULL, '2026-06-28 17:21:58', '2026-06-28 17:24:08'),
(39, 50, 'Illimath SOULEYMANE', 'test@test.test', NULL, '$2y$12$kR0fjX262hYlYRpDhUJRi.qEq0QK9q2u3aVIO2ktsnvDMl2b9KZNS', NULL, '2026-06-29 08:44:10', '2026-06-29 08:44:10'),
(40, 51, 'Nestor ATCHOUGO', 'sounesatchougo@gmail.com', NULL, '$2y$12$lpg1ldAWf/YRr9umZQRFnOD7Uta4er/wLtC/HOuoDK2kLv6feZzR6', NULL, '2026-06-29 08:54:34', '2026-06-29 08:54:34'),
(42, 53, 'Nestor ATCHOUGO', 'sounoesatchougo@gmail.com', NULL, '$2y$12$BSj2rlujrRnO.FXdDxIg4.bLuMQVawp7bgmYMZkkSqNQBDO.CT7w2', NULL, '2026-06-29 09:14:56', '2026-06-29 09:14:56');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `albums_slug_unique` (`slug`),
  ADD KEY `albums_created_by_foreign` (`created_by`),
  ADD KEY `albums_category_is_published_order_index` (`category`,`is_published`,`order`),
  ADD KEY `albums_slug_index` (`slug`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `carousels`
--
ALTER TABLE `carousels`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `committee_members`
--
ALTER TABLE `committee_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `committee_members_member_id_foreign` (`member_id`);

--
-- Index pour la table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contributions_receipt_number_unique` (`receipt_number`),
  ADD KEY `contributions_member_id_foreign` (`member_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_uploaded_by_foreign` (`uploaded_by`),
  ADD KEY `documents_member_id_foreign` (`member_id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_responsible_id_foreign` (`responsible_id`),
  ADD KEY `events_start_date_index` (`start_date`),
  ADD KEY `events_status_index` (`status`),
  ADD KEY `events_type_index` (`type`);

--
-- Index pour la table `event_participations`
--
ALTER TABLE `event_participations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_participations_event_id_foreign` (`event_id`),
  ADD KEY `event_participations_member_id_foreign` (`member_id`);

--
-- Index pour la table `event_public_registrations`
--
ALTER TABLE `event_public_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_public_registrations_event_id_whatsapp_number_index` (`event_id`,`whatsapp_number`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Index pour la table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_transactions_member_id_foreign` (`member_id`),
  ADD KEY `financial_transactions_approved_by_foreign` (`approved_by`),
  ADD KEY `financial_transactions_transaction_date_index` (`transaction_date`),
  ADD KEY `financial_transactions_status_index` (`status`),
  ADD KEY `financial_transactions_type_index` (`type`);

--
-- Index pour la table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `galleries_slug_unique` (`slug`),
  ADD KEY `galleries_created_by_foreign` (`created_by`),
  ADD KEY `galleries_category_is_published_order_index` (`category`,`is_published`,`order`),
  ADD KEY `galleries_slug_index` (`slug`);

--
-- Index pour la table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gallery_media_gallery_id_order_index` (`gallery_id`,`order`),
  ADD KEY `gallery_media_type_index` (`type`),
  ADD KEY `gallery_media_is_published_index` (`is_published`);

--
-- Index pour la table `gallery_media_tags`
--
ALTER TABLE `gallery_media_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gallery_media_tags_media_id_tag_id_unique` (`media_id`,`tag_id`),
  ADD KEY `gallery_media_tags_tag_id_foreign` (`tag_id`);

--
-- Index pour la table `gallery_tags`
--
ALTER TABLE `gallery_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gallery_tags_name_unique` (`name`),
  ADD UNIQUE KEY `gallery_tags_slug_unique` (`slug`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_album_id_order_index` (`album_id`,`order`),
  ADD KEY `media_type_index` (`type`),
  ADD KEY `media_is_published_index` (`is_published`);

--
-- Index pour la table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `members_member_number_unique` (`member_number`),
  ADD UNIQUE KEY `members_email_unique` (`email`),
  ADD KEY `members_member_number_index` (`member_number`),
  ADD KEY `members_status_index` (`status`),
  ADD KEY `members_section_index` (`section`),
  ADD KEY `members_instrument_index` (`instrument`),
  ADD KEY `members_user_id_foreign` (`user_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_slug_unique` (`slug`);

--
-- Index pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`),
  ADD KEY `pages_parent_id_foreign` (`parent_id`);

--
-- Index pour la table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `rehearsals`
--
ALTER TABLE `rehearsals`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `rehearsal_attendances`
--
ALTER TABLE `rehearsal_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Index pour la table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_member_id_foreign` (`member_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `carousels`
--
ALTER TABLE `carousels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `committee_members`
--
ALTER TABLE `committee_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `event_participations`
--
ALTER TABLE `event_participations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `event_public_registrations`
--
ALTER TABLE `event_public_registrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `gallery_media`
--
ALTER TABLE `gallery_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT pour la table `gallery_media_tags`
--
ALTER TABLE `gallery_media_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gallery_tags`
--
ALTER TABLE `gallery_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `members`
--
ALTER TABLE `members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `rehearsals`
--
ALTER TABLE `rehearsals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `rehearsal_attendances`
--
ALTER TABLE `rehearsal_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `committee_members`
--
ALTER TABLE `committee_members`
  ADD CONSTRAINT `committee_members_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_responsible_id_foreign` FOREIGN KEY (`responsible_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `event_participations`
--
ALTER TABLE `event_participations`
  ADD CONSTRAINT `event_participations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participations_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event_public_registrations`
--
ALTER TABLE `event_public_registrations`
  ADD CONSTRAINT `event_public_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD CONSTRAINT `financial_transactions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `financial_transactions_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `galleries`
--
ALTER TABLE `galleries`
  ADD CONSTRAINT `galleries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `gallery_media`
--
ALTER TABLE `gallery_media`
  ADD CONSTRAINT `gallery_media_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `gallery_media_tags`
--
ALTER TABLE `gallery_media_tags`
  ADD CONSTRAINT `gallery_media_tags_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `gallery_media` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gallery_media_tags_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `gallery_tags` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
