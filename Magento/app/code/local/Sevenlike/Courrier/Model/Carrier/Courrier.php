<?php

/**
 * Created by PhpStorm.
 * User: andrea.becchio
 * Date: 09/09/2015
 * Time: 10:44
 */
class Sevenlike_Courrier_Model_Carrier_Courrier  extends Mage_Shipping_Model_Carrier_Tablerate
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'courrier';



    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if (!$this->getConfigFlag('include_virtual_price') && $request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getProduct()->isVirtual()) {
                            $request->setPackageValue($request->getPackageValue() - $child->getBaseRowTotal());
                        }
                    }
                } elseif ($item->getProduct()->isVirtual()) {
                    $request->setPackageValue($request->getPackageValue() - $item->getBaseRowTotal());
                }
            }
        }

        // Free shipping by qty
        $freeQty = 0;
        $shippingPrice = 0;
        $freePackageValue = 0;

        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                            $freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeShipping = is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0;
                    $freeQty += $item->getQty() - $freeShipping;
                    $freePackageValue += $item->getBaseRowTotal();
                }
            }
            $oldValue = $request->getPackageValue();
            $request->setPackageValue($oldValue - $freePackageValue);
        }

        if ($freePackageValue) {
            $request->setPackageValue($request->getPackageValue() - $freePackageValue);
        }
        if (!$request->getConditionName()) {
            $conditionName = $this->getConfigData('condition_name');
            $request->setConditionName($conditionName ? $conditionName : $this->_default_condition_name);
        }

        // Package weight and qty free shipping
        $oldWeight = $request->getPackageWeight();
        $oldQty = $request->getPackageQty();

        $request->setPackageWeight($request->getFreeMethodWeight());
        $request->setPackageQty($oldQty - $freeQty);

        $result = $this->_getModel('shipping/rate_result');
        $rate = $this->getRate($request);

        $request->setPackageWeight($oldWeight);
        $request->setPackageQty($oldQty);
        $error = false;
        if (!empty($rate) && $rate['price'] >= 0) {
            if ($request->getFreeShipping() === true || ($request->getPackageQty() == $freeQty)) {
                $shippingPrice = 0;
            } else {
                $shippingPrice = $this->getFinalPriceWithHandlingFee($rate['price']);
            }

            $method = $this->_getMethod('standard',$this->getConfigData('name'),$shippingPrice);
            $result->append($method);



        } elseif (empty($rate) && $request->getFreeShipping() === true) {
            /**
             * was applied promotion rule for whole cart
             * other shipping methods could be switched off at all
             * we must show table rate method with 0$ price, if grand_total more, than min table condition_value
             * free setPackageWeight() has already was taken into account
             */
            $request->setPackageValue($freePackageValue);
            $request->setPackageQty($freeQty);
            $rate = $this->getRate($request);
            if (!empty($rate) && $rate['price'] >= 0) {
                $shippingPrice = 0;
                $method = $this->_getMethod('standard',$this->getConfigData('name'),$shippingPrice);
                $result->append($method);


            }
        } else {
            $error = $this->_getModel('shipping/rate_result_error');
            $error->setCarrier('courrier');
            $error->setCarrierTitle($this->getConfigData('title'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        }

        $postalService = $this->getConfigData('enable_postal_service');
        $postalServiceMaxWeight =$this->getConfigData('postal_service_max_weight');
        $postalServiceFee  = $this->getConfigData('postal_service_fee');
        if(!$error && $oldWeight<=$postalServiceMaxWeight&&$postalService){
            $method = $this->_getMethod('assicurata',$this->getConfigData('postal_service_name'),$postalServiceFee);
            $result->append($method);


        }

        return $result;
    }

    protected function _getMethod($methodCode,$methodName,$price){
        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier('courrier');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod($methodCode);
        $method->setMethodTitle($methodName);


        $method->setPrice($price);
        $method->setCost($price);
        return $method;
    }


    public function getAllowedMethods()
    {
        return array(
            'slcarrier_standard'=>$this->getConfigData('name'),
            'slcarrier_appuntamento'=>$this->getConfigData('name'),
            'slcarrier_piano'=>$this->getConfigData('name'));
    }

}