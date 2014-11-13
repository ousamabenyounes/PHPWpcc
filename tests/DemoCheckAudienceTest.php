<?php
require("DemoCheck.php");
class DemoCheckAudienceTest extends DemoCheck {


   // Check if the given webSiteContent is compatible with allowed Audience configuration
   // @params String $html Given html content
   // @return Boolean 
   public function DemoCheckAudience($html) {
     // Check fsd configuration
     if (FALSE !== strpos($html, "fds" )) {
          return true;
     }
     return false;
     }

  public function testIfSfdContainsAudience() {        
        $html = $this->getHtmlContent("sfd");
        $this->assertTrue($this->DemoCheckAudience($html), "testIfSfdContainsAudienceKO");
    }

}
?>
