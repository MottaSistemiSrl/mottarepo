<?php

class MG_Megaforum_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function searchAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function profileAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function editAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function mytopicAction()
    {

        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {

            $id = $this->getRequest()->getParam("forum_id");
            if (!$id) {
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*'));
            } else {
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*/forum_id/' . $id));
            }
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }

    }

    public function myforumtopicsAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function myforumpostsAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function postAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function mypostAction()
    {

        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {

            $id = $this->getRequest()->getParam("id");
            if (!$id) {
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*'));
            } else {
                Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*/id/' . $id));
            }
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }

    }

    public function imageAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function privatemsgAction()
    {
        $session = Mage::getSingleton('customer/session');
        if (!$session->isLoggedIn()) {
            $id = $this->getRequest()->getParam("id");
            $session->setBeforeAuthUrl(Mage::getUrl('*/*/*/id/' . $id));
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
        } else {
            $this->loadLayout();
            $this->renderLayout();
        }

    }

    public function replyAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function mywatchlistAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function inboxAction()
    {

        $this->loadLayout();
        $this->renderLayout();

    }

    public function myreplyAction()
    {

        $post_data = $this->getRequest()->getPost();
        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("megaforum/privatemsg")->load($id);
        $customerData1 = Mage::getModel('customer/customer')->load($model->getSentFrom());
        $firstname1 = $customerData1->getFirstname();
        $customerData2 = Mage::getModel('customer/customer')->load($model->getSentTo());
        $firstname2 = $customerData2->getFirstname();
        $to = $customerData1->getEmail();

        $model = Mage::getModel("megaforum/privatemsg")
            ->addData($post_data)
            ->setSentFrom($model->getSentTo())
            ->setSentTo($model->getSentFrom())
            ->setSentDate(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
            ->save();

        $customerCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
            ->addFieldToFilter("type_id", 6)
            ->getFirstItem();

        $message1 = $customerCreate->getTemplate();

        $message1 = str_ireplace('{{CUSTOMERNAME}}', $firstname2, $message1);
        $message1 = str_ireplace('{{SUBJECT}}', $post_data['subject'], $message1);
        $message1 = str_ireplace('{{MESSAGE}}', $post_data['message'], $message1);
        $message1 = str_ireplace('{{SENTFROM}}', $firstname1, $message1);
        $message1 = str_ireplace('{{SENTDATE}}', $model->getSentDate(), $message1);

        $subject = "BurdaStyle - un utente ha risposto al tuo messaggio";
        $Body1 = $message1;

        $mail = new Zend_Mail(); //class for mail
        $mail->setBodyHtml($Body1); //for sending message containing html code
        $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');
        $mail->setFrom($adminMail, 'Burdastyle');
        $mail->addTo($to, $model->getSentFrom());
        $mail->setSubject($subject);
        $msg = '';
        try {
            if ($mail->send()) {
                $msg = true;
            }
        } catch (Exception $ex) {
            $msg = false;
            //die("Error sending mail to $to,$error_msg");
        }

        Mage::getSingleton('core/session')->addSuccess("La tua risposta al messaggio privata è stata inviata con successo");
        $this->_redirect('megaforum/index/index/');

    }

    public function privateAction()
    {

        $post_data = $this->getRequest()->getPost();
        $id = $this->getRequest()->getParam("id");
        $model = Mage::getModel("megaforum/post")->load($id);
        $customerData = Mage::getModel('customer/customer')->load($model->getPostedBy());
        $firstname = $customerData->getFirstname();
        $to = $customerData->getEmail();
        $customerName = Mage::getSingleton('customer/session')->getCustomer();
        $sentFrom = $customerName->getFirstname();
        $sentId = $customerName->getId();

        $model = Mage::getModel("megaforum/privatemsg")
            ->addData($post_data)
            ->setSentFrom($sentId)
            ->setSentTo($model->getPostedBy())
            ->setSentDate(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
            ->save();

        $customerCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
            ->addFieldToFilter("type_id", 5)
            ->getFirstItem();

        $message1 = $customerCreate->getTemplate();

        $message1 = str_ireplace('{{CUSTOMERNAME}}', $firstname, $message1);
        $message1 = str_ireplace('{{SUBJECT}}', $post_data['subject'], $message1);
        $message1 = str_ireplace('{{MESSAGE}}', $post_data['message'], $message1);
        $message1 = str_ireplace('{{SENTFROM}}', $sentFrom, $message1);
        $message1 = str_ireplace('{{SENTTO}}', $model->getSentTo(), $message1);
        $message1 = str_ireplace('{{SENTDATE}}', $model->getSentDate(), $message1);

        $subject = "BurdaStyle - un utente ti ha inviato un messaggio";
        $Body1 = $message1;

        $mail = new Zend_Mail(); //class for mail
        $mail->setBodyHtml($Body1); //for sending message containing html code
        $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');
        $mail->setFrom($adminMail, 'Burdastyle');
        $mail->addTo($to, $firstname);
        $mail->setSubject($subject);
        $msg = '';
        try {
            if ($mail->send()) {
                $msg = true;
            }
        } catch (Exception $ex) {
            $msg = false;
            //die("Error sending mail to $to,$error_msg");
        }


        Mage::getSingleton('core/session')->addSuccess("Il tuo messaggio privato è stato inviato con successo");
        $this->_redirect('megaforum/index/index/');

    }

    public function saveAction()
    {

        $request = $this->getRequest();
        $post_data = $this->getRequest()->getPost();
        Mage::log(print_r($post_data,true));
        try {

            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $customerData = Mage::getSingleton('customer/session')->getCustomer();
                $firstname = $customerData->getFirstname();
                $userId = $customerData->getId();
                $id = $post_data["forum_id"];

                if ($post_data['forum_id'] == 0) {
                    $post_data['forum_id'] = 1;
                }

                if ($request->get('captcha') && $request->get('captcha') != Mage::helper('megaforum')->getCaptchaText()) {
                    throw new Mage_Core_Exception($this->__('Invalid verification code. Try again, please.'));
                }

                $model = Mage::getModel("megaforum/topic")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->setCreatedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                    ->setEmail($customerData->getEmail())
                    ->setCreatedBy($userId)
                    ->setStatus('Active')
                    ->save();

                //salvo in automatico un post con il testo inserito nel topic
                $post_data_post = Array();
                $post_data_post['topic_name'] = $post_data['topic_name'] ;
                $post_data_post['topic_id'] = $model->getTopicId();
                $post_data_post['post_text'] = $post_data['message'] ;
                $post_data_post['captcha'] = '' ;


                $modelPost = Mage::getModel("megaforum/post")
                    ->addData($post_data_post)
                    ->setId($this->getRequest()->getParam("id"))
                    ->setPostedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setUpdatedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedBy($userId)
                    ->setPosition('1')
                    ->setStatus('Active')
                    ->save();
                $customerCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
                    ->addFieldToFilter("type_id", 1)
                    ->getFirstItem();

                $adminCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
                    ->addFieldToFilter("type_id", 2)
                    ->getFirstItem();

                $message1 = $customerCreate->getTemplate();
                $message2 = $adminCreate->getTemplate();

                $customerMail = Mage::getSingleton('customer/session')->getCustomer();
                $to = $customerMail->getEmail();
                $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');

                $message1 = str_ireplace('{{CUSTOMERNAME}}', $firstname, $message1);
                $message1 = str_ireplace('{{TOPICID}}', $model->getTopicId(), $message1);
                $message1 = str_ireplace('{{TITLE}}', $post_data['topic_name'], $message1);
                $message1 = str_ireplace('{{DESCRIPTION}}', $post_data['message'], $message1);

                $message2 = str_ireplace('{{TOPICID}}', $model->getTopicId(), $message2);
                $message2 = str_ireplace('{{TITLE}}', $post_data['topic_name'], $message2);
                $message2 = str_ireplace('{{DESCRIPTION}}', $post_data['message'], $message2);

                $subject = "BurdaStyle Forum";
                $Body1 = $message1;
                $Body2 = $message2;

                $mail = new Zend_Mail(); //class for mail
                $mail->setBodyHtml($Body1); //for sending message containing html code
                $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');
                $mail->setFrom($adminMail, 'Burdastyle');
                $mail->addTo($to, $firstname);
                $mail->setSubject($subject);
                $msg = '';
                try {
                    if ($mail->send()) {
                        $msg = true;
                    }
                } catch (Exception $ex) {
                    $msg = false;
                    //die("Error sending mail to $to,$error_msg");
                }

                $mail = new Zend_Mail(); //class for mail
                $mail->setBodyHtml($Body2);
                $mail->addTo($adminMail);
                $mail->setSubject($subject);
                $msg = '';
                try {
                    if ($mail->send()) {
                        $msg = true;
                    }
                } catch (Exception $ex) {
                    $msg = false;
                    //die("Error sending mail to $to,$error_msg");
                }


                Mage::getSingleton('core/session')->addSuccess("Il tuo argomento è stato aggiunto con successo");
                $this->_redirect('megaforum/index/post/id/' . $model->getTopicId());
            }
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect("*/*/mytopic", array("forum_id" => $id));
        }

    }

    public function savemeAction()
    {

        $request = $this->getRequest();
        $post_data = $this->getRequest()->getPost();

        try {

            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $firstname = $customerData->getFirstname();
            $userId = $customerData->getId();
            $id = $post_data["topic_id"];

            $post = Mage::getModel('megaforum/post')->getCollection()->addFieldToFilter("topic_id", $id)->getLastItem();
            $position = $post->getPosition();

            if ($request->get('captcha') && $request->get('captcha') != Mage::helper('megaforum')->getCaptchaText()) {
                throw new Mage_Core_Exception($this->__('Invalid verification code. Try again, please.'));
            }

            if ($position == 0) {

                $model = Mage::getModel("megaforum/post")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->setPostedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setUpdatedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedBy($userId)
                    ->setPosition('1')
                    ->setStatus('Active')
                    ->save();

            } else {

                $model = Mage::getModel("megaforum/post")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->setPostedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setUpdatedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                    ->setPostedBy($userId)
                    ->setPosition($position + 1)
                    ->setStatus('Active')
                    ->save();

            }

            $customerCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
                ->addFieldToFilter("type_id", 3)
                ->getFirstItem();

            $adminCreate = Mage::getModel("megaforum/notificationtemplate")->getCollection()
                ->addFieldToFilter("type_id", 4)
                ->getFirstItem();

            $topicCollection = Mage::getModel('megaforum/topic')->getCollection()->addFieldToFilter("topic_id", $id)->getFirstItem();

            $message1 = $customerCreate->getTemplate();
            $message2 = $adminCreate->getTemplate();

            $customerMail = Mage::getSingleton('customer/session')->getCustomer();
            $to = $customerMail->getEmail();
            $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');

            $message1 = str_ireplace('{{CUSTOMERNAME}}', $firstname, $message1);
            $message1 = str_ireplace('{{POSTID}}', $model->getPostId(), $message1);
            $message1 = str_ireplace('{{POSTTEXT}}', $post_data['post_text'], $message1);
            $message1 = str_ireplace('{{TITLE}}', $topicCollection->getTopicName(), $message1);
            $message1 = str_ireplace('{{DESCRIPTION}}', $topicCollection->getMessage(), $message1);

            $message2 = str_ireplace('{{POSTID}}', $model->getPostId(), $message2);
            $message2 = str_ireplace('{{POSTTEXT}}', $post_data['post_text'], $message2);
            $message2 = str_ireplace('{{TITLE}}', $topicCollection->getTopicName(), $message2);
            $message2 = str_ireplace('{{DESCRIPTION}}', $topicCollection->getMessage(), $message2);

            $subject = "BurdaStyle Forum";
            $Body1 = $message1;
            $Body2 = $message2;

            $mail = new Zend_Mail(); //class for mail
            $mail->setBodyHtml($Body1); //for sending message containing html code
            $adminMail = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');
            $mail->setFrom($adminMail, 'Burdastyle');
            $mail->addTo($to, $firstname);
            $mail->setSubject($subject);
            $msg = '';
            try {
                if ($mail->send()) {
                    $msg = true;
                }
            } catch (Exception $ex) {
                $msg = false;
                //die("Error sending mail to $to,$error_msg");
            }

            $mail = new Zend_Mail(); //class for mail
            $mail->setBodyHtml($Body2);
            $mail->addTo($adminMail);
            $mail->setSubject($subject);
            $msg = '';
            try {
                if ($mail->send()) {
                    $msg = true;
                }
            } catch (Exception $ex) {
                $msg = false;
                //die("Error sending mail to $to,$error_msg");
            }

            $this->sendEmailToAllTopicParticipants(
                $topicCollection,
                $post_data,
                array($userId) // exclude post's owner
            );

            Mage::getSingleton('core/session')->addSuccess("Il tuo post è stato aggiunto con successo");
            $this->_redirect('megaforum/index/post/id/' . $post_data['topic_id']);

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect("*/*/mypost", array("id" => $id));
        }


    }

    private function sendEmailToAllTopicParticipants($topic, $post, $userIds)
    {
        $from     = Mage::getStoreConfig('megaforum_section/megaforum_group1/admincreateemail');
        $subject  = 'Burdastyle - Forum: hanno risposto al tuo post';
        $template = $this->getEmailTemplate(7);
        $participants = Mage::getModel('megaforum/topic')
            ->getAllParticipantsRaw($topic->getId(), $userIds)
        ;

        //@TODO - Refactor this shit plz
        $template = str_ireplace('{{TITLE}}', $topic->getTopicName(), $template);
        $template = str_ireplace('{{POSTTEXT}}', $post['post_text'], $template);
        $body = $template;
        
        foreach ($participants as $participant) {
            $mail = new Zend_Mail();
            $mail->setBodyHtml($body);
            $mail->setFrom($from, 'Burdastyle');
            $mail->addTo($participant['email'], $participant['fullname']);
            $mail->setSubject($subject);
            $mail->send();
        }
    }

    private function getEmailTemplate($templateId)
    {
        return $template = Mage::getModel('megaforum/notificationtemplate')
                ->getCollection()
                ->addFieldToFilter('type_id', $templateId)
                ->getFirstItem()
                ->getTemplate();
    }

    public function editmeAction()
    {

        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $userType = Mage::getModel("megaforum/forumuser")->getCollection()->addFieldToFilter("user_id", $customerData->getId())->getFirstItem();
        $post_data = $this->getRequest()->getPost();

        if (($post_data['posted_by'] == $customerData->getId()) || ($userType->getUserType() == 'Moderator')) {

            $model = Mage::getModel("megaforum/post")->load($post_data['post_id'])
                ->addData($post_data)
                ->setUpdatedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                ->setUpdatedAtNew(date("Y-m-d h:i:s", Mage::getModel('core/date')->timestamp(time())))
                ->save();

            Mage::getSingleton('core/session')->addSuccess("Il tuo post è stato modificato con successo");
        }

        $this->_redirect('megaforum/index/index/');
    }

    public function deleteAction()
    {

        $id = $this->getRequest()->getParam("id");
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $userType = Mage::getModel("megaforum/forumuser")->getCollection()->addFieldToFilter("user_id", $customerData->getId())->getFirstItem();

        if (($userType->getUserType() == 'Moderator')) {

            $model = Mage::getModel("megaforum/post")->load($id)
                ->delete();

            Mage::getSingleton('core/session')->addSuccess("Il tuo post è stato cancellato con successo");
            $this->_redirect('megaforum/index/index/');
        }
    }

    public function privatemsgdeleteAction()
    {

        $id = $this->getRequest()->getParam("id");
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $userType = Mage::getModel("megaforum/forumuser")->getCollection()->addFieldToFilter("user_id", $customerData->getId())->getFirstItem();

//        Dan Nistor - all users can delete a message
//        if (($userType->getUserType() == 'Moderator')) {

            $model = Mage::getModel("megaforum/privatemsg")->load($id)
                ->delete();

            Mage::getSingleton('core/session')->addSuccess("Il tuo messaggio privato è stato cancellato con successo");
            //Modified redirect to my inbox page
            $this->_redirect('*/*/inbox/');
//        }
//        Dan Nistor - all users can delete a message
    }

    public function watchlistdeleteAction()
    {

        $id = $this->getRequest()->getParam("id");
        $watchlist = Mage::getModel("megaforum/watchlist")->getCollection()->addFieldToFilter("topic_id", $id)->getFirstItem();

        $model = Mage::getModel("megaforum/watchlist")->load($watchlist->getWatchlistId())
            ->delete();

        Mage::getSingleton('core/session')->addSuccess("La tua wishlist è stata cancellata con successo");
        $this->_redirect("*/*/mywatchlist", array("id" => $this->getRequest()->getParam("id")));

    }


    public function watchlistAction()
    {
        $customerSession = Mage::getSingleton('customer/session');
        $topicId = $this->getRequest()->getParam("id");

        if (!$customerSession->isLoggedIn()) {
            $customerSession->setBeforeAuthUrl(Mage::getUrl('*/*/*/id/' . $topicId));
            $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
        } else {
            $customerData = $customerSession->getCustomer();
            $userId = Mage::getModel("megaforum/forumuser")->getCollection()->addFieldToFilter("user_id", $customerData->getId())->getFirstItem();

            //check if topic exist in user favorites
            //if not add
            $watchlistCollection = Mage::getModel("megaforum/watchlistsingle")
                ->getCollection()
                ->addFieldToFilter("watchlisted_by", $userId->getForumuserId());

            //$watchlistUserTopics = $watchlistCollection->getColumnValues('watchlisted_by');
            //if (!in_array($topicId, $watchlistUserTopics)) {
            if (!$watchlistCollection->getItemsByColumnValue('topic_id', $topicId)) {
                try {
                    //Dan Nistor - old watchlist
                    //$model = Mage::getModel("megaforum/watchlist")

                    $model = Mage::getModel("megaforum/watchlistsingle")
                        ->setTopicId($topicId)
                        ->setWatchlistedBy($userId->getForumuserId())
                        ->setCreatedAt(date("Y-m-d", Mage::getModel('core/date')->timestamp(time())))
                        ->save();

                    Mage::getSingleton('core/session')->addSuccess("La tua wishlist è stata aggiunta con successo");
                } catch (Exception $e) {
                    Mage::getSingleton('core/session')->addError("C'è stato un errore nell'aggiunta alla wishlist");
                    Mage::log($e->getTraceAsString());
                }
            } else {
                Mage::getSingleton('core/session')->addNotice("L'argomento è tra i tuoi preferiti");
            }

            $this->_redirect("*/*/post", array("id" => $this->getRequest()->getParam("id")));
        }
    }

    public function userimageAction()
    {
        $post_data = $this->getRequest()->getPost();

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
            try {

                $path = Mage::getBaseDir('media') . DS . 'user_image' . DS . 'image' . DS;
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $destFile = $path . $_FILES['image']['name'];
                $filename = $uploader->getNewFileName($destFile);
                $uploader->save($path, $filename);

                $post_data['image'] = 'user_image/image/' . $filename;

            } catch (Exception $e) {
                echo 'Error Message: ' . $e->getMessage();
            }
        }

        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $userId = $customerData->getId();

        $forumUser = Mage::getModel("megaforum/forumuser")->getCollection()->addFieldToFilter("user_id", $userId)->getFirstItem();

        $model = Mage::getModel("megaforum/forumuser")->load($forumUser->getId())
            ->addData($post_data)
            ->save();

        Mage::getSingleton("core/session")->addSuccess($this->__("L'immagine è stata caricata con successo"));
        $this->_redirect("*/*/");
        return;

    }


    public function captchaAction()
    {
        require_once(Mage::getBaseDir('lib') . DS . 'captcha' . DS . 'class.simplecaptcha.php');
        $config['BackgroundImage'] = Mage::getBaseDir('lib') . DS . 'captcha' . DS . "white.png";
        $config['BackgroundColor'] = Mage::getStoreConfig('magegaga_productofferprice/settings/captcha_color_background');
        $config['Height'] = 30;
        $config['Width'] = 100;
        $config['Font_Size'] = 23;
        $config['Font'] = Mage::getBaseDir('lib') . DS . 'captcha' . DS . "ARLRDBD.TTF";
        $config['TextMinimumAngle'] = 0;
        $config['TextMaximumAngle'] = 0;
        $config['TextColor'] = '000000';
        $config['TextLength'] = 4;
        $config['Transparency'] = 80;
        $captcha = new SimpleCaptcha($config);
        Mage::getSingleton('core/session')->setData('forum_captcha_code', $captcha->Code);
    }

    public function refreshcaptchaAction()
    {
        $result = Mage::getModel('core/url')->getUrl('*/*/captcha/') . now();
        echo $result;
    }


}


