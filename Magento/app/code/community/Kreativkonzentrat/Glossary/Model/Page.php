<?php
/**
 * Class Kreativkonzentrat_Glossary_Model_Page
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Model_Page extends Mage_Cms_Model_Page
{

	/**
	 * @return mixed
	 */
	public function getContent () {
		if (Mage::getStoreConfig('glossary/replacings/cms_replace') == 1) {
			$originalContent = parent::getContent();
			//get available glossary entries
			$titles = Mage::getModel('glossary/glossary')->getTitles();
			function kkg_callback ($hit) {
				$result = '<a href="#" onclick="popWin(\' ' . Mage::getUrl('glossary/entry') . urlencode(html_entity_decode($hit[0], ENT_COMPAT, 'UTF-8')) . '/popup\',\'ge\',\'width=300,height=300,resizable=yes,scrollbars=yes\')">' . $hit[0] . '</a>';
				return $result;
			}
			//match entries with description and add link
			if (Mage::getStoreConfig('glossary/replacings/open_popup') == 1) {
				return preg_replace_callback($titles, 'kkg_callback', $originalContent);
			} else {
				return preg_replace_callback($titles, create_function('$hit', 'return "<a href=\"' . Mage::getUrl() . 'glossary/entry/".urlencode(html_entity_decode($hit[0],ENT_COMPAT,"UTF-8")) . "\">" . $hit[0] . "</a>";'), $originalContent);
			}
		} else {
			return parent::getContent();
		}
	}

}