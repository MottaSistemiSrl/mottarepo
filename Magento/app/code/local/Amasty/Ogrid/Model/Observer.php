<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Ogrid
*/
class Amasty_Ogrid_Model_Observer 
{

    public function onSalesOrderItemSaveAfter($observer){
        
        $orderItem = $observer->getEvent()->getItem();
        
        $amOrderItem = Mage::getModel("amogrid/order_item");
                
        $amOrderItem->mapOrder($orderItem);
        
        return true;  
    } 
    
    public function modifyOrderCollection($observer)
    {
        
        $collection = $observer->getCollection();
//        var_dump(get_class($collection));
        if ($collection instanceof Mage_Sales_Model_Mysql4_Order_Grid_Collection
                || $collection instanceof Mage_Sales_Model_Resource_Order_Grid_Collection){
            $layout = Mage::getSingleton('core/layout');
            $grid = $layout->getBlock('sales_order.grid');

            if ($grid){
                $columns = Mage::helper("amogrid/columns");
                
                $columns->prepareOrderCollectionJoins($collection); 
                
                $columns->removeColumns($grid);
                
                $columns->reorder($grid);
                
                $columns->restyle($grid);
                
            }
        }
    }
    
    public function modifyOrderGridAfterBlockGenerate($observer){
        
        
//        
        $permissibleActions = array('index', 'grid', 'exportCsv', 'exportExcel');
        $exportActions = array('exportCsv', 'exportExcel');
        
        if ( false === strpos(Mage::app()->getRequest()->getControllerName(), 'sales_order') || 
             !in_array(Mage::app()->getRequest()->getActionName(), $permissibleActions) ){
             
            return;
        }
        
        $block = $observer->getBlock();

        if ($block instanceof Mage_Adminhtml_Block_Sales_Order_Grid){
            $columns = Mage::helper("amogrid/columns");
            
            $columns->prepareGrid($block, in_array(
                    Mage::app()->getRequest()->getActionName(), $exportActions));
        }
    }
    
    
    
}
?>