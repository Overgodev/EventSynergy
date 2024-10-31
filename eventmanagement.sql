-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 31, 2024 at 01:14 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `attendee_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`attendee_id`, `user_id`, `event_id`, `registration_date`) VALUES
(1, 5, 11, '2024-10-30 03:30:45'),
(2, 5, 9, '2024-10-30 03:33:08'),
(3, 5, 17, '2024-10-30 03:34:43'),
(4, 5, 12, '2024-10-30 03:34:47'),
(5, 5, 13, '2024-10-30 03:35:02'),
(6, 5, 10, '2024-10-30 03:35:57'),
(7, 5, 14, '2024-10-30 03:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `description` text,
  `organizer_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `max_attendance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_date`, `event_time`, `location`, `description`, `organizer_id`, `location_id`, `max_attendance`) VALUES
(9, 'Tech Conference 2024', '2024-11-15', '09:00:00', 'Bangkok Convention Center', 'An event for tech enthusiasts to learn and network.', 1, NULL, 100),
(10, 'Startup Expo 2024', '2024-12-05', '10:00:00', 'Virtual Event', 'A virtual event showcasing innovative startups.', 1, NULL, 200),
(11, 'AI Workshop', '2024-10-30', '14:00:00', 'Chulalongkorn University', 'A hands-on workshop to explore AI and machine learning concepts.', 2, NULL, 150),
(12, 'Cybersecurity Summit', '2024-11-20', '08:30:00', 'Queen Sirikit National Convention Center', 'Discussions on the latest trends in cybersecurity and data protection.', 3, NULL, 300),
(13, 'Blockchain Meetup', '2024-11-25', '17:00:00', 'Impact Arena', 'An open meetup for blockchain developers and enthusiasts.', 2, NULL, 250),
(14, 'Digital Marketing Forum', '2024-12-10', '11:00:00', 'CentralWorld, Bangkok', 'Learn the latest strategies in digital marketing from industry experts.', 4, NULL, 180),
(15, 'Healthcare Innovation Fair', '2025-01-15', '09:30:00', 'Siriraj Hospital', 'Showcasing innovative solutions in healthcare and medical technology.', 3, NULL, 350),
(16, 'E-Sports Championship', '2024-12-20', '12:00:00', 'Impact Challenger Hall', 'An e-sports event featuring competitive gaming tournaments.', 1, NULL, 400),
(17, 'we', '2024-10-26', '20:20:00', 'bangkadee', 'unity', NULL, NULL, 100),
(42, 'AI & Robotics Expo', '2025-01-10', '13:00:00', 'Robotics Innovation Center', 'Exhibition on AI and robotics technologies.', 3, 8, 500),
(43, 'Web Development Bootcamp', '2025-02-05', '09:00:00', 'Web Dev Hub', 'Intensive bootcamp for learning web development skills.', 5, 10, 450),
(44, 'IoT Conference', '2025-02-15', '10:00:00', 'Smart City Convention Center', 'Discussing innovations in the Internet of Things.', 1, 11, 120),
(45, 'Big Data Analytics Meet', '2025-03-01', '15:00:00', 'Data Hub Building', 'Meetup focusing on big data analytics and tools.', 2, 12, 300),
(46, 'AR/VR Summit', '2025-03-10', '11:30:00', 'Virtual Reality Center', 'Event showcasing AR and VR technologies.', 3, 13, 200),
(47, 'Mobile App Hackathon', '2025-03-25', '08:00:00', 'Tech Hub Building', '24-hour hackathon to build innovative mobile apps.', 4, 14, 250),
(48, 'Cloud Computing Expo', '2025-04-05', '14:30:00', 'Cloud Innovation Center', 'Expo on cloud technologies and services.', 5, 15, 320),
(49, 'AI in Finance Summit', '2025-04-20', '09:00:00', 'Finance Tech Auditorium', 'Exploring AI applications in the financial sector.', 1, 16, 280),
(50, 'Data Science Workshop', '2025-05-10', '13:00:00', 'Data Science Hub', 'Hands-on workshop for data science enthusiasts.', 2, 17, 220),
(51, 'Quantum Computing Conference', '2025-05-25', '14:00:00', 'Quantum Innovation Hall', 'Discussing breakthroughs in quantum computing.', 3, 18, 150),
(52, 'Green Tech Expo', '2025-06-05', '11:00:00', 'Green Tech Center', 'Showcasing sustainable and eco-friendly technologies.', 4, 19, 180),
(53, 'Ethical Hacking Workshop', '2025-06-20', '09:30:00', 'Cyber Defense Auditorium', 'Workshop on ethical hacking techniques and tools.', 5, 20, 260);

--
-- Triggers `events`
--
DELIMITER $$
CREATE TRIGGER `check_max_attendance` BEFORE INSERT ON `events` FOR EACH ROW BEGIN
    DECLARE loc_capacity INT;
    
    -- Get the capacity of the location associated with the event
    SELECT capacity INTO loc_capacity
    FROM eventmanagement.locations
    WHERE location_id = NEW.location_id;
    
    -- Check if max_attendance exceeds the location's capacity
    IF NEW.max_attendance > loc_capacity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: max_attendance cannot exceed location capacity';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_max_attendance_update` BEFORE UPDATE ON `events` FOR EACH ROW BEGIN
    DECLARE loc_capacity INT;

    -- Get the capacity of the location associated with the event
    SELECT capacity INTO loc_capacity
    FROM eventmanagement.locations
    WHERE location_id = NEW.location_id;
    
    -- Check if max_attendance exceeds the location's capacity
    IF NEW.max_attendance > loc_capacity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: max_attendance cannot exceed location capacity';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `event_sponsors`
--

CREATE TABLE `event_sponsors` (
  `event_id` int(11) NOT NULL,
  `sponsor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `address` text,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `location_name`, `address`, `city`, `state`, `zip_code`, `capacity`) VALUES
(8, 'Robotics Innovation Center', '123 Robotics St', 'Tech City', 'TX', '75001', 150),
(10, 'Web Dev Hub', '456 Dev Lane', 'Code City', 'CA', '90210', 100),
(11, 'Smart City Convention Center', '789 Smart Rd', 'Innovate City', 'NY', '10001', 200),
(12, 'Data Hub Building', '321 Data Blvd', 'Analytics City', 'FL', '33101', 250),
(13, 'Virtual Reality Center', '654 VR Ave', 'Virtual City', 'WA', '98101', 180),
(14, 'Tech Hub Building', '987 Tech Dr', 'Startup City', 'MA', '02101', 300),
(15, 'Cloud Innovation Center', '111 Cloud Way', 'Cloud City', 'OR', '97201', 120),
(16, 'Finance Tech Auditorium', '222 Finance St', 'Finance City', 'IL', '60601', 220),
(17, 'Data Science Hub', '333 Science Rd', 'Data City', 'TX', '75201', 150),
(18, 'Quantum Innovation Hall', '444 Quantum Blvd', 'Quantum City', 'AZ', '85001', 400),
(19, 'Green Tech Center', '555 Green St', 'Eco City', 'CA', '90001', 350),
(20, 'Cyber Defense Auditorium', '666 Defense Dr', 'Cyber City', 'VA', '22101', 500);

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
  `sponsor_id` int(11) NOT NULL,
  `sponsor_name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sponsors`
--

INSERT INTO `sponsors` (`sponsor_id`, `sponsor_name`, `contact_person`, `contact_email`, `phone_number`) VALUES
(1, 'Tech Corp', 'John Doe', 'johndoe@techcorp.com', '123-456-7890'),
(2, 'Innovate Inc.', 'Jane Smith', 'janesmith@innovate.com', '987-654-3210'),
(3, 'Alpha Industries', 'Michael Brown', 'michael@alpha.com', '456-789-1230'),
(4, 'Future Solutions', 'Emily White', 'emily@future.com', '321-654-9870'),
(5, 'Pioneer Partners', 'Sarah Green', 'sarah@pioneer.com', '789-123-4560'),
(6, 'Tech Corp2', 'Jane Doe', 'Janedoe@techcorp.com', '123-456-7892');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `user_type`, `created_at`) VALUES
(1, 'admin1', '$2y$10$T3vGzaIBlsSILGsWeOn6z.f3U8GLtdrDC9sOl/BxnOwOouYu4xq3a', 'admin1@example.com', 'Admin', '2024-10-23 09:24:26'),
(2, 'admin2', '$2y$10$J/Py40M6pG54L9SWS7CZkeQaHrX9gPkMwLmom5CZr5PC4h1nD4Vu.', 'admin2@example.com', 'Admin', '2024-10-23 09:24:26'),
(3, 'admin3', '$2y$10$JL1.GVOzXQn6mHJYObn/KO04dT1QY1BSa.sfcplg3zZXErkmBkkgC', 'admin3@example.com', 'Admin', '2024-10-23 09:24:26'),
(4, 'admin4', '$2y$10$1GU4to3t0r0ELQyKIdeXNecYEBGK2LKTNu5wtCclufXEtPPdDyApC', 'admin4@example.com', 'Admin', '2024-10-23 09:24:26'),
(5, 'user1', '$2y$10$Bwh6wt9O8FKtoqWwbH5XeemRLOwEDe7RFY.0xtiHo2MYZaDKNy/qK', 'user1@example.com', 'User', '2024-10-23 11:05:53'),
(8, 'user2', '$2y$10$WPoQP7n.icZkoBeDhm.W7.eEajgZjGyqJcWRVgrXHkvnHSj7zchUS', 'user2@example.com', 'User', '2024-10-26 02:39:25'),
(9, 'user3', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user3@example.com', 'User', '2024-10-26 02:45:55'),
(10, 'user4', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user4@example.com', 'User', '2024-10-26 02:45:55'),
(11, 'user5', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user5@example.com', 'User', '2024-10-26 02:45:55'),
(12, 'user6', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user6@example.com', 'User', '2024-10-26 02:45:55'),
(13, 'user7', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user7@example.com', 'User', '2024-10-26 02:45:55'),
(14, 'user8', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user8@example.com', 'User', '2024-10-26 02:45:55'),
(15, 'user9', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user9@example.com', 'User', '2024-10-26 02:45:55'),
(16, 'user10', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user10@example.com', 'User', '2024-10-26 02:45:55'),
(17, 'user11', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user11@example.com', 'User', '2024-10-26 02:45:55'),
(18, 'user12', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user12@example.com', 'User', '2024-10-26 02:45:55'),
(19, 'user13', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user13@example.com', 'User', '2024-10-26 02:45:55'),
(20, 'user14', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user14@example.com', 'User', '2024-10-26 02:45:55'),
(21, 'user15', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user15@example.com', 'User', '2024-10-26 02:45:55'),
(22, 'user16', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user16@example.com', 'User', '2024-10-26 02:45:55'),
(23, 'user17', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user17@example.com', 'User', '2024-10-26 02:45:55'),
(24, 'user18', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user18@example.com', 'User', '2024-10-26 02:45:55'),
(25, 'user19', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user19@example.com', 'User', '2024-10-26 02:45:55'),
(26, 'user20', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user20@example.com', 'User', '2024-10-26 02:45:55'),
(27, 'user21', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user21@example.com', 'User', '2024-10-26 02:45:55'),
(28, 'user22', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user22@example.com', 'User', '2024-10-26 02:45:55'),
(29, 'user23', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user23@example.com', 'User', '2024-10-26 02:45:55'),
(30, 'user24', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user24@example.com', 'User', '2024-10-26 02:45:55'),
(31, 'user25', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user25@example.com', 'User', '2024-10-26 02:45:55'),
(32, 'user26', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user26@example.com', 'User', '2024-10-26 02:45:55'),
(33, 'user27', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user27@example.com', 'User', '2024-10-26 02:45:55'),
(34, 'user28', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user28@example.com', 'User', '2024-10-26 02:45:55'),
(35, 'user29', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user29@example.com', 'User', '2024-10-26 02:45:55'),
(36, 'user30', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user30@example.com', 'User', '2024-10-26 02:45:55'),
(37, 'user31', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user31@example.com', 'User', '2024-10-26 02:45:55'),
(38, 'user32', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user32@example.com', 'User', '2024-10-26 02:45:55'),
(39, 'user33', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user33@example.com', 'User', '2024-10-26 02:45:55'),
(40, 'user34', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user34@example.com', 'User', '2024-10-26 02:45:55'),
(41, 'user35', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user35@example.com', 'User', '2024-10-26 02:45:55'),
(42, 'user36', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user36@example.com', 'User', '2024-10-26 02:45:55'),
(43, 'user37', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user37@example.com', 'User', '2024-10-26 02:45:55'),
(44, 'user38', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user38@example.com', 'User', '2024-10-26 02:45:55'),
(45, 'user39', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user39@example.com', 'User', '2024-10-26 02:45:55'),
(46, 'user40', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user40@example.com', 'User', '2024-10-26 02:45:55'),
(47, 'user41', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user41@example.com', 'User', '2024-10-26 02:45:55'),
(48, 'user42', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user42@example.com', 'User', '2024-10-26 02:45:55'),
(49, 'user43', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user43@example.com', 'User', '2024-10-26 02:45:55'),
(50, 'user44', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user44@example.com', 'User', '2024-10-26 02:45:55'),
(51, 'user45', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user45@example.com', 'User', '2024-10-26 02:45:55'),
(52, 'user46', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user46@example.com', 'User', '2024-10-26 02:45:55'),
(53, 'user47', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user47@example.com', 'User', '2024-10-26 02:45:55'),
(54, 'user48', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user48@example.com', 'User', '2024-10-26 02:45:55'),
(55, 'user49', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'user49@example.com', 'User', '2024-10-26 02:45:55'),
(57, 'admin5', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin5@example.com', 'Admin', '2024-10-26 02:47:00'),
(58, 'admin6', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin6@example.com', 'Admin', '2024-10-26 02:47:00'),
(59, 'admin7', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin7@example.com', 'Admin', '2024-10-26 02:47:00'),
(60, 'admin8', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin8@example.com', 'Admin', '2024-10-26 02:47:00'),
(61, 'admin9', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin9@example.com', 'Admin', '2024-10-26 02:47:00'),
(62, 'admin10', '$2y$10$wJaqd0a1D5nKj/IlG9pB8O7CVG9W/12RjNzKgRIjTBgpffuW3NZFi', 'admin10@example.com', 'Admin', '2024-10-26 02:47:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`attendee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `organizer_id` (`organizer_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `event_sponsors`
--
ALTER TABLE `event_sponsors`
  ADD PRIMARY KEY (`event_id`,`sponsor_id`),
  ADD KEY `sponsor_id` (`sponsor_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`sponsor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `attendee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `sponsor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `attendees_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `event_sponsors`
--
ALTER TABLE `event_sponsors`
  ADD CONSTRAINT `event_sponsors_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`),
  ADD CONSTRAINT `event_sponsors_ibfk_2` FOREIGN KEY (`sponsor_id`) REFERENCES `sponsors` (`sponsor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
