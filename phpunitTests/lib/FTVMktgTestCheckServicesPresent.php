<?
class FTVMktgTestCheckServicesPresent {


   // Check if the given webSiteContent is compatible with allowed Pub configuration
   // @params String $html Given html content
   // @return Boolean
   public function FTVMktgTestCheckPubPresent($html) {
 
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
   public function FTVMktgTestCheckAudiencePresent($html) {
 
    // Check  configuration
    if (FALSE !== strpos($html, "http://static.francetv.fr/js/audience-min.js")) {
         return true;
    }
     return false;
  }




}



?>