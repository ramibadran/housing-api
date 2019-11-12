<?php

namespace App\Utilities;

use Log;
use Config;
use App\Utilities\Mongo;

/**
 * Class RequestResponseLogger.
 *
 * @package App\Utilities
 *
 * @author Rami Badran <ramibadran_82@gmail.com>
 */

class Logger {

	private function createDirectory($dir,$permission=0777){
		if (!file_exists($dir)) {
			return mkdir($dir, $permission, true);
		}
		return true;
	}

	public function logMe($message,$case,$response=''){
	    $dir = storage_path(config('custom.apiLogDir'))."/" .date('Y-m-d');
	    $this->createDirectory($dir,0775);
        switch ($case) {
            case 'RestAPIFail':
                $text = $message . ' on date ' .  date('Y-m-d h:i:sa') ;
                $file = $dir . '/rest-api-fail.log';
                break;	
        }
        //Log::useDailyFiles($file);
        Log::info($text);
	}
	

	private function log($message,$file) {
		$myfile = fopen($file, "a+");
		fwrite($myfile, $message);
		fclose($myfile);
	}

}


?>
