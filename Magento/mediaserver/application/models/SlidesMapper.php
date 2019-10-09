<?php
class Application_Model_SlidesMapper {
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
			$this->setDbTable('Application_Model_DbTable_Slides');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_Slides $slide)
	{
		$data = array(
				'slide_id'   => $slide->getSlideId(),
				'video_id'   => $slide->getVideoId(),
				'slide_number' => $slide->getSlideNumber(),
				'slide_path' => $slide->getSlidePath(),
				'slide_creation_date' => $slide->getSlideCreationDate(),
		);
		$id = $slide->getSlideId();		
		if ($id === null) {
			unset($data['slide_id']);
			$this->getDbTable()->insert($data);			
		} else {
			$this->getDbTable()->update($data, array('slide_id = ?' => $id));
		}
	}
	
	
	public function delete($where)
	{
		$this->getDbTable()->delete($where);
	}

	public function find($id, Application_Model_Slides $slide)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
		$row = $result->current();
		$slide->setSlideId($row->slide_id);
		$slide->setSlideCreationDate($row->slide_creation_date);
		$slide->setSlideNumber($row->slide_number);
		$slide->setSlidePath($row->slide_path);
		$slide->setVideoId($row->video_id);
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		return $this->populateSlides($resultSet);
	}
	
	public function fetchAllByVideoId($video_id) {
		$where = "video_id = " . $video_id;
		$resultSet = $this->getDbTable()->fetchAll($where, "slide_number");
		return $this->populateSlides($resultSet);
	}
	
	private function populateSlides($resultSet) {
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Slides();
			$entry->setSlideId($row->slide_id);
			$entry->setSlideCreationDate($row->slide_creation_date);
			$entry->setSlideNumber($row->slide_number);
			$entry->setSlidePath($row->slide_path);
			$entry->setVideoId($row->video_id);
			$entries[] = $entry;
		}
		return $entries;
	}
}