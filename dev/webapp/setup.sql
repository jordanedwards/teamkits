
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_first_name` varchar(50) NOT NULL DEFAULT '',
  `user_last_name` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_tel` varchar(20) NOT NULL DEFAULT '0',
  `user_password` varchar(100) NOT NULL DEFAULT '',
  `user_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_role` smallint(11) NOT NULL DEFAULT '0',
  `user_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_updated_user` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


INSERT INTO `user` (`user_id`, `user_first_name`, `user_last_name`, `user_email`, `user_tel`, `user_password`, `user_login`, `user_role`, `user_date_created`, `user_last_updated`, `user_last_updated_user`) VALUES
(1, 'Quickstart', 'User','jordan@orchardcity.ca','0', '3eadac27e1ccff186ad15f836bab3c1dcac6405ecc7dd347258fe0d17b615c64', '2015-05-09 19:13:12', 1, '0000-00-00 00:00:00', '2014-12-14 22:27:54', '0');


CREATE TABLE IF NOT EXISTS `log` (
  `log_id` int(8) NOT NULL AUTO_INCREMENT,
  `log_user` int(3) NOT NULL,
  `log_val` longtext NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;


CREATE TABLE IF NOT EXISTS `aclpagerecords` (
  `aclPageRecords_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `aclPageRecords_user_role` int(10) NOT NULL,
  `aclPageRecords_page_id` varchar(200) NOT NULL,
  `aclPageRecords_page_view` int(3) NOT NULL DEFAULT '0',
  `aclPageRecords_page_edit` int(3) NOT NULL DEFAULT '0',
  `aclPageRecords_page_delete` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`aclPageRecords_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `settings_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(200) NOT NULL,
  `settings_value` text NOT NULL,
  `settings_session_load` int(1) NOT NULL,
  `settings_date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings_last_updated_user` varchar(200) NOT NULL DEFAULT '0',
  PRIMARY KEY (`settings_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

INSERT INTO `settings` (`settings_id`, `settings_name`, `settings_value`, `settings_session_load`, `settings_date_created`, `settings_last_updated`, `settings_last_updated_user`) VALUES
(1, 'logo', 'website-logo.png', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0');
(2, 'default_tax_rate', '5', 1, '2014-12-30 15:33:20', '2015-02-26 18:46:33', ''),
(3, 'mail_server', 'chocobo.asmallorange.com', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(4, 'mail_account_type', 'POP3', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(5, 'mail_username', 'website@myproject.ca', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
(6, 'mail_password', '', 0, '0000-00-00 00:00:00', '2015-02-26 18:46:33', '0'),
