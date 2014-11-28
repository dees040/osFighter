-- phpMyAdmin SQL Dump
-- version 4.0.10.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 28, 2014 at 02:34 PM
-- Server version: 5.5.38-MariaDB-cll-lve
-- PHP Version: 5.5.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `deb65120n6_os`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_guests`
--

CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `page` varchar(50) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banned_ip`
--

CREATE TABLE IF NOT EXISTS `banned_ip` (
  `ip` varchar(50) NOT NULL,
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banned_users`
--

CREATE TABLE IF NOT EXISTS `banned_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `config_name` varchar(20) NOT NULL,
  `config_value` varchar(255) NOT NULL,
  UNIQUE KEY `config_name_2` (`config_name`),
  KEY `config_name` (`config_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`config_name`, `config_value`) VALUES
('ACCOUNT_ACTIVATION', '2'),
('TRACK_VISITORS', '1'),
('max_user_chars', '30'),
('min_user_chars', '4'),
('max_pass_chars', '100'),
('min_pass_chars', '4'),
('EMAIL_FROM_NAME', 'osFighter'),
('EMAIL_FROM_ADDR', 'deesoomens@gmail.com'),
('EMAIL_WELCOME', '0'),
('SITE_NAME', 'osFighters'),
('SITE_DESC', 'none'),
('WEB_ROOT', 'http://www.ultimate-survival.net/'),
('ENABLE_CAPTCHA', '0'),
('COOKIE_EXPIRE', '100'),
('COOKIE_PATH', '/'),
('home_page', 'home'),
('ALL_LOWERCASE', '0'),
('Version', '0.1'),
('USER_TIMEOUT', '30'),
('GUEST_TIMEOUT', '5'),
('ACTIVE_THEME', 'FrenzoTheme'),
('RANKS', 'a:10:{i:0;s:6:"Newbie";i:1;s:10:"Office boy";i:2;s:10:"Pickpocket";i:3;s:10:"Shoplifter";i:4;s:5:"Thief";i:5;s:7:"Mobster";i:6;s:8:"Assassin";i:7;s:12:"Local leader";i:8;s:4:"Boss";i:9;s:9:"Godfather";}'),
('CITIES', 'a:8:{i:0;s:9:"Eindhoven";i:1;s:9:"Amsterdam";i:2;s:6:"London";i:3;s:8:"New York";i:4;s:5:"Paris";i:5;s:6:"Berlin";i:6;s:5:"Milan";i:7;s:6:"Madrid";}'),
('CURRENCY', '&#36;'),
('NUMBER_FORMAT', '2'),
('FLY_TICKET_COST', '5000'),
('PAYPAL_CLIENT_ID', ''),
('PAYPAL_SECRET_ID', '');

-- --------------------------------------------------------

--
-- Table structure for table `crimes`
--

CREATE TABLE IF NOT EXISTS `crimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `min_payout` int(11) NOT NULL,
  `max_payout` int(11) NOT NULL,
  `change` int(3) NOT NULL,
  `icon` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `crimes`
--

INSERT INTO `crimes` (`id`, `name`, `min_payout`, `max_payout`, `change`, `icon`) VALUES
(1, 'Steal from child', 10, 100, 10, 'steal_candy.jpg'),
(2, 'Steal bycile', 50, 150, 25, 'steal_bycile.jpg'),
(3, 'Pickpocket', 150, 300, 40, 'zakkenrollen.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `families`
--

CREATE TABLE IF NOT EXISTS `families` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `cash` int(11) NOT NULL,
  `bank` int(11) NOT NULL,
  `power` int(20) NOT NULL,
  `bullits` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `max_members` int(11) NOT NULL DEFAULT '10',
  `info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`id`, `name`, `cash`, `bank`, `power`, `bullits`, `creator`, `max_members`, `info`) VALUES
(1, 'Staff', 10, 1000, 100, 0, 1, 10, '');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'moderator'),
(3, 'members');

-- --------------------------------------------------------

--
-- Table structure for table `items_call_credits`
--

CREATE TABLE IF NOT EXISTS `items_call_credits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `give` int(11) NOT NULL,
  `item` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `items_call_credits`
--

INSERT INTO `items_call_credits` (`id`, `name`, `price`, `give`, `item`) VALUES
(1, '1000 Power', 1, 1000, 'power'),
(2, '15000 Power', 10, 15000, 'power');

-- --------------------------------------------------------

--
-- Table structure for table `items_house`
--

CREATE TABLE IF NOT EXISTS `items_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `items_house`
--

INSERT INTO `items_house` (`id`, `name`, `price`, `image`) VALUES
(0, 'Homeless', 0, 'none.jpg'),
(1, 'Caravan', 10000, 'caravan.jpg'),
(2, 'Wooden house', 30000, 'wooden_house.jpg'),
(3, 'Luxury mountain villa', 50000, 'luxury_mountain_villa.jpg'),
(4, 'Villa', 90000, 'villa.jpg'),
(5, 'Forest house', 100000, 'forest_house.jpg'),
(6, 'Luxury bunker', 150000, 'luxury_bunker.jpg'),
(7, 'Luxury beach villa', 200000, 'luxury_beach_villa.jpg'),
(8, 'Gangster paradise', 250000, 'gangset_paradise.jpg'),
(9, 'Japanese villa', 300000, 'japanese_villa.jpg'),
(10, 'Mediterranean villa', 400000, 'mediterranean_villa.jpg'),
(11, 'Pool paradise', 500000, 'pool_paradise.jpg'),
(12, 'Estate', 700000, 'estate.jpg'),
(13, 'Tropic villa', 900000, 'tropic_villa.jpg'),
(14, 'Fantasy Home', 1100000, 'fantasy_home.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `items_shop`
--

CREATE TABLE IF NOT EXISTS `items_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `power` int(11) NOT NULL,
  `max_amount` int(11) NOT NULL DEFAULT '0',
  `image` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `items_shop`
--

INSERT INTO `items_shop` (`id`, `name`, `description`, `price`, `power`, `max_amount`, `image`) VALUES
(1, 'Bat', 'none', 500, 25, 0, 'knuppel.gif'),
(2, 'Pepperspray', 'none', 950, 40, 0, 'pepperspray.gif'),
(3, 'Desert Eagle', 'none', 1000, 45, 0, 'deserteagle.gif'),
(4, 'Sig P228', 'none', 1400, 55, 0, 'sigp228.gif'),
(5, 'Attack Coin', 'Get 5 attack coins to attack people.', 1500, 0, 0, 'attackcoins.gif'),
(6, 'C4 Bomb', 'none', 3000, 75, 0, 'c4.gif'),
(7, 'Machine Gun', 'none', 5000, 150, 0, 'm16.gif'),
(8, 'Corner Shot', 'none', 5000, 150, 0, 'cornershot.gif'),
(9, 'Switch Blade', 'none', 9500, 180, 0, 'switchblade.gif'),
(10, 'Pitbull', 'none', 12500, 250, 0, 'pitbull.gif'),
(11, 'Sniper', 'none', 20000, 450, 0, 'sniper.gif'),
(12, 'S.W.A.T. Gun', 'none', 37500, 625, 0, 'swatgun.gif'),
(13, 'RPG', 'none', 50000, 950, 0, 'rpg.gif'),
(14, 'Bodyguard', 'none', 50000, 950, 0, 'bodyguards.gif'),
(15, 'War Boat', 'none', 150000, 10000, 0, 'warboot.gif'),
(16, 'Nuke', 'none', 200000, 15000, 0, 'nuke.gif'),
(17, 'Tank', 'none', 240000, 15500, 0, 'tank.gif'),
(18, 'Scud Rocket', 'none', 450000, 25000, 0, 'scudraket.gif');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `menu` varchar(50) NOT NULL,
  `link` varchar(50) NOT NULL,
  `weight` int(11) NOT NULL,
  `display` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `pid`, `menu`, `link`, `weight`, `display`) VALUES
(1, 1, 'personal', 'home', 0, 1),
(2, 2, 'outgame', 'register', 2, 1),
(3, 3, 'outgame', 'forgot-pass', 3, 1),
(4, 4, 'outgame', 'login', 4, 1),
(5, 5, 'admin', 'admin/files', 0, 1),
(6, 8, 'admin', 'admin/menu', 1, 1),
(7, 9, 'admin', 'admin/crimes', 4, 1),
(8, 10, 'admin', 'admin/settings', 2, 1),
(9, 11, 'admin', 'admin/users', 3, 1),
(10, 12, 'crime', 'crime/crimes', 0, 1),
(11, 13, 'family', 'family', 0, 1),
(12, 14, 'statistics', 'online', 0, 1),
(13, 15, 'personal', 'personal/user-edit', 2, 1),
(14, 16, 'personal', 'personal/user-info', 1, 0),
(15, 17, 'locations', 'locations/airport', 3, 1),
(16, 18, 'personal', 'personal/messages', 0, 1),
(17, 19, 'call-credits', 'call-credits', 0, 1),
(18, 20, 'call-credits', 'call-credits/shop', 0, 1),
(19, 21, 'locations', 'locations/shop', 2, 1),
(20, 22, 'locations', 'locations/jail', 1, 1),
(21, 23, 'locations', 'locations/bank', 0, 1),
(22, 24, 'locations', 'locations/housing-market', 5, 1),
(23, 25, 'locations', 'locations/hospital', 4, 1),
(24, 26, 'extra', 'extra/attack', 0, 1),
(25, 27, 'admin', 'admin/modules', 0, 0),
(26, 28, 'extra', 'extra/shoutbox', 0, 1),
(27, 29, 'extra', 'extra/forum', 0, 1),
(28, 30, 'casino', 'casino/crack-the-vault', 0, 1),
(29, 31, 'family', 'family/profile', 0, 0),
(30, 32, 'personal', 'personal/message', 0, 0),
(31, 33, 'call-credits', 'pay/failed', 0, 0),
(32, 34, 'call-credits', 'pay/success', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `from_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `file` varchar(50) NOT NULL,
  `groups` varchar(255) NOT NULL,
  `jail` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `link`, `file`, `groups`, `jail`) VALUES
(1, 'Home', 'home', 'home.php', 'a:0:{}', 0),
(2, 'Register', 'register', 'register.php', 'a:0:{}', 0),
(3, 'Forgot password', 'forgot-pass', 'forgotpass.php', 'a:0:{}', 0),
(4, 'Login', 'login', 'main.php', 'a:0:{}', 1),
(5, 'Files', 'admin/files', 'files.php', 'a:1:{i:0;s:1:"1";}', 0),
(8, 'Menus', 'admin/menu', 'menus.php', 'a:1:{i:0;s:1:"1";}', 0),
(9, 'Manage crimes', 'admin/crimes', 'manage_crimes.php', 'a:1:{i:0;s:1:"1";}', 0),
(10, 'Settings', 'admin/settings', 'settings.php', 'a:1:{i:0;s:1:"1";}', 0),
(11, 'Users', 'admin/users', 'users.php', 'a:1:{i:0;s:1:"1";}', 0),
(12, 'Crimes', 'crime/crimes', 'crimes.php', 'a:0:{}', 1),
(13, 'Families', 'family', 'families.php', 'a:0:{}', 0),
(14, 'Online', 'online', 'online.php', 'a:0:{}', 0),
(15, 'User edit', 'personal/user-edit', 'useredit.php', 'a:0:{}', 0),
(16, 'User info', 'personal/user-info', 'userinfo.php', 'a:0:{}', 0),
(17, 'Airport', 'locations/airport', 'airport.php', 'a:0:{}', 1),
(18, 'Messages', 'personal/messages', 'messages.php', 'a:0:{}', 0),
(19, 'Call Credits Market', 'call-credits', 'call-credits.php', 'a:0:{}', 0),
(20, 'Call Credits Shop', 'call-credits/shop', 'call-credits-shop.php', 'a:0:{}', 0),
(21, 'Shop', 'locations/shop', 'shop.php', 'a:0:{}', 1),
(22, 'Jail', 'locations/jail', 'jail.php', 'a:0:{}', 0),
(23, 'Bank', 'locations/bank', 'bank.php', 'a:0:{}', 1),
(24, 'Housing market', 'locations/housing-market', 'housing-market.php', 'a:0:{}', 1),
(25, 'Hospital', 'locations/hospital', 'hospital.php', 'a:0:{}', 1),
(26, 'Attack', 'extra/attack', 'attack.php', 'a:0:{}', 1),
(27, 'Modules', 'admin/modules', 'modules.php', 'a:1:{i:0;s:1:"1";}', 0),
(28, 'Shoutbox', 'extra/shoutbox', 'shoutbox.php', 'a:0:{}', 0),
(29, 'Forum', 'extra/forum', 'forum.php', 'a:0:{}', 0),
(30, 'Crack the vault', 'casino/crack-the-vault', 'crack-the-vault.php', 'a:0:{}', 1),
(31, 'Family profile', 'family/profile', 'family-profile.php', 'a:0:{}', 1),
(32, 'Message', 'personal/message', 'message_load.php', 'a:3:{i:0;s:1:"1";i:1;s:1:"3";i:2;s:1:"2";}', 0),
(33, 'Pay Failed', 'pay/failed', 'failed.php', 'a:0:{}', 0),
(34, 'Pay Success', 'pay/success', 'success.php', 'a:0:{}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `payment_id` varchar(250) NOT NULL,
  `hash` varchar(100) NOT NULL,
  `complete` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `usersalt` varchar(8) NOT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  `actkey` varchar(35) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `regdate` int(11) unsigned NOT NULL,
  `groups` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_info`
--

CREATE TABLE IF NOT EXISTS `users_info` (
  `uid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `rank` int(2) NOT NULL,
  `rank_process` int(3) NOT NULL,
  `health` int(3) NOT NULL DEFAULT '100',
  `city` int(3) NOT NULL,
  `money` int(20) NOT NULL DEFAULT '100',
  `bank` int(20) NOT NULL DEFAULT '500',
  `power` int(15) NOT NULL,
  `crime_process` int(5) NOT NULL,
  `credits` int(11) NOT NULL,
  `house` int(11) NOT NULL,
  UNIQUE KEY `id` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users_items`
--

CREATE TABLE IF NOT EXISTS `users_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


-- --------------------------------------------------------

--
-- Table structure for table `users_time`
--

CREATE TABLE IF NOT EXISTS `users_time` (
  `uid` int(11) NOT NULL,
  `jail` int(11) NOT NULL,
  `crime_time` int(11) NOT NULL,
  `fly_time` int(11) NOT NULL,
  UNIQUE KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
