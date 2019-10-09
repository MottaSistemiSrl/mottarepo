<?php
/**
 * Class Kreativkonzentrat_Glossary_Model_Product
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Model_Product extends Mage_Catalog_Model_Product
{
	/**
	 * @return mixed
	 */
	public function getShortDescription () {
		if (Mage::getStoreConfig('glossary/replacings/sd_replace') == 1){
			//get original short description
			$originalShortDescription = parent::getShortDescription();
			//get available glossary entries
			$titles = Mage::getModel('glossary/glossary')->getTitles();
			//match entries with description and add link
			if (Mage::getStoreConfig('glossary/replacings/open_popup') == 1) {
				return preg_replace_callback(
						$titles,
						create_function('$hit','return "<a href=\"#\" onclick=\"popWin(\'" 
							. Mage::getBaseUrl() 
							. "glossary/entry/"
							. urlencode(html_entity_decode($hit[0], ENT_COMPAT, \'UTF-8\')) 
							."/popup\',\'ge\',\'width=300,height=300,resizable=yes,scrollbars=yes\')\">"
							. $hit[0]
							. "</a>";'),								
						$originalShortDescription
				);
			} else {
				return preg_replace_callback( 
					$titles, 
					create_function('$hit', 'return "<a href=\"' . Mage::getBaseUrl() . 'glossary/entry/" . urlencode($hit[0]) . "\">" . $hit[0] . "</a>";'),
					$originalShortDescription
				);
			}
		}
		else {
			return parent::getShortDescription();
		}
	}

	/**
	 * @return mixed
	 */
	public function getDescription () {
		if (Mage::getStoreConfig('glossary/replacings/d_replace') == 1){
			//get original description
			$originalDescription = parent::getDescription();
			//get available glossary entries
			$titles = Mage::getModel('glossary/glossary')->getTitles();
			if (Mage::getStoreConfig('glossary/replacings/open_popup') == 1) {
				return preg_replace_callback(
						$titles, 
						create_function('$hit','return "<a href=\"#\" onclick=\"popWin(\'" 
							. Mage::getBaseUrl() 
							. "glossary/entry/"
							. urlencode(html_entity_decode($hit[0], ENT_COMPAT, \'UTF-8\')) 
							."/popup\',\'ge\',\'width=300,height=300,resizable=yes,scrollbars=yes\')\">"
							. $hit[0]
							. "</a>";'),
						$originalDescription
				);
			} else {
				//match entries with description and add link
				return preg_replace_callback( 
					$titles, 
					create_function('$hit','return "<a href=\"'.Mage::getBaseUrl().'glossary/entry/".urlencode($hit[0])."\">".$hit[0]."</a>";'),
					$originalDescription
				);
			}
		}
		else{
			return parent::getDescription();
		}
	}	
}