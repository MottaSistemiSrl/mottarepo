<?php
require_once 'BaseController.php';
require_once 'getid3/getid3.php';

class VideoController extends BaseController
{	

	const COMPRESS_FORMAT = "zip";	//formato del file compresso
	const DECOMPRESS_TARGET = "css";	//directory in cui verr� decompresso lo zip
	const COMPRESS_TARGET = "css";	//directory in cui si trova lo zip
	
	private $product_id;
	private $videoModel;
	

	public function init()
	{	
		parent::init();	
	}
	
	public function indexAction() {	
		$videos = new Application_Model_VideosMapper();
		$this->view->entries = $videos->fetchAll();
		$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $this->getSession()->auth_token);
	}
	
	//pagina di editor: verranno presentati video+slide e i pulsanti per i vari uploads/cancellazioni
	public function editorAction()
	{		
		/*if($this->checkSessionToken())
		{
			echo "step1";
			//$auth_token = $this->getSession()->auth_token;
		}	
		else*/
		if($this->checkRequestToken())
		{			
			$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $this->getSession());
			$auth_token = $this->getRequest()->getParam('auth_token', null);
			//$this->getSession()->auth_token = $auth_token;			
		}
		
		$product_id = $this->getRequest()->getParam('product_id', null);
		if ($product_id === null) {
			throw new Exception("product id is empty or not valid " . $product_id);
		}			
		
		//recupero i dati del video			
		$amvm = new Application_Model_VideosMapper();
		$video = new Application_Model_Videos();
		$amvm->findByProductId($product_id, $video);
		$slides = array();
		$retSlidesTimes = array();
		if ($video->getVideoId() != null) {
			//recupero i dati delle slide ( se presenti )
			$amsm = new Application_Model_SlidesMapper();		
			$slides = $amsm->fetchAllByVideoId($video->getVideoId());			
			
			//recupero i dati delle slide time( se presenti )
			foreach ($slides as $slide) {
				$slide_id = $slide->getSlideId();			
				$amstm = new Application_Model_SlidesTimesMapper();
				$slideTime = new Application_Model_Slides_Times();			
				$slideTime = $amstm->fetchAllBySlideId($slide_id);
				if ($slideTime != null) {
					$retSlidesTimes[$slide_id] = $slideTime;
				}
			}
		}
				
		$this->view->assign("product_id", $product_id);
		$this->view->assign("video", $video);
		$this->view->assign("slides", $slides);
		$this->view->assign("arrSlidesTimes", $retSlidesTimes);
		$this->view->assign("auth_token", $auth_token);
	}

	private function insertVideo($upload, $product_id)
	{				
		$filesinfo = $upload->getFileInfo();
		$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $filesinfo);
		$file = $filesinfo['uploadedfile'];
		
		$videodir = "/resources/".$product_id."-".md5($product_id);
		$insert_data = array(				
				'product_id' => $product_id,
				'video_with_slides' => 0,
				'video_path' => $videodir . '/' . $product_id . ".mp4",
				'video_length' => '0',
				'video_size' => $file['size'],
				'video_creation_date' => date('Y-m-d H:i:s'), 
				'video_lastupdate' => date('Y-m-d H:i:s')					
		);			
				
		$table = new Application_Model_DbTable_Videos();
		$id = $table->insert($insert_data);			
		if($id == null || $id == 0)
		{				
			throw new Exception("[videos] Errore inserimento db");
		}	
				
		return $id;
	}

	private function insertVideoBasic($product_id)
	{
		$videodir = "/resources/".$product_id."-".md5($product_id);
		$insert_data = array(
			'product_id' => $product_id,
			'video_with_slides' => 0,
			'video_path' => $videodir . '/' . $product_id . ".mp4",
			'video_length' => '0',
			'video_size' => 0,
			'video_creation_date' => date('Y-m-d H:i:s'),
			'video_lastupdate' => date('Y-m-d H:i:s')
		);

		$table = new Application_Model_DbTable_Videos();
		$id = $table->insert($insert_data);
		if($id == null || $id == 0)
		{
			throw new Exception("[videos] Errore inserimento db");
		}

		return $id;
	}
	
	private function updateVideo($upload, $product_id, $video_id)
	{
		$filesinfo = $upload->getFileInfo();
		$file = $filesinfo['uploadedfile'];
	
		$videodir = "/resources/".$product_id."-".md5($product_id);
		$update_data = array('video_id' => $video_id,
				'product_id' => $product_id,
				'video_with_slides' => '0',
				'video_path' => $videodir .'/'. $product_id . ".mp4",
				'video_length' => '0',
				'video_size' => $file['size'],		
				'video_lastupdate' => date('Y-m-d H:i:s')
		);		
	
		$table = new Application_Model_DbTable_Videos();
		if($table->update($update_data, 'video_id = '.$video_id))		
		{		
			return true;
		}		
		return false;
	}
			
	//recupero il length del video
	private function updateVideoInfo($filepath, $video_id)
	{
		// Initialize getID3 engine
		$getID3 = new getID3();
		// Analyze file and store returned data in $ThisFileInfo
		$fileInfo = $getID3->analyze($filepath);
		$this->getLogger()->logToFile(Zend_Log::INFO, $filepath . " {$video_id}");
		$this->getLogger()->logToFile(Zend_Log::INFO, $fileInfo['playtime_string']);
		$str_time = $fileInfo['playtime_string'];
		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
		$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
		$this->getLogger()->logToFile(Zend_Log::INFO, $time_seconds);
		$update_data = array(
				'video_length' => $time_seconds,
		);
		$table = new Application_Model_DbTable_Videos();
		$table->update($update_data, 'video_id = ' . $video_id);
	}
		
	
	private function copyVideo($upload, $product_id, $oldVideo, $video_id)
	{		
		$filesinfo = $upload->getFileInfo();
		
		$file = $filesinfo['uploadedfile'];
		$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $file);
		$videodir = $product_id . "-" . md5($product_id);
		
		$uploadDestination = APPLICATION_VIDEO_PATH. '/' .$videodir;
		$this->getLogger()->logToFile(Zend_Log::INFO, "uploadDestination: " . $uploadDestination);
		if (!is_dir($uploadDestination)) {
			mkdir($uploadDestination, 0755, true);
		}
		$this->getLogger()->logVarDumpToFile(Zend_Log::INFO, $oldVideo);
		if ($oldVideo != null && file_exists($uploadDestination . "/" . $oldVideo)) {
			unlink($uploadDestination . "/" . $oldVideo);
		}
					
		$upload->setDestination($uploadDestination);		
		$this->getLogger()->logToFile(Zend_Log::INFO, "copyVideo uploadDestination: " . $uploadDestination." filename : ".$file['name']);
		$upload->receive();	
		$source = $uploadDestination . "/" . $file['name'];
		$destination = $uploadDestination . "/" . $product_id . ".mp4";
		rename($source, $destination);
		
		$this->updateVideoInfo($destination, $video_id);
	}

	public function useexistingAction()
	{
		$product_id = $this->getRequest()->getParam('product_id', null);
		$auth_token = $this->getRequest()->getParam('auth_token', null);
		$selected_file = $this->getRequest()->getParam('file', null);

		if (!$product_id or !$selected_file or !$auth_token) {
			die("error, please hit the back button");
		}

		$videodir = $product_id . "-" . md5($product_id);
		$uploadDestination = APPLICATION_VIDEO_PATH. '/' .$videodir;
		if (!is_dir($uploadDestination)) {
			if (!mkdir($uploadDestination, 0755, true)) {
				die("Impossibile creare cartella $uploadDestination");
			}
		}

		$oldVideo = $this->db->fetchOne("SELECT video_path FROM sl_mediaserver_videos WHERE product_id=?", $product_id);
		if ($oldVideo) $oldVideo = basename($oldVideo);
		if ($oldVideo != null && file_exists($uploadDestination . "/" . $oldVideo)) {
			if (!unlink($uploadDestination . "/" . $oldVideo)) {
				die("Impossibile rimuovere il vecchio $uploadDestination/$oldVideo che era già presente");
			}
		}

		$source = "/var/www/burdastyle.it/media/blfa_files/{$selected_file}";
		$destination = $uploadDestination . "/" . $product_id . ".mp4";
		if (!copy($source, $destination)) {
			die("Impossibile copiare $source in $destination");
		}

		$video_id = $this->db->fetchOne("SELECT video_id FROM sl_mediaserver_videos WHERE product_id=?", $product_id);
		if (!$video_id) {
			$video_id = $this->insertVideoBasic($product_id);
		}
		$this->updateVideoInfo($destination, $video_id);

		header("Location: /video/editor?product_id={$product_id}&auth_token={$auth_token}");
		die();
	}
	
	//metodo che viene richiamato all'upload del video
	public function uploadAction()
	{
		$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $this->getSession());
		//Controllo che il token passato sia corretto
		$this->checkRequestToken();
		
		$this->getNoRender();
		try {
			$request = $this->getRequest();
			$oldVideo = "";
			
			$product_id = $this->getRequest()->getParam('product_id', null);
			$video_id = $this->getRequest()->getParam('video_id', null);
			
			$upload = new Zend_File_Transfer();
			if ($video_id == null) {
				$video_id = $this->insertVideo($upload, $product_id);
			} else {
				$video = new Application_Model_Videos();
				$videosmap = new Application_Model_VideosMapper();
				$videosmap->find($video_id, $video);
				$oldVideo = basename($video->getVideoPath());
				$this->updateVideo($upload, $product_id, $video_id);
			}
			
			if ($video_id != null && $video_id > 0) {		
				$this->copyVideo($upload, $product_id, $oldVideo, $video_id);			
			}
		} catch (Exception $e) {
			$this->getLogger()->logToFile(Zend_Log::ERR, $e->getMessage());
		}
		
		echo "upload avvenuto con successo";
	}	
	
	public function deleteAction()
	{
		$video_id = $this->getRequest()->getParam('video_id', null);
		$product_id = $this->getRequest()->getParam('product_id', null);
		$auth_token = $this->getRequest()->getParam('auth_token', null);
							
		//Controllo che il token passato sia corretto
		$this->checkRequestToken();
		//Controllo che il token passato in sessione sia corretto
		//$this->checkSessionToken()
		
		//$this->db->beginTransaction();		
		try{		
			$amv = new Application_Model_VideosMapper();
			$amv->delete('video_id = '.$video_id);
			
			$amsm = new Application_Model_SlidesMapper();
			$slides = $amsm->fetchAllByVideoId($video_id);			
			foreach ($slides as $slide)
			{
				//cancello le slide del video
				$slide_id = $slide->getSlideId();										
				$amsm->delete('slide_id = '.$slide_id);
				
				//cancello i tempi associati alla slide
				$amstm = new Application_Model_SlidesTimesMapper();
				$slidesTime = $amstm->fetchAllByVideoId($video_id);
				foreach ($slidesTime as $st)
				{				
					$amstm->delete('slide_time_id = '.$st->getSlideTimeId());
				}									
			}
			
			$this->deleteFileIntoDirectory($product_id);
			//$this->db->commit();
			
		}catch(Exception $e){
			echo "ERRORE : $e <hr>";
			exit;
			//$this->db->rollBack();
		}	
		
		$this->_redirect('/video/editor?product_id='.$product_id.'&auth_token='.$auth_token);
	}
	
	private function deleteFileIntoDirectory($product_id)
	{		
		$dirpath = APPLICATION_VIDEO_PATH."/".$product_id."-".md5($product_id);
		$files = glob($dirpath.'/*.*');		
		//cancello tutte le slides
		foreach($files as $file)
			unlink($file);
	
		//cancello la directory
		rmdir($dirpath);
	}
		
	
	public function listAction()
	{
		//Controllo se che il token sia in sessione e sia corretto
		$this->checkSessionToken();
		
		$videos = new Application_Model_VideosMapper();
		$this->view->entries = $videos->fetchAll();
	}
	
	public function updateAction()
	{		
		$this->product_id = $this->getRequest()->getParam('productId', null);
		
		$video = new Application_Model_Videos();
		$videoMapper = new Application_Model_VideosMapper();
		$videoMapper->findByProductId($this->product_id, $video);
		if ($video->getVideoId() === null) {
			throw new Exception("video not found with product_id " . $product_id);
		}
		$this->videoModel = $video;				
	}
	
	private function populateVideoByProductId() {
		$this->product_id = $this->getRequest()->getParam('product_id', null);
		if ($this->product_id === null || (int) $this->product_id <= 0) {
			throw new Exception("parameter product_id cannot be empty");
		}
		$video = new Application_Model_Videos();
		$videoMapper = new Application_Model_VideosMapper();
		$videoMapper->findByProductId($this->product_id, $video);
		if ($video->getVideoId() === null || !is_int($video->getVideoId())) {
			throw new Exception("video not found with product_id " . $this->product_id);
		}
		
		$this->videoModel = $video;
	}
	
	public function playAction() {
		try {
			$this->populateVideoByProductId();
			$this->view->product_id = $this->product_id;
		} catch (Exception $e) {
			$this->getLogger()->logToFile(Zend_Log::ERR, $e->getMessage());
			throw $e;
		}
	}
	
	private function populateSlidesTimes(array $videoJson) {
		$slideTimeMapper = new Application_Model_SlidesTimesMapper();
		$slidesTimes = $slideTimeMapper->fetchAllByVideoId($this->videoModel->getVideoId());
		foreach ($slidesTimes as $slideTime) {
			$entry = array();
			$entry['slide_id'] = $slideTime->getSlideId();
			$entry['video_id'] = $slideTime->getVideoId();
			$entry['slide_time_creation_date'] = $slideTime->getSlideTimeCreationDate();
			$entry['slide_time_id'] = $slideTime->getSlideTimeId();
			$entry['slide_opening_time'] = $slideTime->getSlideOpeningTime();
			$entry['slide_path'] = $slideTime->getSlidePath();
			$entry['slide_path_thumb'] = str_replace(".jpg", "-thumb.jpg", $slideTime->getSlidePath());
			$entry['slide_number'] = $slideTime->getSlideNumber();
			$videoJson['slides_times'][] = $entry;
		}
		
		return $videoJson;
	}
	
	private function populateSlides(array $videoJson) {
		$videoJson['slides'] = array();
		$videoJson['slides_times'] = array();
		
		$video_id = $this->videoModel->getVideoId();
		$num_slides = $this->db->fetchOne("select count(*) from sl_mediaserver_slides_times where slide_id IN (SELECT slide_id FROM sl_mediaserver_slides WHERE video_id={$video_id})");

		if ($num_slides) {
			$slideMapper = new Application_Model_SlidesMapper();
			$slides = $slideMapper->fetchAllByVideoId($this->videoModel->getVideoId());
			foreach ($slides as $slide) {
				$entry = array();
				$entry['slide_id'] = $slide->getSlideId();
				$entry['video_id'] = $slide->getVideoId();
				$entry['slide_creation_date'] = $slide->getSlideCreationDate();
				$entry['slide_number'] = $slide->getSlideNumber();
				$entry['slide_path'] = $slide->getSlidePath();
				$videoJson['slides'][] = $entry;
			}
			// popolo le slides_times
			$videoJson = $this->populateSlidesTimes($videoJson);
		}
		
		return $videoJson;
	}
	
	public function jsondetailsAction() {
		try {
			$this->populateVideoByProductId();
			$this->getLogger()->logToFile(Zend_Log::INFO, "request for product_id => " . $this->product_id);
			$this->getJsonResponse();
			$videoJson = array();
			$videoJson['video_id'] = $this->videoModel->getVideoId();
			$videoJson['product_id'] = $this->videoModel->getProductId();
			$videoJson['product_id'] = $this->videoModel->getProductId();
			$videoJson['video_creation_date'] = $this->videoModel->getVideoCreationDate();
			$videoJson['video_lastupdate'] = $this->videoModel->getVideoLastupdate();
			$videoJson['video_length'] = $this->videoModel->getVideoLength();
			$videoJson['video_path'] = $this->videoModel->getVideoPath();
			$videoJson['video_size'] = $this->videoModel->getVideoSize();
			$videoJson['video_with_slides'] = $this->videoModel->getVideoWithSlides();
			// popolo le slides e slides times
			$videoJson = $this->populateSlides($videoJson);
			
			// printo il json finale
			$json = Zend_Json::prettyPrint(Zend_Json::encode($videoJson));
			$this->getLogger()->logToFile(Zend_Log::DEBUG, $json);
			print $json;
		} catch (Exception $e) {
			$this->getLogger()->logToFile(Zend_Log::ERR, $e->getMessage());
			throw $e;
		}
	}
}
