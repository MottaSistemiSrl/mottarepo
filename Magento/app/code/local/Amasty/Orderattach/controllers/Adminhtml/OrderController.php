<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
 * @package Amasty_Orderattach
 */
class Amasty_Orderattach_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/amorderattach')
            ->_addBreadcrumb(Mage::helper('amorderattach')->__('System'), Mage::helper('sales')->__('System'))
            ->_addBreadcrumb(Mage::helper('amorderattach')->__('Manage Order Attachments'), Mage::helper('amorderattach')->__('Manage Order Attachments'))
        ;
        return $this;
    }

    protected function _initOrder()
    {
        $orderId = $this->getRequest()->getPost('order_id');
        $order   = Mage::getModel('sales/order')->load($orderId);
        Mage::register('current_order', $order);
    }

    protected function _sendResponse($fieldModel)
    {
        $this->getResponse()->setBody($fieldModel->getRenderer()->render());
    }

    public function saveAction()
    {
        $this->_initOrder();
        $fieldModel = Mage::getModel('amorderattach/field')->load($this->getRequest()->getPost('field'), 'code');
        if ($fieldModel->getId())
        {
            $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
            if (!$orderField->getOrderId())
            {
                $orderField->setOrderId(Mage::registry('current_order')->getId());
            }
            if ('date' == $this->getRequest()->getPost('type'))
            {
                if ($this->getRequest()->getPost('value'))
                {
                    $value = date('Y-m-d', strtotime($this->getRequest()->getPost('value')));
                } else
                {
                    $value = null;
                }
            } else
            {
                $value = $this->getRequest()->getPost('value');
            }
            $orderField->setData($this->getRequest()->getPost('field'), $value);
            $orderField->save();

            $orderFieldLoad = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
            // updating "updated_at" ...
            if (Mage::getStoreConfig('amorderattach/general/update_updated_at'))
            {
                $timestamp = Mage::getModel('core/date')->gmtTimestamp();
                if (method_exists('Varien_Date', 'formatDate'))
                    $date = Varien_Date::formatDate($timestamp);
                else
                    $date = date('Y-m-d H:i:s', $timestamp);
               // Mage::registry('current_order')->setUpdatedAt($date)->save();
            }

            Mage::register('current_attachment_order_field', $orderFieldLoad); // required for renderer
            $this->_sendResponse($fieldModel);
        }
    }

    public function uploadAction()
    {
        $this->_initOrder();
        $field = $this->getRequest()->getPost('field');

        $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
        if ($fieldModel->getId())
        {
            $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
            if (!$orderField->getOrderId())
            {
                $orderField->setOrderId(Mage::registry('current_order')->getId());
            }

            // uploading file
            if (isset($_FILES['to_upload']['error']))
            {
                $multiple = is_array($_FILES['to_upload']['error']);
                for ($i = 0; $i < sizeof($_FILES['to_upload']['error']); $i++)
                {
                    $error = $multiple ? $_FILES['to_upload']['error'][$i] : $_FILES['to_upload']['error'];

                    if ($error == UPLOAD_ERR_OK)
                    {
                        try
                        {
                            $fileName = $multiple ? $_FILES['to_upload']['name'][$i] : $_FILES['to_upload']['name'];
                            $fileName = Mage::helper('amorderattach/upload')->cleanFileName($fileName);
                            $uploader = new Varien_File_Uploader($multiple ? "to_upload[$i]" : 'to_upload');
                            $uploader->setFilesDispersion(false);
                            $fileDestination = Mage::helper('amorderattach/upload')->getUploadDir();
                            if (file_exists($fileDestination . $fileName))
                            {
                                $fileName = uniqid(date('ihs')) . $fileName;
                            }
                            $uploader->save($fileDestination, $fileName);
                        } catch (Exception $e)
                        {
                            $this->_getSession()->addException($e, Mage::helper('amorderattach')->__('An error occurred while saving the file: ') . $e->getMessage());
                        }
                        if ('file' == $this->getRequest()->getPost('type')) // each new overwrites old one
                        {
                            $orderField->setData($this->getRequest()->getPost('field'), $fileName);
                        }
                        if ('file_multiple' == $this->getRequest()->getPost('type'))
                        {
                            $fieldData = $orderField->getData($field);
                            $fieldData = explode(';', $orderField->getData($this->getRequest()->getPost('field')));
                            $fieldData[] = $fileName;
                            $fieldData = implode(';', $fieldData);
                            $orderField->setData($field, $fieldData);
                        }
                    }
                }
                $orderField->save();
                die('success');
            }
        }
        die('failed');
    }

    public function downloadAction()
    {
        $fileName = $this->getRequest()->getParam('file');
        $fileName = Mage::helper('amorderattach/upload')->cleanFileName($fileName);
        if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName))
        {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            if(function_exists('mime_content_type'))
            {
                header('Content-Type: ' . mime_content_type(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName));
            }
            else if(class_exists('finfo'))
            {
                $finfo = new finfo(FILEINFO_MIME);
                $mimetype = $finfo->file(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
                header('Content-Type: ' . $mimetype);
            }
            readfile(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
        }
        exit;
    }

    public function deleteAction()
    {
        $this->_initOrder();

        $field = $this->getRequest()->getPost('field');

        $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');

        if ($fieldModel->getId())
        {
            $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
            if ($orderField->getOrderId())
            {
                $fileName = $this->getRequest()->getParam('file');
                if (file_exists(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName))
                {
                    @unlink(Mage::helper('amorderattach/upload')->getUploadDir() . $fileName);
                }
                if ('file' == $this->getRequest()->getPost('type'))
                {
                    $value = '';
                } elseif ('file_multiple' == $this->getRequest()->getPost('type'))
                {
                    $value = explode(';', $orderField->getData($field));
                    foreach ($value as $key => $val)
                    {
                        if ($val == $fileName)
                        {
                            unset($value[$key]);
                        }
                    }
                    $value = implode(';', $value);
                }
                $orderField->setData($this->getRequest()->getPost('field'), $value);
                $orderField->save();
                Mage::register('current_attachment_order_field', $orderField); // required for renderer
            }
            if ($this->getRequest()->getParam('grid'))
            {
                $type = $fieldModel->getType();

                $block = $this->getLayout()
                    ->createBlock('adminhtml/template')
                    ->setData('field', $field)
                    ->setData('order_id', $orderField->getOrderId())
                ;

                if ($type == 'file')
                {
                    $block->setTemplate('amorderattach/grid/file.phtml')
                        ->setData('value', $orderField->getData($field))
                    ;
                }
                else
                {
                    $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                        ->setData('values', explode(';', trim($orderField->getData($field), " \t\n\r\0\x0B;")))
                    ;
                }


                $this->getResponse()->setBody($block->toHtml());
            }
            else
                $this->_sendResponse($fieldModel);
        }
    }

    public function reloadAction()
    {
        $this->_initOrder();

        $field = $this->getRequest()->getPost('field');

        $fieldModel = Mage::getModel('amorderattach/field')->load($field, 'code');
        if ($fieldModel->getId())
        {
            $orderField = Mage::getModel('amorderattach/order_field')->load(Mage::registry('current_order')->getId(), 'order_id');
            Mage::register('current_attachment_order_field', $orderField); // required for renderer

            if ($this->getRequest()->getParam('grid'))
            {
                $type = $fieldModel->getType();

                $block = $this->getLayout()
                    ->createBlock('adminhtml/template')
                    ->setData('field', $field)
                    ->setData('order_id', $orderField->getOrderId())
                ;

                if ($type == 'file')
                {
                    $block->setTemplate('amorderattach/grid/file.phtml')
                        ->setData('value', $orderField->getData($field))
                    ;
                }
                else
                {
                    $block->setTemplate('amorderattach/grid/file_multiple.phtml')
                        ->setData('values', explode(';', trim($orderField->getData($field), " \t\n\r\0\x0B;")))
                    ;
                }

                $this->getResponse()->setBody($block->toHtml());
            }
            else
                $this->_sendResponse($fieldModel);
        }
    }

    public function saveFieldAction()
    {
        if (!Mage::helper('amorderattach')->isAllowedEdit())
            return;

        $orderId = $this->getRequest()->getParam('order_id');
        $value = $this->getRequest()->getParam('value');
        $fieldId = $this->getRequest()->getParam('field');

        $order = Mage::getModel('amorderattach/order_field')->load($orderId, 'order_id');
        $field = Mage::getModel('amorderattach/field')->load($fieldId, 'code');

        if (!$order->getId())
        {
            $order->setOrderId($orderId);
        }

        if ($field->getId())
        {
            /*            if ($field->getType() == 'date')
                        {
                            $value = date("Y-m-d", strtotime($value));
                        }*/

            $order->setData($field->getCode(), $value);

            $order->save();

            $success = 1;
        }
        else
            $success = 0;

        if ($field->getType() == 'text')
        {
            $value = str_replace("\n", "<br>\n", $value);
        }

        die(json_encode(array('success' => $success, 'value' => $value)));
    }
}
