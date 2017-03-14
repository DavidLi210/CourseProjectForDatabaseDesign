-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2017 at 03:39 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `Author_Id` int(20) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `Isbn` varchar(10) NOT NULL,
  `Isbn13` varchar(13) NOT NULL,
  `Title` varchar(200) NOT NULL,
  `Author` varchar(160) NOT NULL,
  `Cover` varchar(65) DEFAULT NULL,
  `Publisher` varchar(60) DEFAULT NULL,
  `Pages` int(11) DEFAULT NULL,
  `Availability` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book_authors`
--

CREATE TABLE `book_authors` (
  `Author_Id` int(20) NOT NULL,
  `Isbn` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `book_loans`
--

CREATE TABLE `book_loans` (
  `Loan_id` varchar(20) NOT NULL,
  `Isbn` varchar(20) NOT NULL,
  `Card_id` varchar(20) NOT NULL,
  `Date_out` datetime DEFAULT CURRENT_TIMESTAMP,
  `Due_date` datetime DEFAULT NULL,
  `Date_in` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `borrower`
--

CREATE TABLE `borrower` (
  `Card_id` varchar(20) NOT NULL,
  `Ssn` varchar(20) NOT NULL,
  `Bname` varchar(15) NOT NULL,
  `Lname` varchar(15) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `Address` varchar(45) NOT NULL,
  `city` varchar(15) NOT NULL,
  `states` varchar(5) NOT NULL,
  `Phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `Loan_id` varchar(20) NOT NULL,
  `Fine_amt` float NOT NULL,
  `Paid` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`Author_Id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`Isbn`);

--
-- Indexes for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD PRIMARY KEY (`Author_Id`,`Isbn`),
  ADD KEY `fk_book_authors2` (`Isbn`);

--
-- Indexes for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD PRIMARY KEY (`Loan_id`),
  ADD KEY `fk_book_loans_book` (`Isbn`),
  ADD KEY `fk_book_loans_borrower` (`Card_id`);

--
-- Indexes for table `borrower`
--
ALTER TABLE `borrower`
  ADD PRIMARY KEY (`Card_id`),
  ADD UNIQUE KEY `uc_ssn` (`Ssn`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`Loan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `Author_Id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25001;
--
-- AUTO_INCREMENT for table `book_authors`
--
ALTER TABLE `book_authors`
  MODIFY `Author_Id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25001;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_authors`
--
ALTER TABLE `book_authors`
  ADD CONSTRAINT `fk_book_authors1` FOREIGN KEY (`Author_Id`) REFERENCES `authors` (`Author_Id`),
  ADD CONSTRAINT `fk_book_authors2` FOREIGN KEY (`Isbn`) REFERENCES `book` (`Isbn`);

--
-- Constraints for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD CONSTRAINT `fk_book_loans_book` FOREIGN KEY (`Isbn`) REFERENCES `book` (`Isbn`),
  ADD CONSTRAINT `fk_book_loans_borrower` FOREIGN KEY (`Card_id`) REFERENCES `borrower` (`Card_id`);

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk_fines_book_loans` FOREIGN KEY (`Loan_id`) REFERENCES `book_loans` (`Loan_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
