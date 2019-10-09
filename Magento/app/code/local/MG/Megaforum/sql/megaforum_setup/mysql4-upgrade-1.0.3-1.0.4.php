<?php

// keep the old columns data for rolling back
/*$this->startSetup()->run('
	ALTER TABLE post CHANGE posted_at posted_at_old DATE
	ALTER TABLE post CHANGE update_at update_at_old DATE
');*/

// create the new columns
$this->startSetup()->run('
	ALTER TABLE post ADD posted_at_new DATETIME AFTER updated_at;
	ALTER TABLE post ADD updated_at_new DATETIME AFTER updated_at;
');

$this->endSetup();
