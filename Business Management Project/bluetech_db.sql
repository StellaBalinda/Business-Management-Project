-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2017 at 05:49 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bluetech_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `brand_name`, `status`, `regdate`) VALUES
(1, 'sony', '1', '2017-04-10 11:17:59'),
(2, 'hp', '1', '2017-04-10 11:19:43'),
(3, 'lenovo', '0', '2017-04-10 14:42:11'),
(4, 'samsung', '0', '2017-04-11 12:06:23'),
(5, 'dell', '1', '2017-04-12 13:00:26'),
(6, 'toshiba', '1', '2017-04-21 09:36:08'),
(7, 'apple', '1', '2017-04-21 09:37:02'),
(8, 'techno', '1', '2017-04-21 09:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `status`, `regdate`) VALUES
(1, 'laptopp', '1', '2017-04-10 10:53:36'),
(2, 'camera', '1', '2017-04-10 12:24:06'),
(3, 'printer', '1', '2017-04-10 12:24:16'),
(4, 'PATCH PANEL', '1', '2017-04-10 13:16:53'),
(5, 'switch', '1', '2017-04-10 14:40:03'),
(6, 'router', '1', '2017-04-10 14:40:59'),
(7, 'computer', '1', '2017-04-10 17:11:35'),
(8, 'micro', '1', '2017-04-12 13:00:02'),
(9, 'cables', '0', '2017-04-12 13:01:02'),
(10, 'desktop', '1', '2017-04-12 13:01:32'),
(11, 'flat screen', '1', '2017-04-12 13:05:16'),
(12, 'server2', '1', '2017-04-21 09:43:04'),
(13, 'server4', '1', '2017-04-21 09:54:27'),
(14, 'computer2', '0', '2017-05-02 11:23:44'),
(15, 'cat test1', '0', '2017-05-03 08:45:01'),
(16, '', '0', '2017-05-04 10:20:12');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `regdate`) VALUES
(1, 'PAPETERIE DU PEUPLE', '2017-04-19 00:00:00'),
(2, 'G.S APEK', '0000-00-00 00:00:00'),
(3, 'PHARMACIE LA DIVINE', '0000-00-00 00:00:00'),
(4, 'customer4', '2017-04-21 10:25:57'),
(5, 'client test', '2017-05-03 08:55:40');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `pname` varchar(255) NOT NULL,
  `description` text,
  `id_category` int(11) NOT NULL,
  `id_brand` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `pname`, `description`, `id_category`, `id_brand`) VALUES
(2, '15 CERELON', 'this is a description for LAPTOP HP 15 CERELON product', 1, 2),
(3, '15 CERELON', 'LAPTOP HP 15 CERELON description', 1, 2),
(4, 'SCREEN 20  FOR DESKTOP', 'this is a escription', 10, 2),
(5, 'test', 'ewewwwe', 8, 6);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `id_product`, `invoice_number`, `unit_price`, `quantity_sold`, `total_amount`, `payment_mode`, `customer_id`, `regdate`) VALUES
(1, 1, '987', 9876, 50, 493800, 'cash', 4, '2017-05-03 09:28:56');

-- --------------------------------------------------------

--
-- Table structure for table `salestock`
--

CREATE TABLE `salestock` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salestock`
--

INSERT INTO `salestock` (`id`, `id_product`, `quantity`) VALUES
(1, 1, 4),
(2, 2, 50);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity_entry` int(11) DEFAULT '0',
  `quantity_out` int(11) DEFAULT '0',
  `destination` text,
  `total_quantity` int(11) DEFAULT '0',
  `purchasing_price` int(11) DEFAULT NULL,
  `selling_price` int(11) NOT NULL,
  `stock_alert_level` int(11) NOT NULL,
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_name` varchar(225) NOT NULL,
  `supplier_country` varchar(100) NOT NULL,
  `supplier_city` varchar(100) NOT NULL,
  `telephone` int(11) NOT NULL,
  `PO_Box` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `regdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_name`, `supplier_country`, `supplier_city`, `telephone`, `PO_Box`, `Email`, `regdate`) VALUES
(1, 'SMART CLEARING AGENCY', 'Rwanda', 'Kigali', 788865486, 'pobox 201', 'smartclearingagency@gmail.com', '2017-05-01 00:00:00'),
(2, 'MONSOON TRADING RWANDA LTD', 'rwanda', 'kigali', 785969874, 'pobox 106', 'monsoontrading@gmail.com', '2017-05-03 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `privilege` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `password`, `privilege`, `status`, `date`, `lastlogin`) VALUES
(1, 'aline', 'akuzwe', 'aline', 'aline123', '1', '1', '2017-01-02 00:00:00', '2017-05-17 16:03:22'),
(2, 'damien', 'cyuzuzo', 'damien', 'damien123', '1', '1', '2017-01-02 00:00:00', '0000-00-00 00:00:00'),
(3, 'linda', 'balinda', 'linda', 'linda123', '0', '1', '2017-04-19 00:00:00', '2017-05-12 18:26:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_brand` (`id_brand`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salestock`
--
ALTER TABLE `salestock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `salestock`
--
ALTER TABLE `salestock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`id_category`) REFERENCES `category` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
