-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 05:50 PM
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
-- Database: `expandx_internship_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `inv_category`
--

CREATE TABLE `inv_category` (
  `categoryuid` int(11) NOT NULL,
  `categoryname` varchar(250) NOT NULL,
  `categorycode` varchar(30) NOT NULL,
  `categorystatus` int(11) NOT NULL,
  `categorydescription` varchar(2000) NOT NULL,
  `categorycreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `categoryupdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_category`
--

INSERT INTO `inv_category` (`categoryuid`, `categoryname`, `categorycode`, `categorystatus`, `categorydescription`, `categorycreated`, `categoryupdated`) VALUES
(5, 'Housekeeping', 'HSKP', 1, 'Cleaning and maintenance supplies used in daily housekeeping operations.', '2025-11-26 09:11:11', NULL),
(6, 'Electrical', 'ELEC', 1, 'Electrical items commonly used for maintenance and repair.', '2025-11-26 09:11:37', NULL),
(10, 'Stationery', 'STAT', 1, 'NA', '2025-11-26 10:48:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_products`
--

CREATE TABLE `inv_products` (
  `productuid` int(11) NOT NULL,
  `categoryuid` int(11) NOT NULL,
  `productname` varchar(120) NOT NULL,
  `productsku` varchar(50) NOT NULL,
  `producthsn` varchar(255) NOT NULL,
  `productprice` decimal(10,2) NOT NULL,
  `productstock` int(11) DEFAULT 0,
  `productminstock` int(11) NOT NULL DEFAULT 0,
  `productimage` varchar(255) DEFAULT NULL,
  `productdescription` varchar(500) DEFAULT NULL,
  `productstatus` int(11) NOT NULL,
  `productcreated` timestamp NULL DEFAULT current_timestamp(),
  `productupdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_products`
--

INSERT INTO `inv_products` (`productuid`, `categoryuid`, `productname`, `productsku`, `producthsn`, `productprice`, `productstock`, `productminstock`, `productimage`, `productdescription`, `productstatus`, `productcreated`, `productupdated`) VALUES
(5, 5, 'Floor Cleaner (5L)', 'HSKP-FLC-001', '3402', 320.00, 18, 5, '1764148565_7571.jpg', 'Concentrated liquid floor cleaner used for daily mopping.', 1, '2025-11-26 09:16:05', '2025-11-26 09:19:04'),
(6, 6, 'Extension Board 4-Socket', 'ELEC-EXB-002', '8536', 320.00, 40, 10, '1764148695_4364.jpg', 'Heavy-duty extension board for power supply.', 1, '2025-11-26 09:18:15', '2025-11-26 09:18:42'),
(7, 10, 'A4 Size White Paper', 'STAT-A4-002', '4231', 120.00, 2, 10, '1764154557_3119.jpg', 'NA                       A           ', 1, '2025-11-26 10:55:57', '2025-11-26 15:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `inv_stockin`
--

CREATE TABLE `inv_stockin` (
  `stockinuid` int(11) NOT NULL,
  `productuid` int(11) NOT NULL,
  `previousstock` int(11) NOT NULL,
  `addedstock` int(11) NOT NULL,
  `newstock` int(11) NOT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `purchasedate` date NOT NULL,
  `stockintime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_stockin`
--

INSERT INTO `inv_stockin` (`stockinuid`, `productuid`, `previousstock`, `addedstock`, `newstock`, `supplier`, `remarks`, `purchasedate`, `stockintime`) VALUES
(1, 6, 20, 20, 40, 'XYZ', 'NA', '2025-11-18', '2025-11-26 09:18:42'),
(2, 7, 302, 20, 322, 'XYZ', 'NA', '2025-11-26', '2025-11-26 15:00:57'),
(3, 7, 322, 20, 342, 'XYZ', 'NA', '2025-11-26', '2025-11-26 15:04:04'),
(4, 7, 2, 100, 102, 'XYZ', 'NA', '2025-10-29', '2025-11-26 15:31:10'),
(5, 7, 2, 10, 12, 'XYZ', 'NA', '2025-11-26', '2025-11-26 15:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `inv_stockout`
--

CREATE TABLE `inv_stockout` (
  `stockoutuid` int(11) NOT NULL,
  `productuid` int(11) NOT NULL,
  `previousstock` int(11) NOT NULL,
  `removedstock` int(11) NOT NULL,
  `newstock` int(11) NOT NULL,
  `customer` varchar(255) DEFAULT NULL,
  `remarks` varchar(500) DEFAULT NULL,
  `useddate` date NOT NULL,
  `stockouttime` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_stockout`
--

INSERT INTO `inv_stockout` (`stockoutuid`, `productuid`, `previousstock`, `removedstock`, `newstock`, `customer`, `remarks`, `useddate`, `stockouttime`) VALUES
(1, 5, 20, 2, 18, 'ABC', 'NA', '2025-11-04', '2025-11-26 09:19:04'),
(2, 7, 342, 120, 222, 'Internal Usage', 'NA', '2025-11-26', '2025-11-26 15:11:34'),
(3, 7, 222, 220, 2, 'Internal Usage', 'NA', '2025-11-26', '2025-11-26 15:30:31'),
(4, 7, 102, 100, 2, 'Internal Usage', 'NA', '2025-11-26', '2025-11-26 15:32:07'),
(5, 7, 12, 10, 2, 'Internal Usage', 'NA', '2025-11-26', '2025-11-26 15:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `inv_users`
--

CREATE TABLE `inv_users` (
  `useruid` int(11) NOT NULL,
  `userfname` varchar(250) NOT NULL,
  `userlname` varchar(250) NOT NULL,
  `useremail` varchar(250) NOT NULL,
  `userpass` varchar(250) NOT NULL,
  `usercreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `userupdated` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_users`
--

INSERT INTO `inv_users` (`useruid`, `userfname`, `userlname`, `useremail`, `userpass`, `usercreated`, `userupdated`) VALUES
(1, 'Demo', 'Admin', 'demo@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', '2025-11-22 18:39:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `inv_users_login_history`
--

CREATE TABLE `inv_users_login_history` (
  `loginid` int(11) NOT NULL,
  `useremail` varchar(250) NOT NULL,
  `logintoken` varchar(250) NOT NULL,
  `logintime` timestamp NOT NULL DEFAULT current_timestamp(),
  `logouttime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inv_users_login_history`
--

INSERT INTO `inv_users_login_history` (`loginid`, `useremail`, `logintoken`, `logintime`, `logouttime`) VALUES
(1, 'demo@gmail.com', '052b6344a8df42f0422e8b89c3503b19', '2025-11-25 10:53:13', NULL),
(2, 'demo@gmail.com', 'ba820a44625a55bd6fdb184bb49a7cd8', '2025-11-25 11:02:56', NULL),
(3, 'demo@gmail.com', '0720b164ecf8acdf43e575f79e2b4323', '2025-11-25 11:05:44', NULL),
(4, 'demo@gmail.com', '8468b7f601b7e360875a7b602e9050d1', '2025-11-25 11:21:46', NULL),
(5, 'demo@gmail.com', 'f0e7cde98c693099d71da2b0d36ad78a', '2025-11-25 18:19:08', NULL),
(6, 'demo@gmail.com', '7691e968d6fa9719a95b1f8d94011756', '2025-11-26 08:17:26', NULL),
(7, 'demo@gmail.com', 'd22b84f7c92a484e1fae48d4ea73f522', '2025-11-26 08:25:44', NULL),
(8, 'demo@gmail.com', '0496ebbf3db8cd6cc1fb069a73d15f3b', '2025-11-26 08:28:54', NULL),
(9, 'demo@gmail.com', 'e9e30631942c24a71baa05878d141b57', '2025-11-26 08:31:53', NULL),
(10, 'demo@gmail.com', '491143cf58af4d5a026bd03e0e94e924', '2025-11-26 08:34:05', NULL),
(11, 'demo@gmail.com', '8aa6ea230c6b105d37c0ba16ccfaba03', '2025-11-26 08:36:39', NULL),
(12, 'demo@gmail.com', '18ba3f568fd5aeef92a8784885677020', '2025-11-26 08:55:50', NULL),
(13, 'demo@gmail.com', '426dd3ac729530c412791f349719aa71', '2025-11-26 08:56:55', NULL),
(14, 'demo@gmail.com', '941396e45f2ba9792ca7be1745ae593b', '2025-11-26 09:01:28', NULL),
(15, 'demo@gmail.com', 'f25e1676bc7e8e0193f7fea1235d14a7', '2025-11-26 09:03:37', NULL),
(16, 'demo@gmail.com', '7ecae32c1193717d611e2375e0120cad', '2025-11-26 09:06:03', NULL),
(17, 'demo@gmail.com', 'be4951d2b2e4c361cad5a02c3474b169', '2025-11-26 09:09:09', NULL),
(18, 'demo@gmail.com', '973022a09ccddfbd56d68c18ebb66c2d', '2025-11-26 09:22:37', NULL),
(19, 'demo@gmail.com', '17acb49152b815e7d98cc5b981580b8b', '2025-11-26 09:24:11', NULL),
(20, 'demo@gmail.com', '52503423c4c4825db91028cdc1a974d0', '2025-11-26 10:15:05', NULL),
(21, 'demo@gmail.com', '98e739dfbac3940740fe5de240a3bf31', '2025-11-26 10:19:42', NULL),
(22, 'demo@gmail.com', '78bda0ae3ffac33317fe003ec4b87e3e', '2025-11-26 10:23:21', NULL),
(23, 'demo@gmail.com', 'a0049958d7abfed2d5b8b6c44848861d', '2025-11-26 10:24:12', NULL),
(24, 'demo@gmail.com', '42fb577d17d7f79c4e0de005637156e6', '2025-11-26 10:25:12', NULL),
(25, 'demo@gmail.com', '8682271351bfb5c4a5724d95adecfd73', '2025-11-26 14:50:29', NULL),
(26, 'demo@gmail.com', '04547cca5a6370692a45b39d560a7922', '2025-11-26 15:43:36', NULL),
(27, 'demo@gmail.com', 'd05af1b7211e5f79434f5fdc73e8c04d', '2025-11-26 15:44:55', NULL),
(28, 'demo@gmail.com', '9c31640a06348e4ef1831196849b293e', '2025-11-26 15:46:00', NULL),
(29, 'demo@gmail.com', 'cfaf36d22e0880b2db3fe58cf93b224f', '2025-11-26 15:51:40', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inv_category`
--
ALTER TABLE `inv_category`
  ADD PRIMARY KEY (`categoryuid`);

--
-- Indexes for table `inv_products`
--
ALTER TABLE `inv_products`
  ADD PRIMARY KEY (`productuid`),
  ADD UNIQUE KEY `productsku` (`productsku`),
  ADD KEY `fk_category` (`categoryuid`);

--
-- Indexes for table `inv_stockin`
--
ALTER TABLE `inv_stockin`
  ADD PRIMARY KEY (`stockinuid`),
  ADD KEY `fk_stockin_product` (`productuid`);

--
-- Indexes for table `inv_stockout`
--
ALTER TABLE `inv_stockout`
  ADD PRIMARY KEY (`stockoutuid`),
  ADD KEY `fk_stockin_product` (`productuid`);

--
-- Indexes for table `inv_users`
--
ALTER TABLE `inv_users`
  ADD PRIMARY KEY (`useruid`);

--
-- Indexes for table `inv_users_login_history`
--
ALTER TABLE `inv_users_login_history`
  ADD PRIMARY KEY (`loginid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inv_category`
--
ALTER TABLE `inv_category`
  MODIFY `categoryuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inv_products`
--
ALTER TABLE `inv_products`
  MODIFY `productuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `inv_stockin`
--
ALTER TABLE `inv_stockin`
  MODIFY `stockinuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inv_stockout`
--
ALTER TABLE `inv_stockout`
  MODIFY `stockoutuid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inv_users`
--
ALTER TABLE `inv_users`
  MODIFY `useruid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inv_users_login_history`
--
ALTER TABLE `inv_users_login_history`
  MODIFY `loginid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inv_products`
--
ALTER TABLE `inv_products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`categoryuid`) REFERENCES `inv_category` (`categoryuid`);

--
-- Constraints for table `inv_stockin`
--
ALTER TABLE `inv_stockin`
  ADD CONSTRAINT `fk_stockin_product` FOREIGN KEY (`productuid`) REFERENCES `inv_products` (`productuid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
