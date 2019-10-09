<?php
require_once 'BaseController.php';

class SlidesController extends BaseController {
	
	const COMPRESS_FORMAT = "zip";	//formato del file compresso
	const DECOMPRESS_TARGET = "css";	//directory in cui verrà decompresso lo zip
	const COMPRESS_TARGET = "css";	//directory in cui si trova lo zip
	
	public function init()
	{
		parent::init();
	}
	
	public function indexAction() {						
		//$this->getLogger()->logToFile(Zend_Log::DEBUG, 'controller: slides, action: index');
		$slide = new Application_Model_SlidesMapper();
		$this->view->entries = $slide->fetchAll();
	}
	
	//inserimento-update
	public function uploadAction()
	{
		//Controllo che il token passato sia corretto
		$this->checkRequestToken();
		try {
			$product_id = $this->getRequest()->getParam('product_id', null);
			$video_id = $this->getRequest()->getParam('video_id', null);				
			
			$this->deleteSlides($video_id, $product_id);
			
			$upload = new Zend_File_Transfer();
			//$productId = 100;
			//$videoId = 1;
			$oldZip = "";		
			
			$uploadDestination = sys_get_temp_dir();
			if (!is_dir($uploadDestination)) {
				mkdir($uploadDestination, 0755, true);
			}
			
				
			$upload->setDestination($uploadDestination);
			$upload->receive();
					
			$this->unzipPackage($upload, $product_id, $video_id);
		} catch (Exception $e) {
			$this->getLogger()->logToFile(Zend_log::ERR, $e->getMessage());
			throw $e;
		}
	}
	

	private function copyZipFiles($zipName, $product_id, $video_id)
	{		
		//$this->deleteSlideIntoDirectory($product_id);			
		
		$dirname = basename($zipName, ".zip");
		$pathorig = sys_get_temp_dir() . "/" . $dirname;
		$pathdestination = $product_id . "-" . md5($product_id);
				
		if (is_dir($pathorig))
		{	
			//se non c'è la directory, la creo		
			if (!is_dir(APPLICATION_VIDEO_PATH . "/" . $pathdestination)) {
				mkdir(APPLICATION_VIDEO_PATH . "/" . $pathdestination);
			}
				
			$files = glob($pathorig . "/*.*");
						
			$slideNumber = 1;
			$imageHelper = new SL_ImageHelper();
			foreach($files as $file){								
				$file_to_go = str_replace($pathorig, APPLICATION_VIDEO_PATH."/".$pathdestination, $file);
				$file_to_go = str_replace(basename($file_to_go), $slideNumber .".jpg", $file_to_go);				
				
				$slide = new Application_Model_Slides();
				$slide->setSlideCreationDate(date('Y-m-d H:i:s'));
				$slide->setSlideNumber($slideNumber);
				$slide->setSlidePath("/resources/".$pathdestination."/".$slideNumber .".jpg");
				$slide->setVideoId($video_id);											
								
				$amsm = new Application_Model_SlidesMapper();
				$amsm->save($slide);
				copy($file, $file_to_go);
				
				$imageHelper->createThumb($file_to_go);
				
				$slideNumber++;
			}
		}
	}
		
	
	private function deleteSlides($video_id, $product_id)
	{
		$amsm = new Application_Model_SlidesMapper();
		$slides = $amsm->fetchAllByVideoId($video_id);
		
		//$this->getLogger()->logToFile(Zend_log::DEBUG, "deleteSlides videoId $video_id");
					
		foreach($slides as $slide)
		{
			$slide_id = $slide->getSlideId();		
			//cancello nella tabella slides
			$amsm->delete('slide_id = '.$slide_id);	
			//metto il flag video_with_slide a 0
			$this->updateVideoWithSlides($video_id);		
			//cancello la slide dal filesystem
			$this->deleteFileIntoDirectory($product_id, $slide->getSlideNumber());
		}	
	}
	
	private function updateVideoWithSlides($video_id)
	{		
		$update_data = array('video_id' => $video_id,				
				'video_with_slides' => '0',				
				'video_lastupdate' => date('Y-m-d H:i:s')
		);
		
		$table = new Application_Model_DbTable_Videos();
		if($table->update($update_data, 'video_id = '.$video_id))
		{
			return true;
		}
		return false;
	}
	
	private function unzipPackage($upload, $product_id, $video_id){
		$filesinfo = $upload->getFileInfo();
		$file = $filesinfo['uploadedSlide'];
		//$this->getLogger()->logToFile(Zend_log::DEBUG, "unzip filename: " . $file['name']);
		
		$dirname = basename($file['name'], ".zip");
		$pathtarget = sys_get_temp_dir() . "/" . $dirname;
				
		$filter     = new Zend_Filter_Decompress(array(
		 		'adapter' => self::COMPRESS_FORMAT,
		 		'options' => array(
 					'target' => $pathtarget
		 		)
		 ));		
		$compressed = $filter->filter(sys_get_temp_dir() . "/" . $file['name']);

		//copia il contenuto della cartella temporanea nella destinazione corretta
		$this->copyZipFiles($file['name'], $product_id, $video_id);
		
		echo "Upload Slide Avvenuto con successo";
		exit;
	}	
	
	
	private function deleteFileIntoDirectory($product_id, $slide_number)
	{
		$thumbName = $slide_number . "-thumb";
		$filepath = APPLICATION_VIDEO_PATH . "/" . $product_id . "-" . md5($product_id) . "/" . $slide_number . ".jpg";
		$thumbFilepath = APPLICATION_VIDEO_PATH . "/" . $product_id . "-" . md5($product_id) . "/" . $thumbName . ".jpg";
		unlink($filepath);
		unlink($thumbFilepath);
	}
	
	public function deleteAction()
	{
		$video_id = $this->getRequest()->getParam('video_id', null);
		$auth_token = $this->getRequest()->getParam('auth_token', null);

		$this->getLogger()->logToFile(Zend_log::DEBUG, "deleteAction : video_id: $video_id ");
		
		//Controllo che il token passato in sessione sia corretto
		//$this->checkSessionToken();
		$this->checkRequestToken();
		
		//recupero il product_id da passare al metodo per la cancellazione fisica dei files
		$videoMapper = new Application_Model_VideosMapper();
		$video = new Application_Model_Videos();
		$videoMapper->find($video_id, $video);
		$product_id = $video->getProductId();	
		$this->getLogger()->logToFile(Zend_log::DEBUG, "deleteAction : product_id: $product_id ");

		$amsm = new Application_Model_SlidesMapper();
		$slides = $amsm->fetchAllByVideoId($video_id);			
		
		$this->db->beginTransaction();
		try{			
			
			//cancello nella tabella slides_times
			$amstm = new Application_Model_SlidesTimesMapper();
			$slidesTime = $amstm->fetchAllByVideoId($video_id);			
			foreach ($slidesTime as $st)
			{				
				$amstm->delete('slide_time_id = '.$st->getSlideTimeId());
			}
			
			foreach($slides as $slide)
			{	
				$this->getLogger()->logVarDumpToFile(Zend_log::DEBUG, $slide);
				$slide_id = $slide->getSlideId();
				//cancello nella tabella slides
				$amsm->delete('slide_id = '.$slide_id);
				//cancello la slide dal filesystem						
				$this->deleteFileIntoDirectory($product_id, $slide->getSlideNumber());										
			}
													
			//se è tutto ok, committo le modifiche al db
			$this->db->commit();
		}catch(Exception $ex){
			$this->db->rollBack();			
		}	
				
		//$this->_redirect('/video/editor/product_id/'.$product_id.'/auth_token/'.$this->getSession()->auth_token);
		$this->_redirect('/video/editor?product_id='.$product_id.'&auth_token='.$auth_token);
	}
	
	
	public function listAction()
	{
		$slides = new Application_Model_SlidesMapper();
		$this->view->entries = $slides->fetchAll();
	}
	
	/**
	 * @param $video_id
	 * @param $seconds
	 * @param $current_slide_time_id
	 */
	public function currentslideAction() {
		$this->getJsonResponse();
		$video_id = $this->getRequest()->getParam('video_id', null);
		$seconds = $this->getRequest()->getParam('seconds', null);
		$current_slide_time_id = $this->getRequest()->getParam('current_slide_time_id', null);
		
		$slideTimeMapper = new Application_Model_SlidesTimesMapper();
		$row = $slideTimeMapper->fetchByVideoIdAndSeconds($video_id, $seconds);
		$json = Zend_Json::prettyPrint(Zend_Json::encode($row));
		$this->getLogger()->logToFile(Zend_log::DEBUG, $json);
		print $json;
	}
}
