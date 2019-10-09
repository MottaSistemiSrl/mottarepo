<?php

$this->startSetup()->run("

    DROP TABLE IF EXISTS {$this->getTable('watchlistsingle')};
    CREATE TABLE {$this->getTable('watchlistsingle')} (
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

 ");
 

$this->endSetup();
