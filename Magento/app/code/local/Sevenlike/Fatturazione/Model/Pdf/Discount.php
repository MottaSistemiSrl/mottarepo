<?php 
class Sevenlike_Fatturazione_Model_Pdf_Total_Discount extends Mage_Sales_Model_Order_Pdf_Total_Default
{

	public function getAmount()
	{
		if ($this->getSource()->getDiscountAmount() != 0) {
			$disc = $this->getSource()->getDiscountAmount();
			if ($disc > 0) {
				$disc = -1 * $disc;
			}
			$disc += $this->getSource()->getHiddenTaxAmount();
		} else {
			$disc = 0;
		}
		return $disc;
	}
}