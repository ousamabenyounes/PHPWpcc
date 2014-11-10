<?php
   $finalConf = '<?php
$webParsingConfig = array(
';
   foreach ($_POST["service"] as $key => $service)
   {
	$nbFilesService = $_POST["nbfile_" . $service];
	$finalConf .= "  '" . $service . "' => array(
	'acceptedConfig' => array(
"; 
			      for ($i = 0; $i < $nbFilesService; $i++)
			      {
				$version = $_POST["version_" . $service . "_" .$i];
				//$fileToCheck = $_POST["file_" . $service . '_' .$i];

   				$filesToCheckArray = explode("\n", $_POST['file_' . $service . '_' .$i]);				
				$filesToCheckString = '';
                		foreach ($filesToCheckArray as $file) {
                         		$breaks = array("\r\n", "\n", "\r");
                        		$file = str_replace($breaks, "", $file);
					$filesToCheckString .= '				"' . $file . '",
';
				}
				$finalConf .= '		      	"' . $version. '" => array(
' . $filesToCheckString . '
    			), 
';				
			      }
			      $finalConf .= "	  ),
         'urls' => array(
";
	        $urls = explode("\n", $_POST['urls_' . $service]);
		foreach ($urls as $url) {
			 $breaks = array("\r\n", "\n", "\r");
			$url = str_replace($breaks, "", $url);
			$finalConf .= "				'" . $url . "',
";
  		}
		$finalConf .= '	),
  ),
';
   }
  $finalConf .= ');
?>
';

   // Write the contents back to the file
   $dateString = date("YmdHis");
   file_put_contents("wpcc_config.php", $finalConf);
   file_put_contents("config_sav/wpcc_config_" . $dateString . ".php", $finalConf);
   file_get_contents("http://ftven.jecodedoncjeteste.fr/wpccGenerate.php");
?>
