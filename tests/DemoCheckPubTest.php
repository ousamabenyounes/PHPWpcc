<?php
require("DemoCheck.php");
class DemoCheckPubTest extends DemoCheck {


   // Check if the given webSiteContent is compatible with allowed Pub configuration
   // @params String $html Given html content
   // @return Boolean 
   public function DemoCheckPub($html) {
     // Check fsd configuration
     if (FALSE !== strpos($html, "fds" )) {
          return true;
     }
     return false;
     }

  public function testIfFdsContainsPub() {        
        $html = $this->getHtmlContent("fds");
        $this->assertTrue($this->DemoCheckPub($html), "testIfFdsContainsPubKO");
    }

}
?>
