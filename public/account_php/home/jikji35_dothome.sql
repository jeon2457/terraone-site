-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- 생성 시간: 25-03-08 16:31
-- 서버 버전: 5.7.44
-- PHP 버전: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `jikji35`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `expense_table`
--

CREATE TABLE `expense_table` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `expense_table`
--

INSERT INTO `expense_table` (`id`, `date`, `category`, `description`, `amount`) VALUES
(3, '2024-08-19', '식사', '', 20000),
(4, '2025-01-07', '퀵보드', '인터넷', 1250000),
(8, '2025-02-20', '화장품', '1개', 36000),
(10, '2025-03-04', '컴퓨터', '조립품', 1200045),
(12, '2025-03-04', 'USB', '1개', 12500),
(13, '2025-03-05', '이발', '', 14000),
(14, '2025-02-05', '샴푸', '1', 17501),
(15, '2025-01-15', '회식비', '5명', 110000);

-- --------------------------------------------------------

--
-- 테이블 구조 `images`
--

CREATE TABLE `images` (
  `idx` int(11) NOT NULL,
  `id` int(25) DEFAULT NULL,
  `password` int(25) DEFAULT NULL,
  `photo` varchar(2048) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `notice` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `images`
--

INSERT INTO `images` (`idx`, `id`, `password`, `photo`, `date`, `notice`) VALUES
(7, 0, 0, 'data/profile/1000069643.png', '2025-02-09 06:43:38', '터스트'),
(8, 0, 0, 'data/profile/1000087879.png', '2025-03-01 04:26:33', '카톡'),
(10, 0, 0, 'data/profile/1000087611.png', '2025-03-04 00:32:39', '크로버'),
(11, 0, 0, 'data/profile/1000087956.png', '2025-03-04 20:50:40', '증권'),
(12, 0, 0, 'data/profile/dafdc290-4a22-4f71-a2d1-3830821a00f4-1_all_65896.jpg', '2025-01-05 00:00:00', 'Watch'),
(13, NULL, NULL, 'data/profile/1000087830.png', '2025-03-02 00:00:00', '유튜브');

-- --------------------------------------------------------

--
-- 테이블 구조 `income_table`
--

CREATE TABLE `income_table` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `category` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `income_table`
--

INSERT INTO `income_table` (`id`, `date`, `category`, `description`, `amount`) VALUES
(1, '2025-01-01', '이월잔액', '', 3990000),
(11, '2025-01-02', '회비', 'Test', 25000),
(12, '2025-02-02', '월회비', '5', 90001),
(15, '2025-03-04', '찬조금', '2', 150405),
(16, '2025-02-10', '찬조금', '', 70000);

-- --------------------------------------------------------

--
-- 테이블 구조 `member`
--

CREATE TABLE `member` (
  `idx` int(10) DEFAULT NULL,
  `id` varchar(300) DEFAULT NULL,
  `name` varchar(300) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `tel` varchar(39) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  `zipcode` char(15) DEFAULT NULL,
  `addr1` varchar(765) DEFAULT NULL,
  `addr2` varchar(765) DEFAULT NULL,
  `photo` varchar(300) DEFAULT NULL,
  `create_at` datetime DEFAULT NULL,
  `login_dt` datetime DEFAULT NULL,
  `ip` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `member`
--

INSERT INTO `member` (`idx`, `id`, `name`, `password`, `tel`, `email`, `zipcode`, `addr1`, `addr2`, `photo`, `create_at`, `login_dt`, `ip`) VALUES
(10, 'jeon6409', '안중근', '$2y$10$fRoOrIPm3bGwgxNiN7DgVO4iIcmezEvCDeFYqYFCzT5iJ3zBaGo.W', '010-5252-8888', 'jeon6409@naver.com', '15293', '경기 안산시 상록구 성포동 595 (성포동, 청소년수련관)', '233', 'jeon6409.png', '2023-05-13 18:43:24', NULL, '::1'),
(11, 'terraone', '홍길동888', '$2y$10$OMpmptiI7QWOHnOGQhh0IuimkNTeL1xS9mfW1cKKujgwuJc9CcL/a', '010-2544-6666', 'terraone@nate.com', '15292', '경기 안산시 상록구 성포동 591-1 (성포동, 경일초등학교)', '1818', 'terraone.png', '2023-05-13 22:35:56', '2023-05-15 13:04:23', '::1'),
(12, 'korea', '김유신', '$2y$10$UB/ukSfBo86OybfBJ2eIxuqdSbwAlO3LBiXioRv9dT9bWsbqBsYJy', '010-2323-5555', 'korea@gmail.com', '25477', '강원 강릉시 가작로 267 (포남동, MBC강원영동)', '', 'korea.png', '2023-05-13 22:45:32', NULL, '::1'),
(13, 'korea-1', '이성계', '$2y$10$KSFGll90xsslFpRW5TJq0.7ql8l7kHYqWsOQjakvaF6gmjbclPh0K', '010-5555-6688', 'korea-1@naver.com', '25909', '강원 삼척시 새천년도로 629-59 (갈천동, MBC 강원영동)', '', 'korea-1.png', '2023-05-14 19:59:56', NULL, '::1'),
(14, 'jeon64090', '아이유999', '$2y$10$7YS.nIbqXtRhkSLue.5em.JpQ0MET.WVcoq9ExfXQM/eZMLBdoRQm', '010-9999-9999', 'jeon64090@nate.net', '05562', '서울 송파구 백제고분로9길 10 (잠실동)', '', 'jeon64090.png', '2023-05-17 21:21:30', '2023-05-17 21:54:46', '::1');

-- --------------------------------------------------------

--
-- 테이블 구조 `tel`
--

CREATE TABLE `tel` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `addr` varchar(255) NOT NULL,
  `remark` varchar(12) NOT NULL,
  `sms` varchar(128) NOT NULL,
  `sms_2` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 덤프 데이터 `tel`
--

INSERT INTO `tel` (`id`, `name`, `tel`, `addr`, `remark`, `sms`, `sms_2`) VALUES
(1, '김병철', '010-3371-7107', '김천', '총무', '010-3371-7107', '010-9142-7982,010-9140-8020,010-8980-3564,010-3588-2322,010-9321-1183,010-9535-4311,010-3535-0111,010-6866-8844,010-6790-3746,010-5281-8008,010-6294-5982,010-6488-2412,010-2760-3519,010-7289-0987,010-9609-1688,010-4538-6724,010-5958-9945,010-2276-6007,010-6623-5695,010-8586-2506'),
(2, '김상우', '010-9140-8020', '안양', '', '010-9140-8020', ''),
(3, '김상철', '010-8980-3564', '서울', '', '010-8980-3564', ''),
(4, '김이식', '010-3588-2322', '부산', '', '010-3588-2322', ''),
(6, '김춘배', '010-9321-1183', '울산', '', '010-9321-1183', ''),
(7, '라찬숙', '010-9535-4311', '여주', '', '010-9535-4311', ''),
(8, '박건용', '010-3535-0111', '상주', '', '010-3535-0111', ''),
(9, '박영미', '010-6866-8844', '청주', '', '010-6866-8844', ''),
(11, '백금순', '010-6790-3746', '구미', '', '010-6790-3746', ''),
(12, '백기성', '010-5281-8008', '김천', '', '010-5281-8008', ''),
(13, '백상숙', '010-6294-5982', '김천', '', '010-6294-5982', ''),
(16, '안 호', '010-6488-2412', '김천', '회장', '010-6488-2412', '010-9142-7982,010-3371-7107,010-9140-8020,010-8980-3564,010-3588-2322,010-9321-1183,010-9535-4311,010-3535-0111,010-6866-8844,010-6790-3746,010-5281-8008,010-6294-5982,010-2760-3519,010-7289-0987,010-9609-1688,010-4538-6724,010-5958-9945,010-2276-6007,010-6623-5695,010-8586-2506'),
(17, '유영식', '010-2760-3519', '인천', '', '010-2760-3519', ''),
(21, '전기성', '010-7289-0987', '부산', '', '010-7289-0987', ''),
(22, '전상준', '010-9609-1688', '안산', '', '010-9609-1688', ''),
(24, '전종철', '010-4538-6724', '김천', '', '010-4538-6724', ''),
(25, '전창섭', '010-5958-9945', '김천', '', '010-5958-9945', ''),
(26, '조병남', '010-2276-6007', '천안', '', '010-2276-6007', ''),
(28, '최영애', '010-6623-5695', '황간', '', '010-6623-5695', ''),
(29, '허남희', '010-8586-2506', '김천', '', '010-8586-2506', ''),
(30, '김미영', '010-9142-7982', '김천', '', '010-9142-7982', ''),
(40, '홍길동2', '010-3333-1155', '수원', '', '010-3333-1155', '0101111-2121,010-2232-4545'),
(43, '가로수', '010-3333-9999', '대전', '', '010-3333-9999', ''),
(45, '김기수', '010-8888-2222', '군포', '', '010-8888-2222', '01011112222,0102222-3333,0103333-4444'),
(46, '김다은', '010-6565-9999', '서울', '', '010-6565-9999', ''),
(47, '강감찬', '010-2222-2222', '제주', '감사', '010-2222-2222', '01022225555,0106668888'),
(48, '강원래', '010-9999-3311', '부천', '감사a', '010-9999-3311', '010-1111-1111,010-2222-3232,010-3333-6565');

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `expense_table`
--
ALTER TABLE `expense_table`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`idx`);

--
-- 테이블의 인덱스 `income_table`
--
ALTER TABLE `income_table`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `tel`
--
ALTER TABLE `tel`
  ADD PRIMARY KEY (`id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `expense_table`
--
ALTER TABLE `expense_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 테이블의 AUTO_INCREMENT `images`
--
ALTER TABLE `images`
  MODIFY `idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- 테이블의 AUTO_INCREMENT `income_table`
--
ALTER TABLE `income_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- 테이블의 AUTO_INCREMENT `tel`
--
ALTER TABLE `tel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
