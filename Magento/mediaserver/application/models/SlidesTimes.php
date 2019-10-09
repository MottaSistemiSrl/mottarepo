<?php
class Application_Model_Slides_Times extends Application_Model_Slides {
		
	protected $_slide_time_id;
	protected $_slide_id;	
	protected $_slide_opening_time;	
	protected $_slide_time_creation_date;
	
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
			throw new Exception('Invalid slidestimes property');
		}
		$this->$method($value);
	}
	public function __get($name) {
		$method = 'get' . $name;
		$method = str_replace("_", null, $method);
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid slidestime property');
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
	
		
	public function getSlideTimeId() {
		return $this->_slide_time_id;
	}
	public function setSlideTimeId($slide_time_id) {
		$this->_slide_time_id = (int) $slide_time_id;
	}
	
	public function getSlideId() {
		return $this->_slide_id;
	}
	public function setSlideId($slide_id) {
		$this->_slide_id = (int) $slide_id;
	}
			
	public function getSlideOpeningTime() {
		return $this->_slide_opening_time;
	}
	public function setSlideOpeningTime($slide_opening_time) {
		$this->_slide_opening_time = (int) $slide_opening_time;
	}
	
	public function getSlideTimeCreationDate() {
		return $this->_slide_time_creation_date;
	}
	public function setSlideTimeCreationDate($slide_time_creation_date) {
		$this->_slide_time_creation_date = $slide_time_creation_date;
	}
		
}