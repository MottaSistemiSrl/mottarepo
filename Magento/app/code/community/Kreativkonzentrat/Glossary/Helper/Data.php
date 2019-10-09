<?php
/**
 * Class Kreativkonzentrat_Glossary_Helper_Data
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * @return mixed
	 */
	public function getGlossaryIndexUrl () {
		return $this->_getUrl('glossary');
	}

	/**
	 * @param string $letter
	 *
	 * @return string
	 */
	public  function asciiLetter ($letter) {
		if (preg_match('/^[a-z]$/i', $letter)) {
			return strtoupper($letter);
		}
		switch ($letter) {
			case 'ä':
			case 'Ä':
				return 'A';
			case 'ö':
			case 'Ö':
				return 'O';
			case 'ü':
			case 'Ü':
				return 'U';
			default:
				return '123';
		}
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public function convertUmlauts($string) {
		$replace = array(
			' ' => '_',
			'Ä' => 'Ae',
			'Ö' => 'Oe',
			'Ü' => 'Ue',
			'ä' => 'ae',
			'ö' => 'oe',
			'ü' => 'ue',
			'ß' => 'ss'
		);
		return strtr($string, $replace);
	}
}