<?php

$this->startSetup()->run("

DROP TABLE IF EXISTS {$this->getTable('forumstore')};
CREATE TABLE {$this->getTable('forumstore')} (
  `forumstore_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `forum_id` int(11) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  INDEX(`forum_id`),
  FOREIGN KEY (`forum_id`) REFERENCES {$this->getTable('forum')} (`forum_id`),
  INDEX(`store_id`),
  FOREIGN KEY (`store_id`) REFERENCES {$this->getTable('core/store')} (`store_id`),
  PRIMARY KEY (`forumstore_id`)
  );
 ");
 

$this->endSetup();
