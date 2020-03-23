<?php


ALTER TABLE `abandonedbookings` ADD COLUMN `is_v12` tinyint(1) DEFAULT '0';

/* `bedroomtypes` will have a new row, "Extra Bedroom" */
/* `bedtypes` will have some new values */

ALTER TABLE `bookings` ADD COLUMN `is_v12` tinyint(1) DEFAULT '0';

ALTER IGNORE TABLE `properties` ADD UNIQUE (unit_number);

ALTER TABLE `properties` ADD COLUMN `avail_run` datetime DEFAULT NULL;
ALTER TABLE `properties` ADD COLUMN `rate_run` datetime DEFAULT NULL;

/* `property_types` will have "Condos" added */

ALTER TABLE `promotions` ADD COLUMN `nthdayfree` tinyint(3) NOT NULL;
ALTER TABLE `promotions` ADD COLUMN `discountamount_daily` decimal(10,2) NOT NULL;
ALTER TABLE `promotions` ADD COLUMN `discountamount_weekly` decimal(10,2) NOT NULL;
ALTER TABLE `promotions` ADD COLUMN `discountamount_monthly` decimal(10,2) NOT NULL;

ALTER TABLE `property_images` ADD COLUMN `image_hash` varchar(100) DEFAULT NULL;

ALTER TABLE `rates` ADD COLUMN `available` tinyint(1) DEFAULT '1';
ALTER TABLE `rates` ADD COLUMN `last_updated` datetime DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `synctypes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `last_pull_start` datetime DEFAULT NULL,
  `is_running` tinyint(1) DEFAULT '0',
  `last_pull_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `synctypes` VALUES ('1', 'availability-initial', '2017-01-10 14:01:53', '1', '2017-01-10 14:01:50');
INSERT INTO `synctypes` VALUES ('3', 'availability-partial', '2017-01-24 22:15:01', '1', '2017-01-13 20:00:01');
INSERT INTO `synctypes` VALUES ('5', 'rate-initial_DELETE', '2016-12-06 15:10:23', '0', null);
INSERT INTO `synctypes` VALUES ('7', 'rate-partial', '2017-01-24 22:15:01', '0', '2017-01-24 22:15:06');
INSERT INTO `synctypes` VALUES ('9', 'properties-initial', '2016-12-15 13:59:38', '0', '2016-12-15 14:04:13');
INSERT INTO `synctypes` VALUES ('11', 'properties-partial', null, '0', null);
INSERT INTO `synctypes` VALUES ('13', 'fullsync', '2017-01-24 22:22:49', '0', '2017-01-24 22:23:13');
INSERT INTO `synctypes` VALUES ('15', 'propertyrates', '2017-01-24 22:15:01', '0', '2017-01-24 22:15:04');

CREATE TABLE IF NOT EXISTS `v12_changeloginfo` (
  `properties_id` int(11) NOT NULL DEFAULT '0',
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `changelogtype` varchar(20) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `changedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdated_cron` datetime DEFAULT NULL,
  PRIMARY KEY (`properties_id`,`changelogtype`,`changedate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `v12_nonavaildates` (
  `properties_id` int(11) NOT NULL DEFAULT '0',
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `enddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `confnum` int(11) NOT NULL DEFAULT '0',
  `staytype` char(1) NOT NULL DEFAULT '',
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `changedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdated_cron` datetime DEFAULT NULL,
  PRIMARY KEY (`properties_id`,`startdate`,`enddate`,`confnum`,`staytype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `v12_rates` (
  `properties_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `rateplan_name` varchar(50) NOT NULL DEFAULT '0',
  `startdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00', `enddate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastupdated_cron` datetime DEFAULT NULL,
  PRIMARY KEY (`properties_id`,`rateplan_name`,`startdate`,`enddate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `v12_reschangeloginfo` (
  `property_id` int(11) NOT NULL DEFAULT '0',
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime DEFAULT NULL,
  `confnum` int(11) NOT NULL DEFAULT '0',
  `statusflag` char(1) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `changedate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `logid` int(11) DEFAULT NULL,
  `lastupdated_cron` datetime DEFAULT NULL,
  PRIMARY KEY (`property_id`,`confnum`,`changedate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
