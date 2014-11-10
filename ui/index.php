<?php
require("wpcc_config.php");

// Form Step 1 / 2 - Default
$mainHtml = "<form method='post' action='index.php' />
<div class='input_fields_wrap'>
    <button class='add_field_button'>Add another service</button><br/><br/>
    <div>
        Service: <input type='text' name='service[]' /> Number Of file to check: <input type='text' name='nbConfig[]'  size='1'/>
    </div>
</div>
 <br/><br/>
    <input type='submit' value='send configuration'/>
    <input type='hidden' name='formType' value='create' />
</form>
";



// Form Step 1 / 2 - Update
if (0 !== sizeof($webParsingConfig) && 0 === sizeof($_POST)) {
   $mainHtml = "<form method='post' action='index.php'>";
   foreach ($webParsingConfig as $service => $config)
   {
           $mainHtml .= "<div id='service_" . $service . "'><b>" . $service;
           $mainHtml .= " Service Configuration </b><input type='hidden' name='service[]' value='" . $service . "'>";
                    $mainHtml .= "<div id='service_" . $service . "_config'>";
                    $nbFiles = sizeof($config["acceptedConfig"]);
                    $urls = $config["urls"];
                    $mainHtml .= "<input name='nbfile_" . $service . "' type='hidden' value='" . $nbFiles . "'>";
                    $i = 0;
                    foreach ($config["acceptedConfig"] as $version => $files) {
                            $mainHtml .= "<div name='version_" . $service. "_" . $i . "_block'>";
                            $mainHtml .= "Version: <input type='text' name='version_" . $service. "_" . $i . "' value='" . $version . "'>";
                            $mainHtml .= " File: <textarea name='file_" . $service . "_" . $i . "' rows='4' cols='100'>";
                            $mainHtml .= implode("\r\n", $files) ."</textarea> </div>";
                            $i++;
                    }
                    $mainHtml .= "url to check for this configuration: <br/><textarea name='urls_" . $service . "' rows='10' cols='50'>";
                    $mainHtml .= implode("\r\n", $urls) . "</textarea><br/><br/><br/>";
                    $mainHtml .= "</div><hr width='100%'>";
           $mainHtml .= "</div>";
   }
   $mainHtml .= "<input type='submit' value='generate configuration'><input type='hidden' name='formType' value='update' /></form>";
}



// Form Step 2 / 2
if (0 != sizeof($_POST) && 
      ('update' === $_POST["formType"])) {
   require("generate_conf.php");
   $mainHtml = 'Step 2 / 2';
/*   die();
   $mainHtml = "<form method='post' action='generate_conf.php'>";
   foreach ($_POST["service"] as $key => $service)
   {
   	   $mainHtml .= "<div id='service_" . $service . "'><b>" . $service . " Service Configuration </b>";
	   $mainHtml .= "<input type='hidden' name='service[]' value='" . $service . "'>";
	   	    $mainHtml .= "<div id='service_" . $service . "_config'>";
	   	    $nbFiles = $_POST["nbfile_" . $service];
		    $mainHtml .= "<input name='nbfile_" . $service . "' type='hidden' value='" . $nbFiles . "'>";
	   	    for ($i = 0; $i < $nbFiles; $i++) {
	       	    	$mainHtml .= "<div name='version_" . $service. "_" . $i . "_block'>";
			$mainHtml .= "Version: <input type='text' name='version_" . $service. "_" . $i . "' >";
			$mainHtml .= "File: <textarea name='file_" . $service . "_" . $i . "' rows='4' cols='100'></textarea> </div>";
	   	    }
		    $mainHtml .= "url to check for this configuration: <br/>";
		    $mainHtml .= "<textarea name='urls_" . $service . "' rows='10' cols='50'></textarea><br/><br/><br/>";
	   	    $mainHtml .= "</div><hr width='100%'>";
	   $mainHtml .= "</div>";
   }
   $mainHtml .= "<input type='submit' value='generate configuration'><input type='hidden' name='formType' value='generate' /></form>";
*/
}


if (0 != sizeof($_POST) && 'generate' === $_POST["formType"]) {
   require("generate_conf.php");
   $mainHtml = 'Finished other';
}

?>
<html>
<head>

<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="js/addRemove.js"></script>

</head>
<body>
<?=$mainHtml?>
</body>
</html>