-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 03:12 AM
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
-- Database: `ccis_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `atb_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` varchar(25) DEFAULT NULL,
  `profimg` varchar(255) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contactnum` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`atb_id`, `user_id`, `admin_id`, `profimg`, `lname`, `fname`, `mname`, `birthdate`, `sex`, `address`, `contactnum`) VALUES
(1, 1, 'superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `audience` enum('All','Students','Faculty') DEFAULT 'All',
  `created_at` datetime DEFAULT current_timestamp(),
  `event_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `ftb_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `section` varchar(25) NOT NULL,
  `lec_room` varchar(25) DEFAULT NULL,
  `lec_day` varchar(25) DEFAULT NULL,
  `lec_start` time DEFAULT NULL,
  `lec_end` time DEFAULT NULL,
  `lab_room` varchar(25) DEFAULT NULL,
  `lab_day` varchar(25) DEFAULT NULL,
  `lab_start` time DEFAULT NULL,
  `lab_end` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `ftb_id`, `subject_id`, `semester_id`, `section`, `lec_room`, `lec_day`, `lec_start`, `lec_end`, `lab_room`, `lab_day`, `lab_start`, `lab_end`) VALUES
(1, 1, 1, 2, 'A', 'CHED 121', 'M,W,F', '08:00:00', '09:00:00', NULL, NULL, NULL, NULL),
(2, 1, 1, 2, 'B', 'CHED 121', 'T,TH', '08:00:00', '09:00:00', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `curriculums`
--

CREATE TABLE `curriculums` (
  `curriculum_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `curriculum_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `curriculums`
--

INSERT INTO `curriculums` (`curriculum_id`, `program_id`, `curriculum_name`) VALUES
(1, 1, 'CMO No. 25, Series 2015 Board Resolution No. 17 '),
(2, 1, 'CMO No. 25, Series 2015 COPC No. 86, s 2021');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `ftb_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `faculty_id` varchar(25) DEFAULT NULL,
  `profimg` varchar(255) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contactnum` varchar(15) DEFAULT NULL,
  `employee_status` enum('Full-time','Part-time') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`ftb_id`, `user_id`, `faculty_id`, `profimg`, `lname`, `fname`, `mname`, `birthdate`, `sex`, `address`, `contactnum`, `employee_status`) VALUES
(1, 2, 'TBA', 'default.png', 'tba', 'Tba', 'tba', '2025-07-15', 'Male', 'San Nicolas, Candon City, Ilocos Sur', '09123456789', 'Part-time'),
(2, 3, 'FMAGBULOS', 'default.png', 'Agbulos', 'Leslie', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 4, 'FMANCHETA', 'default.png', 'Ancheta', 'Cynthia', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 5, 'FMBARAYUGA', 'default.png', 'Barayuga', 'Vitruvius John', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 6, 'FMBATIN', 'default.png', 'Batin', 'Ramil', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 7, 'FMCABATIC', 'default.png', 'Cabatic', 'Rovel Harold Paul', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 8, 'FMCUEVAS', 'default.png', 'Cuevas', 'Ryan Jay', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 9, 'FMEVANGELISTA', 'default.png', 'Evangelista', 'April', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 10, 'FMGACULA', 'default.png', 'Gacula', 'Arjay', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 11, 'FMGACUSAN', 'default.png', 'Gacusan', 'Ronald', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 12, 'FMPASCUA', 'default.png', 'Pascua', 'Cliff Owen', NULL, NULL, NULL, NULL, NULL, NULL),
(12, 13, 'FMRACELES', 'default.png', 'Raceles', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 14, 'FMRAFAEL', 'default.png', 'Rafael', 'Kim Dave', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 15, 'FMLEE', 'default.png', 'Lee', 'Rosemarie', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 16, 'FMSUNIO', 'default.png', 'Sunio', 'Clarisse', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 17, 'FMVALDEZG', 'default.png', 'Valdez', 'Gerald', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 18, 'FMVALDEZH', 'default.png', 'Valdez', 'Helen', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `stb_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `mt_grade` float(4,2) DEFAULT NULL,
  `fn_grade` float(4,2) DEFAULT NULL,
  `sem_grade` float(4,2) DEFAULT NULL,
  `mt_numgrade` float(4,2) DEFAULT NULL,
  `fn_numgrade` float(4,2) DEFAULT NULL,
  `sem_numgrade` float(4,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`grade_id`, `stb_id`, `class_id`, `mt_grade`, `fn_grade`, `sem_grade`, `mt_numgrade`, `fn_numgrade`, `sem_numgrade`) VALUES
(1, 1, 1, 1.75, 2.75, 2.25, 90.18, 78.00, 84.09),
(2, 3, 1, 1.75, 5.00, 5.00, 90.00, 32.00, 61.00),
(3, 1, 2, 1.75, 2.75, 2.25, 90.18, 78.00, 84.09),
(4, 3, 2, 1.75, 5.00, 5.00, 90.00, 32.00, 61.00);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program_id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program_id`, `program_name`) VALUES
(1, 'Bachelor of Science in Computer Science'),
(2, 'Bachelor of Science in Information Technology');

-- --------------------------------------------------------

--
-- Table structure for table `schoolyears`
--

CREATE TABLE `schoolyears` (
  `schoolyear_id` int(11) NOT NULL,
  `schoolyear` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schoolyears`
--

INSERT INTO `schoolyears` (`schoolyear_id`, `schoolyear`) VALUES
(1, '2024-2025'),
(2, '2025-2026');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int(11) NOT NULL,
  `semester` enum('First Semester','Second Semester','Midyear') NOT NULL,
  `schoolyear_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `semester`, `schoolyear_id`, `is_active`) VALUES
(1, 'Midyear', 1, 0),
(2, 'First Semester', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stb_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `student_id` varchar(25) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `curriculum_id` int(11) DEFAULT NULL,
  `year_level` enum('First Year','Second Year','Third Year','Fourth Year') DEFAULT NULL,
  `profimg` varchar(255) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contactnum` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stb_id`, `user_id`, `student_id`, `program_id`, `major`, `curriculum_id`, `year_level`, `profimg`, `lname`, `fname`, `mname`, `birthdate`, `sex`, `address`, `contactnum`) VALUES
(1, 19, 'NLP-00-00000', 1, NULL, 1, 'Fourth Year', 'default.png', 'Student1', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 20, 'NLP-00-00001', 1, NULL, 1, 'Third Year', 'default.png', 'Student2', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 21, 'NLP-00-00002', 1, NULL, 2, 'Second Year', 'default.png', 'Student3', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 22, 'NLP-00-00003', 1, NULL, 2, 'First Year', 'default.png', 'Student4', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_schedules`
--

CREATE TABLE `student_schedules` (
  `schedule_id` int(11) NOT NULL,
  `stb_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_schedules`
--

INSERT INTO `student_schedules` (`schedule_id`, `stb_id`, `class_id`) VALUES
(1, 1, 1),
(3, 3, 1),
(4, 1, 2),
(5, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `curriculum_id` int(11) NOT NULL,
  `subject_code` varchar(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_type` enum('LEC','LEC with LAB') NOT NULL,
  `lec_units` int(1) NOT NULL,
  `lab_units` int(1) NOT NULL,
  `total_units` int(1) NOT NULL,
  `yearlevel_sem` enum('Y1S1','Y1S2','Y2S1','Y2S2','Y3S1','Y3S2','Y3S3','Y4S1','Y4S2') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `curriculum_id`, `subject_code`, `subject_name`, `subject_type`, `lec_units`, `lab_units`, `total_units`, `yearlevel_sem`) VALUES
(1, 1, 'CS 101', 'Introduction to Computing', 'LEC', 3, 0, 3, 'Y1S1'),
(2, 1, 'CS 102', 'Fundamentals of Programming', 'LEC with LAB', 2, 1, 3, 'Y1S1'),
(3, 1, 'Gen Ed103', 'Mathematics in the Modern World', 'LEC', 3, 0, 3, 'Y1S1'),
(4, 1, 'Gen Ed104', 'Understanding the Self', 'LEC', 3, 0, 3, 'Y1S1'),
(5, 1, 'GE Elec1', 'People and the Earth\'s Ecosystems', 'LEC', 3, 0, 3, 'Y1S1'),
(6, 1, 'Gen Ed110', 'The Entrepreneurial Mind', 'LEC', 3, 0, 3, 'Y1S1'),
(7, 1, 'PATHFit 1', 'Movement Competency Training or MCT', 'LEC', 2, 0, 2, 'Y1S1'),
(8, 1, 'NSTP 1', 'CWTS/ROTC 1', 'LEC', 3, 0, 3, 'Y1S1'),
(9, 1, 'CS 104', 'Discrete Structures 1: Logic, Sets, Relation, Function, and Proof Techniques', 'LEC', 3, 0, 3, 'Y1S2'),
(10, 1, 'CS 103', 'Intermediate Programming', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(11, 1, 'CS 105', 'Fundamentals of Human Computer Interaction', 'LEC', 3, 0, 3, 'Y1S2'),
(12, 1, 'Gen Ed 101', 'Purposive Communication', 'LEC', 3, 0, 3, 'Y1S2'),
(13, 1, 'PATHFit 2', 'Exercise-based Fitness Activities', 'LEC', 2, 0, 2, 'Y1S2'),
(14, 1, 'GE Elec 2', 'Philippine Popular Culture', 'LEC', 3, 0, 3, 'Y1S2'),
(15, 1, 'NSTP 2', 'CWTS/ROTC 1', 'LEC', 3, 0, 3, 'Y1S2'),
(16, 1, 'CS 201', 'Data Structures and Algorithms', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(17, 1, 'CS 202', 'Discrete Structures 2: Graphs, Trees, Matrices, Combinatorics, and Recurrences ', 'LEC', 3, 0, 3, 'Y2S1'),
(18, 1, 'Gen Ed107', 'The Contemporary World', 'LEC', 3, 0, 3, 'Y2S1'),
(19, 1, 'Gen Ed108', 'Art Appreciation', 'LEC', 3, 0, 3, 'Y2S1'),
(20, 1, 'PATHFit 3', 'Dances', 'LEC', 2, 0, 2, 'Y2S1'),
(21, 1, 'CS 203', 'Social Issues and Professional Practices', 'LEC', 3, 0, 3, 'Y2S1'),
(22, 1, 'CS 204', 'Parallel and Distributive Computing', 'LEC', 3, 0, 3, 'Y2S1'),
(23, 1, 'CS 205', 'Object-oriented Programming', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(24, 1, 'CS 206', 'System Fundamentals', 'LEC', 3, 0, 3, 'Y2S2'),
(25, 1, 'Gen Ed106', 'Ethics', 'LEC', 3, 0, 3, 'Y2S2'),
(26, 1, 'CS 207 ', 'Information Management', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(27, 1, 'CS 208', 'Architecture and Organization', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(28, 1, 'Gen Ed105', 'Science, Technology & Society', 'LEC', 3, 0, 3, 'Y2S2'),
(29, 1, 'CS 209', 'Applications Development and Emerging Technologies', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(30, 1, 'Math', 'Differential and Integral Calculus', 'LEC', 3, 0, 3, 'Y2S2'),
(31, 1, 'PATHFit 4', 'Sports', 'LEC', 2, 0, 2, 'Y2S2'),
(32, 1, 'CS 301', 'Programming Languages', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(33, 1, 'CS 302', 'Automata Theory and Formal Languages', 'LEC', 3, 0, 3, 'Y3S1'),
(34, 1, 'CS 303', 'Networks and Communication', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(35, 1, 'CS 304', 'Operating Systems', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(36, 1, 'CS 305', 'Software Engineering 1', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(37, 1, 'CS 306', 'Computational Science', 'LEC', 3, 0, 3, 'Y3S1'),
(38, 1, 'CS 307', 'Quantitative Methods', 'LEC', 3, 0, 3, 'Y3S1'),
(39, 1, 'Elective 1', 'Communicating Effectively', 'LEC', 3, 0, 3, 'Y3S1'),
(40, 1, 'CS 308', 'Software Engineering 2: Implementation and Management', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(41, 1, 'CS 309', 'Algorithm and Complexity', 'LEC', 3, 0, 3, 'Y3S2'),
(42, 1, 'CS 310', 'Intelligent System', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(43, 1, 'Gen Ed102', 'Reading in Philippine History', 'LEC', 3, 0, 3, 'Y3S2'),
(44, 1, 'CS 311', 'Graphics and Visual  Arts Computing', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(45, 1, 'CS 312', 'Research Methodology', 'LEC', 3, 0, 3, 'Y3S2'),
(46, 1, 'CS 313', 'Web Development', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(47, 1, 'Elective 2', 'Creative Writing', 'LEC', 3, 0, 3, 'Y3S2'),
(48, 1, 'CS 314', 'Ojt/Practicum (200 hrs)', 'LEC', 3, 0, 3, 'Y3S3'),
(49, 1, 'CS 401', 'Seminars and Tours', 'LEC', 1, 0, 1, 'Y4S1'),
(50, 1, 'CS 404', 'Project Study 1', 'LEC', 3, 0, 3, 'Y4S1'),
(51, 1, 'CS 402', 'Multimedia System', 'LEC with LAB', 2, 11, 13, 'Y4S1'),
(52, 1, 'Rizal', 'Rizal’s Life and Works', 'LEC', 3, 0, 3, 'Y4S1'),
(53, 1, 'CS 403', 'Information Assurance and Security', 'LEC with LAB', 2, 1, 3, 'Y4S2'),
(54, 1, 'CS 405', 'Project Study 2', 'LEC', 3, 0, 3, 'Y4S2'),
(55, 2, 'Gen Ed 101', 'Understanding the Self', 'LEC', 3, 0, 3, 'Y1S1'),
(56, 2, 'Gen Ed 102', 'Mathematics in the Modern World', 'LEC', 3, 0, 3, 'Y1S1'),
(57, 2, 'GE Elec 101', 'People and the Earth\'s Ecosystem', 'LEC', 3, 0, 3, 'Y1S1'),
(58, 2, 'Gen Ed 103', 'The Entrepreneurial Mind', 'LEC', 3, 0, 3, 'Y1S1'),
(59, 2, 'PATHFit 1', 'Movement Competency Training or MCT', 'LEC', 2, 0, 2, 'Y1S1'),
(60, 2, 'NSTP 1', 'CWTS 1/ ROTC 1', 'LEC', 3, 0, 3, 'Y1S1'),
(61, 2, 'CC 101', 'Introduction  to Computing', 'LEC with LAB', 2, 1, 3, 'Y1S1'),
(62, 2, 'CC 102', 'Fundamentals of Programming', 'LEC with LAB', 2, 1, 3, 'Y1S1'),
(63, 2, 'Gen Ed 104', 'Art Application', 'LEC', 3, 0, 3, 'Y1S2'),
(64, 2, 'PATHFit 2', 'Exercise-based Fitness Activities', 'LEC', 2, 0, 2, 'Y1S2'),
(65, 2, 'NSTP 2', 'CWTS 2 / ROTC 2', 'LEC', 3, 0, 3, 'Y1S2'),
(66, 2, 'CC 103', 'Intermediate Programming', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(67, 2, 'CC 104', 'Data Structures and Algorithms', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(68, 2, 'CC 105', 'Information Management', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(69, 2, 'CS 101', 'Fundamentals of HCI and Office Application', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(70, 2, 'CS 102', 'Discrete Structures', 'LEC', 3, 0, 3, 'Y1S2'),
(71, 2, 'CS 103', 'Object-Oriented Programming', 'LEC with LAB', 2, 1, 3, 'Y1S2'),
(72, 2, 'Gen Ed 105', 'Science, Technology and Society', 'LEC', 3, 0, 3, 'Y2S1'),
(73, 2, 'Math 1', 'Calculus 1', 'LEC', 3, 0, 3, 'Y2S1'),
(74, 2, 'Gen Ed 106', 'Probability and Statistics', 'LEC', 3, 0, 3, 'Y2S1'),
(75, 2, 'PATHFit 3', 'Choice of Dances', 'LEC', 2, 0, 2, 'Y2S1'),
(76, 2, 'CS 104', 'Programming Languages', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(77, 2, 'CS 105', 'Data Communication and Networks 1', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(78, 2, 'CS 106', 'Web Development 1', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(79, 2, 'CC 106', 'Application Development and Emerging Technologies', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(80, 2, 'CS 107', 'Systems Analysis and Design', 'LEC with LAB', 2, 1, 3, 'Y2S1'),
(81, 2, 'Gen Ed 107', 'Purposive Communication', 'LEC', 3, 0, 3, 'Y2S2'),
(82, 2, 'Gen Ed 108', 'Readings in Philippine History', 'LEC', 3, 0, 3, 'Y2S2'),
(83, 2, 'PATHFit 4', 'Sports', 'LEC', 2, 0, 2, 'Y2S2'),
(84, 2, 'CS 108', 'Web Development 2', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(85, 2, 'CS 109', 'Intelligent and Embedded Systems', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(86, 2, 'CS 110', 'Software Engineering and Software Testing', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(87, 2, 'CS 111', 'Parallel and Distributed Computing', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(88, 2, 'CS 112', 'Multimedia Systems and Animation', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(89, 2, 'CS Elective', 'CS Elective 1', 'LEC with LAB', 2, 1, 3, 'Y2S2'),
(90, 2, 'GE Elec 102', 'Philippine Popular Culture', 'LEC', 3, 0, 3, 'Y3S1'),
(91, 2, 'GE Elec 103', 'Reading Visual Art', 'LEC', 3, 0, 3, 'Y3S1'),
(92, 2, 'Math 2', 'Calculus 2', 'LEC', 3, 0, 3, 'Y3S1'),
(93, 2, 'CS 113', 'Data Communication and Networks 2', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(94, 2, 'CS 114', 'Graphics and Visual Computing', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(95, 2, 'CS 115', 'Automata Theory and Formal Languages', 'LEC', 3, 0, 3, 'Y3S1'),
(96, 2, 'CS 116', 'Algorithm and Complexity', 'LEC', 3, 0, 3, 'Y3S1'),
(97, 2, 'CS Elective', 'CS Elective 2', 'LEC with LAB', 2, 1, 3, 'Y3S1'),
(98, 2, 'Gen Ed 109', 'Contemporary World', 'LEC', 3, 0, 3, 'Y3S2'),
(99, 2, 'Gen Ed 110', 'Ethics', 'LEC', 3, 0, 3, 'Y3S2'),
(100, 2, 'Gen Ed 111', 'Gender and Society', 'LEC', 3, 0, 3, 'Y3S2'),
(101, 2, 'GE Elec 104', 'Communicating Effectively', 'LEC', 3, 0, 3, 'Y3S2'),
(102, 2, 'CS 117', 'Quantitative Methods - Advanced Statistics', 'LEC', 3, 0, 3, 'Y3S2'),
(103, 2, 'CS 118', 'Operating Systems and Architecture Organization', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(104, 2, 'Thesis 1', 'Thesis Study 1', 'LEC', 3, 0, 3, 'Y3S2'),
(105, 2, 'CS Elective', 'CS Elective 3', 'LEC with LAB', 2, 1, 3, 'Y3S2'),
(106, 2, 'OJT', 'Industry Immersion (280 hrs)', 'LEC', 3, 0, 3, 'Y3S3'),
(107, 2, 'Thesis 2', 'Thesis Study 2', 'LEC', 3, 0, 3, 'Y4S1'),
(108, 2, 'CS 119', 'Seminars and Tours', 'LEC', 1, 0, 1, 'Y4S1'),
(109, 2, 'CS 120', 'Technopreneurship', 'LEC', 3, 0, 3, 'Y4S2'),
(110, 2, 'CS 121', 'Social and Professional Practice', 'LEC', 3, 0, 3, 'Y4S2'),
(111, 2, 'CS 122', 'Computational Science', 'LEC', 3, 0, 3, 'Y4S2'),
(112, 2, 'Rizal', 'Rizal\'s Life and Works', 'LEC', 3, 0, 3, 'Y4S2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `userpassword` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','faculty','student') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'inactive',
  `created_at` datetime DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `userpassword`, `role`, `status`, `created_at`, `last_login`, `email`, `otp_code`, `otp_expiry`, `is_verified`) VALUES
(1, 'superadmin', '$2y$10$QqwfnGhTHxSxr3szS7D7FeVYFfuCULfKwuyN6RdpPRz03dPv2HxvC', 'superadmin', 'active', '2025-07-15 13:58:07', '2025-07-21 22:17:17', 'ccisportal2025@gmail.com', '328582', '2025-07-16 06:02:12', 1),
(2, 'TBA', '$2y$10$wQdpwRnSPG1DLneRfn12xOR/xSyVVfYPrJJc1fiBlkDSkD0gRv3SS', 'faculty', 'inactive', '2025-07-15 06:04:49', '2025-07-21 06:49:16', 'tbafaculty2025@gmail.com', '', '0000-00-00 00:00:00', 0),
(3, 'FMAGBULOS', '$2y$10$dakOB2.Md8MWn1qpAmfK6eWcfEwGSju8iW6vLF8AGu.XMKLtz5X1a', 'faculty', 'inactive', '2025-07-16 01:29:47', NULL, 'fmagbulos@gmail.com', '', '0000-00-00 00:00:00', 0),
(4, 'FMANCHETA', '$2y$10$hvf2Q7l9i8q4ojG7/Dum6.cRWgDcW2OLr0EwiSxz.cfb9VDM7v8Em', 'faculty', 'inactive', '2025-07-16 01:30:11', NULL, 'fmancheta@gmail.com', '', '0000-00-00 00:00:00', 0),
(5, 'FMBARAYUGA', '$2y$10$rfqjHKhEaa2pREXCz7LK0eO9b/g8x/9VCjl38cQKvBQ.PMinSMuwC', 'faculty', 'inactive', '2025-07-16 01:30:36', NULL, 'fmbarayuga@gmail.com', '', '0000-00-00 00:00:00', 0),
(6, 'FMBATIN', '$2y$10$F3AO6lNxGXQRgIRYeUsbquQMUDN6DoTlZFR6gyNCAOYKkx.urQNVC', 'faculty', 'inactive', '2025-07-16 01:31:18', NULL, 'fmbatin@gmail.com', '', '0000-00-00 00:00:00', 0),
(7, 'FMCABATIC', '$2y$10$XnCkjcwCmDUYdxpyxA/FW.Jk01FPYQBximtYeKQ4q./ZTuXoJC5bW', 'faculty', 'inactive', '2025-07-16 01:31:49', NULL, 'fmcabatic@gmail.com', '', '0000-00-00 00:00:00', 0),
(8, 'FMCUEVAS', '$2y$10$McVnVcG94SQ7dEhA6bRah.llmy1LfmbdtZovh54zomT51KqIlZ.MS', 'faculty', 'inactive', '2025-07-16 01:32:33', NULL, 'fmcuevas@gmail.com', '', '0000-00-00 00:00:00', 0),
(9, 'FMEVANGELISTA', '$2y$10$jiAP70yoLEi/qgQOs4v5Zeko/eEV9lLcz7J7BmU62fekoGhJVxdCO', 'faculty', 'inactive', '2025-07-16 01:33:18', NULL, 'fmevangelista@gmail.com', '', '0000-00-00 00:00:00', 0),
(10, 'FMGACULA', '$2y$10$TIfojyhV63xo8yZ7jVeNDuIZITyhbM7SdFRMR5/SB.lBOFj2E6NX.', 'faculty', 'inactive', '2025-07-16 01:33:42', NULL, 'fmgacula@gmail.com', '', '0000-00-00 00:00:00', 0),
(11, 'FMGACUSAN', '$2y$10$YwNqgNfe4feoc.GlB9zaB.w76ury45Y9xvZJWLseaTymQKffk4X7m', 'faculty', 'inactive', '2025-07-16 01:34:25', NULL, 'fmgacusan@gmail.com', '', '0000-00-00 00:00:00', 0),
(12, 'FMPASCUA', '$2y$10$Mks41lt31UU2Z4pYwdRtOe4tAIY8PeVyZyXxzeBBRczgrsm3BwK0O', 'faculty', 'inactive', '2025-07-16 01:34:47', NULL, 'fmpascua@gmail.com', '', '0000-00-00 00:00:00', 0),
(13, 'FMRACELES', '$2y$10$UHQHmvbPSMVooYjx450nYOJbgDHDBkGOgoDYPdJ.FqOQti6JEf8HG', 'faculty', 'inactive', '2025-07-16 01:35:24', NULL, 'fmraceles@gmail.com', '', '0000-00-00 00:00:00', 0),
(14, 'FMRAFAEL', '$2y$10$y6TQC0huAj4qwm.vd8KAh.R.o5ttdsfx8gqQYncvtbLg.l9v54l8W', 'faculty', 'inactive', '2025-07-16 01:36:25', NULL, 'fmrafael@gmail.com', '', '0000-00-00 00:00:00', 0),
(15, 'FMLEE', '$2y$10$pITejl5.n862Hv0Rxtr/kuIMGUk.EUp093Zr0Twkk1EdKhxwB4QuK', 'faculty', 'inactive', '2025-07-16 01:36:54', NULL, 'fmlee@gmail.com', '', '0000-00-00 00:00:00', 0),
(16, 'FMSUNIO', '$2y$10$40P4EmK9ZON9nk3UFMogEOx45HPldDMuic4FC16nEXNSYtLWJo41C', 'faculty', 'inactive', '2025-07-16 01:37:19', NULL, 'fmsunio@gmail.com', '', '0000-00-00 00:00:00', 0),
(17, 'FMVALDEZG', '$2y$10$jfxVFSif3BjCnb/mB.CgaOrRDyYc1IH.MTANocOCutI7GBCoUSII2', 'faculty', 'inactive', '2025-07-16 01:38:21', NULL, 'fmvaldezg@gmail.com', '', '0000-00-00 00:00:00', 0),
(18, 'FMVALDEZH', '$2y$10$cdmhZxAwXHBrpM/1jx7pkuYM6ZWkbjvLnU8ZbuJfbjTjnu0eZD8MC', 'faculty', 'inactive', '2025-07-16 01:38:51', NULL, 'fmvaldezh@gmail.com', '', '0000-00-00 00:00:00', 0),
(19, 'NLP-00-00000', '$2y$10$HElcyp2XeZBGdl7JICyIg.PruMnEC5snDT.JbvJP42/YElx/sian6', 'student', 'inactive', '2025-07-17 04:07:46', '2025-07-21 07:03:36', 'samplestudent@gmail.com', '', '0000-00-00 00:00:00', 0),
(20, 'NLP-00-00001', '$2y$10$gXmj9ok/q6/Zy3SDvGz7euZygiPTmQs9qn1xj9JTwSk9ZGPrPjeiG', 'student', 'inactive', '2025-07-17 04:08:32', NULL, 'samplestudent2@gmail.com', '', '0000-00-00 00:00:00', 0),
(21, 'NLP-00-00002', '$2y$10$bq.ysIrT4vhfVLOdrrSrPu6sflFv9LwqPzEUewMatcENUFZDltBQS', 'student', 'inactive', '2025-07-17 04:09:34', NULL, 'samplestudent3@gmail.com', '', '0000-00-00 00:00:00', 0),
(22, 'NLP-00-00003', '$2y$10$8OJKS4A08WwCmfDaHacwI.LMppvdTW/d7Ki7Mvkgn8On4AorTPtWG', 'student', 'inactive', '2025-07-17 04:10:02', NULL, 'samplestudent4@gmail.com', '', '0000-00-00 00:00:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`atb_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `admin_id` (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `semester_id` (`semester_id`),
  ADD KEY `classes_ibfk_1` (`ftb_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`curriculum_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`ftb_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `faculty_id` (`faculty_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `student_id` (`stb_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `schoolyears`
--
ALTER TABLE `schoolyears`
  ADD PRIMARY KEY (`schoolyear_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`),
  ADD KEY `schoolyear_id` (`schoolyear_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stb_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `curriculum_id` (`curriculum_id`);

--
-- Indexes for table `student_schedules`
--
ALTER TABLE `student_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `stb_id` (`stb_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `curriculum_id` (`curriculum_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `atb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `curriculum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `ftb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schoolyears`
--
ALTER TABLE `schoolyears`
  MODIFY `schoolyear_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stb_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_schedules`
--
ALTER TABLE `student_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`ftb_id`) REFERENCES `faculty` (`ftb_id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`semester_id`),
  ADD CONSTRAINT `classes_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `curriculums`
--
ALTER TABLE `curriculums`
  ADD CONSTRAINT `curriculums_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`);

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `faculty_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`stb_id`) REFERENCES `students` (`stb_id`);

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_ibfk_1` FOREIGN KEY (`schoolyear_id`) REFERENCES `schoolyears` (`schoolyear_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`program_id`),
  ADD CONSTRAINT `students_ibfk_3` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`curriculum_id`);

--
-- Constraints for table `student_schedules`
--
ALTER TABLE `student_schedules`
  ADD CONSTRAINT `student_schedules_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `student_schedules_ibfk_2` FOREIGN KEY (`stb_id`) REFERENCES `students` (`stb_id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`curriculum_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
