SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_category` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci NOT NULL,
  `parent_id` varchar(255) collate utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_coupon_element` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `x` int(11) NOT NULL,
  `y` int(11) NOT NULL,
  `font_size` int(2) NOT NULL,
  `width` int(2) NOT NULL,
  `height` int(2) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

INSERT INTO `#__enmasse_coupon_element` (`id`, `name`, `x`, `y`, `font_size`, `width`, `height`, `published`) VALUES
(1, 'dealName', 15, 140, 20, 600, 65, 1),
(2, 'serial', 425, 65, 12, 200, 50, 1),
(3, 'merchantName', 335, 245, 14, 280, 50, 1),
(4, 'highlight', 15, 325, 10, 280, 50, 1),
(5, 'personName', 15, 245, 14, 280, 50, 1),
(6, 'term', 335, 325, 10, 280, 50, 1);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_deal` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `slug_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `short_desc` varchar(500) collate utf8_unicode_ci NOT NULL,
  `highlight` text collate utf8_unicode_ci NOT NULL,
  `pic_dir` varchar(550) collate utf8_unicode_ci NOT NULL,
  `terms` text collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci,
  `origin_price` decimal(10,2) default NULL,
  `price` decimal(10,2) default NULL,
  `min_needed_qty` int(11) NOT NULL,
  `max_buy_qty` int(11) NOT NULL,
  `max_coupon_qty` int(11) NOT NULL default '-1',
  `max_qty` int(11) NOT NULL,
  `cur_sold_qty` int(11) NOT NULL,
  `start_at` datetime default NULL,
  `end_at` datetime default NULL,
  `merchant_id` bigint(20) default NULL,
  `sales_person_id` bigint(20) default NULL,
  `status` varchar(20) collate utf8_unicode_ci NOT NULL default 'On Sales',
  `published` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug_name` (`slug_name`),
  KEY `merchant_id_idx` (`merchant_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_deal_category` (
  `id` int(20) NOT NULL auto_increment,
  `deal_id` int(20) NOT NULL,
  `category_id` int(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_deal_location` (
  `id` int(20) NOT NULL auto_increment,
  `deal_id` int(20) NOT NULL,
  `location_id` int(20) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_delivery_gty` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `class_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

INSERT INTO `#__enmasse_delivery_gty` (`id`, `name`, `class_name`, `created_at`, `updated_at`) VALUES
(1, 'Email', 'email', '2010-10-25 12:00:00', '2010-10-25 12:00:00');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_email_template` (
  `id` bigint(20) NOT NULL auto_increment,
  `slug_name` varchar(225) collate utf8_unicode_ci NOT NULL,
  `avail_attribute` varchar(225) collate utf8_unicode_ci NOT NULL,
  `subject` varchar(225) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug_name` (`slug_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

INSERT INTO `#__enmasse_email_template` (`id`, `slug_name`, `avail_attribute`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(1, 'receipt', '$buyerName, $buyerEmail, $deliveryName, $deliveryEmail, $orderId, $dealName, $price, $createdAt', 'You have made an Order', '<p>Hi $buyerName,</p>\r\n<p>You have made an Order at EnMasse with following detail:</p>\r\n<table border="0">\r\n<tr><td><b>Order:</b><td><td>$orderId</td></tr>\r\n<tr><td><b>Deal:</b><td><td>$dealName</td></tr>\r\n<tr><td><b>Total Qty:</b><td><td>$totalQty</td></tr>\r\n<tr><td><b>Total Price:</b><td><td>$totalPrice</td></tr>\r\n<tr><td><b>Purchase Date:</b><td><td>$createdAt</td></tr>\r\n<tr><td><b>Delivery:</b><td><td>$deliveryName ($deliveryEmail)</td></tr>\r\n</table>', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'confirm_deal_buyer', '$orderId, $dealName, $buyerName, $deliveryName, $deliveryEmail', 'Deal $dealName has been confirmed.', '<p>Hi $buyerName,</p>\r\n<p>Your deal $dealName you ordered has been confirmed.</p>\r\n<p>The coupon will be delivered to $deliveryName ($deliveryEmail)</p>\r\nOrder Id: $orderId', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'confirm_deal_receiver', '$orderId, $dealName, $buyerName, $deliveryName, $deliveryMsg, $linkToCoupon', 'Receive your coupon !!!', '<p>Hi $deliveryName,</p>\r\n<p>\r\n$buyerName has bought you a set of coupon for <a href="$linkToCoupon" target="_blank">$dealName</a></p>\r\n<p>$deliveryMsg</p>\r\n<br/>\r\n<font size=''1''>Please go to <a href="$linkToCoupon" target="_blank">$linkToCoupon</a> if the hyperlink has being blocked.</font>\r\n', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'void_deal', '$buyerName, $orderId, $dealName, $refundAmt', 'Deal $dealName has been canceled', '<p>Hi $buyerName,</p>\r\n<p>The Order($orderId) for deal $dealName has been cancel.</p>\r\n<p>$refundAmt will be refunded to you.</p>', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'void_deal_with_point', '$buyerName, $orderId, $dealName, $refundAmt, $refundPoint', 'Deal $dealName has been canceled', '<p>Hi $buyerName,</p>\r\n<p>The Order($orderId) for deal $dealName has been cancel.</p>\r\n<p>$refundAmt cash and $refundPoint point(s) will be refunded to you.</p>\r\n<p>However you can get all the refund in point by going to My Orders page and choose the amount of point you want to get back.</p>', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_invty` (
  `id` bigint(20) NOT NULL auto_increment,
  `order_item_id` bigint(20) NOT NULL,
  `pdt_id` int(11) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `deallocated_at` datetime NOT NULL,
  `status` varchar(30) collate utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_location` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci NOT NULL,
  `parent_id` varchar(255) collate utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_merchant` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `telephone` varchar(20) collate utf8_unicode_ci NOT NULL,
  `fax` varchar(20) collate utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `sales_person_id` bigint(20) default NULL,
  `web_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `address` varchar(255) collate utf8_unicode_ci NOT NULL,
  `postal_code` varchar(20) collate utf8_unicode_ci NOT NULL,
  `logo_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `location_id` int(11) NOT NULL,
  `google_map_lat` float NOT NULL,
  `google_map_long` float NOT NULL,
  `google_map_width` float NOT NULL,
  `google_map_height` float NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_order` (
  `id` bigint(20) NOT NULL auto_increment,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `total_buyer_paid` decimal(10,2) default NULL,
  `point_used_to_pay` int(11) NOT NULL default '0',
  `status` varchar(255) collate utf8_unicode_ci default NULL,
  `refunded_amount` int(11) NOT NULL,
  `session_id` varchar(255) collate utf8_unicode_ci default NULL,
  `buyer_id` bigint(20) default NULL,
  `buyer_detail` longtext collate utf8_unicode_ci,
  `referral_id` bigint(20) NOT NULL,
  `pay_gty_id` bigint(20) default NULL,
  `pay_detail` longtext collate utf8_unicode_ci,
  `delivery_gty_id` bigint(20) default NULL,
  `delivery_detail` longtext collate utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `buyer_id_idx` (`buyer_id`),
  KEY `pay_gty_id_idx` (`pay_gty_id`),
  KEY `delivery_gty_id_idx` (`delivery_gty_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_order_item` (
  `id` bigint(20) NOT NULL auto_increment,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `signature` varchar(255) collate utf8_unicode_ci default NULL,
  `unit_price` decimal(10,2) default NULL,
  `qty` bigint(20) default NULL,
  `total_price` decimal(10,2) default NULL,
  `pdt_id` bigint(20) default NULL,
  `pdt_promo_id` bigint(20) default NULL,
  `order_id` bigint(20) default NULL,
  `status` varchar(255) collate utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pdt_id_idx` (`pdt_id`),
  KEY `pdt_promo_id_idx` (`pdt_promo_id`),
  KEY `order_id_idx` (`order_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_pay_gty` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` longtext collate utf8_unicode_ci NOT NULL,
  `class_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `attributes` longtext collate utf8_unicode_ci NOT NULL,
  `attribute_config` longtext collate utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

INSERT INTO `#__enmasse_pay_gty` (`id`, `name`, `description`, `class_name`, `attributes`, `attribute_config`, `published`, `created_at`, `updated_at`) VALUES
(1, 'Cash / Bank Transfer', '', 'cash', 'instruction', '{"instruction":"<p>Dear customers,<\\/p>\\r\\n<p>Cash\\/Bank Transfer payment is only convenient for customers living in  Singapore. For overseas payment, we would like to encourage users to  pay through\\u00a0Credit\\/Debit card or\\u00a0PayPal option.<\\/p>\\r\\n<p>For payment through Cash\\/Bank Transfer, please kindly follow these steps:<\\/p>\\r\\n<ol>\\r\\n<li>Go to your nearest ATM or online iBanking and transfer the payment to account: 123-234456-7<\\/li>\\r\\n<li>Print screen your transfer page if you are using iBanking, or get a receipt from the machine if you transfer through ATM<\\/li>\\r\\n<li>Email us the image of the receipt\\/print screen and kindly state the reference no.<\\/li>\\r\\n<li>We will mark your order as paid as soon when we receive your email.<\\/li>\\r\\n<li>Payment is to be done within 7 days from the date of purchase or else your order will be cancelled automatically. <\\/li>\\r\\n<\\/ol>\\r\\n<p>Thank you!<\\/p>"}', 1, '0000-00-00 00:00:00', '2011-05-30 06:38:27'),
(2, 'Credit Card / Debit Card / Paypal', '<p><img src="./components/com_enmasse/images/paypal_logo.png"/><p><br/><br/><p>Matamko''s merchants integrate PayPal as their payment option to increase their sales, expand globally, attract more buyers, and keep their business secure. PayPal is a global leader in online payments with a total payment volume of US$71 billion in 2009 - approximately 15% of global ecommerce and 16.5% of US ecommerce.<br/><a href="https://www.paypal.com/sg/mrb/pal=HUASLHP6T2UVU&mrb=R-6BG16433XS203062L" target="_blank">Click Here</a> to Register Your PayPal Merchant Account to start your social buying site today!</p>\r\n', 'paypal', 'merchant_email,api_username,signature,country_code,currency_code', '{"merchant_email":"account@matamko.com","api_username":"account_api1.matamko.com","signature":"AHTJGXIeu6pqPQTY4IgtDMcydaNXABXByLeQ.ZUvtfSMkGyt4.jLJyZ-","country_code":"SG","currency_code":"SGD"}', 1, '0000-00-00 00:00:00', '2011-06-16 07:28:28'),
(3, 'Point payment', '<p>This payment is only used when users buy a deal by all points (integration with point systems link AlphaUserPoints).</p>', 'point', 'instruction', '{"instruction":"<p>Dear customers,<\\/p>\\r\\n<p>You have just did a payment with points, no cash is paid. In future if this order is refunded, points will be given back to you automatically.<\\/p>\\r\\n<p>Thank you!<\\/p>"}', 1, '0000-00-00 00:00:00', '2011-06-23 03:02:03');


-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_sales_person` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `user_name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `address` varchar(255) collate utf8_unicode_ci NOT NULL,
  `phone` varchar(20) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_setting` (
  `company_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `id` bigint(20) NOT NULL auto_increment,
  `address1` varchar(255) collate utf8_unicode_ci NOT NULL,
  `address2` varchar(255) collate utf8_unicode_ci NOT NULL,
  `city` varchar(255) collate utf8_unicode_ci NOT NULL,
  `state` varchar(255) collate utf8_unicode_ci NOT NULL,
  `country` varchar(255) collate utf8_unicode_ci NOT NULL,
  `postal_code` varchar(30) collate utf8_unicode_ci NOT NULL,
  `tax` varchar(20) collate utf8_unicode_ci NOT NULL,
  `tax_number1` varchar(30) collate utf8_unicode_ci NOT NULL,
  `tax_number2` varchar(30) collate utf8_unicode_ci NOT NULL,
  `logo_url` varchar(255) collate utf8_unicode_ci NOT NULL,
  `contact_number` varchar(30) collate utf8_unicode_ci NOT NULL,
  `contact_fax` varchar(30) collate utf8_unicode_ci NOT NULL,
  `customer_support_email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `default_currency` varchar(5) collate utf8_unicode_ci NOT NULL,
  `currency_prefix` varchar(5) collate utf8_unicode_ci NOT NULL,
  `currency_postfix` varchar(5) collate utf8_unicode_ci NOT NULL,
  `currency_decimal` tinyint(2) NOT NULL,
  `currency_separator` varchar(1) collate utf8_unicode_ci NOT NULL,
  `currency_decimal_separator` varchar(1) collate utf8_unicode_ci NOT NULL,
  `image_height` int(5) NOT NULL,
  `image_width` int(5) NOT NULL,
  `article_id` int(5) NOT NULL,
  `subscription_class` varchar(200) collate utf8_unicode_ci NOT NULL default '0',
  `theme` varchar(50) collate utf8_unicode_ci NOT NULL,
  `coupon_bg_url` varchar(225) collate utf8_unicode_ci NOT NULL,
  `minute_release_invty` int(2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `point_system_class` varchar(200) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `logo_url` (`logo_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;


INSERT INTO `#__enmasse_setting` (`company_name`, `id`, `address1`, `address2`, `city`, `state`, `country`, `postal_code`, `tax`, `tax_number1`, `tax_number2`, `logo_url`, `contact_number`, `contact_fax`, `customer_support_email`, `default_currency`, `currency_prefix`, `currency_postfix`, `currency_decimal`, `currency_separator`, `currency_decimal_separator`, `image_height`, `image_width`, `article_id`, `subscription_class`, `theme`, `coupon_bg_url`, `minute_release_invty`, `created_at`, `updated_at`, `point_system_class`) VALUES
('Your company name', 1, 'Your company''s address', '', 'Singapore', 'Singapore', 'SG', '12345', '', '', '', '', '', '', 'support@yourcompany.com', 'USD', '$', '', 2, ',', ',', 252, 400, 1, 'emlocation', 'apollo_blue', 'a%3A1%3A%7Bi%3A0%3Bs%3A51%3A%22components%5Ccom_enmasse%5Cupload%5C18040samplecoupon.png%22%3B%7D', 10, '0000-00-00 00:00:00', '2011-06-16 06:59:59', 'no');

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `#__enmasse_tax` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `tax_rate` double NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;