<?php
require_once 'BaseController.php';

class IndexController extends BaseController {

    public function init() {
        parent::init();
    }

    public function indexAction()
    {
		// action body
    	echo "This is the indexAction().";
    }     
}