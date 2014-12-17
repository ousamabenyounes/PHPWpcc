<?
class FTVENMktgCheckServicesPresent {


   // Check if the given webSiteContent is compatible with allowed Cnil configuration
   // @params String $html Given html content
   // @return Boolean
   public function FTVENMktgCheckCnilPresent($html) {
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/css/cnil-min-v1.0.1.css") &&
     FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/js/cnil-min-v1.2.1.js")) {
         return true;
    }
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140612.css") &&
     FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140627.js")) {
         return true;
    }
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140612.css") &&
     FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140703.js")) {
         return true;
    }
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/css/cnil-min-v20140904.css") &&
     FALSE !== strpos($html, "http://newsletters.francetv.fr/cnil/js/cnil-min-v20140627.js")) {
         return true;
    }
     return false;
  }


   // Check if the given webSiteContent is compatible with allowed Metanav configuration
   // @params String $html Given html content
   // @return Boolean
   public function FTVENMktgCheckMetanavPresent($html) {
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://static.francetv.fr/js/jquery.metanav-min.js?20140822")) {
         return true;
    }
     return false;
  }


   // Check if the given webSiteContent is compatible with allowed Pub configuration
   // @params String $html Given html content
   // @return Boolean
   public function FTVENMktgCheckPubPresent($html) {
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://static.francetv.fr/js/publicite-min.js")) {
         return true;
    }
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://static.francetv.fr/js/publicite-async-min.js")) {
         return true;
    }
     return false;
  }


   // Check if the given webSiteContent is compatible with allowed Audience configuration
   // @params String $html Given html content
   // @return Boolean
   public function FTVENMktgCheckAudiencePresent($html) {
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://static.francetv.fr/js/audience-min.js")) {
         return true;
    }
     return false;
  }




}



?>