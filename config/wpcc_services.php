<?php
$servicesConfig = array(

    "Cnil" => array(
        'acceptedConfig' => array(
            "minOld" => array(
                "http://newsletters.francetv.fr/cnil/css/cnil-min-v1.0.1.css",
                "http://newsletters.francetv.fr/cnil/js/cnil-min-v1.2.1.js",

            ),

            "minArchimade" => array(
                "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140612.css",
                "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140627.js",

            ),

            "minLast" => array(
                "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140612.css",
                "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140703.js",

            ),

            "minCurent" => array(
                "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140904.css",
                "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140627.js",
            ),
        )
    ),

    "Metanav" => array(
        'acceptedConfig' => array(
            "minCurrent" => array(
                "http://static.francetv.fr/js/jquery.metanav-min.js?20140822",

            ),
        )
    ),

    "Pub" => array(
        'acceptedConfig' => array(
            "minCurrent" => array(
                "http://static.francetv.fr/js/publicite-min.js",

            ),

            "minAsyncCurrent" => array(
                "http://static.francetv.fr/js/publicite-async-min.js",

            ),
        )
    ),

    "Audience" => array(
        'acceptedConfig' => array(
            "minCurrent" => array(
                "http://static.francetv.fr/js/audience-min.fr",

            ),
        )
    ),


);
?>