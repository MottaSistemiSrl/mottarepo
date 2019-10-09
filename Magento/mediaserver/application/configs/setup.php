<?php
$serverName = -1;
if (isset($_SERVER['SERVER_NAME'])) {
	$serverName = $_SERVER['SERVER_NAME'];
}
switch ($serverName) {
	case "video-magento.dev":
		define('SL_SECRET', 'nkasj1929haSHDBbA277Ba226');
		define('SL_LOGLEVEL', 7);
		define('SL_LOGFOLDER', '/var/dev/video-magento.dev/webapp/logs');
		define('SL_LOGMAXSIZE', 5242880);
		define('SL_THUMB_HEIGHT', 150);
	break;
	
	case "video-burda.sevenlike.dev":
		define('SL_SECRET', 'nkasj1929haSHDBbA277Ba226');
		define('SL_LOGLEVEL', 7);
		define('SL_LOGFOLDER', '/var/dev/burda.sevenlike.dev/mediaserver/logs');
		define('SL_LOGMAXSIZE', 5242880);
		define('SL_THUMB_HEIGHT', 150);
		break;
	
	default:
		define('SL_SECRET', 'nkasj1929haSHDBbA277Ba226');
		define('SL_LOGLEVEL', 6);
		define('SL_LOGFOLDER', dirname(dirname(dirname(__DIR__))) . '/var/log');
		define('SL_LOGMAXSIZE', 5242880);
		define('SL_THUMB_HEIGHT', 150);
}