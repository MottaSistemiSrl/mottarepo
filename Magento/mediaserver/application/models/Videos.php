<?php
class Application_Model_Videos {
	protected $_video_id;
	protected $_product_id;
	protected $_video_with_slides;
	protected $_video_path;
	protected $_video_length;
	protected $_video_size;
	protected $_video_creation_date;	
	protected $_video_lastupdate;
	
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}
	
	public function __set($name, $value) {
		$method = 'set' . $name;
		$method = str_replace("_", null, $method);
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid videos property');
		}
		$this->$method($value);
	}
	public function __get($name) {
		$method = 'get' . $name;
		$method = str_replace("_", null, $method);
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid videos property');
		}
		return $this->$method();
	}
	
	public function setOptions(array $options) {
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	
	public function getVideoId() {
		return $this->_video_id;
	}
	public function setVideoId($video_id) {
		$this->_video_id = (int) $video_id;
	}
	
	public function getProductId() {
		return $this->_product_id;
	}
	public function setProductId($product_id) {
		$this->_product_id = (int) $product_id;
	}
	
	public function getVideoWithSlides() {
		return $this->_video_with_slides;
	}
	public function setVideoWithSlides($video_with_slides) {
		$this->_video_with_slides = (int) $video_with_slides;
	}
	
	public function getVideoPath() {
		return $this->_video_path;
	}
	public function setVideoPath($video_path) {
		$this->_video_path = (string) $video_path;
	}
	
	
	public function getVideoLength() {
		return $this->_video_length;
	}
	public function setVideoLength($video_length) {
		$this->_video_length = (int) $video_length;
	}
	
	public function getVideoSize() {
		return $this->_video_size;
	}
	public function setVideoSize($video_size) {
		$this->_video_size = (int) $video_size;
	}
	
	public function getVideoCreationDate() {
		return $this->_video_creation_date;
	}
	public function setVideoCreationDate($video_creation_date) {
		$this->_video_creation_date = $video_creation_date;
	}
	
	public function getVideoLastupdate() {
		return $this->_video_lastupdate;
	}
	public function setVideoLastupdate($video_lastupdate) {
		$this->_video_lastupdate = $video_lastupdate;
	}		
}