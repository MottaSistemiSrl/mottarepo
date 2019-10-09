<?php
class Amm_Product_Block_Monblock extends Mage_Core_Block_Template
{
     public function __construct()
    {
        parent::__construct();

        $session = $this->_getSession()->toArray();

        $column = 'id_customer';
        $value = $session['id'];

        $collection = Mage::getModel('product/product')->getCollection()->addFilter($column,$value)->setOrder('id','desc');

        $this->setCollection($collection);
    }

    protected function _getSession()
    {   
        return Mage::getSingleton('customer/session');
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        
        return $this;
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getEditProduct(){

      $param = $this->getRequest()->getParams();
      if($param['ep']!=0){

        $session = $this->_getSession()->toArray();

        $resource = Mage::getSingleton('core/resource');

        $table1 = $resource->getTableName('amm_product');
        $table2 = $resource->getTableName('catalog_product_entity');
        $table3 = $resource->getTableName('catalog_product_entity_varchar');
        $table4 = $resource->getTableName('catalog_product_entity_decimal');
        $table5 = $resource->getTableName('catalog_product_entity_int');
        $table6 = $resource->getTableName('catalog_product_entity_text');        
        $table7 = $resource->getTableName('catalog_category_product');
        $table8 = $resource->getTableName('catalog_product_entity_media_gallery');
        $table9 = $resource->getTableName('catalog_product_entity_media_gallery_value'); 
        $table10 = $resource->getTableName('downloadable_sample');  
        $table11 = $resource->getTableName('downloadable_sample_title'); 
        $table12 = $resource->getTableName('downloadable_link');  
        $table13 = $resource->getTableName('downloadable_link_title'); 
        $table14 = $resource->getTableName('eav_attribute');     

        $query = 'select 
          t2.*, 
          t3.value as name, 
          t4.value as price, 
          t5.value as status, 
          t6.value as description
        from
          '.$table1.' as t1, 
          '.$table2.' as t2, 
          '.$table3.' as t3, 
          '.$table4.' as t4, 
          '.$table5.' as t5, 
          '.$table6.' as t6
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t2.entity_id=t1.id_product and 
          t3.entity_id=t1.id_product and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code=\'name\') and 
          t5.entity_id=t1.id_product and 
          t5.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code=\'status\') and 
          t4.entity_id=t1.id_product and 
          t4.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code=\'price\') and 
          t6.entity_id=t1.id_product and
          t6.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code=\'description\')
        ';

        $productsArray = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query); 
        
        $query2 = 'select 
          t6.value as short_description 
        from 
          '.$table1.' as t1,
          '.$table6.' as t6
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t6.entity_id=t1.id_product and
          t6.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t6.entity_type_id and ea1.attribute_code=\'short_description\')
        ';

        $productsArray2 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query2); 

        $query3 = 'select 
          t7.category_id 
        from 
          '.$table1.' as t1,
          '.$table7.' as t7
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t7.product_id=t1.id_product
        ';

        $productsArray3 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query3);

        $query4 = 'select 
          t8.value_id,
          t8.value,
          t9.label,
          t9.position,
          t9.disabled
        from 
          '.$table1.' as t1, 
          '.$table2.' as t2,
          '.$table8.' as t8,
          '.$table9.' as t9
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t2.entity_id=t1.id_product and 
          t8.entity_id=t1.id_product and 
          t8.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code=\'media_gallery\') and 
          t9.value_id=t8.value_id 
        order by t9.position asc;
        ';

        $productsArray4 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query4);

        $query5 = 'select 
          t3.value 
        from 
          '.$table1.' as t1,
          '.$table3.' as t3
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t3.entity_id=t1.id_product and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t3.entity_type_id and ea1.attribute_code=\'image\')
        ';

        $productsArray5 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query5);

        $query6 = 'select 
          t3.value 
        from 
          '.$table1.' as t1,
          '.$table3.' as t3
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t3.entity_id=t1.id_product and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t3.entity_type_id and ea1.attribute_code=\'small_image\')
        ';

        $productsArray6 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query6);

        $query7 = 'select 
          t3.value 
        from 
          '.$table1.' as t1,
          '.$table3.' as t3
        where
          t1.id_customer='.$session['id'].' and 
          t1.id_product='.$param['ep'].' and 
          t3.entity_id=t1.id_product and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t3.entity_type_id and ea1.attribute_code=\'thumbnail\')
        ';

        $productsArray7 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query7);

        $query8 = 'select 
          t3.value 
        from 
          '.$table3.' as t3
        where
          t3.entity_id='.$param['ep'].' and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t3.entity_type_id and ea1.attribute_code=\'samples_title\')
        ';

        $productsArray8 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query8);

        $query9 = 'select 
          t3.value 
        from 
          '.$table3.' as t3
        where
          t3.entity_id='.$param['ep'].' and 
          t3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=t3.entity_type_id and ea1.attribute_code=\'links_title\')
        ';

        $productsArray9 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query9);

        $query10 = 'select 
          t10.*, 
          t11.title 
        from 
          '.$table10.' as t10, 
          '.$table11.' as t11
        where
          t10.product_id='.$param['ep'].' and 
          t11.sample_id=t10.sample_id 
        ';

        $productsArray10 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query10);

        $query11 = 'select 
          t12.*, 
          t13.title 
        from 
          '.$table12.' as t12, 
          '.$table13.' as t13
        where
          t12.product_id='.$param['ep'].' and 
          t13.link_id=t12.link_id 
        ';

        $productsArray11 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query11);
        
        foreach($productsArray as $pa){
          foreach($productsArray2 as $pa2){
            $pa['short_description'] = $pa2['short_description'];
          }
          foreach($productsArray3 as $pa3){
            $pa['category_id'][] = $pa3['category_id'];
          }
          $ik=0;
          foreach($productsArray4 as $pa4){
            $pa['image'][$ik]['value_id'] = $pa4['value_id'];
            $pa['image'][$ik]['value'] = $pa4['value'];            
            $pa['image'][$ik]['label'] = $pa4['label'];
            $pa['image'][$ik]['position'] = $pa4['position'];
            $pa['image'][$ik]['disabled'] = $pa4['disabled'];
            $ik++;
          }
          foreach($productsArray5 as $pa5){
            $pa['image_radio'] = $pa5['value'];
          }
          foreach($productsArray6 as $pa6){
            $pa['small_image_radio'] = $pa6['value'];
          }
          foreach($productsArray7 as $pa7){
            $pa['thumb_image_radio'] = $pa7['value'];
          }
          foreach($productsArray8 as $pa8){
            $pa['samples_title'] = $pa8['value'];
          }
          foreach($productsArray9 as $pa9){
            $pa['links_title'] = $pa9['value'];
          }
          $ikd=0;
          foreach($productsArray10 as $pa10){
            $pa['sample'][$ikd]['sample_id'] = $pa10['sample_id'];
            $pa['sample'][$ikd]['product_id'] = $pa10['product_id'];            
            $pa['sample'][$ikd]['sample_url'] = $pa10['sample_url'];
            $pa['sample'][$ikd]['sample_file'] = $pa10['sample_file'];
            $pa['sample'][$ikd]['sample_type'] = $pa10['sample_type'];
            $pa['sample'][$ikd]['sort_order'] = $pa10['sort_order'];
            $pa['sample'][$ikd]['title'] = $pa10['title'];
            $ikd++;
          }
          $ikl=0;
          foreach($productsArray11 as $pa11){
            $pa['links'][$ikl]['link_id'] = $pa11['link_id'];
            $pa['links'][$ikl]['product_id'] = $pa11['product_id'];            
            $pa['links'][$ikl]['number_of_downloads'] = $pa11['number_of_downloads'];
            $pa['links'][$ikl]['is_shareable'] = $pa11['is_shareable'];
            $pa['links'][$ikl]['link_url'] = $pa11['link_url'];
            $pa['links'][$ikl]['link_file'] = $pa11['link_file'];
            $pa['links'][$ikl]['link_type'] = $pa11['link_type'];
            $pa['links'][$ikl]['sample_url'] = $pa11['sample_url'];
            $pa['links'][$ikl]['sample_file'] = $pa11['sample_file'];
            $pa['links'][$ikl]['sample_type'] = $pa11['sample_type'];
            $pa['links'][$ikl]['sort_order'] = $pa11['sort_order'];
            $pa['links'][$ikl]['title'] = $pa11['title'];
            $ikl++;
          }

            $p = Mage::getModel("catalog/product")->load($param['ep']);
            $pa["sku_cartamodello"] = $p->getSkuCartamodello();
            $pa["materiale_utilizzato"] = $p->getMaterialeUtilizzato();
          return $pa;
        }
      }

      return null;

    }

         public function productList()
     {

        $this->saveForm();

        $params = $this->getRequest()->getParams();

        $mLimit = 5;
          if(isset($params['limit'])){ $mLimit = $params['limit']; }
        $mPage  = 0;
          if(isset($params['p'])){ $mPage = $params['p'] * $mLimit - $mLimit; }



        $session = $this->_getSession()->toArray();

        $resource = Mage::getSingleton('core/resource');

        $table1 = $resource->getTableName('amm_product');
        $table2 = $resource->getTableName('catalog_product_entity');
        $table3 = $resource->getTableName('catalog_product_entity_varchar');
        $table4 = $resource->getTableName('catalog_product_entity_decimal');
        $table5 = $resource->getTableName('catalog_product_entity_int');
        $table6 = $resource->getTableName('eav_attribute');

        //echo $table3.'<br />';

        $query = "select 
          t2.*, 
          t3.value as name, 
          t4.value as price, 
          t5.value as status
        from 
          ".$table1." as t1, 
          ".$table2." as t2, 
          ".$table3." as t3, 
          ".$table4." as t4, 
          ".$table5." as t5
        where 
          t1.id_customer=".$session['id']." and
          t2.entity_id=t1.id_product and 
          t3.entity_id=t1.id_product and 
          t3.entity_type_id=t2.entity_type_id and 
          t3.attribute_id=(select ea1.attribute_id from ".$table6." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='name') and 
          t5.entity_id=t1.id_product and 
          t5.entity_type_id=t2.entity_type_id and 
          t5.attribute_id=(select ea1.attribute_id from ".$table6." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='status') and 
          t4.entity_id=t1.id_product and 
          t4.entity_type_id=t2.entity_type_id and 
          t4.attribute_id=(select ea1.attribute_id from ".$table6." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='price') 
        order by t2.created_at desc, t2.updated_at desc 
        limit ".$mPage.", ".$mLimit;

         // echo $query.'<br />';

        $arrayProductRecords = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);

        if($arrayProductRecords){

          $arrayProductRecords[0]['limit']=$mLimit;
          $arrayProductRecords[0]['page']=($mPage==0?1:$mPage);

        }
        
        return $arrayProductRecords; 

     }

     public function productListStatistica()
     {

        $params = $this->getRequest()->getParams();

        $mLimit = 5;
          if(isset($params['limit'])){ $mLimit = $params['limit']; }
        $mPage  = 0;
          if(isset($params['p'])){ $mPage = $params['p'] * $mLimit - $mLimit; }
        $filter_from = '';
        $db_filter_from = '';
          if(isset($params['_dob'])){ 
            $filter_from = $params['_dob']; 
            $dbfilterdate = split('\.', $filter_from);
            $db_filter_from = $dbfilterdate[2].'-'.$dbfilterdate[1].'-'.$dbfilterdate[0]; 
          }
        $filter_to = '';
        $db_filter_to = '';
          if(isset($params['_dobto'])){ 
            $filter_to = $params['_dobto']; 
            $dbfilterdate = split('\.', $filter_to);
            $db_filter_to = $dbfilterdate[2].'-'.$dbfilterdate[1].'-'.$dbfilterdate[0];
          }


        $session = $this->_getSession()->toArray();

        $resource = Mage::getSingleton('core/resource');

        $table1 = $resource->getTableName('amm_product');
        $table2 = $resource->getTableName('catalog_product_entity');
        $table3 = $resource->getTableName('catalog_product_entity_varchar');
        $table4 = $resource->getTableName('catalog_product_entity_decimal');
        $table5 = $resource->getTableName('catalog_product_entity_int');

        $table6 = $resource->getTableName('sales_flat_order_item');
        $table7 = $resource->getTableName('sales_flat_order');
        $table8 = $resource->getTableName('eav_attribute');

        //echo $table3.'<br />';

        $query = "select 
          t2.*, 
          t3.value as name, 
          t4.value as price, 
          t5.value as status
        from 
          ".$table1." as t1, 
          ".$table2." as t2, 
          ".$table3." as t3, 
          ".$table4." as t4, 
          ".$table5." as t5
        where 
          t1.id_customer=".$session['id']." and 
          t2.entity_id=t1.id_product and 
          t3.entity_id=t1.id_product and 
          t3.attribute_id=(select ea1.attribute_id from ".$table8." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='name') and 
          t5.entity_id=t1.id_product and 
          t5.attribute_id=(select ea1.attribute_id from ".$table8." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='status') and 
          t4.entity_id=t1.id_product and 
          t4.attribute_id=(select ea1.attribute_id from ".$table8." as ea1 where ea1.entity_type_id=t2.entity_type_id and ea1.attribute_code='price') 
        order by t2.created_at desc, t2.updated_at desc 
        limit ".$mPage.", ".$mLimit;

          //echo $query.'<br />';

        $arrayProductRecords = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);

        $ia=0;
        foreach($arrayProductRecords as $apr){

            $query1 = 'select 
              t7.entity_id
            from 
              '.$table6.' as t6, 
              '.$table7.' as t7
            where 
              (t7.customer_id='.$session['id'].' or ISNULL(t7.customer_id)) and 
              t7.status=\'complete\' and 
              t6.product_id='.$apr['entity_id'].' and 
              t6.order_id=t7.entity_id
            '.($filter_from!=''?' and t6.updated_at>="'.$db_filter_from.' 00:00:00"':'').'
            '.($filter_to!=''?' and t6.updated_at<="'.$db_filter_to.' 23:59:59"':'').'
            ';

            //echo $query1; exit();

            $countSales = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query1);
            $ic=0;
            foreach($countSales as $cs){
                $ic++;
            }

            $arrayProductRecords[$ia]['count']=$ic;

            $ia++;

        }
        

        if($arrayProductRecords){

          $arrayProductRecords[0]['limit']=$mLimit;
          $arrayProductRecords[0]['page']=($mPage==0?1:$mPage);

          $arrayProductRecords[0]['filter_from']=$filter_from;
          $arrayProductRecords[0]['filter_to']=$filter_to;

        }
        
        return $arrayProductRecords; 

     }

     public function selectTreeCategory($root=1, $category_id=array()){

          //$html = 'rootID = '.$root.'<br />';
          $html .= '<ul>';

              $parentCategoryId = $root;
              $categories = Mage::getModel('catalog/category')
                  ->getCollection()
                  ->addFieldToFilter('parent_id', array('eq'=>$parentCategoryId))
                  ->addFieldToFilter('is_active', array('eq'=>'1'))
                  ->addAttributeToSelect('*');

                foreach($categories as $cats){
                   $html .= '<li><input type="checkbox" id="checkcat'.$cats->getEntityId().'" name="checkcat'.$cats->getEntityId().'" value="'.$cats->getEntityId().'" onclick="categorytree('.$cats->getEntityId().')" '.(in_array($cats->getEntityId(),$category_id)?'checked="checked"':'').'> '.$cats->getName(); 

                   $html .= $this->selectTreeCategory($cats->getEntityId(),$category_id);

                   $html .= '</li>';    
                 }

          $html .= '</ul>';

          return $html;
     }



     public function saveForm(){

        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $productId      = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);

        $data = $this->getRequest()->getPost();
        
        if ($data) {
          
            $this->_filterStockData($data['product']['stock_data']);

            $product = $this->_initProductSave();

                if ($downloadable = $this->getRequest()->getPost('downloadable')) {
                    $product->setDownloadableData($downloadable);
                }

            try {


                $visibility = array (
                    'thumbnail',
                    'small_image',
                    'image'
                );

                foreach ($_FILES as $futureImage) {
                    $fileName       = $futureImage['name'];
                    if (empty($fileName)) {
                        continue;
                    }
                    $fileExt        = strtolower(substr(strrchr($fileName, "."), 1));
                    $fileNamewoe    = rtrim($fileName, $fileExt);
                    $fileName       = preg_replace('/\s+', '', $fileNamewoe) . time() . '.' . $fileExt;

                    $uploader = new Varien_File_Uploader($futureImage);
                    $uploader->setAllowedExtensions(array('jpg', 'png', 'jpeg'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $path = Mage::getBaseDir('media') . DS . 'showcase';
                    if(!is_dir($path)){
                        mkdir($path, 0777, true);
                    }
                    $uploader->save($path . DS, $fileName);
                    $filePath = $path . DS . $fileName;

                    $product->addImageToMediaGallery($filePath, $visibility, false, false);
                }


                $product->save();
                $productId = $product->getId();

                if(!$isEdit){
                  
                  $resource = Mage::getSingleton('core/resource');
                  $table_amm = $resource->getTableName('amm_product');

                  $session = $this->_getSession()->toArray(); 
                  $write = Mage::getSingleton('core/resource')->getConnection('core_write'); 
                  $write->query("insert into ".$table_amm." (id_customer, id_product) values (".$session['id'].",".$product->getId().")");
                  
                }

                /**
                 * Do copying data to stores
                 */
                if (isset($data['copy_to_stores'])) {
                    foreach ($data['copy_to_stores'] as $storeTo=>$storeFrom) {
                        $newProduct = Mage::getModel('catalog/product')
                            ->setStoreId($storeFrom)
                            ->load($productId)
                            ->setStoreId($storeTo)
                            ->save();
                    }
                }

                Mage::getModel('catalogrule/rule')->applyAllRulesToProduct($productId);

            } catch (Mage_Core_Exception $e) {
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $redirectBack = true;
            }
        }
     }
     


protected function _initProduct()
    {
        $productId  = (int) $this->getRequest()->getParam('id');
        $product    = Mage::getModel('catalog/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }

            if ($typeId = $this->getRequest()->getParam('type')) {
                $product->setTypeId($typeId);
            }
        }

        $product->setData('_edit_mode', true);
        if ($productId) {
            try {
                $product->load($productId);
            } catch (Exception $e) {
                $product->setTypeId(Mage_Catalog_Model_Product_Type::DEFAULT_TYPE);
                Mage::logException($e);
            }
        }

        $attributes = $this->getRequest()->getParam('attributes');
        if ($attributes && $product->isConfigurable() &&
            (!$productId || !$product->getTypeInstance()->getUsedProductAttributeIds())) {
            $product->getTypeInstance()->setUsedProductAttributeIds(
                explode(",", base64_decode(urldecode($attributes)))
            );
        }

        // Required attributes of simple product for configurable creation
        if ($this->getRequest()->getParam('popup')
            && $requiredAttributes = $this->getRequest()->getParam('required')) {
            $requiredAttributes = explode(",", $requiredAttributes);
            foreach ($product->getAttributes() as $attribute) {
                if (in_array($attribute->getId(), $requiredAttributes)) {
                    $attribute->setIsRequired(1);
                }
            }
        }

        if ($this->getRequest()->getParam('popup')
            && $this->getRequest()->getParam('product')
            && !is_array($this->getRequest()->getParam('product'))
            && $this->getRequest()->getParam('id', false) === false) {

            $configProduct = Mage::getModel('catalog/product')
                ->setStoreId(0)
                ->load($this->getRequest()->getParam('product'))
                ->setTypeId($this->getRequest()->getParam('type'));

            /* @var $configProduct Mage_Catalog_Model_Product */
            $data = array();
            foreach ($configProduct->getTypeInstance()->getEditableAttributes() as $attribute) {

                /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
                if(!$attribute->getIsUnique()
                    && $attribute->getFrontend()->getInputType()!='gallery'
                    && $attribute->getAttributeCode() != 'required_options'
                    && $attribute->getAttributeCode() != 'has_options'
                    && $attribute->getAttributeCode() != $configProduct->getIdFieldName()) {
                    $data[$attribute->getAttributeCode()] = $configProduct->getData($attribute->getAttributeCode());
                }
            }

            $product->addData($data)
                ->setWebsiteIds($configProduct->getWebsiteIds());
        }

        Mage::register('product', $product);
        Mage::register('current_product', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

protected function _initProductSave()
    {
        $product     = $this->_initProduct();
        $productData = $this->getRequest()->getPost('product');
        if ($productData) {
            $this->_filterStockData($productData['stock_data']);
        }

        /**
         * Websites
         */
        if (!isset($productData['website_ids'])) {
            $productData['website_ids'] = array();
        }

        $wasLockedMedia = false;
        if ($product->isLockedAttribute('media')) {
            $product->unlockAttribute('media');
            $wasLockedMedia = true;
        }

        $product->addData($productData);

        if ($wasLockedMedia) {
            $product->lockAttribute('media');
        }

        if (Mage::app()->isSingleStoreMode()) {
            $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
        }

        /**
         * Create Permanent Redirect for old URL key
         */
        if ($product->getId() && isset($productData['url_key_create_redirect']))
        // && $product->getOrigData('url_key') != $product->getData('url_key')
        {
            $product->setData('save_rewrites_history', (bool)$productData['url_key_create_redirect']);
        }

        /**
         * Check "Use Default Value" checkboxes values
         */
        if ($useDefaults = $this->getRequest()->getPost('use_default')) {
            foreach ($useDefaults as $attributeCode) {
                $product->setData($attributeCode, false);
            }
        }

        /**
         * Init product links data (related, upsell, crosssel)
         */
        $links = $this->getRequest()->getPost('links');
        if (isset($links['related']) && !$product->getRelatedReadonly()) {
            $product->setRelatedLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']));
        }
        if (isset($links['upsell']) && !$product->getUpsellReadonly()) {
            $product->setUpSellLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['upsell']));
        }
        if (isset($links['crosssell']) && !$product->getCrosssellReadonly()) {
            $product->setCrossSellLinkData(Mage::helper('adminhtml/js')
                ->decodeGridSerializedInput($links['crosssell']));
        }
        if (isset($links['grouped']) && !$product->getGroupedReadonly()) {
            $product->setGroupedLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['grouped']));
        }

        /**
         * Initialize product categories
         */
        $categoryIds = $this->getRequest()->getPost('category_ids');

        if (null !== $categoryIds) {
            if (empty($categoryIds)) {
                $categoryIds = array();
            }
            $product->setCategoryIds($categoryIds);
        }

        /**
         * Initialize data for configurable product
         */
        if (($data = $this->getRequest()->getPost('configurable_products_data'))
            && !$product->getConfigurableReadonly()
        ) {
            $product->setConfigurableProductsData(Mage::helper('core')->jsonDecode($data));
        }
        if (($data = $this->getRequest()->getPost('configurable_attributes_data'))
            && !$product->getConfigurableReadonly()
        ) {
            $product->setConfigurableAttributesData(Mage::helper('core')->jsonDecode($data));
        }

        $product->setCanSaveConfigurableAttributes(
            (bool) $this->getRequest()->getPost('affect_configurable_product_attributes')
                && !$product->getConfigurableReadonly()
        );

        /**
         * Initialize product options
         */
        if (isset($productData['options']) && !$product->getOptionsReadonly()) {
            $product->setProductOptions($productData['options']);
        }

        $product->setCanSaveCustomOptions(
            (bool)$this->getRequest()->getPost('affect_product_custom_options')
            && !$product->getOptionsReadonly()
        );

        Mage::dispatchEvent(
            'catalog_product_prepare_save',
            array('product' => $product, 'request' => $this->getRequest())
        );

        return $product;
    }

protected function _filterStockData(&$stockData) {
        if (!isset($stockData['use_config_manage_stock'])) {
            $stockData['use_config_manage_stock'] = 0;
        }
        if (isset($stockData['qty']) && (float)$stockData['qty'] > self::MAX_QTY_VALUE) {
            $stockData['qty'] = self::MAX_QTY_VALUE;
        }
        if (isset($stockData['min_qty']) && (int)$stockData['min_qty'] < 0) {
            $stockData['min_qty'] = 0;
        }
        if (!isset($stockData['is_decimal_divided']) || $stockData['is_qty_decimal'] == 0) {
            $stockData['is_decimal_divided'] = 0;
        }
    }


 


}