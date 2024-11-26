-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2024 at 12:09 AM
-- Server version: 9.0.1
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_name`, `email`, `pass`) VALUES
('a', 'a@a.com', 'a'),
('admin', 'admin@gmail.com', 'admin'),
('systemadmin', 'systemadmin@a.com', 'systemadmin');

-- --------------------------------------------------------

--
-- Table structure for table `airline`
--

CREATE TABLE `airline` (
  `email` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `airline_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airline`
--

INSERT INTO `airline` (`email`, `pass`, `airline_name`, `logo`) VALUES
('acron@gmail.com', '42347', '«Солидарность Самара Арена»', 'uploads/акрон.png'),
('akhmat@gmail.com', '30597', '«Ахмат Арена»', 'uploads/ахмат.png'),
('cskaM@gmail.com', '30000', '«ВЭБ Арена»', 'uploads/цскаМ.png'),
('dinamoM@gmail.com', '25716', '«ВТБ Арена»', 'uploads/динамоМ.png'),
('fakel@gmail.com', '10052', '«Факел»', 'uploads/факел.png'),
('khimki@gmail.com', '14950', '«Арена Химки»', 'uploads/химки.png'),
('krasnodar@gmail.com', '35178', 'Стадион ФК «Краснодар»', 'uploads/краснодар.png'),
('lokoM@gmail.com', '27320', '«РЖД Арена»', 'uploads/локоМ.png'),
('makhachkala@gmail.com', '15200', '«Динамо»', 'uploads/махачкала.jfif'),
('orenburg@gmail.com', '10046', '«Газовик»', 'uploads/оренбург.png'),
('pariNN@gmail.com', '45319', '«Стадион Нижний Новгород»', 'uploads/париНН.png'),
('rostov@gmail.com', '45415', '«Ростов Арена»', 'uploads/ростов.png'),
('rubin@gmail.com', '45379', '«Ак Барс Арена»', 'uploads/рубин.png'),
('spartakM@gmail.com', '45360', '«Лукойл Арена»', 'uploads/спартакм.png'),
('wingssovetov@gmail.com', '44918', '«Самара Арена»', 'uploads/крылья_советов.png'),
('zenit@gmail.com', '68000', '«Газпром Арена»', 'uploads/зенит.png');

-- --------------------------------------------------------

--
-- Table structure for table `airport`
--

CREATE TABLE `airport` (
  `airport_id` int NOT NULL,
  `airport_name` char(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `capacity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `city` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `place` int DEFAULT '0',
  `game` int DEFAULT '0',
  `win` int DEFAULT '0',
  `defeat` int DEFAULT '0',
  `draw` int DEFAULT '0',
  `scored_goals` int DEFAULT '0',
  `missed_goals` int DEFAULT '0',
  `goal_difference` int GENERATED ALWAYS AS ((`scored_goals` - `missed_goals`)) STORED,
  `points` int DEFAULT '0',
  `prev_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airport`
--

INSERT INTO `airport` (`airport_id`, `airport_name`, `capacity`, `city`, `logo`, `place`, `game`, `win`, `defeat`, `draw`, `scored_goals`, `missed_goals`, `points`, `prev_place`) VALUES
(81, 'Краснодар', 'Мурад Олегович Мусаев', 'Краснодар', 'краснодар.png', 2, 16, 11, 0, 5, 34, 9, 38, '2'),
(82, 'Зенит', 'Сергей Богданович Семак ', 'Санкт-Петербург', 'зенит.png', 1, 16, 12, 1, 3, 34, 7, 39, '1'),
(83, 'Локомотив Москва', 'Михаил Михайлович Галактионов', 'Москва', 'локоМ.png', 3, 16, 11, 4, 1, 32, 23, 34, '4'),
(84, 'Динамо Москва', 'Славиша Йоканович', 'Москва', 'динамоМ.png', 5, 16, 9, 3, 4, 32, 18, 31, '3'),
(85, 'ЦСКА Москва', 'Марко Николич', 'Москва', 'цскаМ.png', 6, 16, 8, 5, 3, 32, 18, 27, '6'),
(86, 'Спартак Москва', 'Деяна Станковича', 'Москва', 'спартакм.png', 4, 16, 9, 3, 4, 30, 14, 31, '5'),
(87, 'Рубин', 'Рашид Маматкулович Рахимов', 'Казань', 'рубин.png', 7, 16, 6, 6, 4, 20, 22, 22, '8'),
(88, 'Акрон', 'Рамон Трибульетч', 'Самара', 'акрон.png', 11, 16, 4, 8, 4, 19, 33, 16, 'Не участвовал'),
(89, 'Ростов', 'Валерий Георгиевич Карпин', 'Ростов-на-Дону', 'ростов.png', 8, 16, 5, 6, 5, 22, 27, 20, '7'),
(90, 'Крылья Советов', 'Игорь Витальевич Осинькин', 'Самара', 'крылья_советов.png', 12, 16, 4, 9, 3, 15, 24, 15, '9'),
(91, 'Махачкала', 'Хасанби Биджиев', 'Махачкала', 'махачкала.jfif', 9, 16, 3, 6, 7, 9, 14, 16, 'Не участвовал'),
(92, 'Пари Нн', 'Виктор Михайлович Ганчаренко', 'Нижний Новгород', 'париНН.png', 10, 16, 4, 8, 4, 15, 27, 16, '13'),
(93, 'Химки', 'Франк Артига', 'Химки', 'химки.png', 13, 16, 2, 7, 7, 18, 30, 13, 'Не участвовал'),
(94, 'Факел', 'Игорь Геннадьевич Черевченко', 'Воронеж', 'факел.png', 14, 16, 2, 7, 7, 11, 24, 13, '11'),
(95, 'Ахмат', 'Мирослав Ромащенко', 'Грозный', 'ахмат.png', 15, 16, 1, 9, 6, 14, 31, 9, '10'),
(96, 'Оренбург', 'Владимир Слишкович', 'Оренбург', 'оренбург.png', 16, 16, 1, 10, 5, 16, 31, 8, '12');

-- --------------------------------------------------------

--
-- Table structure for table `booked`
--

CREATE TABLE `booked` (
  `id` int NOT NULL,
  `flight_id` int DEFAULT NULL,
  `customer_email` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booked`
--

INSERT INTO `booked` (`id`, `flight_id`, `customer_email`) VALUES
(244, 63, 'alex.sidorov@mail.ru'),
(245, 63, 'alex.sidorov@mail.ru'),
(246, 63, 'alex.sidorov@mail.ru'),
(247, 63, 'alex.sidorov@mail.ru'),
(248, 63, 'alex.sidorov@mail.ru'),
(249, 63, 'alex.sidorov@mail.ru');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `first_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `customer_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` int DEFAULT NULL,
  `gender` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pass` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`first_name`, `last_name`, `customer_name`, `email`, `phone`, `gender`, `pass`) VALUES
('Алексей', 'Сидоров', 'Алексей', 'alex.sidorov@mail.ru', 79009998, 'Мужской', 'alexpass'),
('Александр', 'Зайцев', 'Александр', 'alex.zay@mail.ru', 79005556, 'Мужской', 'zaypass001'),
('Алина', 'Орлова', 'Алина', 'alina.orlov@mail.ru', 79001112, 'Женский', 'orlov1234'),
('Анна', 'Иванова', 'Анна', 'anna.iv@mail.ru', 79001234, 'Женский', 'pass123'),
('Димочка', 'Богатов', 'Димочка', 'dimochka@gmail.com', 753543278, 'male', '789'),
('Дмитрий', 'Петров', 'Дмитрий', 'dmitry.pet@mail.ru', 79007654, 'Мужской', 'pass456'),
('Екатерина', 'Смирнова', 'Екатерина', 'ekaterina.smir@mail.ru', 79001112, 'Женский', 'ekat890'),
('Елена', 'Попова', 'Елена', 'elena.pop@mail.ru', 79002221, 'Женский', 'lenapop'),
('Иван', 'Васильев', 'Иван', 'ivan.vas@mail.ru', 79003334, 'Мужской', 'ivan2023'),
('Кирилл', 'Михайлов', 'Кирилл', 'kir.mih@mail.ru', 79009996, 'Мужской', 'kirpass456'),
('Мария', 'Кузнецова', 'Мария', 'maria.kuz@mail.ru', 79007775, 'Женский', 'mariapass1'),
('Максим', 'Новиков', 'Максим', 'maxim.nov@mail.ru', 79006667, 'Мужской', 'maxnov123'),
('Наталья', 'Федорова', 'Наталья', 'nat.fed@mail.ru', 79002223, 'Женский', 'fednat22'),
('Никита', 'Гусев', 'Никита', 'nik.gus@mail.ru', 79003335, 'Мужской', 'gusnik2024'),
('Олег', 'Громов', 'Олег', 'oleg.grom@mail.ru', 79009990, 'Мужской', 'grompass321'),
('Ольга', 'Лебедева', 'Ольга', 'olga.leb@mail.ru', 79004443, 'Женский', 'olga2023'),
('Полина', 'Егорова', 'Полина', 'pol.ego@mail.ru', 79002220, 'Женский', 'polpass2024'),
('Роман', 'Ковалев', 'Роман', 'roman.kov@mail.ru', 79006668, 'Мужской', 'kovpass100'),
('Руслан', 'Толкачев', 'Руслан', 'ruskaspb@mail.ru', 78421545, 'Мужской', '2'),
('Сергей', 'Морозов', 'Сергей', 'sergey.moroz@mail.ru', 79005554, 'Мужской', 'morozpass'),
('Светлана', 'Соколова', 'Светлана', 'svet.sok@mail.ru', 79007774, 'Женский', 'sokpass890'),
('Максим', 'Владимиров', 'Максим', 'topmaksim03@gmail.com', 7565469, 'Мужской', '1'),
('Виктория', 'Павлова', 'Виктория', 'vika.pav@mail.ru', 790044422, 'Женский', 'vikapass77');

-- --------------------------------------------------------

--
-- Table structure for table `flight`
--

CREATE TABLE `flight` (
  `id` int NOT NULL,
  `source_date` date DEFAULT NULL,
  `source_time` time DEFAULT NULL,
  `dest_date` date DEFAULT NULL,
  `dest_time` time DEFAULT NULL,
  `dep_airport` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `arr_airport` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `seats` int DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `flight_class` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `airline_name` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dep_airport_id` int DEFAULT NULL,
  `arr_airport_id` int DEFAULT NULL,
  `airline_email` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `result` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight`
--

INSERT INTO `flight` (`id`, `source_date`, `source_time`, `dest_date`, `dest_time`, `dep_airport`, `arr_airport`, `seats`, `price`, `flight_class`, `airline_name`, `dep_airport_id`, `arr_airport_id`, `airline_email`, `result`, `info`) VALUES
(60, '2024-11-04', '09:23:00', '2024-11-18', '09:21:00', 'Зенит', 'Краснодар', 45484, 1545.00, 'Economy', '«Стадион Нижний Новгород»', 82, 81, 'pariNN@gmail.com', 'Не определен', 'Зенит:\r\nСхема 4-3-3\r\nКраснодар:\r\n'),
(61, '2024-11-13', '07:29:00', '2024-11-19', '06:29:00', 'Ростов', 'Динамо Москва', 7528528, 72772.00, 'Economy', '«Солидарность Самара Арена»', 89, 84, 'acron@gmail.com', '1:1', '?'),
(62, '2024-11-11', '19:38:00', '2024-11-18', '20:39:00', 'Рубин', 'Зенит', 15300, 5000.00, 'Economy', 'Стадион ФК «Краснодар»', 87, 82, 'krasnodar@gmail.com', NULL, NULL),
(63, '2024-11-25', '05:00:00', '2024-11-25', '06:30:00', 'Ростов', 'Крылья Советов', 45000, 1000.00, 'Economy', '«ВТБ Арена»', 89, 90, 'dinamoM@gmail.com', NULL, NULL),
(64, '2024-11-11', '16:00:00', '2024-11-25', '00:58:00', 'Рубин', 'Ростов', 505720, 1414.00, 'Economy', '«РЖД Арена»', 87, 89, 'lokoM@gmail.com', NULL, NULL),
(65, '2024-12-02', '00:00:00', '2024-12-09', '00:00:00', 'ЦСКА Москва', 'Рубин', 7, 1.00, 'Economy', '«Факел»', 85, 87, 'fakel@gmail.com', NULL, NULL),
(66, '2024-11-01', '23:02:00', '2024-11-25', '02:01:00', 'Махачкала', 'Крылья Советов', 2755, 42742.00, 'Economy', '«Стадион Нижний Новгород»', 91, 90, 'pariNN@gmail.com', NULL, NULL),
(67, '2024-10-28', '00:04:00', '2024-11-04', '00:05:00', 'Краснодар', 'Зенит', 575, 1000.00, 'Economy', '«Ак Барс Арена»', 81, 82, 'rubin@gmail.com', '??7', NULL),
(68, '2024-11-01', '00:12:00', '2024-11-25', '03:12:00', 'Крылья Советов', 'Ростов', 45, 700.00, 'Economy', '«Самара Арена»', 90, 89, 'wingssovetov@gmail.com', NULL, NULL),
(69, '2024-11-11', '02:16:00', '2024-11-25', '23:18:00', 'Пари Нн', 'Краснодар', 1, 1.00, 'First Class', '«Факел»', 92, 81, 'fakel@gmail.com', NULL, NULL),
(70, '2024-11-18', '23:28:00', '2024-11-25', '01:28:00', 'Оренбург', 'Махачкала', 0, 1.00, 'Economy', '«Стадион Нижний Новгород»', 96, 91, 'pariNN@gmail.com', NULL, NULL),
(71, '2024-11-01', '01:41:00', '2024-11-11', '00:41:00', 'Ростов', 'Химки', 2, 50.00, 'Economy', '«Стадион Нижний Новгород»', 89, 93, 'pariNN@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int NOT NULL,
  `image` char(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `age` int NOT NULL,
  `num_pos` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cards` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `goals` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `image`, `fio`, `age`, `num_pos`, `cards`, `goals`) VALUES
(1, 'uploads/1732476878_cr.png', 'Петя Петушок', 4488, '15, Нападающийq', '2 желтые, 1 красная ffff', -156),
(2, 'player2.jpg', 'Петров Петр Петрович', 28, '7, Полузащитник', '1 желтая', 8),
(3, 'player3.jpg', 'Сидоров Сергей Сергеевич', 30, '4, Защитник', '3 желтые', 2),
(4, 'player4.jpg', 'Кузнецов Константин Константинович', 23, '1, Вратарь', '0 карточек', 0),
(7, 'uploads/1732477820_криш.jfif', 'Васичка', 9876, 'poiuytr', ',mnbvc', 9876);

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `id` int NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`id`, `item_name`, `price`, `description`, `image`) VALUES
(1, 'Футбольный мяч Adidas', 300, 'Официальный мяч для профессиональных матчей. Высокая износостойкость.', 'мяч.jfif'),
(2, 'Футболка сборной России', 2500, 'Оригинальная футболка сборной России с эмблемой и гербом.', 'футболка.jpg'),
(3, 'Шарф болельщика', 1200, 'Двусторонний шарф с символикой любимой команды.', 'fan_scarf.jpg'),
(4, 'Бутсы Nike Mercurial', 7000, 'Легкие бутсы для профессионального футбола с улучшенным сцеплением.', 'nike_boots.jpg'),
(5, 'Кепка с логотипом FIFA', 1000, 'Стильная кепка с официальной символикой FIFA.', 'fifa_cap.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `airline`
--
ALTER TABLE `airline`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `airline_name` (`airline_name`);

--
-- Indexes for table `airport`
--
ALTER TABLE `airport`
  ADD PRIMARY KEY (`airport_id`);

--
-- Indexes for table `booked`
--
ALTER TABLE `booked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flight_id` (`flight_id`),
  ADD KEY `customer_email` (`customer_email`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `flight`
--
ALTER TABLE `flight`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dep_airport_id` (`dep_airport_id`),
  ADD KEY `arr_airport_id` (`arr_airport_id`),
  ADD KEY `airline_email` (`airline_email`),
  ADD KEY `airline_name` (`airline_name`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `airport`
--
ALTER TABLE `airport`
  MODIFY `airport_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `booked`
--
ALTER TABLE `booked`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `flight`
--
ALTER TABLE `flight`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booked`
--
ALTER TABLE `booked`
  ADD CONSTRAINT `booked_ibfk_1` FOREIGN KEY (`flight_id`) REFERENCES `flight` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booked_ibfk_2` FOREIGN KEY (`customer_email`) REFERENCES `customer` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `flight`
--
ALTER TABLE `flight`
  ADD CONSTRAINT `flight_ibfk_1` FOREIGN KEY (`dep_airport_id`) REFERENCES `airport` (`airport_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_2` FOREIGN KEY (`arr_airport_id`) REFERENCES `airport` (`airport_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_3` FOREIGN KEY (`airline_email`) REFERENCES `airline` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `flight_ibfk_4` FOREIGN KEY (`airline_name`) REFERENCES `airline` (`airline_name`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
