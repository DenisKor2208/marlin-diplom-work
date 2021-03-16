-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 16 2021 г., 10:48
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `marlin_exam_three`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `verified` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `resettable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `roles_mask` int UNSIGNED NOT NULL DEFAULT '0',
  `registered` int UNSIGNED NOT NULL,
  `last_login` int UNSIGNED DEFAULT NULL,
  `force_logout` mediumint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `status`, `verified`, `resettable`, `roles_mask`, `registered`, `last_login`, `force_logout`) VALUES
(11, 'oliver.kop@gmail.com', '$2y$10$NY2BJ3b3ijZQDlfV16OTOe.ZrNQ4Aw8ei6yAroMIZJ2lChFSioW32', NULL, 0, 1, 1, 1, 1615283954, 1615878138, 26),
(10, 'chtil@list.ru', '$2y$10$X1VlHbkZmucI9.tydIEAuOyepuY1bb3i08JvNm413Omv8VIGy40Dy', NULL, 0, 1, 1, 1, 1614854721, 1615879400, 11),
(12, 'alita.gray@ebay.com', '$2y$10$hX1VSsRiDVrBQK7wDqfyYOll/mglm4DNBUiUx6YlCbtAJ/eIUPYXG', NULL, 0, 1, 1, 2, 1615380083, NULL, 1),
(13, 'dr.cook55@smartweb.eu', '$2y$10$beKAEt5nVi7nknM/mZoZDOkoRyESod3iVQHSxorn5wFOO0EWdxWna', NULL, 0, 1, 1, 2, 1615388576, NULL, 1),
(15, 'jim.ketty@laksltd.com', '$2y$10$DY6aUCyxKxWzzd0Uhx8EFuxE8/IkOpBGI49D6vkg9FPSiBBP4v.fC', NULL, 0, 1, 1, 2, 1615449050, 1615449080, 1),
(17, 'bagena881@rambler.ru', '$2y$10$t9dkeavn5oOEMqoZZMpE1OTgaFVU/oGUzdeIqKA3LXL2ZQKabLovu', NULL, 0, 1, 1, 2, 1615646236, NULL, 1),
(20, 'jimmy.fellan@smartweb.com', '$2y$10$J86oprpEVNpeJNZYTHgUFugJO84HKSS5o.fyLb6.q/q10m9WAU1v.', NULL, 0, 1, 1, 2, 1615871577, NULL, 1),
(19, 'arica.grace@smartweb.com', '$2y$10$Vn1WqBqZtMrLlat47USzgOaBTQFQBVgS3dPzPGsa5p1FgqjcL6LKe', NULL, 0, 1, 1, 2, 1615870130, NULL, 1),
(21, 'sarah.mcbrook@smartweb.com', '$2y$10$4aR4/j/EoYM1oAucfmv8AuVHqLk7p6NFBMjkm77IfE58KUXo7o.ca', NULL, 0, 1, 1, 2, 1615871796, 1615872434, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users_confirmations`
--

CREATE TABLE `users_confirmations` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `email` varchar(249) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `selector` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_data`
--

CREATE TABLE `users_data` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'First name Last name',
  `user_work` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Position, Company',
  `user_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '+0 000-000-0000',
  `user_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'user address',
  `user_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'online',
  `user_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'avatar-demo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users_data`
--

INSERT INTO `users_data` (`id`, `username`, `user_work`, `user_phone`, `user_address`, `user_status`, `user_avatar`) VALUES
(10, 'Adam Jones V.', 'TeamLead, Microsoft LTD', '+3 722-044-41-30', '15 Charist St, Detroit, MI, 48212, UAE', 'online', 'avatar_10_16032021.png'),
(11, 'Oliver Kopyov', 'IT Director, Gotbootstrap Inc.', '+1 317-456-25-64', '15 Charist St, Detroit, MI, 48212, USA', 'online', 'avatar_11_16032021.png'),
(12, 'Alita Gray', 'Project Manager, Gotbootstrap Inc.', '+1 154-987-32-65', '134 Hamtrammac, Detroit, MI, 48314, USA', 'online', 'avatar_12_16032021.png'),
(13, 'Dr. John Cook PhD', 'Human Resources, Gotbootstrap Inc.', '+1 317-456-25-64', ' 798 Smyth Rd, Detroit, MI, 48341, USA', 'busy', 'avatar_13_16032021.png'),
(15, 'Jim Ketty', 'Staff Orgnizer, Gotbootstrap Inc.', '+1 317-456-25-64', '134 Tasy Rd, Detroit, MI, 48212, USA', 'online', 'avatar_15_16032021.png'),
(17, 'Dr. John Oliver', 'Oncologist, Gotbootstrap Inc.', '+1 317-456-25-64', '134 Gallery St, Detroit, MI, 46214, USA', 'busy', 'avatar_17_16032021.png'),
(19, 'Arica Grace', 'Accounting, Gotbootstrap Inc.', '+1 317-456-25-64', '798 Smyth Rd, Detroit, MI, 48341, USA', 'busy', 'avatar_19_16032021.png'),
(20, 'Jimmy Fellan', 'Accounting, Gotbootstrap Inc.', '+1 317-456-25-64', ' 55 Smyth Rd, Detroit, MI, 48341, USA', 'away', 'avatar_20_16032021.png'),
(21, 'Sarah McBrook', 'Xray Division, Gotbootstrap Inc.', '+1 317-456-25-64', '798 Smyth Rd, Detroit, MI, 48341, USA', 'online', 'avatar_21_16032021.png');

-- --------------------------------------------------------

--
-- Структура таблицы `users_links`
--

CREATE TABLE `users_links` (
  `id` int NOT NULL,
  `user_vk_link` varchar(255) NOT NULL DEFAULT '#',
  `user_telegram_link` varchar(255) NOT NULL DEFAULT '#',
  `user_instagram_link` varchar(255) NOT NULL DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users_links`
--

INSERT INTO `users_links` (`id`, `user_vk_link`, `user_telegram_link`, `user_instagram_link`) VALUES
(10, 'https://vk.com/denis_korotin', '#', '#'),
(11, '#', '#', '#'),
(12, 'Ссылка VK', 'Ссылка telegram', 'Ссылка instagram'),
(13, 'Ссылка VK', 'Ссылка telegram', 'Ссылка instagram'),
(15, '#', '#', '#'),
(17, 'Ссылка VK', 'Ссылка telegram', 'Ссылка instagram'),
(19, 'Ссылка VK', 'Ссылка telegram', 'Ссылка instagram'),
(20, '#', '#', '#'),
(21, '##', '##', '#');

-- --------------------------------------------------------

--
-- Структура таблицы `users_remembered`
--

CREATE TABLE `users_remembered` (
  `id` bigint UNSIGNED NOT NULL,
  `user` int UNSIGNED NOT NULL,
  `selector` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_resets`
--

CREATE TABLE `users_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `user` int UNSIGNED NOT NULL,
  `selector` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `token` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `expires` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_throttling`
--

CREATE TABLE `users_throttling` (
  `bucket` varchar(44) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `tokens` float UNSIGNED NOT NULL,
  `replenished_at` int UNSIGNED NOT NULL,
  `expires_at` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users_throttling`
--

INSERT INTO `users_throttling` (`bucket`, `tokens`, `replenished_at`, `expires_at`) VALUES
('PZ3qJtO_NLbJfRIP-8b4ME4WA3xxc6n9nbCORSffyQ0', 0.131649, 1615875528, 1616307528),
('QduM75nGblH2CDKFyk0QeukPOwuEVDAUFE54ITnHM38', 55.2471, 1615879400, 1616419400),
('OMhkmdh1HUEdNPRi-Pe4279tbL5SQ-WMYf551VVvH8U', 18.3311, 1615879395, 1615915395),
('nT9U65-8Dw6OKoF-9WVW8YRjMF4dfwI-kU4vlm7DYq0', 499, 1615879395, 1616052195),
('HfLHrYj0cxdybmeJRuuK5qkKbk5NrqBQIPyEotSr8xI', 499, 1615346603, 1615519403),
('CIxOL4q0nE19hsTKhGm-SYI8WMJ9-Zno-u4na7yGxys', 499, 1615878132, 1616050932);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `users_confirmations`
--
ALTER TABLE `users_confirmations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `email_expires` (`email`,`expires`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_data`
--
ALTER TABLE `users_data`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_links`
--
ALTER TABLE `users_links`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_remembered`
--
ALTER TABLE `users_remembered`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user` (`user`);

--
-- Индексы таблицы `users_resets`
--
ALTER TABLE `users_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `user_expires` (`user`,`expires`);

--
-- Индексы таблицы `users_throttling`
--
ALTER TABLE `users_throttling`
  ADD PRIMARY KEY (`bucket`),
  ADD KEY `expires_at` (`expires_at`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблицы `users_confirmations`
--
ALTER TABLE `users_confirmations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_remembered`
--
ALTER TABLE `users_remembered`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `users_resets`
--
ALTER TABLE `users_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
