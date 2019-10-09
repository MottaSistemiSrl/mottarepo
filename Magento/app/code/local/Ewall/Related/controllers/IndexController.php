<?php

class Ewall_Related_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock('head')->setTitle($this->__('Realted Products'));
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', array(
            'label' => $this->__('Home Page'),
            'title' => $this->__('Home Page'),
            'link'  => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb('realted products', array(
            'label' => $this->__('Realted Products'),
            'title' => $this->__('Realted Products')
        ));

        $this->renderLayout();

    }
    protected function _initProduct($productId)
    {

        //$productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }
    public function UpdatecartAction() {
        $params = $this->getRequest()->getParams();
        $qty = $this->getRequest()->getParam('qty');
        $item_id = $this->getRequest()->getParam('pid');
        $cart = $this->_getCart();
        if(empty($params)) {
            $refererUrl = $this->_getRefererUrl();
            $this->_redirectUrl($refererUrl);
        }
        if($params['isAjax'] == 1){
            $response = array();
            try {
                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct($item_id);
                $product_image =  (string)Mage::helper('catalog/image')->init($product, 'image')->resize(50);
                $product_title = $product->getName();
                //$related = $this->getRequest()->getParam('related_product');
                /**
                 * Check product availability
                 */
                if (!$product) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Unable to find Product ID');
                }

                $cart->addProduct($product, $params);
                //~ if (!empty($related)) {
                //~ $cart->addProductsByIds(explode(',', $related));
                //~ }

                $cart->save();

                $this->_getSession()->setCartWasUpdated(true);

                /**
                 * @todo remove wishlist observer processAddToCart
                 */
                Mage::dispatchEvent('checkout_cart_add_product_complete',
                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
                );

                if (!$this->_getSession()->getNoCartRedirect(true)) {
                    if (!$cart->getQuote()->getHasError()){
                        $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));
                        $response['status'] = 'SUCCESS';
                        $response['message'] = $message;

                        //New Code Here
                        $this->loadLayout();
                        $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                        $sidebar = $this->getLayout()->getBlock('cart_sidebar')->toHtml();
                        $response['toplink'] = $toplink;
                        $response['sidebar'] = $sidebar;
                        $response['addmessage'] = $this->__('Item was added to your shopping cart');
                        $response['productimage'] = $product_image;
                        $response['producttitle'] = $product_title;
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $msg = '';
                if ($this->_getSession()->getUseNotice(true)) {
                    $msg = $e->getMessage();
                } else {
                    $messages = array_unique(explode('\n', $e->getMessage()));
                    foreach ($messages as $message) {
                        $msg .= $message.'<br/>';
                    }
                }

                $response['status'] = 'ERROR';
                $response['message'] = $msg;
            } catch (Exception $e) {
                $response['status'] = 'ERROR';
                $response['message'] = $this->__('Cannot add the item to shopping cart.');
                Mage::logException($e);
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
    }
    public function deletecartAction(){
        if((int) $this->getRequest()->getParam('isAjax') == 1){
            $pid = (int) $this->getRequest()->getParam('pid');
            $product = $this->_initProduct($pid);
            $product_image =  (string)Mage::helper('catalog/image')->init($product, 'image')->resize(50);
            $product_title = $product->getName();
            if ($pid) {
                try {
                    $items = $this->_getCart()->getItems();
                    foreach ($items as $item) {
                        if ($item->getProduct()->getId() == $pid) {
                            $itemId = $item->getItemId();
                            $this->_getCart()->removeItem($itemId)->save();
                            break;
                        }
                    }
                    $message = $this->__('Item was removed from your shopping cart.');
                    $response['status'] = 'SUCCESS';
                    $response['message'] = $message;
                    $this->loadLayout();
                    $toplink = $this->getLayout()->getBlock('top.links')->toHtml();
                    $sidebar = $this->getLayout()->getBlock('cart_sidebar')->toHtml();
                    $response['toplink'] = $toplink;
                    $response['sidebar'] = $sidebar;
                    $response['removemessage'] = $this->__('Item was removed from your shopping cart.');
                    $response['productimage'] = $product_image;
                    $response['producttitle'] = $product_title;
                    $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                    return;

                } catch (Exception $e) {
                    $response['status'] = 'ERROR';
                    $response['message'] = $this->__('Cannot remove the item from shopping cart.');
                    Mage::logException($e);
                }
            }
        }
    }
}
?>
