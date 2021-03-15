-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 15, 2021 at 11:10 AM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stag_wongalive`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE `cms_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','block') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `title`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Terms & Condition Page', 'INTRODUCTIONS:-\r\n\r\n\r\n- These terms and conditions shall govern your use of our App.\r\n- By using our App, you accept these terms and conditions in full accordingly, if you disagree with these terms and conditions or any part of these terms and conditions, you must not use our App.\r\n- If you [register with our App, submit any material to our App or use any of our App services], we will ask you to expressly agree to these terms and conditions.\r\n- You must be at least 12 years of age to use our App, by using our App or agreeing to these terms and conditions, you warrant and represent to us that you are at least (12) years of age.\r\n\r\n\r\nCOPYRIGHT NOTICE:-\r\n\r\n\r\n= Copyright (c) 2020 Wonga Live (https://www.aistechnolabs.com/). Subject to the express provisions of these terms and conditions:\r\n- We, together with our licensors, own and control all the copyright and other intellectual property rights in our App and the material on our App.\r\n- All the copyright and other intellectual property rights in our App and the material on our App are reserved.\r\n- Subject to the other provisions of these terms and conditions.\r\n- You may only use our App for Entertainment OR influencing, you must not use our App for any other purposes[for objectionable content or abusive users].\r\n- Except as expressly permitted by these terms and conditions, you must not edit or otherwise modify any material.\r\n- Unless you own or control the relevant rights in the material, you must not, republish material from our App (including republication on another App) sell, rent or sub-license material from our App;\r\n- Show any material from our App in public.\r\n- Exploit material from our App for a commercial purpose or redistribute material from our App.\r\n- We reserve the right to suspend or restrict access to our App, to areas of our App and/or to functionality upon our App. We may, for example, suspend access to the App (during server maintenance or when we update the App].\r\n- You must not circumvent or bypass, any access restriction measures on the App.\r\n\r\n\r\nMISUSE OF APP:-\r\n\r\n\r\n= You must not:-\r\n\r\n- Use our App in any way or take any action that causes, damage to the App or impairment of the performance, availability, accessibility, integrity or security of the App.\r\n- Use our App in any way that is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity hack or otherwise tamper with our App. Probe, scan or test the vulnerability of our App without our permission, circumvent any authentication or security systems or processes on or relating to our App.\r\n- Use our App to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software;\r\n\r\n:-NOTE:- These terms must make it clear that there is no tolerance for objectionable content or abusive users\r\n\r\n- You must ensure that all the information you supply to us through our App, or in relation to our App, is [true, accurate, current, complete and non misleading).\r\n\r\n= Use on behalf of organisation:-\r\n\r\n- If you use our App or expressly agree to these terms and conditions:-\r\n- users agree to terms (EULA) and these terms must make it clear that there is no tolerance for objectionable content or abusive users\r\n- On objectionable content reports within 24 hours by removing the content and ejecting the user who provided the offending content [An email will be sent to reported user regarding the issue (from the Developer Side to the user)]\r\n\r\n= Registration and accounts:-\r\n\r\n- You may register for an account with our App by [completing and submitting the account registration form on our App, and clicking on the verification link in the email that the App will send to you).\r\n- You must not allow any other person to use your account to access the App.\r\n- You must notify us in writing immediately if you become aware of any unauthorised use of your account.\r\n- You must not use any other person\"s account to access the Apps, unless you have that person\"s express permission to do so] user login details\r\n\r\n- If you register for an account with our App, [we will provide you with] OR [you will be asked to choose] [a user ID and password].\r\n- Your user ID must not be liable to mislead and must comply with the content you must not use your account or user ID for or in connection with the impersonation of any person.\r\n- You must keep your password confidential.\r\n- You must notify us in writing immediately if you become aware of any disclosure of your password.\r\n- You are responsible for any activity on our App arising out of any failure to keep your password confidential, and may be held liable for any losses arising out of such a failure.\r\nCancellation and suspension of account\r\n\r\n\r\nOUR RIGHTS TO USE YOUR CONTENT:-\r\n\r\n\r\n- In these terms and conditions, \"your content\" means all works and materials that you submit to our App for storage, processing by, or transmission via, our App.\r\n- You grant to us a [worldwide, irrevocable, non-exclusive, royalty-free licence] to [use, reproduce, store, adapt, publish, translate and distribute your content in any existing or future media] OR (reproduce, store and publish your content on and in relation to this App and any successor App] OR [reproduce, store and, with your specific consent, publish your content on and in relation to this App).\r\n- Without prejudice to our other rights under these terms and conditions, if you breach any provision of these terms and conditions in any way, or if we reasonably suspect that you have breached these terms and conditions in any way, we may delete, unpublish or edit any or all of your content.\r\n\r\n\r\nRULES ABOUT YOUR CONTENT\r\n\r\n\r\n- You warrant and represent that your content will comply with these terms and conditions.\r\n- Your content must not be illegal or unlawful, must not infringe any person\"s legal rights, and must not be capable of giving rise to legal action against any person (in each case in any jurisdiction and under any applicable law).\r\n- Your account get suspended or deleted permanantely for the violation of our terms.', 'active', '2020-09-05 03:12:39', '2020-11-20 00:47:28', NULL),
(2, 'Privacy Policy Page', 'PRIVACY POLICY:-\r\n\r\n\r\n- If you require any more information or have any questions about our privacy policy, please feel free to contact us by email at biz@aistechnolabs.com At AIS Technolabs, we consider the privacy of our visitors to be extremely important. This privacy policy document describes in detail the types of personal information is collected and recorded and how we use it.\r\n\r\n\r\n\r\nLOG FILES:-\r\n\r\n\r\n- Like many other Web sites, we makes use of log files. These files merely logs visitors to the site – usually a standard procedure for hosting companies and a part of hosting service’s analysis. The information inside the log files includes internet protocol (IP) addresses, browser type, Internet Service Provider (ISP), date/time stamp, referring/exit pages, and possibly the number of clicks. This information is used to analyze trends, administer the site, track user’s movement around the site, and gather demographic information. IP addresses, and other such information are not linked to any information that is personally identifiable.\r\n\r\n\r\n\r\nCOOKIES AND WEB BEACONS, WE DO NOT USE COOKIES:-\r\n\r\n\r\n- DoubleClick DART Cookie,\r\n- Google, as a third party vendor, uses cookies to serve ads on websites. Google’s use of the DART cookie enables it to serve ads to our site’s visitors based upon their visit to AIS Technolabs and other sites on the Internet. Users may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy.\r\n\r\n\r\n\r\nTHIRD PARTY PRIVACY POLICIES:-\r\n\r\n\r\n- You should consult the respective privacy policies of these third-party ad servers for more detailed information on their practices as well as for instructions about how to opt-out of certain practices. Our privacy policy does not apply to, and we cannot control the activities of, such other advertisers or web sites. You may find a comprehensive listing of these privacy policies If you wish to disable cookies, you may do so through your individual browser options. More detailed information about cookie management with specific web browsers can be found at the browsers’ respective websites.\r\n\r\n\r\n\r\nONLINE PRIVACY POLICY ONLY:-\r\n\r\n\r\n- This privacy policy applies only to our online activities and is valid for visitors to our website and regarding information shared and/or collected there. This policy does not apply to any information collected offline or via channels other than this website.\r\n\r\n\r\n\r\nCONSENT:-\r\n\r\n\r\nBy using our website, you hereby consent to our privacy policy and agree to its terms.', 'active', '2020-09-05 03:12:39', '2020-11-20 02:42:32', NULL),
(3, 'User Policy', 'https://www.aistechnolabs.com/. All rights reserved. Wonga Live APP and Wonga Live APP logo are registered trademarks of https://www.aistechnolabs.com/.', 'active', '2020-09-05 03:12:39', '2021-03-15 09:55:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_20_100152_create_cms_table', 2),
(5, '2020_08_20_131149_create_cms_pages_table', 3),
(6, '2020_08_24_044800_create_tasks_table', 4),
(7, '2020_09_05_062120_create_cms_pages_table', 5),
(8, '2020_09_05_131301_create_report_category_table', 6),
(9, '0000_00_00_000000_create_settings_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `otps`
--

CREATE TABLE `otps` (
  `id` int(11) NOT NULL,
  `email` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_number` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `otp_number` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `otp_expire` varchar(191) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`, `updated_at`) VALUES
(1, 'admin@aistechnolabs.xyz', 'GPNumUYen6gI1DYBoyKf3uTz9KWuw7a4imkDBLKJmDuHJR3yZg0KaZ7i2A5K', '2020-12-29 13:16:29', '2020-12-29 13:16:29');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('BOOLEAN','NUMBER','DATE','TEXT','SELECT','FILE','TEXTAREA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `code`, `type`, `label`, `value`, `hidden`, `created_at`, `updated_at`) VALUES
(1, 'application_logo', 'FILE', 'Application logo', 'application_logo.png', 0, '2019-12-17 18:59:48', '2019-12-17 18:59:48'),
(2, 'application_title', 'TEXT', 'Application Title', 'Project Name', 0, '2019-12-17 18:59:48', '2021-03-15 09:54:05'),
(3, 'favicon_logo', 'FILE', 'favicon Logo', 'favicon_logo.png', 0, '2019-12-17 18:59:48', '2020-09-08 05:59:06'),
(4, 'copyright', 'TEXT', 'Copyright Title', 'Project Name Web Site', 0, '2019-12-17 18:59:48', '2021-03-15 09:54:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','block') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `device_token` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` enum('ios','android') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_id` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_media` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sign_up_as` enum('web','other','fb','google','app') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_code` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_varifiy` enum('0','1') COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `available_flag` enum('online','offline') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `contact_number`, `avatar`, `email`, `email_verified_at`, `password`, `user_type`, `status`, `device_token`, `device_type`, `social_id`, `social_media`, `sign_up_as`, `link_code`, `otp_varifiy`, `available_flag`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'ais_mico', 'AIS', 'Technolabs Pvt', '9898989898', 'nuhde3Blqc.png', 'admin@aistechnolabs.xyz', NULL, '$2y$10$YF1p92RGDeaYcz3zeEhq/Oa3Vu9LqxCRhSGnQcN03w8THQa3Mcncu', 'superadmin', 'active', 'dIg-imQbOWw:APA91bFyP3ks7USAaryG6d_gUvxXVIGm3MvBp4nVW3o2QkSO-RRNa7Od1eZfz3JXrtYAR4xs80oKWTVDIaod6I2X9vvFMaAduikSamjGlhNQIlrRFpwkFaBZ3Vj2mNEDC62eqdDEd73i', 'android', NULL, NULL, NULL, NULL, '0', '', 'q8rT3pELuKb5cIMYkeJKMPYiqFAAA4g9gE6sLtEJ37YbDJf783SAhla2BG1g', '2020-08-07 04:44:53', '2021-03-15 10:01:39', NULL),
(2, 'maha11', 'Hardik', 'Maheshwari', '8488080145', 'default.png', 'user@mailinator.com', NULL, '$2y$10$rQX3RCVQpL.2jKgPViHMteZKyEWiDExEPUuaVM0TWeOhNj/711i46', 'user', 'active', 'dIg-imQbOWw:APA91bFyP3ks7USAaryG6d_gUvxXVIGm3MvBp4nVW3o2QkSO-RRNa7Od1eZfz3JXrtYAR4xs80oKWTVDIaod6I2X9vvFMaAduikSamjGlhNQIlrRFpwkFaBZ3Vj2mNEDC62eqdDEd73i', 'android', NULL, NULL, NULL, NULL, '0', '', NULL, '2020-08-17 23:26:38', '2021-03-15 10:02:15', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cms_pages`
--
ALTER TABLE `cms_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otps`
--
ALTER TABLE `otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
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
-- AUTO_INCREMENT for table `cms_pages`
--
ALTER TABLE `cms_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `otps`
--
ALTER TABLE `otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
