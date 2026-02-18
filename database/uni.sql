-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2026 at 07:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uni`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `DepartmentID` varchar(10) NOT NULL,
  `FacultiesID` varchar(10) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Founded_year` year(4) NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`DepartmentID`, `FacultiesID`, `Name`, `Founded_year`, `Status`) VALUES
('DID-0001', 'FID-0001', 'Physics', '2022', 'Active'),
('DID-0002', 'FID-0002', 'Business Administration', '2022', 'Active'),
('DID-0003', 'FID-0001', 'Biology', '2022', 'Inactive'),
('DID-0004', 'FID-0004', 'Civil Law', '2022', 'Inactive'),
('DID-0005', 'FID-0006', 'Computer Science', '2020', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `FacultiesID` varchar(10) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Founded_year` year(4) NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`FacultiesID`, `Name`, `Founded_year`, `Status`) VALUES
('FID-0001', 'Faculties of Science', '2022', 'Active'),
('FID-0002', 'Faculties of Business', '2022', 'Active'),
('FID-0003', 'Faculties of Engineering', '2020', 'Inactive'),
('FID-0004', 'Faculties of Law', '2020', 'Inactive'),
('FID-0005', 'Faculties of Medicine', '2020', 'Active'),
('FID-0006', 'Faculties of Computing & IT', '2020', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE `program` (
  `ProgramID` varchar(10) NOT NULL,
  `DepartmentID` varchar(10) NOT NULL,
  `Program_Name` varchar(50) NOT NULL,
  `Degree_level` varchar(20) NOT NULL,
  `Duration_years` int(11) NOT NULL,
  `Start_year` year(4) NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`ProgramID`, `DepartmentID`, `Program_Name`, `Degree_level`, `Duration_years`, `Start_year`, `Status`) VALUES
('PGID-0001', 'DID-0005', 'BSc Computer Science', 'Bachelor', 4, '2020', 'Active'),
('PGID-0002', 'DID-0005', 'MSc Computer Science', 'Master', 2, '2022', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `RoleID` varchar(10) NOT NULL,
  `Role` varchar(30) NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`RoleID`, `Role`, `Status`) VALUES
('RID-0001', 'Admin', 'Active'),
('RID-0002', 'Staff', 'Active'),
('RID-0003', 'Student', 'Active'),
('RID-0004', 'Guest', 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `SemesterID` varchar(10) NOT NULL,
  `ProgramID` varchar(10) NOT NULL,
  `Semester_no` tinyint(4) NOT NULL,
  `Name` varchar(10) NOT NULL,
  `Academic_year` varchar(10) NOT NULL,
  `Start_date` date NOT NULL,
  `End_date` date NOT NULL,
  `Status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` varchar(10) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `Status` varchar(10) NOT NULL,
  `Created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `UserID` varchar(10) NOT NULL,
  `RoleID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`DepartmentID`),
  ADD KEY `FacultiesID` (`FacultiesID`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`FacultiesID`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`ProgramID`),
  ADD KEY `DepartmentID` (`DepartmentID`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`RoleID`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`SemesterID`),
  ADD KEY `ProgramID` (`ProgramID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`UserID`,`RoleID`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `department`
--
ALTER TABLE `department`
  ADD CONSTRAINT `department_ibfk_1` FOREIGN KEY (`FacultiesID`) REFERENCES `faculties` (`FacultiesID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `program`
--
ALTER TABLE `program`
  ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`DepartmentID`) REFERENCES `department` (`DepartmentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `semester`
--
ALTER TABLE `semester`
  ADD CONSTRAINT `semester_ibfk_1` FOREIGN KEY (`ProgramID`) REFERENCES `program` (`ProgramID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`RoleID`) REFERENCES `role` (`RoleID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
