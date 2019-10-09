<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
DROP TABLE IF EXISTS {$this->getTable('forumuser')};
CREATE TABLE {$this->getTable('forumuser')} (
   `forumuser_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `user_id` int(10) NOT NULL,
   `user_type` varchar(100) NOT NULL,
   `image` varchar(250) NOT NULL, 
   PRIMARY KEY (`forumuser_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('forumusertype')};
CREATE TABLE {$this->getTable('forumusertype')} (
   `forumusertype_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `type` varchar(20) NOT NULL,
   PRIMARY KEY (`forumusertype_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('forum')};
CREATE TABLE {$this->getTable('forum')} (
   `forum_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `forum_name` varchar(100) NOT NULL,
   `priority` varchar(50) NOT NULL,
   `url_key` varchar(100) NOT NULL,
   `status` varchar(20) NOT NULL,
   `created_at` date NOT NULL,
   `created_by` int(10) unsigned NOT NULL,
   `meta_key` varchar(256) NULL,
   `meta_desc` varchar(1024) NULL,
   INDEX(`created_by`),
   FOREIGN KEY (`created_by`) REFERENCES {$this->getTable('admin/user')} (`user_id`),
   PRIMARY KEY (`forum_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('topic')};
CREATE TABLE {$this->getTable('topic')} (
   `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
   `forum_id` int(10) unsigned NOT NULL,
   `topic_name` varchar(100) NOT NULL,
   `created_at` date NOT NULL,
   `created_by` int(10) unsigned NOT NULL,
   `email` varchar(100) NOT NULL,
   `message` text,
   `views` int(20) NOT NULL,
   `status` varchar(20) NOT NULL,
   `meta_key` varchar(256) NULL,
   `meta_desc` varchar(1024) NULL,
   INDEX(`forum_id`),
   FOREIGN KEY (`forum_id`) REFERENCES {$this->getTable('forum')} (`forum_id`),
   INDEX(`created_by`),
   FOREIGN KEY (`created_by`) REFERENCES {$this->getTable('customer/entity')} (`entity_id`),
   PRIMARY KEY (`topic_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('post')};
CREATE TABLE {$this->getTable('post')} (
  `post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL,
  `position` int(10) NOT NULL,
  `post_text` text ,
  `posted_at` date NOT NULL,
  `updated_at` date NOT NULL,
  `posted_by` int(10) unsigned NOT NULL,
  `status` varchar(20) NOT NULL,
  INDEX(`topic_id`),
  FOREIGN KEY (`topic_id`) REFERENCES {$this->getTable('topic')} (`topic_id`),
  INDEX(`posted_by`),
  FOREIGN KEY (`posted_by`) REFERENCES {$this->getTable('customer/entity')} (`entity_id`),
  PRIMARY KEY (`post_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('watchlist')};
CREATE TABLE {$this->getTable('watchlist')} (
  `watchlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(10) unsigned NOT NULL,
  `watchlisted_by` int(10) unsigned NOT NULL,
  `created_at` date NOT NULL,
  INDEX(`topic_id`),
  FOREIGN KEY (`topic_id`) REFERENCES {$this->getTable('topic')} (`topic_id`),
  INDEX(`watchlisted_by`),
  FOREIGN KEY (`watchlisted_by`) REFERENCES {$this->getTable('forumuser')} (`forumuser_id`),
  PRIMARY KEY (`watchlist_id`)
  );
  
DROP TABLE IF EXISTS {$this->getTable('notificationtemplate')};
CREATE TABLE {$this->getTable('notificationtemplate')} (
  `notificationtemplate_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` smallint(10) NOT NULL,
  `template` text ,
  PRIMARY KEY (`notificationtemplate_id`)
) ;

DROP TABLE IF EXISTS {$this->getTable('privatemsg')};
CREATE TABLE {$this->getTable('privatemsg')} (
  `privatemsg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(256) NOT NULL,
  `message` text,
  `sent_from` varchar(100) NOT NULL,
  `sent_to` varchar(100) NOT NULL,
  `sent_date` date NOT NULL,
  PRIMARY KEY (`privatemsg_id`)
) ;

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 