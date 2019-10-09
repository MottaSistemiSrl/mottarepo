<?php
class Application_Model_VideosMapper {
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
			$this->setDbTable('Application_Model_DbTable_Videos');
		}
		return $this->_dbTable;
	}

	public function save(Application_Model_Videos $video)
	{
		$data = array(
				'video_id'   => $video->getVideoId(),
				'product_id'   => $video->getProductId(),
				'video_with_slides'   => $video->getVideoWithSlides(),
				'video_path'   => $video->getVideoPath(),
				'video_length'   => $video->getVideoLength(),
				'video_size'   => $video->getVideoSize(),
				'video_creation_date'   => date('Y-m-d H:i:s'),
				'video_lastupdate'   => date("Y-m-d H:i:s")		
		);
		$id = $video->getVideoId();
		if ($id === null) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('video_id = ?' => $id));
		}
	}
	
	public function delete($where)
	{
		$this->getDbTable()->delete($where);
	}
	
	private function populateVideo($row, Application_Model_Videos $video) {
		$video->setVideoId($row->video_id);
		$video->setProductId($row->product_id);
		$video->setVideoWithSlides($row->video_with_slides);
		$video->setVideoPath($row->video_path);
		$video->setVideoLength($row->video_length);
		$video->setVideoSize($row->video_size);
		$video->setVideoCreationDate($row->video_creation_date);
		$video->setVideoLastupdate($row->video_lastupdate);
	}
	
	public function findByProductId($product_id, Application_Model_Videos $video)
	{
		$where = "product_id = " . $product_id;
		$row = $this->getDbTable()->fetchRow($where, null);
		
		if ($row !== null) {
			$this->populateVideo($row, $video);
		}
	}

	public function find($id, Application_Model_Videos $video)
	{		
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}
				
		$row = $result->current();
		$this->populateVideo($row, $video);
	}

	public function fetchAll()
	{
		$resultSet = $this->getDbTable()->fetchAll();
		$entries   = array();
		foreach ($resultSet as $row) {
			$entry = new Application_Model_Videos();
			$entry->setVideoId($row->video_id);
			$entry->setProductId($row->product_id);
			$entry->setVideoWithSlides($row->video_with_slides);
			$entry->setVideoPath($row->video_path);
			$entry->setVideoLength($row->video_length);
			$entry->setVideoSize($row->video_size);
			$entry->setVideoCreationDate($row->video_creation_date);
			$entry->setVideoLastupdate($row->video_lastupdate);
						
			$entries[] = $entry;
		}
		return $entries;
	}
}