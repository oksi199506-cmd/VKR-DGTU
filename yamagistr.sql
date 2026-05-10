-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Май 06 2026 г., 16:48
-- Версия сервера: 8.0.40
-- Версия PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `yamagistr`
--

-- --------------------------------------------------------

--
-- Структура таблицы `chat_history`
--

CREATE TABLE `chat_history` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','assistant') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `chat_history`
--

INSERT INTO `chat_history` (`id`, `user_id`, `message`, `role`, `created_at`) VALUES
(78, 10, 'Подать заявку', 'user', '2026-05-06 16:36:29'),
(79, 10, 'Для подачи заявки на олимпиаду «Я Магистр» перейдите по ссылке:\n\n<b>👉 <a href=\'https://magistr.donstu-talant.ru/login/signup.php?\' target=\'_blank\' rel=\'noopener noreferrer\'>Подать заявку онлайн</a></b>\n\n<br>После перехода заполните анкету участника. Срок подачи заявок — <b>с 1 по 30 ноября 2025 года</b>.\n\n<span style=\'color: red;\'><b>ВНИМАНИЕ!</b></span> Для участия в олимпиаде при регистрации необходимо указать выбранные вами направления и программы подготовки (не более 3-х). Если графы не будут заполнены, регистрация не считается пройденной.', 'assistant', '2026-05-06 16:36:29'),
(80, 10, 'Расписание', 'user', '2026-05-06 16:36:30'),
(81, 10, 'Расписание олимпиады «Я Магистр»:<br>\n\n<b>Регистрация:</b> с 1 по 30 ноября 2025 года<br>\n\n<b>Отборочный этап:</b> с 1 декабря 2025 г. (с 19:00 по московскому времени) по 15 января 2026 г. (23:59 по московскому времени).<br>\n\n<b>Заключительный этап:</b> с 16 января по 20 февраля 2026 г.<br>\n\n<b>Подробнее:</b> 👉 <a href=\'https://talant.donstu.ru/ya-magistr/#block-2468\' target=\'_blank\'>Смотреть расписание</a>', 'assistant', '2026-05-06 16:36:30'),
(82, 10, 'Экзамены', 'user', '2026-05-06 16:36:31'),
(83, 10, 'Методические рекомендации для подготовки к <b>заключительному этапу:</b><br>\n\n 👉 <a href=\'https://talant.donstu.ru/ya-magistr/profili-olimpiady-ya-magistr/m25.php\' target=\'_blank\' rel=\'noopener noreferrer\'>Выбор направления</a>', 'assistant', '2026-05-06 16:36:31'),
(84, 10, 'FAQ', 'user', '2026-05-06 16:36:32'),
(85, 10, 'Часто задаваемые вопросы:\n\n<br><b>В презентации вы можете ознакомиться со всей актуальной информацией и вопросами, которые вас интересуют:</b>\n\n👉 <a href=\'https://constr.donstu.ru/upload/iblock/e19/kqb2cim3y9439i6ar4f7wa0tcjealn2m.pdf\' target=\'_blank\'>Все FAQ тут!</a>', 'assistant', '2026-05-06 16:36:32');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `token`) VALUES
(10, 'Чумаков Станислав Евгеньевич', '1@mail.ru', '$2y$10$RNVzy969OcJKSGsxFix7yeM5daYKRAGkyatap92Zb6f7B.IkkoP/q', '2026-05-06 16:35:32', '4c1774f1078d363a8ad3d6970be81d3daf5a25a9cf171393af98547ba18b272a');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `chat_history`
--
ALTER TABLE `chat_history`
  ADD CONSTRAINT `chat_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
