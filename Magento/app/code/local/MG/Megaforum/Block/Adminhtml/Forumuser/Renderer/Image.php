<?php
	
class MG_Megaforum_Block_Adminhtml_Forumuser_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
  		$data=$row->getData($this->getColumn()->getIndex());
		$image = Mage::getBaseUrl('media').$row['image'];

		return '<img src="'.$image.'" alt="User Image" height="50" width="50">' ;
    }
}
