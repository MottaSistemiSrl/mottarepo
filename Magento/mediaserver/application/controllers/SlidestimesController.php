<?php
require_once 'BaseController.php';
require_once (APPLICATION_PATH.'/models/DbTable/SlidesTimes.php');
require_once (APPLICATION_PATH.'/models/SlidesTimes.php');

class SlidestimesController extends BaseController {		
	
	public function addtimeAction()
	{
		//Controllo che il token passato sia corretto
		$this->checkRequestToken();
		
		$slide_id = $this->getRequest()->getParam('slide_id', null);		
		$opening_time = $this->getRequest()->getParam('opening_time', null);	
	
		
		$this->getNoRender();		
		$this->db->beginTransaction();		
		//creo l'oggetto da salvare		
		try{					
			$amst = new Application_Model_Slides_Times();			
			$amst->setSlideId($slide_id);		
			$amst->setSlideOpeningTime($opening_time);
			$amst->setSlideTimeCreationDate(date('Y-m-d H:i:s'));
									
			//salvo su db
			$amstm = new Application_Model_SlidesTimesMapper();
			$id = $amstm->save($amst);		
			
			$this->db->commit();
		}catch(Exception $ex){
			echo "error: $ex";
			$this->db->rollBack();
			$this->getLogger()->logToFile(Zend_Log::ERR, $e->getMessage());
		}
		
		$amst = new Application_Model_Slides_Times();
		$amstm->find($id, $amst);
		$stObj = array();
		$stObj['slide_time_id'] = $amst->getSlideTimeId();
		$stObj['slide_id'] = $amst->getSlideId();
		$stObj['slide_opening_time'] = $amst->getSlideOpeningTime();
		$stObj['slide_time_creation_date'] = $amst->getSlideTimeCreationDate();
		
		$ret = array();
		$ret['message'] = "Inserimento riuscito";
		$ret['st'] = $stObj; 		
				
		header("Content-Type: application/json", true);		
		/* Return JSON */
		echo json_encode($ret);					
	}
	
	public function deletetimeAction()
	{
		//Controllo che il token passato sia corretto
		$this->checkRequestToken();
		
		$slide_time_id = $this->getRequest()->getParam('slide_time_id', null);		
		$this->getNoRender();		
		
		if($slide_time_id != null)
		{	
			$amstm = new Application_Model_SlidesTimesMapper();
			$amstm->delete('slide_time_id = '.$slide_time_id);
			
			echo "Record cancellato";
		}					
		else{
			echo "Nessun record selezionato";
		}	
	}
	
		
	public function getslideswithtimesAction()
	{
		$video_id = $this->getRequest()->getParam('video_id', null);		
		$this->getNoRender();
		
		$amstm = new Application_Model_SlidesTimesMapper();
		$entries = $amstm->fetchAllByVideoId($video_id);
		
		$ret = array();
		foreach($entries as $entry)
		{
			$tmp = array();
			$tmp['slide_id'] = $entry->getSlideId();
			$tmp['slide_number'] = $entry->getSlideNumber();
			$tmp['slide_path'] = $entry->getSlidePath();
			$tmp['slide_time_id'] = $entry->getSlideTimeId();
			$tmp['slide_opening_time'] = $entry->getSlideOpeningTime();
			
			array_push($ret, $tmp);
		}	
				
		header('Content-Type: application/json');
		echo json_encode($ret);
		exit;
	}
}
