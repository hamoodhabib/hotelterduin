-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 21 jun 2023 om 16:43
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotelterduin_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `contacts`
--

CREATE TABLE `contacts` (
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `contacts`
--

INSERT INTO `contacts` (`contact_id`, `user_id`, `name`, `email`, `phone`, `message`) VALUES
(1, NULL, 'bismillah', 'a_ridha@hotmail.nl', '0623099584', 'bing chilling'),
(2, NULL, 'Ahmed Ridha', 'a_ridha@hotmail.nl', '0623099584', 'd'),
(3, NULL, 'Ahmed Ridha', 'a_ridha@hotmail.nl', '0623099584', 'aaa'),
(4, NULL, 'Morhaf', 'a_ridha@hotmail.nl', '0623099584', 'aaa'),
(5, 3, 'derbas', 'derb@derb.nl', '01823418', '12345'),
(6, 3, 'derbas', 'ahmedspam1@hotmail.com', '0623099584', '11111'),
(7, NULL, 'derbas', 'no@mo.nl', '076255424', 'fajj'),
(8, 4, 'hahah', 'ha@ha.ha', '08978654', 'gooi'),
(10, 7, 'derbas', 'sfgsagsagf@live.nl', '08978654', 'hoeqveel kost de kamer'),
(11, 10, 'hoi', 'hoi@hoi.nl', '08w46w43264363643653', 'hoi');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`) VALUES
(20, 4, 2, '2023-06-28', '2023-07-06'),
(21, 3, 2, '2023-06-19', '2023-06-21');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_type`, `description`, `available`) VALUES
(2, 'Regular', 'Very regular', 8),
(12, 'Deluxe', 'Very deluxe', 6);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `address`, `email`, `phone`) VALUES
(3, 'morhaf', '$2y$10$.6QjEy1fMLEyMZx.cajYAu1yrl619WRSZ78UTZFobA3', 'customer', 'Amsterdam 123', 'morhaf@gmail.com', '0612345678'),
(4, 'hamood', '$2y$10$cE8NXsDqQ2hh0hCrzh1B4OzCfKDL9J.ywZsJzxhjnTz', 'employee', 'Jan Hoeckstraat', 'a_ridha@hotmail.nl', '0623099584'),
(7, 'Hoedie', '$2y$10$EzGi8FoJ4F2LhHSlrJY/6uUuH/Wb2gQ7Vw5uM/H/mpx', 'employee', 'esadfsf', 'sdfsfsf@live.nl', '08978654'),
(10, 'hoi', '$2y$10$x45KP3t.1rHxDIfI9h.azOBQ3Ct4CsKR2O5uEtpucSb', 'employee', 'sesamstraat 123', 'hoi@hoi.nl', '0612345678');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexen voor tabel `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexen voor tabel `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `contacts`
--
ALTER TABLE `contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT voor een tabel `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT voor een tabel `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
