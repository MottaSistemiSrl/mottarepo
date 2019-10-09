<?php

/**
 * @var Mage_Catalog_Model_Resource_Setup $installer
 */
$installer = $this;
$installer->startSetup();
$installer->run("

CREATE TABLE `sl_mediaserver_slides` (
  `slide_id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) DEFAULT NULL,
  `slide_number` int(11) NOT NULL,
  `slide_path` varchar(1000) NOT NULL,
  `slide_creation_date` datetime NOT NULL,
  PRIMARY KEY (`slide_id`),
  KEY `sl_mediaserver_video_id_ix` (`video_id`),
  KEY `sl_mediaserver_slide_number_ix` (`slide_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sl_mediaserver_slides_times` (
  `slide_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `slide_id` int(11) DEFAULT NULL,
  `slide_opening_time` int(11) NOT NULL,
  `slide_time_creation_date` datetime NOT NULL,
  PRIMARY KEY (`slide_time_id`),
  KEY `sl_mediaserver_slide_id_opening_time_idx` (`slide_id`,`slide_opening_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `sl_mediaserver_videos` (
  `video_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `video_with_slides` tinyint(1) NOT NULL DEFAULT '0',
  `video_path` varchar(1000) NOT NULL,
  `video_length` int(11) DEFAULT NULL,
  `video_size` int(11) DEFAULT NULL,
  `video_creation_date` datetime NOT NULL,
  `video_lastupdate` datetime NOT NULL,
  PRIMARY KEY (`video_id`),
  UNIQUE KEY `sl_mediaserver_product_id_ux` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");
$installer->endSetup();