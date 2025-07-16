-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2025 at 02:42 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `money_mate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance`
--

CREATE TABLE `balance` (
  `balanceID` bigint NOT NULL,
  `userID` bigint NOT NULL,
  `currentBal` decimal(10,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `budgetID` bigint NOT NULL,
  `userID` bigint NOT NULL,
  `budgetDate` date NOT NULL,
  `totBudget` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `budget`
--

INSERT INTO `budget` (`budgetID`, `userID`, `budgetDate`, `totBudget`) VALUES
(1, 2, '2025-07-01', '4000.00'),
(8, 4, '2025-07-01', '5000.00'),
(9, 5, '2025-07-01', '10000.00');

-- --------------------------------------------------------

--
-- Table structure for table `expcatlookup`
--

CREATE TABLE `expcatlookup` (
  `catLookupID` bigint NOT NULL,
  `catName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expcatlookup`
--

INSERT INTO `expcatlookup` (`catLookupID`, `catName`) VALUES
(5, 'Bill'),
(7, 'Entertainment'),
(1, 'Food'),
(3, 'Shopping'),
(6, 'Top Up'),
(2, 'Transport'),
(4, 'Utilities');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenseID` bigint NOT NULL,
  `userID` bigint NOT NULL,
  `expTitle` varchar(255) NOT NULL,
  `expAmount` decimal(10,2) NOT NULL,
  `catLookupID` bigint NOT NULL,
  `expDate` date NOT NULL,
  `expCreatdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expenseID`, `userID`, `expTitle`, `expAmount`, `catLookupID`, `expDate`, `expCreatdAt`) VALUES
(1, 1, 'laptop', '1000.00', 5, '2025-07-10', '2025-07-09 05:41:21'),
(2, 1, 'laptop', '100.00', 7, '2025-07-08', '2025-07-09 09:43:56'),
(5, 1, 'laptop', '3000.00', 4, '2025-07-15', '2025-07-14 09:04:38'),
(15, 2, 'nasi', '10.00', 1, '2025-07-14', '2025-07-14 16:54:25'),
(23, 2, 'laptop', '1000.00', 3, '2025-07-15', '2025-07-14 21:59:24'),
(24, 2, 'veggies', '150.00', 1, '2025-07-16', '2025-07-14 23:17:01'),
(25, 2, 'fipper', '13.00', 3, '2025-07-16', '2025-07-15 00:02:36'),
(26, 2, 'bulb', '30.00', 4, '2025-07-15', '2025-07-15 00:03:21'),
(28, 4, 'Nasi ayam penyet', '50.00', 1, '2025-07-15', '2025-07-15 00:34:04'),
(29, 5, 'veggies', '200.00', 1, '2025-07-17', '2025-07-15 01:37:52'),
(30, 5, 'towel', '15.10', 3, '2025-07-15', '2025-07-15 01:38:13');

-- --------------------------------------------------------

--
-- Table structure for table `savinggoals`
--

CREATE TABLE `savinggoals` (
  `savingID` bigint NOT NULL,
  `userID` bigint NOT NULL,
  `savTitle` varchar(255) NOT NULL,
  `savAmount` decimal(10,2) NOT NULL,
  `targetDate` date NOT NULL,
  `curSavings` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `savinggoals`
--

INSERT INTO `savinggoals` (`savingID`, `userID`, `savTitle`, `savAmount`, `targetDate`, `curSavings`) VALUES
(2, 2, 'Brik', '1300.00', '2025-07-15', '650.00'),
(3, 2, 'GNX', '1000000.00', '2025-07-14', '200.00'),
(4, 2, 'Adapter', '40.00', '2025-07-15', '0.00'),
(5, 4, 'New Laptop', '3900.00', '2025-07-17', '3000.00'),
(6, 5, 'F80', '1000000.00', '2028-02-15', '1401.00'),
(7, 5, 'Sepang GP', '2500.00', '2025-10-16', '1320.00'),
(8, 7, 'New Laptop', '4000.00', '2025-07-22', '800.00');

-- --------------------------------------------------------

--
-- Table structure for table `userlog`
--

CREATE TABLE `userlog` (
  `id` int NOT NULL,
  `userID` int DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `login_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `logout` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint NOT NULL,
  `fName` varchar(255) NOT NULL,
  `lName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `proPic` varchar(255) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `fName`, `lName`, `email`, `pw`, `proPic`, `createdAt`) VALUES
(1, 'Rebecca', 'Smith', 'testuser123@gmail.com', '$2y$10$tJJNEYDnL1LyPzAnFgtaP.0obV0jzdiYD5Eiwr5QjN8TqkykT.2eS', NULL, '2025-07-08 18:54:41'),
(2, 'Rebecca', 'Donaldson', 'littledonaldson@gmail.com', '$2y$10$TdRiVSVOvPlcHSinUQAaPeMLhAFgEKMi9F5gXLzsxIpKVWhUtJxa2', '../uploads/profile_pics/687515abdb5d6_6872a0e5b6bfd-787ff40c52756595b45060ae9324a8c8.jpg', '2025-07-13 14:56:10'),
(3, 'Shaza', 'Ahmad', 'shazaahmad@gmail.com', '$2y$10$sf7cYbe5vRN43YzCJh.aC.twgRKiLM/e4gwaCnLnGsGfmBECXub1W', 'uploads/6873d73664933-bf34c4a038d760acff7169938249f853.jpg', '2025-07-13 23:56:38'),
(4, 'farhana', 'malik', 'farhanamalik199@gmail.com', '$2y$10$j.i21tULbN/P4FUaSu1vSOVmCddMZo4kYI8KiCkgWUXCliEMLrm3G', 'uploads/6875306a8c1fe-c129c2b32a2dbb860c69ca3741b832f3.jpg', '2025-07-15 00:29:30'),
(5, 'Johnny', 'Jeong', 'sweetboy14@gmail.com', '$2y$10$r7YjHO90r5VwsWC3/N6HguaE55Md/20jIQH8Te.SswCDcQdk.Lfii', '../uploads/profile_pics/687540b5e22bf_johnny hensem 2.jpg', '2025-07-15 01:34:32'),
(6, 'Chenle', 'Zhong', 'wangmori1@gmail.com', '$2y$10$7IFgoG/2qT2XBP/xu0.U.O.FNCskfYRs5pUdoNk2DMNpRjt67M38i', 'uploads/687541cf6f866-80ebec249a9efc73d587b3956973fe45.jpg', '2025-07-15 01:43:43'),
(7, 'Arissa', 'Aziz', 'arissa@gmail.com', '$2y$10$IkOlh5Fm3b.zotiJe.LUXOWZvq91XNbhH.kg29oZH84wTk7CbzJiK', 'uploads/68770ee00693a-a1eedc2d988d39af08b42a5951507ce0.jpg', '2025-07-16 10:30:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance`
--
ALTER TABLE `balance`
  ADD PRIMARY KEY (`balanceID`),
  ADD UNIQUE KEY `userID` (`userID`,`date`);

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`budgetID`),
  ADD UNIQUE KEY `userID` (`userID`,`budgetDate`);

--
-- Indexes for table `expcatlookup`
--
ALTER TABLE `expcatlookup`
  ADD PRIMARY KEY (`catLookupID`),
  ADD UNIQUE KEY `catName` (`catName`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenseID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `catLookupID` (`catLookupID`);

--
-- Indexes for table `savinggoals`
--
ALTER TABLE `savinggoals`
  ADD PRIMARY KEY (`savingID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `userlog`
--
ALTER TABLE `userlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance`
--
ALTER TABLE `balance`
  MODIFY `balanceID` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `budgetID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `expcatlookup`
--
ALTER TABLE `expcatlookup`
  MODIFY `catLookupID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenseID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `savinggoals`
--
ALTER TABLE `savinggoals`
  MODIFY `savingID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `userlog`
--
ALTER TABLE `userlog`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balance`
--
ALTER TABLE `balance`
  ADD CONSTRAINT `balance_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`catLookupID`) REFERENCES `expcatlookup` (`catLookupID`);

--
-- Constraints for table `savinggoals`
--
ALTER TABLE `savinggoals`
  ADD CONSTRAINT `savinggoals_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
