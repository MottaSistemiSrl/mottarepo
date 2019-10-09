<?php
class Ewall_Related_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_cartProductIds;

    public function __construct() {
        $product_ids = array();
        $products = Mage::getSingleton('checkout/session')->getQuote()->getAllVisibleItems();
        foreach ($products as $_products){
            $product_ids [$_products->getProductId()] = $_products->getQty();
        }
        $this->_cartProductIds = $product_ids;
    }

    public function isInCart($product) {
        return array_key_exists($product->getId(), $this->_cartProductIds);
    }

    public function categorygrouping($items) {

        foreach($items as $_item){

            $cat_ids = $_item->getCategoryIds();

            $pro_id = $_item->getEntityId();
            $cat_count = count($cat_ids);
            if($cat_count>1){
                sort($cat_ids);

                foreach($cat_ids as $cat){
                    $m = '';
                    $category = Mage::getModel('catalog/category');
                    $parentId=$category->load($cat)->getParentId();

                    $children = $category->getResource()->getAllChildren($category->load($cat));

                    if(isset($children)) {
                        $res = array_intersect($cat_ids,$children);

                        if(count($res)<=1){
                            $m = $cat;
                        }
                    }

                    if($m){
                        $itmes_col[$m][] = $_item->getId();
                    }

                }
            } else {
                $cat = $cat_ids[0];
                $itmes_col[$cat][] = $_item->getId();
            }
        }
        foreach($itmes_col as $key=>$coll){
            $uni[$key] = array_unique($coll);
        }
        ksort($uni);
        return $uni;
    }

    public function hasrelatedproducts($products) {
        $related_prods = $products->getRelatedProductIds();
        return count($related_prods);
    }
    public function getDatas($child_catid){
        return Mage::getModel('catalog/product')->load($child_catid);
    }
}
?>
