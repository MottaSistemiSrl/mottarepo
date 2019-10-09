<?php

class Telemantis_Video_Model_Source extends Mage_Core_Helper_Abstract
{

  public function toOptionArray()
  {
	    return array(
	      array('value' => 'none', 'label' =>'None'),
	      array('value' => 'exactfit', 'label' => 'Exactfit'),
	      array('value' => 'uniform', 'label' =>'Uniform'),
              array('value' => 'fill', 'label' =>'Fill'),
	     // and so on...
    );
  }
}
