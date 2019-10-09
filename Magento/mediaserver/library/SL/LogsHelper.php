<?php
class SL_LogsHelper {
	/**
	 * Log function with log file rotation
	 * and loglevel restrictions
	 *
	 * @param <int> $level
	 * @param <string> $event
	 */
	public function logToFile($level, $event) {
		$maxsize = SL_LOGMAXSIZE; // Max filesize in bytes (e.q. 5MB)
		$dir = SL_LOGFOLDER;
		$filename = "video-magento.log";
		$logfile = $dir . '/' . $filename;
		$loglevel = SL_LOGLEVEL;
		
		if (!is_dir($dir)) {
			mkdir($dir, null, true);
		}
	
		if (file_exists($logfile) && filesize($logfile) > $maxsize) {
			$nb = 1;
			$logfiles = scandir($dir);
			$count = 0;
			foreach ($logfiles as $file) {
				$tmpnb = substr($file, strlen($filename));
				if ($nb < $tmpnb) {
					$nb = $tmpnb;
				}
				if ($count > 3) {
					unlink($filename);
				}
				$count++;
			}
			rename($logfile, $logfile . ($nb + 1));
		}
		if ($level <= $loglevel) {
			$stream = @fopen($logfile, 'a', false);
			if (!$stream) {
				throw new Exception('Failed to open stream');
			}
			
			$writer = new Zend_Log_Writer_Stream($stream);
			$logger = new Zend_Log($writer);
			$logger->log($event, $level);
		}
	}
	
	/**
	 * 
	 * @param unknown $level
	 * @param unknown $variable
	 */
	public function logVarDumpToFile($level, $variable) {
		ob_start();
		var_dump($variable);
		$event = ob_get_clean();
		$this->logToFile($level, $event);
	}
}