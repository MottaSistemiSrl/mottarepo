<?php
class MG_Megaforum_Helper_Data extends Mage_Core_Helper_Abstract
{
	
public function getCaptchaText()
{
  return Mage::getSingleton('core/session')->getData('forum_captcha_code');
}

}
	 