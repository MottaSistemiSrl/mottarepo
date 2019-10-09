<?php
require_once ('DbTable/SlidesTimes.php');
require_once ('SlidesTimes.php');

class Application_Model_SlidesTimesMapper {
	protected $_dbTable;

	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Application_Model_DbTable_Slides_Times');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_Slides_Times $slideTime)
	{			
		$data = array(
				'slide_time_id'   => $slideTime->getSlideTimeId(),
				'slide_id' => $slideTime->getSlideId(),
				'slide_opening_time' => $slideTime->getSlideOpeningTime(),
				'slide_time_creation_date' => $slideTime->getSlideTimeCreationDate()
		);
		$id = $slideTime->getSlideTimeId();		
		if ($id === null) {
			unset($data['slide_time_id']);
			$id = $this->getDbTable()->insert($data);					
		} else {
			$this->getDbTable()->update($data, array('slide_time_id = ?' => $id));
		}
				
		return $id;
	}

	public function find($id, Application_Model_Slides_Times $slideTime)
	{		
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$slideTime->setSlideTimeId($row->slide_time_id);
		$slideTime->setSlideId($row->slide_id);
		$slideTime->setSlideOpeningTime($row->slide_opening_time);
		$slideTime->setSlideTimeCreationDate($row->slide_time_creation_date);			
	}
	
	public function delete($where)
	{
		$this->getDbTable()->delete($where);
	}
	
	private function populateSlidesTimes($resultSet) {
		$entries = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Slides_Times();
			$entry->setSlideTimeId($row->slide_time_id);
			$entry->setSlideId($row->slide_id);
			$entry->setSlideOpeningTime($row->slide_opening_time);
			$entry->setSlideTimeCreationDate($row->slide_time_creation_date);
			$entry->setSlidePath($row->slide_path);
			$entry->setSlideNumber($row->slide_number);
			$entry->setVideoId($row->video_id);
			$entries[] = $entry;
		}
		
		return $entries;
	}

	public function fetchAll() {
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = $this->populateSlidesTimes($resultSet);
		return $entries;
	}
	
	public function fetchAllByVideoId($video_id) {
		$where = "sl_mediaserver_slides.video_id = " . $video_id;
		$select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
		->setIntegrityCheck(false)
		->joinLeft('sl_mediaserver_slides', 'sl_mediaserver_slides.slide_id = sl_mediaserver_slides_times.slide_id')
		->where($where)
		->order('sl_mediaserver_slides_times.slide_opening_time ASC');
		
		$resultSet = $this->getDbTable()->fetchAll($select);
		$entries   = $this->populateSlidesTimes($resultSet);
		return $entries;
	}
	
	public function fetchByVideoIdAndSeconds($video_id, $seconds) {
		$where = "sl_mediaserver_slides.video_id = " . $video_id . " AND sl_mediaserver_slide_opening_time < " . round($seconds, 0);
		$select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
		->setIntegrityCheck(false)
		->joinLeft('sl_mediaserver_slides', 'sl_mediaserver_slides.slide_id = sl_mediaserver_slides_times.slide_id')
		->where($where)
		->order('sl_mediaserver_slides_times.slide_opening_time DESC');
	
		$row = $this->getDbTable()->fetchRow($select);
		if ($row !== null) {
			 return $row->toArray();
		} else {
			return array();
		}
		
		return $row;
	}
	
	
	public function fetchAllBySlideId($slide_id) {		
		$where = "sl_mediaserver_slides.slide_id = " . $slide_id;
		$select = $this->getDbTable()->select(Zend_Db_Table::SELECT_WITH_FROM_PART)
		->setIntegrityCheck(false)
		->joinLeft('sl_mediaserver_slides', 'sl_mediaserver_slides.slide_id = sl_mediaserver_slides_times.slide_id')
		->where($where)
		->order('sl_mediaserver_slides_times.slide_opening_time ASC');
		
		$resultSet = $this->getDbTable()->fetchAll($select);
		$entries   = $this->populateSlidesTimes($resultSet);
		return $entries;
	}
}