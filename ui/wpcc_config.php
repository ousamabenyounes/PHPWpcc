<?php
$webParsingConfig = array(
  'Jquery' => array(
	'acceptedConfig' => array(
		      	"1.0" => array(
				"http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js",

    			), 
		      	"supinfoJs" => array(
				"/js/jquery-1.7.2.min.js",

    			), 
	  ),
         'urls' => array(
				'http://www.epitech.fr',
				'http://www.supinfo.fr',
	),
  ),
  'Paypal' => array(
	'acceptedConfig' => array(
		      	"logo" => array(
				"logo_paiement_paypal.jpg",

    			), 
		      	"module" => array(
				"modules/paypal",

    			), 
	  ),
         'urls' => array(
				'http://www.foodistrib.com',
				'http://www.shop2tout.com',
	),
  ),
);
?>
