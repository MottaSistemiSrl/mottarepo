<?php
class Application_Model_Slides {
	protected $_slide_id;
	protected $_video_id;
	protected $_slide_number;
	protected $_slide_path;
	protected $_slide_creation_date;
	
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
			throw new Exception('Invalid slide property');
		}
		$this->$method($value);
	}
	public function __get($name) {
		$method = 'get' . $name;
		$method = str_replace("_", null, $method);
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid slide property');
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
	
	public function getSlideId() {
		return $this->_slide_id;
	}
	public function setSlideId($slide_id) {
		$this->_slide_id = (int) $slide_id;
	}
	
	public function getVideoId() {
		return $this->_video_id;
	}
	public function setVideoId($video_id) {
		$this->_video_id = (int) $video_id;
	}
	
	public function getSlideNumber() {
		return $this->_slide_number;
	}
	public function setSlideNumber($slide_number) {
		$this->_slide_number = (int) $slide_number;
	}
	
	public function getSlidePath() {
		return $this->_slide_path;
	}
	public function setSlidePath($slide_path) {
		$this->_slide_path = (string) $slide_path;
	}
	
	public function getSlideCreationDate() {
		return $this->_slide_creation_date;
	}
	public function setSlideCreationDate($slide_creation_date) {
		$this->_slide_creation_date = $slide_creation_date;
	}
}